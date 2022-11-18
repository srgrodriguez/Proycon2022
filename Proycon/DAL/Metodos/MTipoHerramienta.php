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
        on th.CodigoFormaCobro = fc.CodigoFormaCobro  where TipoEquipo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $datoLimpio);
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
                $sqlDelete = "DELETE FROM tbl_tipoherramienta WHERE ID_Tipo =?";
                if ($stmt2 = $this->conn->prepare($sqlDelete)) {
                    $stmt2->bind_param("i", $ID);
                    $stmt2->execute();                   
                    $resultado->esValido = $stmt2->get_result();

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
}


