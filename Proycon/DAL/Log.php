<?php
 class Log {

    public static function GuardarEvento($exception,$nombreTrasaccion){
        try {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $getError =$exception->getMessage()." \n ". $exception->getTrace()." ".date("Y-m-d H:i:s");
        $sql5 = "Insert into tbl_manejoerrores(NombreTrasaccion,Descripcion) values ('" .$nombreTrasaccion  . "','" . $getError . "');";
        $conn->query($sql5);
        } catch (Exception $e) {
            Log::GenerarArchivoLog($e->getMessage());
        }

        echo "Ocurrio un error al procesar la transacción";
       
    }

    public static function GenerarArchivoLog($strError){
        $filename = "Logs/error".date("Y-m-d").".log";
         error_log($strError.'; \n ',3,$filename);
    }
 }
