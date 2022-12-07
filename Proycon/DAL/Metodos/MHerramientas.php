<?php

class MHerramientas implements IHerrramientas
{

    var $conn;

    public function __construct()
    {

        $conexion = new Conexion();
        $this->conn = $conexion->CrearConexion();
    }

    public function FacturacionReparacion(Factura $Facturacion)
    {
        mysqli_begin_transaction($this->conn);
        $resultado = new Resultado();
        $resultado->esValido = false;
        try {
            $Facturacion->Codigo = LimpiarCadenaCaracter($this->conn, $Facturacion->Codigo);
            $Facturacion->NumFactura = LimpiarCadenaCaracter($this->conn, $Facturacion->NumFactura);
            $Facturacion->DescripcionFactura = LimpiarCadenaCaracter($this->conn, $Facturacion->DescripcionFactura);
            $Facturacion->FechaEntrada = LimpiarCadenaCaracter($this->conn, $Facturacion->FechaEntrada);
            $Facturacion->NumBoleta = LimpiarCadenaCaracter($this->conn, $Facturacion->NumBoleta);
            $sqlUpdateReparacion = "UPDATE tbl_reparacionherramienta SET 
            ID_FacturaReparacion=" . $Facturacion->NumFactura . ",
            Descripcion='" . $Facturacion->DescripcionFactura . "', 
            FechaEntrada='" . $Facturacion->FechaEntrada . "', 
            MontoReparacion=" . $Facturacion->CostoFactura . "
            where Codigo = '$Facturacion->Codigo'
            and NumBoleta = '$Facturacion->NumBoleta'";


            $sqlEliminarEquipoReparacion = "Delete from tbl_tempoherramientareparacion where Codigo = '$Facturacion->Codigo'";
            // Insetar el transtalo de Bodega al proyecto 
            $sqlObtenerUbicacion = "select Ubicacion from tbl_herramientaelectrica where Codigo = '$Facturacion->Codigo'";
            $ubicacion = "";
            if ($ubi =  $this->conn->query($sqlObtenerUbicacion)) {
                while ($fila = mysqli_fetch_array($ubi, MYSQLI_ASSOC)) {
                    $ubicacion = $fila['Ubicacion'];
                }
            }
            //Si la ubicacion despues de que regresa de reparacion es bodega se mantiene disponible si no se pone en estado no dispobible
            if ($ubicacion == 1)
                $sqlActualizarHerramienta = "UPDATE tbl_herramientaelectrica SET Disposicion = 1,Estado = 1 
            WHERE Codigo = '$Facturacion->Codigo'";
            else {
                $sqlActualizarHerramienta = "UPDATE tbl_herramientaelectrica SET Disposicion = 0,Estado = 1 
            WHERE Codigo = '$Facturacion->Codigo'";
            }

            $sqlInsertarHistorial = "Insert into tbl_historialherramientas (Codigo,Ubicacion,Destino,NumBoleta,Fecha) values ('" . $Facturacion->Codigo . "','" . "1000" . "','" . $ubicacion . "','" . $Facturacion->NumBoleta . "'," . "'$Facturacion->FechaEntrada');";
            //Insertar en el historial


            if ($ubicacion != "") {
                if ($this->conn->query($sqlUpdateReparacion)) {
                    if ($this->conn->query($sqlEliminarEquipoReparacion)) {
                        if ($this->conn->query($sqlInsertarHistorial)) {
                            if ($this->conn->query($sqlActualizarHerramienta)) {
                                $resultado->esValido = true;
                                $resultado->mensaje = "Se procesaron los datos correctamente";
                            }
                            else
                              $resultado->mensaje = "Ocurrio al actualizar el equipo";
                        }
                        else
                           $resultado->mensaje = "Ocurrio un error al insertar el historial del equipo";
                    }
                    else
                        $resultado->mensaje = "Ocurrio un error al sacar el equipo de reparación";
                }
                else
                  $resultado->mensaje = "Ocurrio un error al ingresar la factura de la herramienta";
            }
            else
                $resultado->mensaje = "No se puedo obtener la ubicación actual del equipo";
            

            if ($resultado->esValido) {
                mysqli_commit($this->conn);
            } else {
                mysqli_rollback($this->conn);
            }

            $this->conn->close();
            return $resultado;
        } catch (\Throwable $th) {
            mysqli_rollback($this->conn);
            Log::GuardarEvento($th, "FacturacionReparacion");
            $resultado->mensaje = "Falló el proceso de facturación ".$th->getMessage();
            $this->conn->close();
            return $resultado;
        }
    }

    public function listaEnviadas($codigo)
    {
        $sql = "select a.ID_FacturaReparacion from tbl_reparacionherramienta a, tbl_herramientaelectrica b where a.Codigo = b.Codigo and a.Codigo = '$codigo';";
        $resultado = $this->conn->query($sql);
        $this->conn->close();
        return $resultado;
    }

