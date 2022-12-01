<?php


class MTipoHerramienta implements ITipoHerramienta
{

    var $conn;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conn = $conexion->CrearConexion();
    }

    public function AgregarTipoHerramienta(TipoHerramienta $tipoHerramienta)
    {

        $resultado = new Resultado();
        $sql = "INSERT INTO `tbl_tipoherramienta`
        ( `Descripcion`,
          `PrecioEquipo`,
          `CodigoMonedaCobro`, 
          `CodigoFormaCobro`, 
          `TipoEquipo`) 
          values (?,?,?,?,?)";
        $tipoHerramienta->TipoEquipo = LimpiarCadenaCaracter($this->conn, $tipoHerramienta->TipoEquipo);
        $tipoHerramienta->descripcion = LimpiarCadenaCaracter($this->conn, $tipoHerramienta->Descripcion);
        $tipoHerramienta->CodigoFormadeCobro = LimpiarCadenaCaracter($this->conn, $tipoHerramienta->CodigoFormadeCobro);
        
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("sdsss", 
             $tipoHerramienta->Descripcion,
             $tipoHerramienta->Precio, 
             $tipoHerramienta->MonedaCobro,
             $tipoHerramienta->CodigoFormadeCobro,
             $tipoHerramienta->TipoEquipo,        
            );
            $resultado->esValido  =  $stmt->execute();
            $stmt->close();
            $resultado->mensaje =  $resultado->esValido ? "Transacción procesada" : "Ocurrio un error al agregar el tipo de herramienta";

        }
        else
        {
            $resultado->esValido=  false;
            $resultado->mensaje =  "Error de sintaxis en consulta SQL ";
        }
       
        $this->conn->close();
        return $resultado;
    }

    public function ActualizarTipoHerramienta(TipoHerramienta $tipoHerramienta)
    {

        $resultado = new Resultado();
        $tipoHerramienta->Descripcion = LimpiarCadenaCaracter($this->conn,$tipoHerramienta->Descripcion );
        $tipoHerramienta->TipoEquipo =  LimpiarCadenaCaracter($this->conn,$tipoHerramienta->TipoEquipo );
        $tipoHerramienta->CodigoFormadeCobro =  LimpiarCadenaCaracter($this->conn,$tipoHerramienta->CodigoFormadeCobro );
        $tipoHerramienta->MonedaCobro =  LimpiarCadenaCaracter($this->conn,$tipoHerramienta->MonedaCobro );

        $sql = "UPDATE tbl_tipoherramienta set 
        Descripcion=?,
        PrecioEquipo=?,
        TipoEquipo=?,
        CodigoFormaCobro=?,
        CodigoMonedaCobro =?
        where ID_Tipo=?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param(
                "sdsssi",
                $tipoHerramienta->Descripcion,
                $tipoHerramienta->Precio,
                $tipoHerramienta->TipoEquipo,
                $tipoHerramienta->CodigoFormadeCobro,
                $tipoHerramienta->MonedaCobro,
                $tipoHerramienta->IDTipo
            );
            $resultado->esValido  =  $stmt->execute();
            $stmt->close();
            $resultado->mensaje =  $resultado->esValido ? "Transacción procesada" : "Ocurrio un error al actualizar el tipo de herramienta";

        }
        else {
            $resultado->esValido=  false;
            $resultado->mensaje =  "Error de sintaxis en consulta SQL ";
        }
       
        $this->conn->close();
        return $resultado;
    }

    public function ListarTipoHerramientas($tipo)
    {
        $datoLimpio = LimpiarCadenaCaracter($this->conn, $tipo);
        $sql = "select 
        ID_Tipo,
        Descripcion,
        PrecioEquipo,
        TipoEquipo,
        fc.DescripcionFormaDeCobro,
        fc.CodigoFormaCobro,
        CodigoMonedaCobro
        from tbl_tipoherramienta th
        LEFT JOIN tbl_FormasCobroEquipo fc
        on th.CodigoFormaCobro = fc.CodigoFormaCobro  where TipoEquipo = ?
        ORDER BY ID_Tipo DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $datoLimpio);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function ObtenerTipoHerramientaPorNombre($tipo,$nombre)
    {
        $datoLimpio = LimpiarCadenaCaracter($this->conn, $tipo);
        $strDescripcion = LimpiarCadenaCaracter($this->conn, $nombre);
        $sql = "select 
        ID_Tipo,
        Descripcion,
        PrecioEquipo,
        TipoEquipo,
        fc.DescripcionFormaDeCobro,
        fc.CodigoFormaCobro,
        CodigoMonedaCobro
        from tbl_tipoherramienta th
        LEFT JOIN tbl_FormasCobroEquipo fc
        on th.CodigoFormaCobro = fc.CodigoFormaCobro  where TipoEquipo = ? and th.Descripcion like ?
        ORDER BY ID_Tipo DESC
        ";
        $stmt = $this->conn->prepare($sql);
        $like = "%" . $strDescripcion . "%";
        $stmt->bind_param("ss", $datoLimpio,$like);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function EliminarTipoHerramienta($ID)
    {
        $resultado = new Resultado();
        $ID = LimpiarCadenaCaracter($this->conn, $ID);
        $sql = "select Codigo from tbl_herramientaelectrica where ID_Tipo = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("i", $ID);
            $stmt->execute();
            $existenRegistros = $stmt->get_result();

            if (mysqli_num_rows($existenRegistros) > 0) {
                $resultado->esValido = false;
                $resultado->mensaje="Este tipo de herramienta ya presenta equipo asociado, por lo tanto no puede ser eliminada.";
            }else
            {
                $sqlDelete = "DELETE FROM tbl_tipoherramienta WHERE ID_Tipo =".$ID;
                if ($stmt2 = $this->conn->prepare($sqlDelete)) {
                    $stmt2->execute();                   
                    $resultado->esValido = $stmt2->affected_rows >0;

                    $stmt2->close();
                }
                $resultado->mensaje =  $resultado->esValido ? "Datos eliminados correctamente" : "Ocurrio un error al eliminar los datos";

            }
            $stmt->close();
        }
        else{
            $resultado->esValido = false;
            $resultado->mensaje="Ocurrio un error al eliminar los datos";     
        }
        $this->conn->close();
        return $resultado;
    }

    public function CargarComboBoxTipoHerramienta(string $tipoEquipo){

        $sql = "Select Descripcion,ID_Tipo from tbl_tipoherramienta 
                where TipoEquipo = ?
               order by Descripcion";
        if ($stmt2 = $this->conn->prepare($sql)) {
            $stmt2->bind_param("s", $tipoEquipo);
            $stmt2->execute();                   
            $resultado  = $stmt2->get_result();
            $stmt2->close();
            return $resultado;
        }

    }

    public function CargarComboBoxFormaCobroHerramienta(){

        $sql = "SELECT CodigoFormaCobro,DescripcionFormaDeCobro FROM `tbl_formascobroequipo`
                ORDER BY CodigoFormaCobro";
        if ($stmt2 = $this->conn->prepare($sql)) {     
            $stmt2->execute();                   
            $resultado  = $stmt2->get_result();
            $stmt2->close();
            return $resultado;
        }

    }

    public function ConsultarTipoHerramientaPorID(int $id)
    {
        $id= LimpiarCadenaCaracter($this->conn, $id);
        $sql = "select 
                ID_Tipo,
                Descripcion,
                PrecioEquipo,
                TipoEquipo,
                CodigoMonedaCobro,
                CodigoFormaCobro
                from tbl_tipoherramienta 
                where ID_Tipo = ?";
        if( $stmt = $this->conn->prepare($sql))
        {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
        }
        else{
            echo "Error de sintaxis SQL ";
        }
    }
}


