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
        $sql = "Insert into tbl_tipoherramienta(Descripcion,PrecioEquipo,TipoEquipo) values (?,?,?)";
        $tipoHerramienta->tipo = LimpiarCadenaCaracter($this->conn, $tipoHerramienta->TipoEquipo);
        $tipoHerramienta->descripcion = LimpiarCadenaCaracter($this->conn, $tipoHerramienta->Descripcion);

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("sds", $tipoHerramienta->Descripcion, $tipoHerramienta->Precio, $tipoHerramienta->TipoEquipo);
            $resultado->esValido  =  $stmt->execute();
            $stmt->close();
        }
        $resultado->mensaje =  $resultado->esValido ? "Transacción procesada" : "Ocurrio un error al agregar el tipo de herramienta";

        $this->conn->close();
        return $resultado;
    }

    public function ActualizarTipoHerramienta(TipoHerramienta $tipoHerramienta)
    {

        $resultado = new Resultado();
        $tipoHerramienta->Descripcion = LimpiarCadenaCaracter($this->conn,$tipoHerramienta->Descripcion );
        $tipoHerramienta->TipoEquipo =  LimpiarCadenaCaracter($this->conn,$tipoHerramienta->TipoEquipo );

        $sql = "UPDATE tbl_tipoherramienta set Descripcion=?,PrecioEquipo=?,TipoEquipo=? where ID_Tipo=?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param(
                "sdsi",
                $tipoHerramienta->Descripcion,
                $tipoHerramienta->Precio,
                $tipoHerramienta->TipoEquipo,
                $tipoHerramienta->IDTipo
            );
            $resultado->esValido  =  $stmt->execute();
            $stmt->close();
        } else {
            $resultado->esValido = false;
        }
        $resultado->mensaje =  $resultado->esValido ? "Transacción procesada" : "Ocurrio un error al actualizar el tipo de herramienta";

        $this->conn->close();
        return $resultado;
    }

    public function ListarTipoHerramientas($tipo)
    {
        $datoLimpio = LimpiarCadenaCaracter($this->conn, $tipo);
        $sql = "select ID_Tipo, Descripcion,PrecioEquipo,TipoEquipo from tbl_tipoherramienta where TipoEquipo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $datoLimpio);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }
}
