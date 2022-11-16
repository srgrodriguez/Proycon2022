<?php

class Conexion
{

    function CrearConexion()
    {
        $dominio = $_SERVER['HTTP_HOST'];
        $connect = null;
        if ($dominio == 'localhost')
            $connect = new mysqli("localhost", "root", "", "proycon_bodega");
        else
            $connect = new mysqli("localhost", "proycon_BD", "Pr0yc0n+", "proycon_BODEGA");

        if ($connect->connect_error || $connect->error) {
            echo "<script>alert('Error de Conexion con la base de datos " .
                $connect->connect_error . " ERROR " . $connect->error . "')</script>";
            exit();
        } else {
            return $connect;
        }
    }
}
