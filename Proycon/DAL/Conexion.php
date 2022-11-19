<?php
require_once 'Log.php';
class Conexion
{

    function CrearConexion()
    {
        try {

            $connect = null;
            if (EsProduccion())
                $connect = new mysqli("localhost", "proycon_BD", "Pr0yc0n+", "proycon_BODEGA");
            else
                $connect = new mysqli("localhost", "root", "", "proycon_bodega");

            if ($connect->connect_error || $connect->error) {
                $error = "Error de Conexion con la base de datos " . $connect->connect_error . " ERROR " . $connect->error;
                Log::GenerarArchivoLog($error);
                exit();
            } else {
                return $connect;
            }
        } catch (Exception  $e) {
            throw $e;
        }
    }
}
