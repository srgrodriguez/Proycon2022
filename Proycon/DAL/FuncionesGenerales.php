<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function LimpiarCadenaCaracter($conexionBD, $candena) {
    $ArregloPalabras = array("DROP", "TABLE", "DATABASE", "SCRIPT", "DELETE", "INSERT", "UPDATE", "SELECT");
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
