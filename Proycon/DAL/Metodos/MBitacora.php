<?php


class MBitacora
{
    public static function InsertarBitacora(string $descripcion, int $idUsuario, string $Accion)
    {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = "INSERT INTO tbl_bitacoras(
            Descripcion,
            IdUsuario, 
            Fecha, 
            Accion
            ) values(?,?,Now(),?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param(
                "sis",
                $descripcion,
                $idUsuario,
                $Accion
            );
            $stmt->execute();
            $stmt->close();
        }
        $conn->close();
    }
}
