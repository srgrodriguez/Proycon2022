<?php
session_start();
require_once '../BLL/Autorizacion.php';
ValidarIniciodeSession();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Herramientas</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css" />
    <link href="../css/responsivecss.css" rel="stylesheet" type="text/css" />
    <link href="../fonts/icon/style.css" rel="stylesheet" type="text/css" />
    <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script src="../js/FuncionesGenerales.js" type="text/javascript"></script>
    <script src="../js/jsTipoHerramienta.js" type="text/javascript"></script>
    <script src="../js/jquery.table2excel.js" type="text/javascript"></script>
    <script src="../js/jsMenu.js" type="text/javascript"></script>
    <script src="../js/jsLogin.js" type="text/javascript"></script>
    <script src="../js/jspdf.debug.js" type="text/javascript"></script>
    <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
</head>

<body>
    <header id="header">
        <?php
        include 'Menu.php';
        include '../BLL/Maquinaria_BL.php';
        require_once '../BLL/TipoHerramienta_BL.php';
        Crearmenu();
        ?>
    </header>

    <main id="contenedor">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3>Mantenimiento tipos de maquinaria</h3>
                <button style="float: right;margin-right: 20px;position: relative;top:-40px" type="button" class="btn btn-default" id="btnAtrasNoResponsive" onclick="window.location.href='Maquinaria.php'">
                    <img src="../resources/imagenes/regresar.png" alt="" width="20px;">
                </button>
            </div>
            <div class="panel-body">
                <div class="col-lg-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <h3>Tipos</h3>
                        </div>
                        <div class="panel-body">
                        <div>
                                    <div class="k-grid-toolbar">
                                        <?php if ($_SESSION['ID_ROL'] == Constantes::RolBodega || $_SESSION['ID_ROL'] == Constantes::RolAdminBodega) { ?>

                                            <ul class="lista-accion">
                                                <li>
                                                    <button type="button" class="btn-accion btn btn-info btn-sm" data-toggle="modal" data-target="#ModalAgregarTipoMaquinaria" onclick="AbriModalAgregar()">Agregar tipo maquinaria</button>
                                                </li>                           
                                            </ul>
                                        <?php } ?>
                                    </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="tablasG">
                                        <thead>
                                            <tr>
                                                <th style='display:none'>ID</th>
                                                <th>Tipo</th>
                                                <th>Precio</th>
                                                <th>Moneda</th>
                                                <th>Forma cobro</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="listadoTipoHerramientas">
                                            <?php  ListarTipoHerramientas('M')?>
                                        </tbody>
                                    </table>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                        <!-- AGREGAR TIPO HERRAMIENTA MODAL -->
                <div id="ModalAgregarTipoMaquinaria" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div id="modalTipo" class="modal-header headerModal">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title" id="tituloModalAgregarTipo">Agregar nuevo tipo de maquinaria</h4>
                            </div>
                            <div class="modal-body">
                                <form method="POST" name="frmInsertar" class="form-horizontal" action="">
                                    <div class="form-group">
                                        <label class="col-lg-3">Tipo</label>
                                        <div class="col-lg-6">
                                            <input type="text" name="IdTipoEquipo" id="IdTipoEquipo" style="display:none" placeholder="Nombre tipo maquinaria" class="form-control " />
                                            <input type="text" name="IdTipoHerramienta" id="IdTipoHerramienta" style="display:none" placeholder="Nombre tipo maquinaria" class="form-control " />
                                            <input type="text" name="txtnombreTipoMaquinaria" id="txtnombreTipoMaquinaria" placeholder="Nombre tipo maquinaria" class="form-control " />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-3">Precio alquiler equipo</label>
                                        <div class="col-lg-6">
                                            <input type="text" onkeypress="return AceptarSoloNumerosMonto(event);" onchange="Formateo_Monto(this)" name="txtPrecioAlquiler" id="txtPrecioAlquiler" class="form-control " placeholder="Precio de alquiler de equipo" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-3">Moneda</label>
                                        <div class="col-lg-6">
                                            <?php ObtenerComboBoxMonedas('cboMonedaTipo') ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-3">Forma de cobro</label>
                                        <div class="col-lg-6">
                                            <?php CargarComboBoxFormaCobroHerramienta()  ?>
                                        </div>
                                    </div>

                                </form>
                                <div id="respuestaTipoHerramienta">
                                </div>
                                <div class="modal-footer">
                                <button type="button"  id="btnEditar" class="btn btn-primary" onclick="ActualizarTipo()">Editar</button>                                 
                                <button type="button" id="btnGuardarTipo" class="btn btn-success" onclick="AgregarNuevoTipo('M')">Guardar</button>
                                    <button type="submit" class="btn btn-default" data-dismiss="modal" onclick="LimpiarColorTipoHerramienta()">Cerrar</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
    </main>
</body>

</html>