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
    <title>Maquinaria</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css" />
    <link href="../css/responsivecss.css" rel="stylesheet" type="text/css" />
    <link href="../fonts/icon/style.css" rel="stylesheet" type="text/css" />
    <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script src="../js/FuncionesGenerales.js" type="text/javascript"></script>
    <script src="../js/jsMaquinaria.js" type="text/javascript"></script>
    <script src="../js/jsTipoHerramienta.js" type="text/javascript"></script>
    <script src="../js/jsReportesMaquinari.js" type="text/javascript"></script>    
    <script src="../js/jquery.table2excel.js" type="text/javascript"></script>
    <script src="../js/jsMenu.js" type="text/javascript"></script>
    <script src="../js/jsLogin.js" type="text/javascript"></script>
    <script src="../js/jspdf.debug.js" type="text/javascript"></script>
    <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
    <?php if ($_SESSION['ID_ROL'] == 4 || $_SESSION['ID_ROL'] == 5) { ?>
        <script src="../js/Notificaciones.js" type="text/javascript"></script>
        <script src="../js/push.min.js" type="text/javascript"></script>
    <?php } ?>
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
                <h3>Maquinaria</h3>
                <button style="float: right;margin-right: 20px;position: relative;top:-40px" type="button" class="btn btn-default" id="btnAtrasNoResponsive" onclick="Atras()">
                    <img src="../resources/imagenes/regresar.png" alt="" width="20px;" />
                </button>

            </div>
            <div class="panel-body">
                <div class="col-lg-12">
                    <div id="buscarMaquinaria" class="form-group codigoHerramienta">
                        <div class="buscarHerramienta">
                            <div class="input-group">
                                <input id="txtBuscaraquinariaTiempoReal" name="txtBuscaraquinariaTiempoReal" onkeyup="BuscarMaquinariaEnTiempoReal()" type="text" class="form-control" placeholder="Ingrese el tipo de maquinaria que desea encontrar">
                                <span class="input-group-btn">
                                    <button id="btnBuscar" class="btn btn-default" type="button" onclick="BuscarMaquinariaEnTiempoReal()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt="" /></button>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div id="idInventarioMaquinaria">
                        <div class="panel panel-info" style=" width: auto">

                            <div class="panel-heading">
                                <h4>Maquinaria en inventario</h4>
                                <button id="btnImprimirHerramientas1" type="button" onclick="GenerarReporteTotalMaquinaria()" class="btn btn-default"><img src="../resources/imagenes/Excel.png" alt="" width="20" /> </button>
                            </div>
                            <div class="panel-body">
                                <div>
                                    <div class="k-grid-toolbar">
                                        <?php if ($_SESSION['ID_ROL'] == Constantes::RolBodega || $_SESSION['ID_ROL'] == Constantes::RolAdminBodega) { ?>

                                            <ul class="lista-accion">
                                                <li>
                                                    <button type="button" class="btn-accion btn btn-info btn-sm" data-toggle="modal" data-target="#ModalAgregarMaquinaria">Agregar Maquinaria</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="btn-accion btn btn-info btn-sm"  onclick="window.location.href='TipoMaquinaria.php'">Agregar tipo maquinaria</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="btn-accion btn btn-info btn-sm" onclick="ListarTotalMaquinaria()">Listar total maquinaria</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="btn-accion btn btn-info btn-sm" onclick="window.location.href='Historial_Y_Reparaciones.php'">Historial y reparaciones</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="btn-accion btn btn-info btn-sm" onclick="window.location.href='TrasladarMaquinaria.php'">Trasladar maquinaria</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="btn-accion btn btn-info btn-sm" onclick="window.location.href='boletasBodega.php'">Boletas Desecho maquinaria</button>
                                                </li>
                                            </ul>
                                        <?php } ?>
                                    </div>
                                </div>
                                <br>
                                <div id="bodyBusquedaMaquinaria">
                                    <div class="row">
                                        <div class="col-lg-12"></div>
                                        <div class="col-lg-4 col-sm-12">
                                            <div class="form-group ">
                                                <div class="input-group">
                                                    <input id="txtCodigoMaquinariaBuscar" name="txtCodigoMaquinariaBuscar" type="text" onkeypress="BuscarMaquinariaPorCodigoEnter(event)" class="form-control" placeholder="Código">
                                                    <span class="input-group-btn">
                                                        <button id="btnBuscarCodigo" class="btn btn-default" type="button" onclick="BuscarMaquinariaPorCodigoGetHtml()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt="" /></button>
                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-lg-3  col-sm-12"></div>
                                        <div class="col-lg-4  col-sm-12">
                                            <div class="form-group ">
                                                       <Select style="font-size: 15px" class="form-control" name="cboFiltroHerramienta" id="cboFiltroHerramienta" onchange="OrdenarConsusltaMaquinaria()">
                                                            <option value="0">Ordenar por...</option>
                                                            <option value="ID_Tipo">Tipo</option>
                                                            <option value="Disposicion">Disposición</option>
                                                            <option value="Ubicacion">Ubicacion</option>
                                                            <option value="Estado">Estado</option>
                                                            <option value="VerTotales">Ver totales</option>
                                                        </select>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table-bordered table-responsive tablasG" id='tbl_total_maquinaria'>
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Tipo</th>
                                                <th>Precio Alquiler</th>
                                                <th>Fecha Registro</th>
                                                <th>Precio</th>
                                                <th>Disposición</th>
                                                <th>Ubicación</th>
                                                <th>Estado</th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="listadoHerramientas">
                                            <?php ListarTotalMaquinaria(); ?>
                                        </tbody>
                                    </table>
                                    <table class="table-bordered table-responsive tablasG" style="display: none" id='tbl_total__tipo_herramientas'>
                                        <thead>
                                            <tr>
                                                <th>Descripción</th>
                                                <th>Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listadoTotalTipoHerramientas">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!---Inicio Modals-->

                <div id="ModalAgregarMaquinaria" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <div class="modal-content">

                            <div id="modalheaderAgregarHerramienta" class="modal-header headerModal">

                                <button type="button" class="close" data-dismiss="modal" onclick="LimpiarColorHerramienta()">&times;</button>

                                <h4 class="modal-title" id="tituloModalAgregarMaquinaria">Registrar maquinaria</h4>
                            </div>

                            <div class="modal-body">
                                <form method="POST" name="frmInsertar" class="form-horizontal" action="">

                                    <div class="form-group">
                                        <label class="col-lg-2">Código</label>
                                        <div class="col-lg-10">
                                            <div class="input-group col-md-7">
                                                <input type="text" class="form-control" placeholder="Código" name="txtCodigoMaquinaria" id="txtCodigoMaquinaria" />
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-lg-2">Descripcion</label>
                                        <div class="col-md-8">
                                            <textarea type="text" name="txtDescripcionMaquinaria" id="txtDescripcionMaquinaria" class="form-control " placeholder="Descripcion"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2">Marca</label>
                                        <div class="col-md-8">
                                            <textarea type="text" name="txtMarca" id="txtMarca" class="form-control " placeholder="Marca"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2">Moneda compra</label>
                                        <div class="col-lg-8">
                                            <?php ObtenerComboBoxMonedas("cboMonedaAgregar") ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2">Valor del equipo</label>
                                        <div class="col-md-8">
                                            <input type="text" name="txtPrecio" onkeypress="return AceptarSoloNumerosMonto(event);" onchange="Formateo_Monto(this)" id="txtPrecio" class="form-control " onkeypress="return soloNumeros(event)" placeholder="Precio" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-2">Fecha</label>
                                        <div class="col-md-6">
                                            <input type="date" name="txtFechaRegistro" id="txtFechaRegistro" class="form-control " placeholder="Fecha" />

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-2">Procedencia</label>
                                        <div class="col-md-6">
                                            <input type="text" name="txtProcedencia" id="txtProcedencia" class=" form-control " placeholder="Procedencia" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-2">Tipo</label>
                                        <div class="col-md-6">
                                            <?php
                                            CargarComboBoxTipoHerramienta("M", "comboHerramientaTipo")
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-2">Num. Factura</label>
                                        <div class="col-md-6">
                                            <input type="text" name="txtNumFactura" id="txtNumFactura" class=" form-control " placeholder="Numero de Factura" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2">Ficha Tecnica</label>
                                        <div class="col-md-6">
                                            <input type="file" name="txtFileFichaTecnica" id="txtFileFichaTecnica" accept=".pdf" class=" form-control " placeholder="Numero de Factura" />
                                        </div>
                                    </div>
                                </form>
                                <div id="respuestaAgregarMaquinaria">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success btn-estilos" onclick="AgregarMaquinaria()">Guardar</button>
                                    <button type="submit" class="btn btn-default" data-dismiss="modal" onclick="LimpiarColorHerramienta()">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal EditarMaquinaria-->
                <div id="ModalEditarMaquinaria" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <div class="modal-content">

                            <div class="modal-header headerModal">
                                <button type="button" class="close" data-dismiss="modal" onclick="LimpiarColorHerramienta()">&times;</button>
                                <h4 class="modal-title" id="tituloModalAgregarMaquinaria">Editar maquinaria</h4>
                            </div>

                            <div class="modal-body">
                                <form method="POST" name="frmInsertar" class="form-horizontal" action="">

                                    <div class="form-group">
                                        <label class="col-lg-2">Código</label>
                                        <div class="col-lg-10">
                                            <div class="input-group col-md-7">
                                                <input type="text" style="display:none" name="txtIdHerramientaEditar" id="txtIdHerramientaEditar" />
                                                <input type="text" style="display:none" name="txtIdArchivoEditar" id="txtIdArchivoEditar" />
                                                <input type="text" style="display:none" name="txtCodigoActualEditarMaquinaria" id="txtCodigoActualEditarMaquinaria" />
                                                <input type="text" class="form-control" placeholder="Código" name="txtCodigoEditarMaquinaria" id="txtCodigoEditarMaquinaria" />
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-lg-2">Descripcion</label>
                                        <div class="col-md-8">
                                            <textarea type="text" name="txtDescripcionEditarMaquinaria" id="txtDescripcionEditarMaquinaria" class="form-control " placeholder="Descripcion"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2">Marca</label>
                                        <div class="col-md-8">
                                            <textarea type="text" name="txtMarcaEditar" id="txtMarcaEditar" class="form-control " placeholder="Marca"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2">Moneda compra</label>
                                        <div class="col-lg-8">
                                            <?php ObtenerComboBoxMonedas("cboMonedaEditar") ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2">Valor del equipo</label>
                                        <div class="col-md-8">
                                            <input type="text" name="txtPrecioEditar" onkeypress="return AceptarSoloNumerosMonto(event);" onchange="Formateo_Monto(this)" id="txtPrecioEditar" class="form-control " onkeypress="return soloNumeros(event)" placeholder="Precio" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-2">Fecha</label>
                                        <div class="col-md-6">
                                            <input type="date" name="txtFechaRegistroEditar" id="txtFechaRegistroEditar" class="form-control " placeholder="Fecha" />

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-2">Procedencia</label>
                                        <div class="col-md-6">
                                            <input type="text" name="txtProcedenciaEditar" id="txtProcedenciaEditar" class=" form-control " placeholder="Procedencia" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-2">Tipo</label>
                                        <div class="col-md-6">
                                            <?php
                                            CargarComboBoxTipoHerramienta("M", "comboHerramientaTipoEditar")
                                            ?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-lg-2">Num. Factura</label>
                                        <div class="col-md-6">
                                            <input type="text" name="txtNumFacturaEditar" id="txtNumFacturaEditar" class=" form-control " placeholder="Numero de Factura" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-lg-2">Ficha Tecnica</label>
                                        <div class="col-md-6">
                                            <input type="file" name="txtFileFichaTecnicaEditar" id="txtFileFichaTecnicaEditar" accept=".pdf" class=" form-control " placeholder="Numero de Factura" />
                                        </div>
                                    </div>
                                </form>
                                <div id="respuestaEditarMaquinaria">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success btn-estilos" onclick="OnclickEditarMaquinaria()">Guardar</button>
                                    <button type="submit" class="btn btn-default" data-dismiss="modal" onclick="LimpiarColorHerramienta()">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL Eliminar Maquinaria -->
                <div id="ModalEliminarMaquinaria" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div id="modalTipo" class="modal-header headerModal">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Eliminar maquinaria</h4>
                            </div>
                            <div class="modal-body">
                                <form method="POST" name="frmInsertar" class="form-horizontal" action="">

                                    <div class="form-group">
                                        <label class="col-lg-3">Motivo</label>
                                        <div class="col-lg-12">
                                            <input type="text" name="IdCodigo" id="IdCodigo" style="display:none" class="form-control " />
                                            <textarea type="text" rows="2" name="txtMotivoEliminarMaquinria" id="txtMotivoEliminarMaquinria" class="form-control " placeholder="Ingrese el motivo por el cual se elimina">

                                        </textarea>
                                        </div>
                                    </div>

                                </form>
                                <div id="idResultadoEliminarMaquinaria"></div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" onclick="EliminarMaquinaria()">Eliminar</button>
                                    <button type="submit" class="btn btn-default" data-dismiss="modal" onclick="LimpiarColorTipoHerramienta()">Cerrar</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- MODAL Ficha técnica -->
                <div id="ModalFichaTecnica" class="modal fade" role="dialog">
                    <div class="modal-dialog" style="width:80% ;">
                        <div class="modal-content">
                            <div id="modalTipo" class="modal-header headerModal">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Ficha técnica</h4>
                            </div>
                            <div class="modal-body">
                                <iframe id="idVerPDF" width="100%" height="500px"></iframe>
                                <div id="idResultadoConsultarFichaTecnica"></div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-default" data-dismiss="modal" onclick="LimpiarColorTipoHerramienta()">Cerrar</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                                <!-- MODAL Ficha técnica -->
              <div id="ModalLoanding" class="modal fade" role="dialog">
                    <div class="modal-dialog" style="width:15% ;">
                        <div class="modal-content">
                            <div  class="modal-header">
                                <button id="btnCloseModalLoading" type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body" style="text-align:center ;">
                            <h3>Procesando...</h3>
                            <img src="../resources/imagenes/loanding.gif" alt="" width="60px"/>                         
                            <br>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</body>

</html>