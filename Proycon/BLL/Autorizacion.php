<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function Autorizacion() {
    if (!isset($_SESSION)) {
        session_start();
    }
    ValidarTimpoInactividad();
    //$token = $_POST["token"];
    //$tokenServer = $_SESSION['SKey'];
    if (!isset($_SESSION['userAgent']) || !isset($_SESSION['IPaddress'])) {
        logOut();
        RedireccionarInicio();
    } else {
        $userAgentRequest = $_SESSION['userAgent'];
        $userAgentServer = $_SERVER['HTTP_USER_AGENT'];
        $ipRequest = ExtractUserIpAddress();
        $ip = $_SESSION['IPaddress'];
        if ($userAgentRequest != $userAgentServer || $ipRequest != $ip) {
            logOut();
            RedireccionarInicio();
        }
    }
}

function ValidarIniciodeSession() {
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION['Nombre']) || !isset($_SESSION['Usuario']) || !isset($_SESSION['ID_Usuario']) || !isset($_SESSION['ID_Usuario'])) {
        RedireccionarInicio();
    }
}

function ExtractUserIpAddress() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function ValidarTimpoInactividad() {
    if (isset($_SESSION['tiempo'])) {
        //Tiempo en segundos para dar vida a la sesión.
        $inactivo = 600; //10min en este caso.
        //Calculamos tiempo de vida inactivo.
        $vida_session = time() - $_SESSION['tiempo'];
        //Compraración para redirigir página, si la vida de sesión sea mayor a el tiempo insertado en inactivo.
        if ($vida_session > $inactivo) {
            logOut();
            RedireccionarInicio();
        }
        else{
             $_SESSION['tiempo'] = time();
        }
    } else {
        //Activamos sesion tiempo.
        $_SESSION['tiempo'] = time();
    }
}

function logOut() {
    session_unset();
    session_destroy();
    session_start();
    session_regenerate_id(true);
    // echo "Acceso no autorizado";
    // exit();
}

function RedireccionarInicio() {
    $html = "<div class='coverCodigo'>"
            ."<div class='overlay text-center'>"
            ."<h1 class='info'>Su sesión ha expirado </h1><br>
              <input type='button' class='btn btn-danger' value='Aceptar' onclick='Salir()'>
              <br>"
            . "</div>"
            . "</div>";

    $js = " <script>" .
            "$('#ModalSession').modal({backdrop: 'static', keyboard: false})"
            ."; function Salir(){window.location.href = '../index.php';}"
            . "</script>";
    
    $style = " <style>"
            . "  
     .coverCodigo {
        position: fixed;
        top: 0;
        left: 0;
        background: rgba(0,0,0,0.6);
        z-index: 9997;
        width: 100%;
        height: 100%;
    }

    .overlay {
        color:black;
        position: relative;
        top: 118px;
        width: 550px;
        height: 148px;
        background-color: white;
        z-index: 9998;
        border-radius:5px;
        text-align: center;
        padding: 10px 10px 10px 10px;
        margin:auto;
    }"
            . "</style>";
    $html = $html . $js.$style;
    echo $html;
    exit();
    die();
    return;
}
