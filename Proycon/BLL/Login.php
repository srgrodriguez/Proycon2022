<?php
header("Content-Type: text/html;charset=utf-8");
require_once '..//DAL/Interfaces/IUsuarios.php';
require_once '..//DAL/Metodos/MUsuarios.php';
require_once '../DAL/Conexion.php';
include '../DAL/FuncionesGenerales.php';
include '../DATA/Resultado.php';
require_once 'Autorizacion.php';
require_once '../DAL/Log.php';

if (isset($_POST['Usuario'])) {
    ValidarLogin($_POST['Usuario'], $_POST['Pass']);
}

function ValidarLogin($Usuario, $Pass) {
    $usuario = new MUsuarios();
    $Resultado = new Resultado();
    try {
        $result = $usuario->ValidarLogin($Usuario);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqlI_fetch_array($result, MYSQLI_ASSOC); // traer el usuario
       
        if (password_verify($Pass, $row['Pass'])) { // Verifico que el password sea el correcto
            $usuario = new MUsuarios();
            $passCorrecto = $usuario->ObtieneInfoUsuario($Usuario);
            $rows = mysqlI_fetch_array($passCorrecto, MYSQLI_ASSOC); // traer el usuario

            session_start();
            $_SESSION['Usuario'] = $rows['Usuario'];
            $_SESSION['Nombre'] = $rows["Nombre"];
            $_SESSION['ID_Usuario'] = $rows["ID_Usuario"];
            $_SESSION['ID_ROL'] = $rows["ID_ROL"];
            $_SESSION['ID_Proyecto'] = $rows["ID_Proyecto"];

            crearSessionesSistema();
            $Resultado->codigo = "1";
            $Resultado->mensaje = "Usuario Correcto";
            echo json_encode($Resultado);
        } else {
            $Resultado->codigo = "0";
            $Resultado->mensaje = "Credenciales Incorrectas";
            echo json_encode($Resultado);
        }
    } else {
        $Resultado->codigo = "0";
        $Resultado->mensaje = "Usuario no existe";
        echo json_encode($Resultado);
    }
    } catch (\Throwable $ex) {
        echo  Log::GuardarEvento($ex,"ValidarLogin");
    }
       
}

function crearSessionesSistema(){
    
    $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
    $_SESSION['SKey'] = uniqid(mt_rand(), true);
    $_SESSION['IPaddress'] = ExtractUserIpAddress();
    $_SESSION['LastActivity'] = $_SERVER['REQUEST_TIME'];
    $_SESSION['tiempo'] = time();
    
            
    
    
}
