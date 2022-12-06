<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function LimpiarCadenaCaracter($conexionBD, $candena) {
    if($conexionBD == null || $conexionBD == "")
    {
        $strMensaje = "En LimpiarCadenaCaracter la conexion con la BD llego nula";
        Log::GenerarArchivoLog($strMensaje);
        exit();
    }
    $ArregloPalabras = array("DROP", "TABLE", "DATABASE", "SCRIPT", "DELETE", "INSERT", "UPDATE", "SELECT","ALTER");
    $cadenaMayuscula = strtoupper($candena);
    for ($index = 0; $index < count($ArregloPalabras); $index++) {
        $revision = strpos($cadenaMayuscula, $ArregloPalabras[$index]);
        if ($revision == TRUE) {
            ECHO "Sentencia no valida";
            exit();
            return;
        }
    }
   $resultado= $conexionBD->real_escape_string(strip_tags($candena));
   return $resultado;
}

function EsProduccion()
{
    $servername = $_SERVER['SERVER_NAME'];
    return !($servername == "localhost");
}

function ObtenerRol():int{
    if (!isset($_SESSION)) 
        session_start();
    
    return $_SESSION['ID_ROL']; 
}

function UsuarioLogueado():Usuarios
{
    if (!isset($_SESSION)) 
    { session_start();}

     $usuario = new Usuarios();
     $usuario->Nombre = $_SESSION['Nombre'];
     $usuario->ID_Rol = $_SESSION['ID_ROL'];
     $usuario->Usuario=  $_SESSION['Usuario'];
     $usuario->ID_Usuarios = $_SESSION['ID_Usuario'];
     $usuario->IdProyecto = $_SESSION['ID_Proyecto'];
     return $usuario;
    
}
