<?php
session_start();
require_once '../BLL/Autorizacion.php';
ValidarIniciodeSession();
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="resources\imagenes\favicon.ico" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Mi Cuenta</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css" />
    <link href="../css/responsivecss.css" rel="stylesheet" type="text/css" />
    <link href="../css/responsivecss.css" rel="stylesheet" type="text/css" />
    <link href="../fonts/icon/style.css" rel="stylesheet" type="text/css" />
    <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../js/jsUsuarios.js" type="text/javascript"></script>
    <script src="../js/jsMenu.js" type="text/javascript"></script>
    <script src="../js/jsLogin.js" type="text/javascript"></script>
    <?php if ($_SESSION['ID_ROL'] == 4 || $_SESSION['ID_ROL'] == 5) { ?>
        <script src="../js/Notificaciones.js" type="text/javascript"></script>
        <script src="../js/push.min.js" type="text/javascript"></script>
    <?php } ?>
</head>

<body class="body">
    <header id="header">
        <?php
        require_once 'Menu.php';
        Crearmenu();
        ?>
    </header>

    <main id="contenedor">


        <div class="panel panel-info">

            <div class="panel-heading">
                <h3>Datos de Mi Cuenta</h3>
            </div>
            <div class="panel-body">
                <div id="perfilUsuario">
                    <div style="width:100%">
                        <div id="imgPerfil">
                            <img src="../resources/imagenes/profile.png" alt="" width="200px" />
                            <div id="EstadoNombre">
                                <div id="ActivoUsuario"></div>
                                <h3 id="NombreUsuario"><?php echo $_SESSION["Nombre"] ?></h3>
                            </div>
                        </div>
                    </div>
                    <div id="InformacionCuenta">
                        <div class="contieneDatos">
                            <form method="POST" name="frmInsertar" class="form-horizontal" action="mantenimientoUsuario.php">
                                <legend style="color: #003DA6">Datos de mi Cuenta</legend>
                                <?php
                                require_once '../BLL/Usuario.php';
                                ObtenerDatosUsuario($_SESSION['ID_Usuario']);
                                ?>
                            </form>
                        </div>


                    </div>


                </div>

            </div>




            <div id="ModalCambioPassword" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="modalheaderCambioPass" class="modal-header headerModal">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="TituloCambioPass">Cambiar Contraseña</h4>
                        </div>
                        <div class="modal-body">
                            <form method="POST" name="frmInsertar" class="form-horizontal" action="mantenimientoUsuario.php">
                                <div class="form-group">
                                    <label class="col-lg-2"> Contraseña Actual</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="contraActual" id="contraActual" class="form-control " placeholder="Contraseña Actual" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2"> Contraseña Nueva</label>
                                    <div class="col-lg-10">
                                        <input type="password" name="contraNueva" id="contraNueva" class="form-control " placeholder="Contraseña Nueva" />
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-lg-2"> Confirma Contraseña</label>
                                    <div class="col-lg-10">
                                        <input type="password" name="confirmarContra" id="confirmarContra" class="form-control" placeholder="Confirmar Contraseña" />
                                    </div>
                                </div>
                            </form>


                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success btn-estilos" onclick="CambioPassword()">Guardar Cambios</button>
                                <button type="submit" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!--                  regsd-->
            <div id="ModalCambioNombreCuenta" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="headerModalCambiarNombre" class="modal-header headerModal">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="TituloCambiarNombre">Cambiar de Nombre</h4>
                        </div>
                        <div class="modal-body">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-lg-2"> Nombre Actual</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="nombreActual" id="nombreActual" class="form-control" value="" disabled="disabled" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2"> Nuevo Nombre</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="nuevoNombre" id="nuevoNombre" class="form-control " placeholder="Nuevo Nombre" />
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success btn-estilos" onclick="CambiarNombre()">Guardar Cambios</button>
                                    <button type="submit" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
    </main>

</body>

</html>