    public function BuscarHerramientaPorCodigo($codigo)
    {
        $sql = "SELECT tt.ID_Tipo,Codigo, tt.Descripcion,th.Descripcion AS DesH,Marca,th.Precio,th.Procedencia,th.FechaIngreso from tbl_herramientaelectrica th, tbl_tipoherramienta tt
                where th.Codigo= '" . $codigo . "' AND th.ID_Tipo = tt.ID_Tipo AND tt.TipoEquipo = 'H'";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function BuscarMaquinariaPorCodigo($codigo)
    {
        $sql = "SELECT tt.ID_Tipo,Codigo, tt.Descripcion,th.Descripcion AS DesH,Marca,th.Precio,th.Procedencia,th.FechaIngreso from tbl_herramientaelectrica th, tbl_tipoherramienta tt
                where th.Codigo= '" . $codigo . "' AND th.ID_Tipo = tt.ID_Tipo AND tt.TipoEquipo = 'M'";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function RegistrarTipoHerramienta(Herramientas $Tipo)
    {

        if ($this->conn->connect_errno) {
            return -1;
        }
        $sql = "Insert into tbl_tipoherramienta(ID_Tipo,Descripcion) values (?,?)";
        $Tipo->tipo = LimpiarCadenaCaracter($this->conn, $Tipo->tipo);
        $Tipo->descripcion = LimpiarCadenaCaracter($this->conn, $Tipo->descripcion);
        $OK = false;
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("is", $Tipo->tipo, $Tipo->descripcion);
            $OK = $stmt->execute();
        }
        $stmt->close();
        $this->conn->close();
        return $OK ? 1 : 0;
    }

    // LISTADO DEL TIPO DE HERRAMIENTAS

    public function listarTipoHerramientas()
    {
        if ($this->conn->connect_errno) {
            return -1;
        }
        $sql = "select ID_Tipo, Descripcion from tbl_tipoherramienta";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    // OBTENER EL CONSECUTIVO EN EL TIPO DE HERRAMIENTA

    public function ObtenerConsecutivoTipo()
    {
        $sql = "Select ID_Tipo from tbl_tipoherramienta order by ID_Tipo desc limit 1";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }

        $result = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $result;
    }

    // JALA TODOS LOS VALORES DE LAS HERRAMIENTAS

    public function listarTotalHerramientas()
    {
        $sql = "select Codigo,
         b.Descripcion as Tipo,
         a.Descripcion,
         FechaIngreso, 
         IF(Disposicion = '1','Disponible','No Disponible')as Disposicion, 
         c.Nombre,
         IF(a.Estado = '1','Buena','En Reparacion')as Estado,
         a.Estado as numEstado,
         Precio,
         MonedaCompra,
         b.PrecioEquipo as PrecioAlquiler,
         b.CodigoMonedaCobro,
         b.CodigoFormaCobro 
        from tbl_herramientaelectrica a, tbl_tipoherramienta b, tbl_proyectos c where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto";
        $sql2 = "Delete from tbl_trasladotemporal where idTrasladoT = '1'";
        $this->conn->query($sql2);
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    // JALA TODOS LOS VALORES DE LAS HERRAMIENTAS MENOS LOS QUE ESTAN DAÑADOS

    public function listarTotalHerramientasTranslado()
    {

        $sql = "Select Codigo, b.Descripcion as Tipo, FechaIngreso, IF(Disposicion = '1','Disponible','No Disponible')as Disposicion, c.Nombre,IF(a.Estado = '1','Buena','En Reparacion')as Estado,a.Estado as numEstado from tbl_herramientaelectrica a, tbl_tipoherramienta b, tbl_proyectos c where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto and a.Estado = 1 and Codigo = '0' ";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $sql2 = "Delete from tbl_trasladotemporal where idTrasladoT = '1'";
        $this->conn->query($sql2);
        $this->conn->close();
        return $resultado;
    }

    //  FILTRO DE TRASLADO DE HERRAMIENTAS POR TIPO

    public function FiltroTrasladoTipo($tipo)
    {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = "Select Codigo, b.Descripcion as Tipo, FechaIngreso, IF(Disposicion = '1','Disponible','No Disponible')as Disposicion, c.Nombre,c.ID_Proyecto,IF(a.Estado = '1','Buena','En Reparacion')as Estado,a.Estado as numEstado from tbl_herramientaelectrica a, tbl_tipoherramienta b, tbl_proyectos c where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto and a.Estado = 1 
		and b.ID_Tipo = '$tipo';";
        $resultado = $conn->query($sql);
        $conn->close();
        return $resultado;
    }

    //  FILTRO DE TRASLADO DE HERRAMIENTAS POR UBICACION

    public function FiltrosHerramientasU($ubicacion)
    {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = "Select Codigo, b.Descripcion as Tipo, FechaIngreso, IF(Disposicion = '1','Disponible','No Disponible')as Disposicion, c.Nombre,c.ID_Proyecto,IF(a.Estado = '1','Buena','En Reparacion')as Estado,a.Estado as numEstado from tbl_herramientaelectrica a, tbl_tipoherramienta b, tbl_proyectos c where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto and a.Estado = 1 
		and a.Ubicacion = '$ubicacion';";
        $resultado = $conn->query($sql);
        $conn->close();
        return $resultado;
    }

    public function totalReparaciones()
    {
        if ($this->conn->connect_errno) {
            return -1;
        }
        $sql = "SELECT tr.ID,tr.Codigo,tt.Descripcion,tr.Fecha,DATEDIFF(CURDATE(),tr.Fecha) as Dias, tr.Boleta  ,tb.ProveedorReparacion from tbl_tempoherramientareparacion tr,tbl_tipoherramienta tt, tbl_herramientaelectrica th ,tbl_boletareparacion tb WHERE tr.Codigo = th.Codigo and tr.Boleta = tb.NumBoleta and  th.ID_Tipo = tt.ID_Tipo;";
        $resultado = $this->conn->query($sql);
        $sql2 = "Delete from tbl_trasladotemporal where idTrasladoT = '1'";
        $this->conn->query($sql2);
        $this->conn->close();
        return $resultado;
    }

    public function cambiarTipo($ID_Tipo, $DescripcionTipo)
    {

        if ($this->conn->connect_errno) {
            return -1;
        }
        $sql = "UPDATE tbl_tipoherramienta SET Descripcion=? WHERE ID_Tipo= ?";
        $DescripcionTipo = LimpiarCadenaCaracter($this->conn, $DescripcionTipo);
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("si", $DescripcionTipo, $ID_Tipo);
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function FacturaReparacion($idReparacion)
    {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        if ($conn->connect_errno) {
            return -1;
        }
        $sql = ""; //"UPDATE tbl_reparacionherramienta SET Descripcion='$DescripcionTipo' WHERE ID_Tipo='$ID_Tipo'";
        $resultado = $conn->query($sql);
        $conn->close();
        return $resultado;
    }

    // <!--Agregar una Nueva Herramienta -->

    public function RegistrarHerramientas(Herramientas $Herramientas)
    {

        if ($this->conn->connect_errno) {
            return -1;
        }

        $sql = "SELECT Codigo FROM tbl_herramientaelectrica WHERE Codigo =?";

        if ($stmt = $this->conn->prepare($sql)) {
            $codigo = LimpiarCadenaCaracter($this->conn, $Herramientas->codigo);
            $stmt->bind_param("s", $codigo);
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $OK = TRUE;
        $result = $stmt->get_result();
        $stmt->close();
        if (mysqli_num_rows($result) > 0) {    // si el resultado es 1 quiere decir que si existe, por lo tanto no entra a insertar el registro 
            $this->conn->close();
            return 0;
        } else {
            $sql = "Insert into tbl_herramientaelectrica(ID_Tipo,Codigo,Marca,Descripcion,FechaIngreso,Estado,Disposicion,Procedencia,Ubicacion,Precio,NumFactura,MonedaCompra) values(?,?,?,?,?,?,?,?,?,?,?,?)";
            if ($stmt = $this->conn->prepare($sql)) {
                $Herramientas->codigo = LimpiarCadenaCaracter($this->conn, $Herramientas->codigo);
                $Herramientas->marca = LimpiarCadenaCaracter($this->conn, $Herramientas->marca);
                $Herramientas->descripcion = LimpiarCadenaCaracter($this->conn, $Herramientas->descripcion);
                $Herramientas->fechaIngreso = LimpiarCadenaCaracter($this->conn, $Herramientas->fechaIngreso);
                $Herramientas->estado = LimpiarCadenaCaracter($this->conn, $Herramientas->estado);
                $Herramientas->disposicion = LimpiarCadenaCaracter($this->conn, $Herramientas->disposicion);
                $Herramientas->procedencia = LimpiarCadenaCaracter($this->conn, $Herramientas->procedencia);
                $Herramientas->ubicacion = LimpiarCadenaCaracter($this->conn, $Herramientas->ubicacion);
                $Herramientas->precio = LimpiarCadenaCaracter($this->conn, $Herramientas->precio);
                $Herramientas->numFactura = LimpiarCadenaCaracter($this->conn, $Herramientas->numFactura);
                $Herramientas->monedaCompra = LimpiarCadenaCaracter($this->conn, $Herramientas->monedaCompra);
                $stmt->bind_param("issssiisiiss", $Herramientas->tipo, $Herramientas->codigo, $Herramientas->marca, $Herramientas->descripcion, $Herramientas->fechaIngreso, $Herramientas->estado, $Herramientas->disposicion, $Herramientas->procedencia, $Herramientas->ubicacion, $Herramientas->precio, $Herramientas->numFactura,$Herramientas->monedaCompra);
                $OK = $stmt->execute();
            } else {
                echo "Error de sintaxis en consulta SQL ";
            }
            $this->conn->close();
            return $OK ? 1 : 0;
        }
    }

    // <!--Agregar el consecutivo de la Nueva Herramienta -->


    public function ObtenerConsecutivoHerramienta()
    {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = "SELECT MAX(SUBSTRING(Codigo, 2,6) + 0) AS mayor from tbl_herramientaelectrica;";
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }

    public function ObternerCosecutivoReparacion()
    {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = " select NumBoleta from tbl_boletareparacion order by NumBoleta desc limit 1;";
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }

    public function ObternerCosecutivoPedido()
    {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = " select Consecutivo from tbl_boletaspedido order by Consecutivo desc limit 1;";
        $result = $conn->query($sql);
        $conn->close();
        return $result;
    }

    public function BuscarHerramientaNombre($descripcion)
    {
        if ($this->conn->connect_errno) {
            return -1;
        } else {
            $descripcion = LimpiarCadenaCaracter($this->conn, $descripcion);
            $sql = "SELECT a.Codigo,tt.Descripcion,a.Marca, b.Nombre,IF(a.Estado = '1','Buena','Mala')as Estado FROM tbl_herramientaelectrica a, tbl_proyectos b, tbl_tipoherramienta tt where a.Ubicacion = b.ID_Proyecto and a.ID_Tipo = tt.ID_Tipo  and  a.Codigo = ?";
            if ($stmt = $this->conn->prepare($sql)) {
                $stmt->bind_param("s", $descripcion);
                $stmt->execute();
            } else {
                echo "Error de sintaxis en consulta SQL ";
            }
            $resultado = $stmt->get_result();
            $stmt->close();
            $this->conn->close();
            return $resultado;
        }
    }

    public function RegistrarReparacion($consecutivo, $fecha, $ID_Usuario, $provedorReparacion)
    {
        $consecutivo = LimpiarCadenaCaracter($this->conn, $consecutivo);
        $provedorReparacion = LimpiarCadenaCaracter($this->conn, $provedorReparacion);
        $sql = "Insert into tbl_boletareparacion(Numboleta,Fecha,ID_Usuario,ProveedorReparacion,TipoEquipo) values (?,?,?,?,?);";
        if ($stmt = $this->conn->prepare($sql)) {
            $tipoEquipo = "H";
            $stmt->bind_param("isiss", $consecutivo, $fecha, $ID_Usuario, $provedorReparacion, $tipoEquipo);
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function RegistrarReparacionHerramienta($consecutivo, $codigoHerramienta, $fecha)
    {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = "Insert into tbl_reparacionherramienta (Codigo,FechaSalida,NumBoleta) values ('" . $codigoHerramienta . "','" . $fecha . "',$consecutivo);";
        $sql3 = "Insert into tbl_tempoherramientareparacion(Codigo,Fecha,Boleta) values('$codigoHerramienta','$fecha',$consecutivo)";
        $sql2 = "UPDATE tbl_herramientaelectrica SET Disposicion = 0, Estado = 0 WHERE Codigo='$codigoHerramienta'";


        // validar para insertar en el historial

        $sql4 = "select Ubicacion from tbl_herramientaelectrica where Codigo = '$codigoHerramienta'";
        $ubi = $conn->query($sql4);

        if ($ubi <> null) {
            while ($fila = mysqli_fetch_array($ubi, MYSQLI_ASSOC)) {
                $ubicacion = $fila['Ubicacion'];
            }
        }

        //Insertar en el historial
        if ($sql4 <> null) {
            $sql5 = "Insert into tbl_historialherramientas (Codigo,Ubicacion,Destino,NumBoleta,Fecha) values ('" . $codigoHerramienta . "','" . $ubicacion . "','" . "1000" . "','" . $consecutivo . "'," . "'$fecha');";
            $conn->query($sql5);
        }


        $result = $conn->query($sql);
        $conn->query($sql2);
        $conn->query($sql3);
        $conn->close();
        return $result;
    }

    public function listarBoletasReparacion()
    {
        $sql = "SELECT a.NumBoleta,a.Fecha,b.Nombre FROM tbl_boletareparacion a, tbl_usuario b where b.ID_Usuario = a.ID_Usuario order by NumBoleta DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $this->conn->close();
        return $resultado;
    }

    public function eliminarBoletaR($eliboleta)
    {
        $eliboleta = LimpiarCadenaCaracter($this->conn, $eliboleta);
        $sql = "delete from tbl_boletareparacion where NumBoleta = '$eliboleta';";
        $result = $this->conn->query($sql);
        $sql2 = "select * from tbl_reparacionherramienta where NumBoleta = '$eliboleta';";
        $rs_cambio = mysqli_query($this->conn, $sql2);
        while ($row = mysqli_fetch_array($rs_cambio)) {
            $Codigo = $row['Codigo'];
            $sql3 = "UPDATE tbl_herramientaelectrica SET Disposicion = 1, Estado = 1 WHERE Codigo= '$Codigo'";
            $result = $this->conn->query($sql3);
        }

        $sql4 = "delete from tbl_reparacionherramienta where NumBoleta = '$eliboleta';";
        $this->conn->query($sql4);

        $sql5 = "delete from tbl_tempoherramientareparacion where Boleta = '$eliboleta';";
        $this->conn->query($sql5);
        $this->conn->close();
        return $result;
    }

    public function EliminarTraslado($CodigoTH)
    {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = "Delete from tbl_trasladotemporal where Codigo = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $CodigoTH);
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function ListarTrasladoMo()
    {
        $sql = "SELECT a.Codigo,c.Nombre as Ubicacion,a.FechaIngreso,a.Marca,a.Descripcion FROM tbl_herramientaelectrica a, tbl_trasladotemporal b,tbl_proyectos c WHERE a.Codigo = b.Codigo and a.Ubicacion = c.ID_Proyecto;";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function GuardarTrasladoT($CodigoT)
    {
        try {

            $sql1 = "SELECT Codigo FROM tbl_trasladotemporal WHERE Codigo = ? ";
            $CodigoT = LimpiarCadenaCaracter($this->conn, $CodigoT);
            if ($stmt = $this->conn->prepare($sql1)) {
                $stmt->bind_param("s", $CodigoT);
                $stmt->execute();
            } else {
                echo "Error de sintaxis en consulta SQL ";
            }
            $resultado = $stmt->get_result();
            if (mysqli_num_rows($resultado) > 0) {
                $sqldelte = "DELETE FROM tbl_trasladotemporal WHERE Codigo = ?";
                $stmt = $this->conn->prepare($sqldelte);
                $stmt->bind_param("s", $CodigoT);
                $stmt->execute();
                $result = 0;
            } else {
                $sql = "INSERT INTO tbl_trasladotemporal (Codigo, idTrasladoT) VALUES (?,'1');";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("s", $CodigoT);
                $stmt->execute();
                $result = 1;
            }
            $this->conn->close();
            $stmt->close();
            return $result;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function VerBoletaReparacion($NumBoleta)
    {
        $NumBoleta = LimpiarCadenaCaracter($this->conn, $NumBoleta);
        $sql = "SELECT tr.Codigo,tt.Descripcion,th.Marca, boleta.ProveedorReparacion as proveedor from tbl_reparacionherramienta tr, tbl_herramientaelectrica th, tbl_tipoherramienta tt, tbl_boletareparacion boleta WHERE tr.Codigo = th.Codigo and th.ID_Tipo = tt.ID_Tipo and tr.NumBoleta = boleta.NumBoleta and tr.NumBoleta = ? ;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $NumBoleta);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $this->conn->close();
        $stmt->close();
        return $resultado;
    }

    // FILTROS QUE ORDENAN EL TOTAL DE HERRAMIENTAS

    public function FiltrosHerramientas0()
    {

        $sql = "select Codigo,
         b.Descripcion as Tipo,
         a.Descripcion,
         FechaIngreso,
         IF(Disposicion = '1','Disponible','No Disponible')as Disposicion,
         c.Nombre,IF(a.Estado = '1','Buena','En Reparacion')as Estado,
         a.Estado as numEstado,
         Precio,
         b.PrecioEquipo as PrecioAlquiler,
         b.CodigoMonedaCobro,
         b.CodigoFormaCobro 
         from tbl_herramientaelectrica a,
         tbl_tipoherramienta b,
         tbl_proyectos c 
         where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $this->conn->close();
        $stmt->close();
        return $resultado;
    }

    public function FiltrosHerramientas1()
    {
        $sql = "select Codigo,
         b.Descripcion as Tipo,
         a.Descripcion,
         FechaIngreso,
         IF(Disposicion = '1','Disponible','No Disponible')as Disposicion,
         c.Nombre,IF(a.Estado = '1','Buena','En Reparacion')as Estado,
         a.Estado as numEstado,
         Precio,
         b.PrecioEquipo as PrecioAlquiler,
         b.CodigoMonedaCobro,
         b.CodigoFormaCobro  
         from tbl_herramientaelectrica a,
         tbl_tipoherramienta b,
         tbl_proyectos c 
         where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto ORDER BY b.ID_Tipo";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $this->conn->close();
        $stmt->close();
        return $resultado;
    }

    public function FiltrosHerramientas2()
    {
        $sql = "select Codigo,
        b.Descripcion as Tipo,
        a.Descripcion,
        FechaIngreso,
        IF(Disposicion = '1','Disponible','No Disponible')as Disposicion,
        c.Nombre,IF(a.Estado = '1','Buena','En Reparacion')as Estado,
        a.Estado as numEstado,
        Precio,
        b.PrecioEquipo as PrecioAlquiler,
        b.CodigoMonedaCobro,
        b.CodigoFormaCobro  
        from tbl_herramientaelectrica a,
        tbl_tipoherramienta b,
        tbl_proyectos c 
        where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto ORDER BY Disposicion;";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $this->conn->close();
        $stmt->close();
        return $resultado;
    }

    public function FiltrosHerramientas3()
    {
        $sql = "select Codigo,
        b.Descripcion as Tipo,
        a.Descripcion,
        FechaIngreso,
        IF(Disposicion = '1','Disponible','No Disponible')as Disposicion,
        c.Nombre,IF(a.Estado = '1','Buena','En Reparacion')as Estado,
        a.Estado as numEstado,
        Precio,
        b.PrecioEquipo as PrecioAlquiler,
        b.CodigoMonedaCobro,
        b.CodigoFormaCobro  
        from tbl_herramientaelectrica a,
        tbl_tipoherramienta b,
        tbl_proyectos c 
        where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto ORDER BY a.Ubicacion,b.Descripcion;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $this->conn->close();
        $stmt->close();
        return $resultado;
    }

    public function FiltrosHerramientas4()
    {
        $sql = "select Codigo,
        b.Descripcion as Tipo,
        a.Descripcion,
        FechaIngreso,
        IF(Disposicion = '1','Disponible','No Disponible')as Disposicion,
        c.Nombre,IF(a.Estado = '1','Buena','En Reparacion')as Estado,
        a.Estado as numEstado,
        Precio,
        b.PrecioEquipo as PrecioAlquiler,
        b.CodigoMonedaCobro,
        b.CodigoFormaCobro  
        from tbl_herramientaelectrica a,
        tbl_tipoherramienta b,
        tbl_proyectos c 
        where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto ORDER BY a.Estado;";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $this->conn->close();
        $stmt->close();
        return $resultado;
    }

    public function FiltroReparacionfecha($fecha)
    {
        $fecha = LimpiarCadenaCaracter($this->conn, $fecha);
        $sql = "select ID_Reparacion,a.Codigo,b.Descripcion ,FechaSalida,FechaEntrada,NumBoleta from tbl_reparacionherramienta a, tbl_tipoherramienta b,  tbl_herramientaelectrica c where c.Codigo = a.Codigo and c.ID_Tipo = b.ID_Tipo and a.FechaSalida = ?;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $fecha);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $this->conn->close();
        return $resultado;
    }

    public function FiltroReparacionTipo($tipo)
    {
        $tipo = LimpiarCadenaCaracter($this->conn, $tipo);
        $sql = "SELECT tr.ID,tr.Codigo,tt.Descripcion,tr.Fecha,DATEDIFF(CURDATE(),tr.Fecha) as Dias,r.ProveedorReparacion ,tr.Boleta from tbl_tempoherramientareparacion tr,tbl_tipoherramienta tt, tbl_herramientaelectrica th, tbl_boletareparacion r WHERE tr.Codigo = th.Codigo and th.ID_Tipo = tt.ID_Tipo and tr.Boleta=r.NumBoleta and tt.ID_Tipo = ? ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $tipo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $this->conn->close();
        $stmt->close();
        return $resultado;
    }

    public function FiltroReparacionCodigo($codigo)
    {
        $codigo = LimpiarCadenaCaracter($this->conn, $codigo);
        $sql = "SELECT tr.ID,tr.Codigo,tt.Descripcion,tr.Fecha,DATEDIFF(CURDATE(),tr.Fecha) as Dias, tr.Boleta,re.ProveedorReparacion from tbl_tempoherramientareparacion tr,tbl_tipoherramienta tt, tbl_herramientaelectrica th,tbl_boletareparacion re WHERE tr.Codigo = th.Codigo and th.ID_Tipo = tt.ID_Tipo and tr.Boleta= re.NumBoleta and th.Codigo = ? ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $codigo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function FiltroReparacionboleta($boleta)
    {
        $boleta = LimpiarCadenaCaracter($this->conn, $boleta);
        $sql = "select ID_Reparacion,a.Codigo,b.Descripcion ,FechaSalida,FechaEntrada,NumBoleta from tbl_reparacionherramienta a, tbl_tipoherramienta b,  tbl_herramientaelectrica c where c.Codigo = a.Codigo and c.ID_Tipo = b.ID_Tipo and a.NumBoleta = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $boleta);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function buscarherramienCodigo($Cod)
    {
        $$Cod = LimpiarCadenaCaracter($this->conn, $Cod);
        $sql = "select ID_Herramienta,Codigo,Marca,NumFactura,Procedencia,b.ID_Tipo, b.Descripcion as Tipo,a.Descripcion, FechaIngreso, IF(Disposicion = '1','Disponible','No Disponible')as Disposicion, c.Nombre,IF(a.Estado = '1','Buena','En Reparacion')as Estado,a.Estado as numEstado,Precio,b.PrecioEquipo as PrecioAlquiler,
        b.CodigoMonedaCobro,
        b.CodigoFormaCobro, MonedaCompra  from tbl_herramientaelectrica a, tbl_tipoherramienta b, tbl_proyectos c where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto and a.Codigo = ? ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $Cod);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    // FILTRO POR CODIGO PARA TRASLADO
    public function buscarTraslado($Cod)
    {
        $$Cod = LimpiarCadenaCaracter($this->conn, $Cod);
        $sql = "Select Codigo, b.Descripcion as Tipo, FechaIngreso, IF(Disposicion = '1','Disponible','No Disponible')as Disposicion, c.Nombre,c.ID_Proyecto,IF(a.Estado = '1','Buena','En Reparacion')as Estado,a.Estado as numEstado from tbl_herramientaelectrica a, tbl_tipoherramienta b, tbl_proyectos c where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto and a.Estado = 1 
		and  a.Codigo = ? ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $Cod);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    // FILTRO PARA EL TOTAL DE REPARACIONES DE LA HERRAMIENTA HISTORIAL
    public function reparacionesTotales($codigo)
    {
        try {
            $codigo = LimpiarCadenaCaracter($this->conn, $codigo);
            $herramienas = new MHerramientas();
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
        } catch (Exception $exc) {
            $exc->getTraceAsString();
        }
    }

    // FILTRO PARA EL TOTAL DE TRASLADOS DE LA HERRAMIENTA HISTORIAL
    public function trasladosTotales($codigo)
    {
        $sql = "SELECT x.NumBoleta, x.Fecha,(SELECT b.Nombre from tbl_proyectos b where x.Ubicacion = b.ID_Proyecto) as Ubicacion,(SELECT b.Nombre from tbl_proyectos b where x.Destino = b.ID_Proyecto) as Destino FROM tbl_historialherramientas x where x.Codigo = ? ";
        if ($stmt = $this->conn->prepare($sql)) {
            $codigo = LimpiarCadenaCaracter($this->conn, $codigo);
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

    public function InfoHerramienta($codigo)
    {
        $sql = "select Codigo, Marca,Descripcion, FechaIngreso, Procedencia, Precio, NumFactura from tbl_herramientaelectrica where Codigo = ? ";
        if ($stmt = $this->conn->prepare($sql)) {
            $codigo = LimpiarCadenaCaracter($this->conn, $codigo);
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

    public function BuscarTiempoRealHerramienta($consulta)
    {
        $consulta = LimpiarCadenaCaracter($this->conn, $consulta);
        if ($_SESSION['ID_ROL'] == '4') {
            $sql = "select Codigo, b.Descripcion as Tipo,a.Descripcion, FechaIngreso, IF(Disposicion = '1','Disponible','No Disponible')as Disposicion, c.Nombre,IF(a.Estado = '1','Buena','En Reparacion')as Estado,a.Estado as numEstado,Precio,b.PrecioEquipo as PrecioAlquiler,
            b.CodigoMonedaCobro,
            b.CodigoFormaCobro, a.MonedaCompra  from tbl_herramientaelectrica a, tbl_tipoherramienta b, tbl_proyectos c where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto and b.Descripcion like ? ";
        } else {
            $sql = "select Codigo, b.Descripcion as Tipo,a.Descripcion, FechaIngreso, IF(Disposicion = '1','Disponible','No Disponible')as Disposicion, c.Nombre,IF(a.Estado = '1','Buena','En Reparacion')as Estado,a.Estado as numEstado,Precio,b.PrecioEquipo as PrecioAlquiler,
            b.CodigoMonedaCobro,
            b.CodigoFormaCobro, a.MonedaCompra  from tbl_herramientaelectrica a, tbl_tipoherramienta b, tbl_proyectos c where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto and a.Disposicion = 1 and a.Estado = 1 and b.Descripcion LIKE ? ";
        }
        if ($stmt = $this->conn->prepare($sql)) {
            $like = "%" . $consulta . "%";
            /* ligar parámetros para marcadores */
            $stmt->bind_param("s", $like);
            /* ejecutar la consulta */
            $stmt->execute();
            /* ligar variables de resultado */
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function FiltrosHerramientas($Tipo, $Disposicion, $Estado, $Ubicacion)
    {
    }

    public function ActualizarHerramienta(\Herramientas $herramienta)
    {
        $sql = "UPDATE tbl_herramientaelectrica set Codigo =?, Descripcion=?,Marca=?,FechaIngreso=?,Procedencia=?,Precio=?,ID_Tipo=? where Codigo=?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $this->conn->real_escape_string($herramienta->codigo));
            $stmt->bind_param("s", $this->conn->real_escape_string($herramienta->descripcion));
            $stmt->bind_param("s", $this->conn->real_escape_string($herramienta->marca));
            $stmt->bind_param("s", $this->conn->real_escape_string($herramienta->fechaIngreso));
            $stmt->bind_param("s", $this->conn->real_escape_string($herramienta->procedencia));
            $stmt->bind_param("i", $this->conn->real_escape_string($herramienta->precio));
            $stmt->bind_param("i", $this->conn->real_escape_string($herramienta->tipo));
            $stmt->bind_param("s", $this->conn->real_escape_string($herramienta->codigo));
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function FiltrarTipoTotalHerramienta()
    {
        $sql = "SELECT tt.Descripcion,COUNT(*) as Cantidad from tbl_herramientaelectrica th, tbl_tipoherramienta tt where th.ID_Tipo = tt.ID_Tipo GROUP by th.ID_Tipo order by tt.Descripcion ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function EliminarHerramienta(string $codigo, string $motivo)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $idUsuario = $_SESSION['ID_Usuario'];
        $resultado = new Resultado();
        $codigo = LimpiarCadenaCaracter($this->conn, $codigo);
        $sql = "SELECT Codigo FROM tbl_historialherramientas WHERE Codigo = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $codigo);
            $stmt->execute();
            $existenRegistros = $stmt->get_result();

            if (mysqli_num_rows($existenRegistros) > 0) {
                $resultado->esValido = false;
                $resultado->mensaje = "Esta maquinaria  ya presenta un historial, por lo tanto no puede ser eliminada.";
            } else {


                $sqlDelete = "DELETE FROM tbl_herramientaelectrica WHERE Codigo =?";
                if ($stmt2 = $this->conn->prepare($sqlDelete)) {
                    $stmt2->bind_param("s", $codigo);
                    $stmt2->execute();
                    $resultado->esValido = $stmt2->affected_rows > 0;
                    $stmt2->close();
                }
                $resultado->mensaje =  $resultado->esValido ? "Datos eliminados correctamente" : "Ocurrio un error al eliminar los datos";
                $motivo .= " codigo equipo eliminado " . $codigo;
                MBitacora::InsertarBitacora($motivo, $idUsuario, "Eliminar maquinaria");
            }
            $stmt->close();
        } else {
            $resultado->esValido = false;
            $resultado->mensaje = "Ocurrio un error al eliminar los datos";
        }
        $this->conn->close();
        return $resultado;
    }

    public function ActualizarHerramientaElectrica(Herramientas $maquinaria)
    {
        $resultado = new Resultado();

        $maquinaria->codigo = LimpiarCadenaCaracter($this->conn, $maquinaria->codigo);
        $maquinaria->tipo = LimpiarCadenaCaracter($this->conn, $maquinaria->tipo);
        $maquinaria->marca = LimpiarCadenaCaracter($this->conn, $maquinaria->marca);
        $maquinaria->descripcion = LimpiarCadenaCaracter($this->conn, $maquinaria->descripcion);
        $maquinaria->fechaIngreso = LimpiarCadenaCaracter($this->conn, $maquinaria->fechaIngreso);
        $maquinaria->procedencia = LimpiarCadenaCaracter($this->conn, $maquinaria->procedencia);
        $maquinaria->precio = LimpiarCadenaCaracter($this->conn, $maquinaria->precio);
        $maquinaria->numFactura = LimpiarCadenaCaracter($this->conn, $maquinaria->numFactura);

        $sql = "UPDATE tbl_herramientaelectrica SET 
        Codigo='" . $maquinaria->codigo . "',
        ID_Tipo=" . $maquinaria->tipo . ",
        Marca='" . $maquinaria->marca . "',
        Descripcion='" . $maquinaria->descripcion . "',
        FechaIngreso='" . $maquinaria->fechaIngreso . "',
        Procedencia='" . $maquinaria->procedencia . "',
        Precio=" . $maquinaria->precio . ",
        NumFactura='" . $maquinaria->numFactura . "'
        WHERE ID_Herramienta = " . $maquinaria->idHerramienta . "";
        $resultado->esValido  = $this->conn->query($sql);

        $resultado->mensaje =  $resultado->esValido ? "Se actualizó la maquinaria correctamente " :
            "Ocurrio un error al actualizar la maquinaria ";

        $this->conn->close();
        return  $resultado;
    }
}
