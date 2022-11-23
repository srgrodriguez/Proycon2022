<?php

class MArchivos
{

    var $conn;

    public function __construct()
    {
        $conexion = new Conexion();
        $this->conn = $conexion->CrearConexion();
    }
    public function AgregarArchivo($nombre, $archivo): int
    {
        $idArchivo = 0;
        try {
            $sqlArchivo = "INSERT INTO tbl_Archivos (Archivo, NombreArchivo) VALUES (?, ?)";
            $stmt = mysqli_prepare($this->conn, $sqlArchivo);

            $stmt->bind_param('ss', $archivo, $nombre);
            if (mysqli_stmt_execute($stmt)) {
                $idArchivo = mysqli_stmt_insert_id($stmt);
            } else {
                $errorAgregarArchivo = "Falló el registro del archivo";
                $error = mysqli_stmt_error($stmt);
                Log::GuardarEventoString($error, "Guardar archivo");
                $errorAgregarArchivo .= $error;
            }
            mysqli_stmt_close($stmt);
            mysqli_close($this->conn);
        } catch (\Throwable $th) {
            mysqli_close($this->conn);
            $idArchivo = 0;
            Log::GuardarEvento($th, "Guardar archivo");
        }

        return $idArchivo;
    }

    public function ConsultarArchivo($id)
    {
        $sql="SELECT Archivo,NombreArchivo FROM tbl_archivos WHERE ID_Archivo = $id";
        
        //ejecutar la sentencia sql
        $resultado = mysqli_query($this->conn, $sql);
        mysqli_close($this->conn);
        return $resultado;
        
    }

    public function EliminarArchivo(int $id)
    {
        try 
        {
            $sqlEliminarArchivo = "DELETE FROM tbl_Archivos Where ID_Archivo = $id";
            $this->conn->query($sqlEliminarArchivo);
            mysqli_close($this->conn);
        } catch (\Throwable $th) {
            Log::GuardarEvento($th, "Eliminar archivo");
        }
            
    }
}

