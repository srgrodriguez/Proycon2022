<?php
require_once '../BLL/Autorizacion.php';
ValidarIniciodeSession();
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="resources\imagenes\favicon.ico"  type="image/x-icon">
        <title>Inicio</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../css/estilos.css"/>
        <link href="../css/responsivecss.css" rel="stylesheet" type="text/css"/>
        <link href="../css/responsivecss.css" rel="stylesheet" type="text/css"/>
        <link href="../fonts/icon/style.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="../js/jquery.js"></script>
        <script src="../js/push.min.js" type="text/javascript"></script>
        <script src="../js/Notificaciones.js" type="text/javascript"></script>
                <script src="../js/jsLogin.js" type="text/javascript"></script>
        <script src="../js/jsMenu.js" type="text/javascript"></script>
    </head>
    <body>
        <header id="header">
            <?php
            include 'Menu.php';
            Crearmenu();
            ?>
        </header>
        <main id="contenedor">
            <div id="contenido">

                <div class="imgInicio">
                    <img src="../resources/imagenes/proycon-slider.png" width="100%" alt=""/>
                </div>
            </div>


        </main>
    </body>
</html>
