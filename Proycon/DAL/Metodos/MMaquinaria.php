<?php


class MMaquinaria implements IMaquinaria
{

    var $conn;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conn = $conexion->CrearConexion();
    }

    public function AgregarMaquinaria(Herramientas $maquinaria)
    {
        $resultado = new Resultado();
        $sql = "INSERT INTO tbl_herramientaelectrica(
             Codigo,
             ID_Tipo, 
             Marca, 
             Descripcion, 
             FechaIngreso, 
             Estado, 
             Disposicion, 
             Procedencia, 
             Ubicacion, 
             Precio, 
             NumFactura,
             ID_Archivo
             ) values(?,?,?,?,?,?,?,?,?,?,?,?)";
           $idArchivo = null;
           $errorAgregarArchivo = "";  
        if ($maquinaria->nombreArchivo != "") {
            $bdArchivos = new MArchivos();
            $idArchivo =  $bdArchivos->AgregarArchivo($maquinaria->nombreArchivo,$maquinaria->archivoBinario);
             if ($idArchivo == 0) {
                $errorAgregarArchivo ="Falló el registro del archivo";
             }                 
        }

        if ($stmt = $this->conn->prepare($sql)) {
            $maquinaria->codigo = LimpiarCadenaCaracter($this->conn, $maquinaria->codigo);
            $maquinaria->tipo = LimpiarCadenaCaracter($this->conn, $maquinaria->tipo);
            $maquinaria->marca = LimpiarCadenaCaracter($this->conn, $maquinaria->marca);
            $maquinaria->descripcion = LimpiarCadenaCaracter($this->conn, $maquinaria->descripcion);
            $maquinaria->fechaIngreso = LimpiarCadenaCaracter($this->conn, $maquinaria->fechaIngreso);
            $maquinaria->estado = LimpiarCadenaCaracter($this->conn, $maquinaria->estado);
            $maquinaria->disposicion = LimpiarCadenaCaracter($this->conn, $maquinaria->disposicion);
            $maquinaria->procedencia = LimpiarCadenaCaracter($this->conn, $maquinaria->procedencia);
            $maquinaria->ubicacion = LimpiarCadenaCaracter($this->conn, $maquinaria->ubicacion);
            $maquinaria->precio = LimpiarCadenaCaracter($this->conn, $maquinaria->precio);
            $maquinaria->numFactura = LimpiarCadenaCaracter($this->conn, $maquinaria->numFactura);
            $stmt->bind_param(
                "sisssiisiisi",
                $maquinaria->codigo,
                $maquinaria->tipo,
                $maquinaria->marca,
                $maquinaria->descripcion,
                $maquinaria->fechaIngreso,
                $maquinaria->estado,
                $maquinaria->disposicion,
                $maquinaria->procedencia,
                $maquinaria->ubicacion,
                $maquinaria->precio,
                $maquinaria->numFactura,
                $idArchivo
            );
            $resultado->esValido =  $stmt->execute();
            $resultado->mensaje =  $resultado->esValido ? "Se agregó la maquinaria correctamente ".$errorAgregarArchivo : 
                                                          "Ocurrio un error al agregar la maquinaria ".$errorAgregarArchivo;
            $stmt->close();

            if(!$resultado->esValido && ($idArchivo!= null || $idArchivo!= ""))
            {
                $bdArchivos = new MArchivos();
                $bdArchivos->EliminarArchivo($idArchivo);
                Log::GuardarEventoString(mysqli_stmt_error($stmt),"AgregarMaquinaria");              
            }
        } else {
            $bdArchivos = new MArchivos();
            $bdArchivos->EliminarArchivo($idArchivo);
            $resultado->esValido =  false;
            $resultado->mensaje =  "Error de sintaxis en consulta SQL ";
        }

        $this->conn->close();
        return  $resultado;
    }
    public function ActualizarMaquinaria(Herramientas $maquinaria)
    {
        $resultado = new Resultado();

        $maquinaria->codigo = LimpiarCadenaCaracter($this->conn, $maquinaria->codigo);
        $maquinaria->tipo = LimpiarCadenaCaracter($this->conn, $maquinaria->tipo);
        $maquinaria->marca = LimpiarCadenaCaracter($this->conn, $maquinaria->marca);
        $maquinaria->descripcion = LimpiarCadenaCaracter($this->conn, $maquinaria->descripcion);
        $maquinaria->fechaIngreso = LimpiarCadenaCaracter($this->conn, $maquinaria->fechaIngreso);
        $maquinaria->estado = LimpiarCadenaCaracter($this->conn, $maquinaria->estado);
        $maquinaria->disposicion = LimpiarCadenaCaracter($this->conn, $maquinaria->disposicion);
        $maquinaria->procedencia = LimpiarCadenaCaracter($this->conn, $maquinaria->procedencia);
        $maquinaria->ubicacion = LimpiarCadenaCaracter($this->conn, $maquinaria->ubicacion);
        $maquinaria->precio = LimpiarCadenaCaracter($this->conn, $maquinaria->precio);
        $maquinaria->numFactura = LimpiarCadenaCaracter($this->conn, $maquinaria->numFactura);

        $sql = "UPDATE tbl_herramientaelectrica SET 
        Codigo='" . $maquinaria->codigo . "',
        ID_Tipo=" . $maquinaria->tipo . ",
        Marca='" . $maquinaria->marca . "',
        Descripcion='" . $maquinaria->descripcion . "',
        FechaIngreso='" . $maquinaria->fechaIngreso . "',
        Estado=" . $maquinaria->estado . ",
        Disposicion=" . $maquinaria->disposicion . ",
        Procedencia='" . $maquinaria->procedencia . "',
        Ubicacion=" . $maquinaria->ubicacion . ",
        Precio=" . $maquinaria->precio . ",
        NumFactura='" . $maquinaria->numFactura . "'
        WHERE ID_Herramienta = " . $maquinaria->idHerramienta . "";
        $resultado->esValido  = $this->conn->query($sql);

        $resultado->mensaje =  $resultado->esValido ? "Se actualizó la maquinaria correctamente" : "Ocurrio un error al actualizar la maquinaria";

        $this->conn->close();
        return  $resultado;
    }
    public function ListarTotalMaquinaria()
    {
        $sql = "select Codigo, 
        b.Descripcion as Tipo,
        a.Descripcion, FechaIngreso, 
        IF(Disposicion = '1','Disponible','No Disponible')as Disposicion, 
        c.Nombre as Ubicacion,
        IF(a.Estado = '1','Buena','En Reparacion')as Estado,
        a.Estado as numEstado,
        Precio,
        b.TipoEquipo,
        b.PrecioEquipo,
        b.CodigoMonedaCobro,
        b.CodigoFormaCobro, 
        a.ID_Archivo
        from tbl_herramientaelectrica a, tbl_tipoherramienta b, tbl_proyectos 
        c where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto and b.TipoEquipo = '" . Constantes::TipoEquipoMaquinaria . "'";
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
    public function EliminarMaquinaria(string $codigo, string $motivo)
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
    public function BuscarMaquinariaEnTiempoReal(string $strDescripcion)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $strDescripcion = LimpiarCadenaCaracter($this->conn, $strDescripcion);

        if ($_SESSION['ID_ROL'] == Constantes::RolBodega) {
            $sql = "select
            ID_Herramienta,
            Codigo, 
            b.Descripcion as Tipo,
            a.Descripcion, 
            FechaIngreso, 
            IF(Disposicion = '1','Disponible','No Disponible')as Disposicion, 
            c.Nombre as Ubicacion,
            IF(a.Estado = '1',
            'Buena','En Reparacion')as Estado,
            a.Estado as numEstado,
            Precio,
            b.TipoEquipo,
            b.PrecioEquipo,
            b.CodigoMonedaCobro,
            b.CodigoFormaCobro,
            a.ID_Archivo   
            from tbl_herramientaelectrica a, tbl_tipoherramienta b, tbl_proyectos c 
            where b.TipoEquipo = '" . Constantes::TipoEquipoMaquinaria . "' 
             AND a.ID_Tipo = b.ID_Tipo 
             and a.Ubicacion = c.ID_Proyecto 
             and b.Descripcion like ? ";
        } else {
            $sql = "select 
            ID_Herramienta,
            Codigo, 
            b.Descripcion as Tipo,
            a.Descripcion, 
            FechaIngreso, 
            IF(Disposicion = '1','Disponible','No Disponible')as Disposicion, 
            c.Nombre as Ubicacion,
            IF(a.Estado = '1','Buena','En Reparacion')as Estado,
            a.Estado as numEstado,
            Precio,
            b.TipoEquipo,
            b.PrecioEquipo,
            b.CodigoMonedaCobro,
            b.CodigoFormaCobro   
            from tbl_herramientaelectrica a, tbl_tipoherramienta b, tbl_proyectos c 
            where 
            b.TipoEquipo = '" . Constantes::TipoEquipoMaquinaria . "' AND
            a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto 
            and a.Disposicion = 1 
            and a.Estado = 1 
            and b.Descripcion  like ? ";
        }
        if ($stmt = $this->conn->prepare($sql)) {
            $like = "%" . $strDescripcion . "%";
            $stmt->bind_param("s", $like);
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function BuscarMaquinariaPorCodigo(string $Codigo)
    {
        $Codigo = LimpiarCadenaCaracter($this->conn, $Codigo);
        $sql = "select 
         ID_Herramienta,
         Codigo,
         b.Descripcion as Tipo,
         a.Descripcion, 
         FechaIngreso, 
         IF(Disposicion = '1','Disponible','No Disponible')as Disposicion, 
         c.Nombre as Ubicacion,
         IF(a.Estado = '1','Buena','En Reparacion')as Estado,
         a.Estado as numEstado,
         Precio,
         b.TipoEquipo,
         b.PrecioEquipo,
         b.CodigoMonedaCobro,
         b.CodigoFormaCobro,
         a.ID_Archivo          
         from tbl_herramientaelectrica a,
         tbl_tipoherramienta b, tbl_proyectos c 
         where a.ID_Tipo = b.ID_Tipo and a.Ubicacion = c.ID_Proyecto and a.Codigo = ? ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $Codigo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }
}
