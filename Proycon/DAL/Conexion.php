<?php

class Conexion {

    function CrearConexion() {
        $connect = new mysqli("localhost", "proycon_BD", "Pr0yc0n+", "proycon_BODEGA");
        //$connect = new mysqli("localhost", "root", "", "proycon_bodega");
        if($connect->connect_error || $connect->error ){
          echo "<script>alert('Error de Conexion con la base de datos ".
                  $connect->connect_error." ERROR ".$connect->error."')</script>";
           exit();      
        }
        else{
        return $connect;
        }
    }

}
