<?php

class MHistoria_Y_ReparacionesMaquinaria
{

    var $conn;
    public function __construct()
    {
        $conexion = new Conexion();
        $this->conn = $conexion->CrearConexion();
    }

    public function ConsultarHistorialMaquinaria($codigo)
    {
        $codigo = LimpiarCadenaCaracter($this->conn, $codigo);
        $sql = "SELECT NumBoleta,
        Fecha,
        b.Nombre AS Ubicacion,
        c.Nombre AS Destino
        FROM tbl_historialherramientas a 
        INNER JOIN tbl_proyectos b ON b.ID_Proyecto = a.Ubicacion
        INNER JOIN tbl_proyectos c ON A.Destino = c.ID_Proyecto
        where a.Codigo = ?";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $codigo);
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function ConsultarReparacionesMaquinaria($codigo)
    {
        $codigo = LimpiarCadenaCaracter($this->conn, $codigo);
        $sql = "SELECT DISTINCT FechaEntrada,ID_FacturaReparacion,Descripcion,MontoReparacion from tbl_reparacionherramienta where Codigo = ? ";
        if ($stmt = $this->conn->prepare($sql)) {

            $stmt->bind_param("s", $codigo);
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function EnviarMaquinariaReparacion($NumBoleta, $idUsuario, $proveedorReparacion, array $listadoMaquinaria)
    {
        $resultado = new Resultado();
        $resultado->esValido = false;
        $fechaActual = date('d/m/y');
        $tipoEquipo = "M";
        $CodigosProcesados=[];
        mysqli_begin_transaction($this->conn);  
        try {               
            $sqlInsertarBoleta = "Insert into tbl_boletareparacion(Numboleta,Fecha,ID_Usuario,ProveedorReparacion,TipoEquipo) values (?,Now(),?,?,?);";
            if ($stmt = $this->conn->prepare($sqlInsertarBoleta)) {
                $stmt->bind_param("iiss", $NumBoleta, $idUsuario, $proveedorReparacion,$tipoEquipo);
                $tamLista = count($listadoMaquinaria);
                if (mysqli_stmt_execute($stmt)) {
                  
                    for ($i = 0; $i < $tamLista; $i++) {
                        $codigo = $listadoMaquinaria[$i];
                        array_push($CodigosProcesados, $codigo);
                        $sqlInsertarReparcion = "Insert into tbl_reparacionherramienta (Codigo,FechaSalida,NumBoleta) values ('" . $codigo . "',Now(),$NumBoleta);";
                        $sqlInsertarReparcionTem = "Insert into tbl_tempoherramientareparacion(Codigo,Fecha,Boleta) values('" . $codigo . "',Now(),$NumBoleta)";
                        $sqlActualizarHerramienta = "UPDATE tbl_herramientaelectrica SET Disposicion = 0, Estado = 0 WHERE Codigo='$codigo'";
                        if ($this->conn->query($sqlInsertarReparcion) &&  $this->conn->query($sqlInsertarReparcionTem)) {
                            if ($this->conn->query($sqlActualizarHerramienta)) {
                                $sqlObtenerUbicacion = "select Ubicacion from tbl_herramientaelectrica where Codigo = '$codigo'";
                                $resultadoUbicacion =  $this->conn->query($sqlObtenerUbicacion);
                                if ($resultadoUbicacion != null) {
                                    $fila = mysqli_fetch_array($resultadoUbicacion, MYSQLI_ASSOC);
                                    $ubicacion = $fila["Ubicacion"];
                                    $sqlInsertarHistorial = "Insert into tbl_historialherramientas (Codigo,Ubicacion,Destino,NumBoleta,Fecha) values ('" . $codigo . "','" . $ubicacion . "','" . "1000" . "','" . $NumBoleta . "'," . "Now());";

                                    if ($this->conn->query($sqlInsertarHistorial)) {
                                        $resultado->esValido = true;
                                    } else {
                                        $resultado->mensaje = "Falló al insertar el historial de la maquinaria";
                                        $resultado->esValido = false;
                                        break;
                                    }
                                } else {
                                    $resultado->mensaje = "Falló al consultar la ubicación de la maquinaria";
                                    $resultado->esValido = false;
                                    break;
                                }
                            } else {
                                $resultado->esValido = false;
                                $resultado->mensaje = "Falló al actualizar el estado de la maquinaria";
                                break;
                            }
                        } else {
                            $resultado->esValido = false;
                            $resultado->mensaje = "Falló al registrar el quipo en reparación";
                            break;
                        }
                    }
                } else {
                    $resultado->mensaje = "Ocurrio un error al generar la boleta de reparación";
                }
            } else {
                $resultado->mensaje = "Error de sintaxis en consulta SQL ";
            }

            if ($resultado->esValido) {
                mysqli_commit($this->conn);
                $resultado->mensaje = "Se envió la maquinaria a reparación correctamente";
            } else
              {  
                mysqli_rollback($this->conn);
                $historial = new MHistoria_Y_ReparacionesMaquinaria();
                $historial -> ReversarEnvioReparacion($CodigosProcesados,$NumBoleta);

              }
            
            $this->conn->close();
            return $resultado;
        } catch (\Throwable $th) {
            mysqli_rollback($this->conn);
            Log::GuardarEvento($th, "EnviarMaquinariaReparacion");
            $resultado->mensaje = "Ocurrio un error al enviar la maquinaria a reparacion " . $th->getMessage();
            $this->conn->close();
            $historial = new MHistoria_Y_ReparacionesMaquinaria();
            $historial -> ReversarEnvioReparacion($CodigosProcesados,$NumBoleta);
            $this->conn->close();
            return $resultado;
        }
    }

    public function ReversarEnvioReparacion(array $CodigosProcesados,$NumBoleta)
    {
        $tamLista = count($CodigosProcesados);
        $conexion  = new Conexion();
        $conn = $conexion->CrearConexion();
        try {

          $restulado1 =  $conn->query("DELETE FROM tbl_boletareparacion WHERE NumBoleta = $NumBoleta");
            for ($i = 0; $i < $tamLista; $i++) {
                $codigo = $CodigosProcesados[$i];
                $sqlBorrarRepracionHerramienta = "DELETE FROM tbl_reparacionherramienta WHERE Codigo = '$codigo' AND NumBoleta = $NumBoleta";
                $sqlReversarInsertarReparcionTem = "DELETE FROM tbl_tempoherramientareparacion  WHERE Codigo = '$codigo' AND Boleta = $NumBoleta";
                $sqlReversarInsertarHistorial = "DELETE FROM tbl_historialherramientas WHERE Codigo = '$codigo' AND NumBoleta = $NumBoleta";
                $restulado2 =    $conn->query($sqlBorrarRepracionHerramienta);
                $restulado3 =  $conn->query($sqlReversarInsertarReparcionTem);
                $restulado4 = $conn->query($sqlReversarInsertarHistorial);
            }

            $conn->close();

        } catch (\Throwable $th) {
            $conn->close();
        }
    }
    
    public function ObternerCosecutivoReparacion() {
        $sql = " select NumBoleta from tbl_boletareparacion order by NumBoleta desc limit 1;";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function EstaEnReparacion($codigo):bool{
         $sql = "SELECT * FROM tbl_tempoherramientareparacion WHERE Codigo = '$codigo'";
         $result = $this->conn->query($sql);
         $this->conn->close();
         return mysqli_num_rows($result) > 0;
    }

    public function ConsultarTodaMaquinariaReparacion() {
        $sql = "SELECT tr.ID,
        tr.Codigo,
        tt.Descripcion,
        tr.Fecha,
        DATEDIFF(CURDATE(),tr.Fecha) as Dias,
         tr.Boleta  ,
         tb.ProveedorReparacion 
         from tbl_tempoherramientareparacion tr,
         tbl_tipoherramienta tt,
        tbl_herramientaelectrica th ,
        tbl_boletareparacion tb 
        WHERE tr.Codigo = th.Codigo 
        and tr.Boleta = tb.NumBoleta 
        and  th.ID_Tipo = tt.ID_Tipo 
        and tt.TipoEquipo = 'M';";
        $resultado = $this->conn->query($sql);
        $this->conn->close();
        return $resultado;
    }

    public function FacturacionReparacionMaquinaria(Factura $Facturacion)
    {
        $resultado = new Resultado();
        $resultado->esValido = false;

        $Facturacion->Codigo = LimpiarCadenaCaracter($this->conn, $Facturacion->Codigo);
        $Facturacion->NumFactura = LimpiarCadenaCaracter($this->conn, $Facturacion->NumFactura);
        $Facturacion->DescripcionFactura = LimpiarCadenaCaracter($this->conn, $Facturacion->DescripcionFactura);
        $Facturacion->FechaEntrada = LimpiarCadenaCaracter($this->conn, $Facturacion->FechaEntrada);
        $Facturacion->NumBoleta = LimpiarCadenaCaracter($this->conn, $Facturacion->NumBoleta);
        mysqli_begin_transaction($this->conn);
        try {
            $sqlActualizarDatosReparacion = "UPDATE tbl_reparacionherramienta SET 
            ID_FacturaReparacion=" . $Facturacion->NumFactura . ",
            Descripcion='" . $Facturacion->DescripcionFactura . "', 
            FechaEntrada='" . $Facturacion->FechaEntrada . "', 
            MontoReparacion=" . $Facturacion->CostoFactura . "
            where Codigo = '$Facturacion->Codigo'
            and NumBoleta = '$Facturacion->NumBoleta'";
            $sqlEliminarMaquinariaReparacion = "Delete from tbl_tempoherramientareparacion where Codigo = '$Facturacion->Codigo'";
            $sqlObtenerUbicacionMaquinaria = "select Ubicacion from tbl_herramientaelectrica where Codigo = '$Facturacion->Codigo'";

            $resultadoUbicacion = $this->conn->query($sqlObtenerUbicacionMaquinaria);
            $ubicacion = "";
            if ($resultadoUbicacion <> null) {
                while ($fila = mysqli_fetch_array($resultadoUbicacion, MYSQLI_ASSOC)) {
                    $ubicacion = $fila['Ubicacion'];
                }
            }

            $sqlActualizarMaquinaria = "UPDATE tbl_herramientaelectrica SET Disposicion =" . ($ubicacion == Constantes::Bodega ? 1 : 0) . " ,Estado = 1 
            WHERE Codigo = '$Facturacion->Codigo'";
            $sqlInsertarHistorial = "Insert into tbl_historialherramientas (Codigo,Ubicacion,Destino,NumBoleta,Fecha) values ('" . $Facturacion->Codigo . "','" . "1000" . "','" . $ubicacion . "','" . $Facturacion->NumBoleta . "'," . "'$Facturacion->FechaEntrada');";

            if ($this->conn->query($sqlActualizarDatosReparacion)) {
                if ($this->conn->query($sqlEliminarMaquinariaReparacion)) {
                    if ($this->conn->query($sqlActualizarMaquinaria) && $this->conn->query($sqlInsertarHistorial)) {
                        $resultado->esValido = true;
                    }
                    else{
                        $resultado->mensaje ="Ocurrio un error al actualizar los datos de la maquinaria en reparación";
                    }
                } else {
                    $resultado->mensaje ="Ocurrio un error al actualizar los datos de la reparación";
                }
            } else {
                $resultado->mensaje ="Ocurrio un error al actualizar los datos de la reparación";
            }

            if ($resultado->esValido) {
                mysqli_commit($this->conn);
                $resultado->mensaje = "Se procesaron los datos correctamente";
            } else
                mysqli_rollback($this->conn);
            
            $this->conn->close();
            return $resultado;
        } catch (\Throwable $th) {
            mysqli_rollback($this->conn);
            Log::GuardarEvento($th, "EnviarMaquinariaReparacion");
            $resultado->mensaje = "Ocurrio un error al enviar la maquinaria a reparacion " . $th->getMessage();
            $this->conn->close();
            return $resultado;
        }
    }

}
