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
        $resultado->mensaje = "Ocurrio un error al procesar la transacción";
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
        $resultado->mensaje = "Ocurrio un error al procesar la transacción";
        return json_encode($resultado);
       
    }

    public static function GenerarArchivoLog($strError){
        $filename = "Logs/error".date("Y-m-d").".log";
         error_log($strError.'; \n ',3,$filename);
    }
 }
