<?php

class MTrasladarEquipo implements ITrasladarEquipo
{

    //var $conn;

    // public function __construct()
    // {
    // }
    public function TrasdalarEquipo(array $equiposTrasladar): Resultado
    {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $resultado = new Resultado();
        $resultado->esValido = true;
        $resultado->mensaje = "Se traslado el equipo correctamente";
        $CodigosProcesados = [];
        $CodigoEquipoQueFallo = "";
        mysqli_begin_transaction($conn);
        try {
            $insertarBoleta = true;;
            foreach ($equiposTrasladar as &$equipo) {
                if ($resultado->esValido) {
                    $sqlObtenerEquipo = "SELECT th.Codigo,th.ID_Tipo,th.Ubicacion,th.Disposicion,tt.TipoEquipo 
                                      FROM tbl_herramientaelectrica th INNER JOIN 
                                      tbl_tipoherramienta tt on th.ID_Tipo = tt.ID_Tipo
                                      WHERE th.Codigo = '" . $equipo->CodigoEquipo . "'";
                    $CodigoEquipoQueFallo =  $equipo->CodigoEquipo;
                    $resultadoObtenerEquipo = $conn->query($sqlObtenerEquipo);

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
                            $resultadoInsertarBoleta =  $conn->query($sqlInsertarBoleta);
                            $insertarBoleta = false;
                        }

                        if ($resultadoInsertarBoleta) {
                            $sqlInsertarPrestamo = "Insert into tbl_prestamoherramientas(NBoleta,ID_Proyecto,Codigo,Estado,FechaSalida,ID_Tipo) values
                          (" . $equipo->NumBoleta . "," . $equipo->IdProyectoDestino . ",'" . $equipo->CodigoEquipo . "',1,Now()," . $equipo->IdTipoEquipo . ")";

                            $resultadoInsertarPrestamo = $conn->query($sqlInsertarPrestamo);

                            if ($resultadoInsertarPrestamo) {
                                $controlarMovimientos = new MTrasladarEquipo();
                                $esValidoElMovimiento = $controlarMovimientos->ControlarMovimientosEquipo($conn, $equipo->CodigoEquipo, $equipo->IdUbicacionActual, $equipo->IdProyectoDestino);
                                if ($esValidoElMovimiento->esValido) {

                                    $sqlInsertarHistorial =  "Insert into tbl_historialherramientas(Codigo,Ubicacion,Destino,NumBoleta,Fecha) 
                                                      values('" . $equipo->CodigoEquipo . "'," . $equipo->IdUbicacionActual . "," . $equipo->IdProyectoDestino . ",'" . $equipo->NumBoleta . "',Now())";
                                    $resultadoInsertarHistorial = $conn->query($sqlInsertarHistorial);

                                    if ($resultadoInsertarHistorial) {
                                        $sqlActualizarHerramienta = "";
                                        if ($equipo->IdProyectoDestino == Constantes::Bodega) {
                                            $sqlActualizarHerramienta = "UPDATE tbl_herramientaelectrica SET Ubicacion=" . Constantes::Bodega . ", Disposicion = '1' WHERE Codigo = '" . $equipo->CodigoEquipo . "'";
                                        } else {
                                            $sqlActualizarHerramienta = "UPDATE tbl_herramientaelectrica SET Ubicacion=" . $equipo->IdProyectoDestino . ", Disposicion = 0 WHERE Codigo = '" . $equipo->CodigoEquipo . "'";
                                        }
                                        $resultadoActualizarHerramienta =   $conn->query($sqlActualizarHerramienta);

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
                                    $resultado->mensaje ="Fall?? el registro del movimiento del equipo";
                                    $CodigoEquipoQueFallo = $equipo->CodigoEquipo;
                                }
                            } else {
                                $resultado->esValido = false;
                            }
                        } else {
                            $resultado->esValido = false;
                            $resultado->mensaje = "Fall?? al registrar la boleta";
                        }
                    } else {
                        $resultado->esValido = false;
                        $resultado->mensaje = "No se encontro el quipo que desea trasladar " . $equipo->CodigoEquipo;
                    }
                } else {
                    $resultado->mensaje = "Fall?? el transado del equipo, debido a que se presentaron problemas con el siguiente equipo " . $CodigoEquipoQueFallo;
                    break;
                }
            }

            if (!$resultado->esValido) {
                mysqli_rollback($conn);
                $class = new MTrasladarEquipo();
                $class->ReversarTrasaldos($CodigosProcesados);
            } else {
                mysqli_commit($conn);
            }
            $conn->close();
            return $resultado;
        } catch (\Throwable $th) {
            mysqli_rollback($conn);
            $conn->close();
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
            $conexion = new Conexion();
            $conn = $conexion->CrearConexion();
            $mensaje = "Se envio a revesar un traslado de equipo, el siguiente equipo:";
            $equipoRevesar = json_encode($equipoProcesados);
            Log::GuardarEventoString($mensaje, "ReversarTrasaldos");
            Log::GuardarEventoString($equipoRevesar, "ReversarTrasaldos");

            foreach ($equipoProcesados as &$equipo) {

                $sqlReversarPrestamo = "DELETE FROM tbl_prestamoherramientas WHERE Codigo = '" . $equipo->CodigoEquipo . "' AND ID_Proyecto = '$equipo->IdProyectoDestino' AND NBOLETA = '$equipo->NumBoleta';";
                $sqlReverdarHistorial = "DELETE FROM tbl_historialherramientas WHERE Codigo = '" . $equipo->CodigoEquipo . "' AND NumBoleta = '$equipo->NumBoleta'; ";
                $sqlReversarBoleta = "DELETE FROM tbl_boletaspedido WHERE Consecutivo = '" . $equipo->NumBoleta . "'; ";
                $sqlReversarHerramienta = "UPDATE tbl_herramientaelectrica SET Ubicacion=" . $equipo->IdUbicacionActual . ", Disposicion = '$equipo->DisposicionActual' WHERE Codigo = '" . $equipo->CodigoEquipo . "'; ";

                $resultado1 =   $conn->query($sqlReversarPrestamo);
                $resultado2 =   $conn->query($sqlReverdarHistorial);
                $resultado3 =   $conn->query($sqlReversarBoleta);
                $resultado4 =   $conn->query($sqlReversarHerramienta);
            }
            $conn->close();
        } catch (\Throwable $th) {
            echo Log::GuardarEvento($th, "ReversarTrasaldos");
        }
    }

    public function ConsultarMaquinariaTrasladar($codigo, $id_tipo, $ubicacion, $tipoEquipo)
    {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $where = "";
        if ($codigo != "") {
            $where = "where a.Codigo = '$codigo' and a.ID_Tipo = b.ID_Tipo and
            a.Ubicacion = c.ID_Proyecto and
            b.TipoEquipo = '" . $tipoEquipo . "' and
            a.Estado = 1 and c.ID_Proyecto != 13";
        } else if ($id_tipo != "") {
            $where = "where a.ID_Tipo = $id_tipo and a.ID_Tipo = b.ID_Tipo and
            a.Ubicacion = c.ID_Proyecto and
            b.TipoEquipo = '" . $tipoEquipo . "' and
            a.Estado = 1 and c.ID_Proyecto != 13";
        } else if ($ubicacion != "") {
            $where = "where a.Ubicacion = $ubicacion and a.ID_Tipo = b.ID_Tipo and
            a.Ubicacion = c.ID_Proyecto and
            b.TipoEquipo = '" . $tipoEquipo . "' and
            a.Estado = 1 and c.ID_Proyecto != 13";
        } else {
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
        $resultadoObtenerEquipo = $conn->query($sql);
        $conn->close();
        return $resultadoObtenerEquipo;
    }

    public function ObternerCosecutivoBoleta()
    {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = " select Consecutivo from tbl_boletaspedido order by Consecutivo desc limit 1;";
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }


    public function ControlarMovimientosEquipo($conexion, $codigo, $idProyectoActual, $idProyectoDestino)
    {

        $resultado = new Resultado();
        $resultado->esValido = false;
        try {

            $sqlObtenerMovimiento = "SELECT ID_Movimiento,ID_Proyecto,Codigo,FechaEntrada,FechaSalida FROM `tbl_movientosequipoproyecto` 
            WHERE ID_Proyecto = $idProyectoActual AND Codigo = '$codigo'
            ORDER by ID_Movimiento desc 
            LIMIT 1;";
            $resultadoMovimiento = $conexion->query($sqlObtenerMovimiento);
            if ($resultadoMovimiento != null && mysqli_num_rows($resultadoMovimiento) > 0) {
                $fila = mysqli_fetch_array($resultadoMovimiento, MYSQLI_ASSOC);
                $ultimoMovimiento = $fila["ID_Movimiento"];
                $sqlActualizarFechaSalida = "UPDATE tbl_movientosequipoproyecto SET FechaSalida = CURRENT_DATE()
                                              where ID_Movimiento = $ultimoMovimiento";
                if ($idProyectoActual != Constantes::Bodega) {

                    if ($conexion->query($sqlActualizarFechaSalida)) {
                        if ($idProyectoDestino != Constantes::Bodega) {
                            $sqlInsertarNuevoMovimiento = "INSERT INTO tbl_movientosequipoproyecto(Codigo,ID_Proyecto,FechaEntrada) 
                                                            VALUES('$codigo',$idProyectoDestino,CURRENT_DATE())";
                            if ($conexion->query($sqlInsertarNuevoMovimiento)) {
                                $resultado->esValido = true;
                            }
                        } else
                            $resultado->esValido = true;
                    }
                } else
                    $resultado->esValido = true;
            } else {
                if ($idProyectoDestino != Constantes::Bodega) {
                    $sqlInsertarNuevoMovimiento = "INSERT INTO tbl_movientosequipoproyecto(Codigo,ID_Proyecto,FechaEntrada) 
                                                VALUES('$codigo',$idProyectoDestino,CURRENT_DATE())";
                    if ($conexion->query($sqlInsertarNuevoMovimiento)) {
                        $resultado->esValido = true;
                    }
                } else
                    $resultado->esValido = true;
            }

            return $resultado;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
