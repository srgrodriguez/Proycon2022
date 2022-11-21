<?php

class MTrasladarEquipo implements ITrasladarEquipo
{

    var $conn;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conn = $conexion->CrearConexion();
    }
    public function TrasdalarEquipo(array $equiposTrasladar): Resultado
    {
        $resultado = new Resultado();
        $resultado->esValido = true;
        $resultado->mensaje = "Se traslado el equipo correctamente";
        $fechaActual = date('d/m/y');
        $CodigosProcesados = [];
        $CodigoEquipoQueFallo = "";
        try {
            foreach ($equiposTrasladar as &$equipo) {
                if ($resultado->esValido) {
                $sqlObtenerEquipo = "SELECT th.Codigo,th.ID_Tipo,th.Ubicacion,th.Disposicion 
                                      FROM tbl_herramientaelectrica th INNER JOIN 
                                      tbl_tipoherramienta tt on th.ID_Tipo = tt.ID_Tipo
                                      WHERE th.Codigo = '" . $equipo->CodigoEquipo . "'";
    
                    $resultadoObtenerEquipo = $this->conn->query($sqlObtenerEquipo);
                    
                    if ($resultadoObtenerEquipo != null && mysqli_num_rows($resultadoObtenerEquipo) > 0) {
    
                        array_push($CodigosProcesados, $equipo);
    
                        $infoEquipo = mysqli_fetch_array($resultadoObtenerEquipo, MYSQLI_ASSOC);
    
                        $equipo->IdUbicacionActual = $infoEquipo["Ubicacion"];
                        $equipo->DisposicionActual = $infoEquipo["Disposicion"];
                        $equipo->IdTipoEquipo = $infoEquipo["ID_Tipo"];
    
                        $sqlInsertarPrestamo = "Insert into tbl_prestamoherramientas(NBoleta,ID_Proyecto,Codigo,Estado,FechaSalida,ID_Tipo) values
                          (" . $equipo->NumBoleta . "," . $equipo->IdProyectoDestino . ",'" . $equipo->CodigoEquipo . "',1,'$fechaActual'," . $equipo->IdTipoEquipo . ")";
    
                        $resultadoInsertarPrestamo = $this->conn->query($sqlInsertarPrestamo);
    
                        if ($resultadoInsertarPrestamo) {
    
                            $sqlInsertarHistorial =  "Insert into tbl_historialherramientas(Codigo,Ubicacion,Destino,NumBoleta,Fecha) 
                                                      values('" . $equipo->CodigoEquipo . "'," . $equipo->IdUbicacionActual . "," . $equipo->IdProyectoDestino . ",'" . $equipo->NumBoleta . "','$fechaActual')";
                            $resultadoInsertarHistorial = $this->conn->query($sqlInsertarHistorial);
    
                            if ($resultadoInsertarHistorial) {

                                $tipoPedido = $equipo->TipoEquipo == Constantes::TipoEquipoHerramientaElectrica ? "2" : "3"; 
                                $sqlInsertarBoleta = "Insert into tbl_boletaspedido(Consecutivo,ID_Proyecto,TipoPedido,ID_Usuario,Fecha) 
                                                      values (" . $equipo->NumBoleta . "," . $equipo->IdProyectoDestino . ",' $tipoPedido','$equipo->IdUsuario','$fechaActual')";
                               $resultadoInsertarBoleta =  $this->conn->query($sqlInsertarBoleta);
                                $sqlActualizarHerramienta = "";
                                if ($equipo->IdProyectoDestino == Constantes::Bodega) {
                                    $sqlActualizarHerramienta = "UPDATE tbl_herramientaelectrica SET Ubicacion=" . Constantes::Bodega . ", Disposicion = '1' WHERE Codigo = '" . $equipo->CodigoEquipo . "'";
                                } else {
                                    $sqlActualizarHerramienta = "UPDATE tbl_herramientaelectrica SET Ubicacion=" . $equipo->IdProyectoDestino . ", Disposicion = 0 WHERE Codigo = '" . $equipo->CodigoEquipo . "'";
                                }
                                $resultadoActualizarHerramienta =   $this->conn->query($sqlActualizarHerramienta);

                              if(!$resultadoInsertarBoleta || !$resultadoActualizarHerramienta  )
                              {
                                $resultado->esValido = false;
                                $CodigoEquipoQueFallo = $equipo->CodigoEquipo;
                              }
                                
                            } else {
                                $resultado->esValido = false;
                                $CodigoEquipoQueFallo = $equipo->CodigoEquipo;
                            }
                        } else {
                            $resultado->esValido = false;
                            $CodigoEquipoQueFallo = $equipo->CodigoEquipo;
                        }
                    } else {
                        $resultado->esValido = false;
                        $resultado->mensaje = "No se encontro el quipo que desea trasladar ". $equipo->CodigoEquipo;
                    }
                } else {
                    $resultado->mensaje = "Falló el transado del equipo, debido a que se presentaron problemas con el siguiente equipo " . $CodigoEquipoQueFallo;
                    break;
                }
            }
            $this->conn->close();
            if (!$resultado->esValido) {
                $class = new MTrasladarEquipo();
                $class->ReversarTrasaldos($CodigosProcesados);
            }
            return $resultado;
        } catch (\Throwable $th) {
            $resultado->esValido = false;
            $resultado->mensaje= "Ocurrio un error al realizar el traslado del equipo";
            echo Log::GuardarEvento($th, "TrasdalarEquipo");
            $class = new MTrasladarEquipo();
            $class->ReversarTrasaldos($CodigosProcesados);
            return  $resultado;
       
        }
        
    }


    public function ReversarTrasaldos(array $equipoProcesados)
    {
        try {
            $fechaActual = date('d/m/y');
            $mensaje= "Se envio a revesar un traslado de equipo, el siguiente equipo:";
            $equipoRevesar = json_encode($equipoProcesados);
            Log::GuardarEventoString($mensaje,"ReversarTrasaldos");
            Log::GuardarEventoString($equipoRevesar,"ReversarTrasaldos");
           
            foreach ($equipoProcesados as &$equipo) {

                $sqlReversarPrestamo = "DELETE FROM tbl_prestamoherramientas WHERE Codigo = '" . $equipo->CodigoEquipo . "' AND ID_Proyecto = '$equipo->IdProyectoDestino' AND NBOLETA = '$equipo->NumBoleta'  AND FechaSalida = '$fechaActual';";
                $sqlReverdarHistorial = "DELETE FROM tbl_historialherramientas WHERE Codigo = '" . $equipo->CodigoEquipo . "' AND NumBoleta = '$equipo->NumBoleta'; ";
                $sqlReversarBoleta = "DELETE FROM tbl_boletaspedido WHERE Consecutivo = '" . $equipo->NumBoleta . "'; ";
                $sqlReversarHerramienta = "UPDATE tbl_herramientaelectrica SET Ubicacion=" . $equipo->IdUbicacionActual . ", Disposicion = '$equipo->DisposicionActual' WHERE Codigo = '" . $equipo->CodigoEquipo . "'; ";

                $resultado1 =   $this->conn->query($sqlReversarPrestamo);
                $resultado2 =   $this->conn->query($sqlReverdarHistorial);
                $resultado3 =   $this->conn->query($sqlReversarBoleta);
                $resultado4 =   $this->conn->query($sqlReversarHerramienta);

                $this->conn->close();
            }
        } catch (\Throwable $th) {
            echo Log::GuardarEvento($th, "ReversarTrasaldos");
        }
    }
}
