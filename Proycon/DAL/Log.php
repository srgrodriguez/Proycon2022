<?php
 class Log {

    public static function GuardarEvento(\Throwable $exception,$nombreTrasaccion){
        $resultado = new Resultado();
        try {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $getError =$exception->getMessage()." \n ". json_encode($exception->getTrace())  ." ";
        $sql5 = "Insert into tbl_manejoerrores(NombreTrasaccion,Descripcion,Fecha) values ('" .$nombreTrasaccion  . "','" . $getError . "',Now());";
        $conn->query($sql5);
        } catch (\Throwable $e) {
            Log::GenerarArchivoLog($e->getMessage());
        }
        $resultado->esValido = false;
        $resultado->mensaje = "Ocurrio un error al procesar la transacción ".$exception->getMessage();
        return json_encode($resultado);
       
    }

    public static function GuardarEventoString($mensaje,$nombreTrasaccion){
        $resultado = new Resultado();
        try {
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql5 = "Insert into tbl_manejoerrores(NombreTrasaccion,Descripcion,Fecha) values ('" .$nombreTrasaccion  . "','" . $mensaje . "',Now());";
        $conn->query($sql5);
        } catch (\Throwable $e) {
            Log::GenerarArchivoLog($e->getMessage());
        }

        $resultado->esValido = false;
        $resultado->mensaje = "Ocurrio un error al procesar la transacción ".$mensaje;
        return json_encode($resultado);
       
    }

    public static function GenerarArchivoLog($strError){
        $hoy = date("Y-m-d H:i:s"); 
        $filename = "Logs/error".date("Y-m-d").".log";
        $strError.=" ".$hoy;
         error_log($strError.'; \n ',3,$filename);
    }
 }
