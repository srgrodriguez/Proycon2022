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
        $CodigosProcesados = [];
        $CodigoEquipoQueFallo = "";
        mysqli_begin_transaction($this->conn);
        try {
            $insertarBoleta = true;;
            foreach ($equiposTrasladar as &$equipo) {
                if ($resultado->esValido) {
                    $sqlObtenerEquipo = "SELECT th.Codigo,th.ID_Tipo,th.Ubicacion,th.Disposicion,tt.TipoEquipo 
                                      FROM tbl_herramientaelectrica th INNER JOIN 
                                      tbl_tipoherramienta tt on th.ID_Tipo = tt.ID_Tipo
                                      WHERE th.Codigo = '" . $equipo->CodigoEquipo . "'";
                    $CodigoEquipoQueFallo =  $equipo->CodigoEquipo;
                    $resultadoObtenerEquipo = $this->conn->query($sqlObtenerEquipo);

                    if ($resultadoObtenerEquipo != null && mysqli_num_rows($resultadoObtenerEquipo) > 0) {

                        array_push($CodigosProcesados, $equipo);

                        $infoEquipo = mysqli_fetch_array($resultadoObtenerEquipo, MYSQLI_ASSOC);

                        $equipo->IdUbicacionActual = $infoEquipo["Ubicacion"];
                        $equipo->DisposicionActual = $infoEquipo["Disposicion"];
                        $equipo->IdTipoEquipo = $infoEquipo["ID_Tipo"];
                        $equipo->TipoEquipo = $infoEquipo["TipoEquipo"];
                        $resultadoInsertarBoleta = true;
                        if ($insertarBoleta) {
                            $tipoPedido = $equipo->TipoEquipo == Constantes::TipoEquipoHerramientaElectrica ? "2" : "3";
                            $sqlInsertarBoleta = "Insert into tbl_boletaspedido(Consecutivo,ID_Proyecto,TipoPedido,ID_Usuario,Fecha) 
                                              values (" . $equipo->NumBoleta . "," . $equipo->IdProyectoDestino . ",' $tipoPedido','$equipo->IdUsuario',Now())";
                            $resultadoInsertarBoleta =  $this->conn->query($sqlInsertarBoleta);
                            $insertarBoleta = false;
                        }
                        
                        if ($resultadoInsertarBoleta) {
                            $sqlInsertarPrestamo = "Insert into tbl_prestamoherramientas(NBoleta,ID_Proyecto,Codigo,Estado,FechaSalida,ID_Tipo) values
                          (" . $equipo->NumBoleta . "," . $equipo->IdProyectoDestino . ",'" . $equipo->CodigoEquipo . "',1,Now()," . $equipo->IdTipoEquipo . ")";

                            $resultadoInsertarPrestamo = $this->conn->query($sqlInsertarPrestamo);

                            if ($resultadoInsertarPrestamo) {

                                $sqlInsertarHistorial =  "Insert into tbl_historialherramientas(Codigo,Ubicacion,Destino,NumBoleta,Fecha) 
                                                      values('" . $equipo->CodigoEquipo . "'," . $equipo->IdUbicacionActual . "," . $equipo->IdProyectoDestino . ",'" . $equipo->NumBoleta . "',Now())";
                                $resultadoInsertarHistorial = $this->conn->query($sqlInsertarHistorial);

                                if ($resultadoInsertarHistorial) {
                                    $sqlActualizarHerramienta = "";
                                    if ($equipo->IdProyectoDestino == Constantes::Bodega) {
                                        $sqlActualizarHerramienta = "UPDATE tbl_herramientaelectrica SET Ubicacion=" . Constantes::Bodega . ", Disposicion = '1' WHERE Codigo = '" . $equipo->CodigoEquipo . "'";
                                    } else {
                                        $sqlActualizarHerramienta = "UPDATE tbl_herramientaelectrica SET Ubicacion=" . $equipo->IdProyectoDestino . ", Disposicion = 0 WHERE Codigo = '" . $equipo->CodigoEquipo . "'";
                                    }
                                    $resultadoActualizarHerramienta =   $this->conn->query($sqlActualizarHerramienta);

                                    if (!$resultadoActualizarHerramienta) {
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
                            $resultado->mensaje = "Falló al registrar la boleta";
                        }
                    } else {
                        $resultado->esValido = false;
                        $resultado->mensaje = "No se encontro el quipo que desea trasladar " . $equipo->CodigoEquipo;
                    }
                } else {
                    $resultado->mensaje = "Falló el transado del equipo, debido a que se presentaron problemas con el siguiente equipo " . $CodigoEquipoQueFallo;
                    break;
                }
            }
          
            if (!$resultado->esValido) {
                mysqli_rollback($this->conn);
                $class = new MTrasladarEquipo();
                $class->ReversarTrasaldos($CodigosProcesados);
            }
            else{
                mysqli_commit($this->conn);
            }
            $this->conn->close();
            return $resultado;
        } catch (\Throwable $th) {
            mysqli_rollback($this->conn);
            $this->conn->close();
            $resultado->esValido = false;
            $resultado->mensaje = "Ocurrio un error al realizar el traslado del equipo";
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
            }
            $this->conn->close();
        } catch (\Throwable $th) {
            echo Log::GuardarEvento($th, "ReversarTrasaldos");
        }
    }

    public function ConsultarMaquinariaTrasladar($codigo,$id_tipo,$ubicacion,$tipoEquipo)
    {
       $where="";
        if($codigo != ""){
            $where = "where a.Codigo = '$codigo' and a.ID_Tipo = b.ID_Tipo and
            a.Ubicacion = c.ID_Proyecto and
            b.TipoEquipo = '" . $tipoEquipo . "' and
            a.Estado = 1 and c.ID_Proyecto != 13";
        }
        else if($id_tipo != ""){
            $where = "where a.ID_Tipo = $id_tipo and a.ID_Tipo = b.ID_Tipo and
            a.Ubicacion = c.ID_Proyecto and
            b.TipoEquipo = '" . $tipoEquipo . "' and
            a.Estado = 1 and c.ID_Proyecto != 13";
        }
        else if($ubicacion != "")
        {
            $where = "where a.Ubicacion = $ubicacion and a.ID_Tipo = b.ID_Tipo and
            a.Ubicacion = c.ID_Proyecto and
            b.TipoEquipo = '" . $tipoEquipo . "' and
            a.Estado = 1 and c.ID_Proyecto != 13";
        }
        else{
            $where = "where a.ID_Tipo = b.ID_Tipo and
            a.Ubicacion = c.ID_Proyecto and
            b.TipoEquipo = '" . Constantes::TipoEquipoMaquinaria . "' and
            a.Estado = 1";
        }

        $sql = "select 
        Codigo, 
        b.Descripcion as Tipo,
        c.Nombre as Ubicacion,
        c.ID_Proyecto,
        b.TipoEquipo,
        b.ID_Tipo
        from tbl_herramientaelectrica a, tbl_tipoherramienta b, tbl_proyectos c 
        $where";
        $resultadoObtenerEquipo = $this->conn->query($sql);
        return $resultadoObtenerEquipo;
    }

    public function ObternerCosecutivoBoleta() {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = " select Consecutivo from tbl_boletaspedido order by Consecutivo desc limit 1;";
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }
}
