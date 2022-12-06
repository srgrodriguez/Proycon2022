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
    <script src="../js/jsHerramienta.js" type="text/javascript"></script>
    <script src="../js/jquery.table2excel.js" type="text/javascript"></script>
    <script src="../js/jsMenu.js" type="text/javascript"></script>
    <script src="../js/jsLogin.js" type="text/javascript"></script>
    <script src="../js/jspdf.debug.js" type="text/javascript"></script>
    <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
    <?php if ($_SESSION['ID_ROL'] == 4 || $_SESSION['ID_ROL'] == 5) { ?>
        <script src="../js/Notificaciones.js" type="text/javascript"></script>
        <script src="../js/push.min.js" type="text/javascript"></script>
    <?php } ?>
    <style type="text/css">
        #tbl_total_herramientas {
            table-layout: fixed
        }

        #tbl_total_herramientas tbody tr td {
            overflow-wrap: break-word;
        }

        #tbl_total_herramientas thead tr th {
            overflow-wrap: break-word;
        }
    </style>

</head>
<header id="header">
    <?php
    include 'Menu.php';
    include '../BLL/Herramientas.php';
    Crearmenu();
    ?>
</header>

<main id="contenedor">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3>Herramientas</h3>
            <?php
            if ($_SESSION['ID_ROL'] == 4 || $_SESSION['ID_ROL'] == 5) {
                echo "<img src='../resources/imagenes/opciones.png' id='imgOpciones' onclick='mostrarOpciones()' width='30px'/>";
            }
            ?>
            <button style="float: right;margin-right: 20px;position: relative;top:-40px" id="btnAtrasNoResponsive" type="button" class="btn btn-default" onclick="AtrasH()">
                <img src="../resources/imagenes/regresar.png" alt="" width="20px;" />
            </button>
        </div>
        <div class="panel-body" id="bodypanelHerramientas">
            <form action="../BLL/Reportes/ReportesExcel.php" method="POST">

                <div id="buscarHerrmientas" class="form-group codigoHerramienta">
                    <div class="buscarHerramienta">
                        <div class="input-group">
                            <input id="txtCodigo" name="txtNombreTipoH" type="text" class="form-control" onclick="LimpiarTodo()" placeholder="Ingrese el Tipo de Herramienta que desea encontrar">
                            <span class="input-group-btn">
                                <button id="btnBuscar" class="btn btn-default" type="button" onclick="BuscarHerramientas()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt="" /></button>
                            </span>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="txtTotalHerramienta" value="" />

                <!--Lista TOTAL HERRAMIENTAS-->

                <div class="col-lg-12" id="MostrarBusquedaHerramienta">
                    <div class="panel panel-info" style=" width: auto">

                        <div class="panel-heading" id="headerpnlTotalHerra">
                            <h4 id="tituloHeaderListadoH">Invertario de herramienta eléctrica</h4>
                            <button id="btnImprimirHerramientas1" type="submit" class="btn btn-default"><img src="../resources/imagenes/Excel.png" alt="" width="20" /> </button>
                        </div>
                        <div class="panel-body">
                        <div>
                                    <div class="k-grid-toolbar">
                                        <?php if ($_SESSION['ID_ROL'] == Constantes::RolBodega || $_SESSION['ID_ROL'] == Constantes::RolAdminBodega) { ?>
                                            <ul class="lista-accion">
                                                <li>
                                                    <button type="button" class="btn-accion btn btn-info btn-sm" data-toggle="modal" data-target="#ModalAgregarHerramienta">Agregar Herramienta</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="btn-accion btn btn-info btn-sm" onclick="window.location.href='TipoHerramientaElectrica.php'">Agregar tipo herramienta</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="btn-accion btn btn-info btn-sm" onclick="listarTotalHerramientas()"">Listar total herramientas</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="btn-accion btn btn-info btn-sm" onclick="MostrarListaReparaciones()">Historial y reparaciones</button>
                                                </li>
                                                <li>
                                                    <button type="button" class="btn-accion btn btn-info btn-sm"  onclick="window.location.href='TrasladarHerramientaElectrica.php'">Trasladar herramientas</button>
                                                </li>
                                            </ul>
                                        <?php } ?>
                                    </div>
                                </div>
                                <br>


                            <div id="bodyBusquedaHerramientas">

                                <div class="opcionesHerramientas">
                                    <table>
                                        <tr>
                                            <td class="translado">

                                                <div style="width: 60%;" class="form-group ">
                                                    <div class="buscarHerramienta" style=" width: 100%;">
                                                        <div class="input-group">

                                                            <input id="txtCodigoHerra" name="txtCodigoHerra" type="text" class="form-control" placeholder="Código" onclick="LimpiarListadoCombo()" onchange="FiltroInicioL()">
                                                            <span class="input-group-btn">
                                                                <button id="btnBuscarCodigo" class="btn btn-default" type="button" onclick="BuscarHerramientasPorCodigo()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt="" /></button>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>


                                            </td>
                                            <td class="translado">
                                                <div style="width: 70%;" class="form-group ">
                                                    <div class="buscarHerramienta" style=" width: 100%;margin-right: 40px">
                                                        <Select style="font-size: 15px" class="form-control" name="cboFiltroHerramienta" id="cboFiltroHerramienta" onchange="FiltrosHerramientas()" onclick="LimpiarCampoCodigo()">
                                                            <option value="0">Ordenar por...</option>
                                                            <option value="1">Tipo</option>
                                                            <option value="2">Disposición</option>
                                                            <option value="3">Ubicacion</option>
                                                            <option value="4">Estado</option>
                                                            <option value="5">Ver totales</option>
                                                        </select>

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <table class="table-bordered table-responsive tablasG" id='tbl_total_herramientas'>
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Tipo</th>
                                            <th>Precio alquiler</th>
                                            <th>Fecha Registro</th>
                                            <th>Precio</th>
                                            <th>Disposición</th>
                                            <th>Ubicación</th>
                                            <th>Estado</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="listadoHerramientas">
                                        <?php listarTotalHerramientas();?>
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

            </form>
            <!----  Modal Agregar Herramienta ---->

            <div id="ModalAgregarHerramienta" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <div class="modal-content">

                        <div id="modalheaderAgregarHerramienta" class="modal-header headerModal">

                            <button type="button" class="close" data-dismiss="modal" onclick="LimpiarColorHerramienta()">&times;</button>

                            <h4 class="modal-title" id="tituloModalAgregarHerramienta">Registrar Herramientas</h4>
                        </div>

                        <div class="modal-body">
                            <form method="POST" name="frmInsertar" class="form-horizontal" action="">


                                <div class="form-group">
                                    <label class="col-lg-2">Código Mayor</label>
                                    <div class="col-lg-10">
                                        <div class="input-group col-md-7">
                                            <input type="txtCodigoH" class="form-control" value="<?php echo ObtenerConsecutivoHerramienta() ?>" name="txtCodigoH" id="txtCodigoH" disabled />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2">Código</label>
                                    <div class="col-lg-10">
                                        <div class="input-group col-md-7">
                                            <input type="txtCodigoH2" class="form-control" placeholder="Código" name="txtCodigoH2" id="txtCodigoH2" />
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-lg-2">Descripcion</label>
                                    <div class="col-md-8">
                                        <textarea type="text" name="txtDescripcionH" id="txtDescripcionH" class="form-control " placeholder="Descripcion"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2">Marca</label>
                                    <div class="col-md-8">
                                        <textarea type="text" name="txtMarcaH" id="txtMarcaH" class="form-control " placeholder="Marca"></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2">Precio</label>
                                    <div class="col-md-8">
                                        <input type="text" name="txtPrecioH" id="txtPrecioH" class="form-control " onkeypress="return soloNumeros(event)" placeholder="Precio" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2">Fecha</label>
                                    <div class="col-md-6">
                                        <input type="date" name="txtFechaRegistroH" id="txtFechaRegistroH" class="form-control " placeholder="Fecha" />

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2">Procedencia</label>
                                    <div class="col-md-6">
                                        <input type="text" name="txtProcedenciaH" id="txtProcedenciaH" class=" form-control " placeholder="Procedencia" />
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2">Tipo</label>
                                    <div class="col-md-6">

                                        <select id="comboHerramientaTipoH" name="comboHerramientaTipoH" class="form-control ">

                                            <option value="0" selected="">Seleccione el tipo de herramienta</option>

                                            <?php
                                            $conexion = new Conexion();
                                            $conn = $conexion->CrearConexion();
                                            $sql = "Select Descripcion,ID_Tipo from tbl_tipoherramienta";
                                            $rec = $conn->query($sql);
                                            $conn->close();
                                            if ($rec != null) {
                                                while ($row = mysqlI_fetch_array($rec, MYSQLI_ASSOC)) {
                                                    echo "<option value ='" . $row['ID_Tipo'], "'>";
                                                    echo $row['Descripcion'];
                                                    echo "</option>";
                                                }
                                            }
                                            ?>

                                        </select>


                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2">Num. Factura</label>
                                    <div class="col-md-6">
                                        <input type="text" name="txtNumFacturaH" id="txtNumFacturaH" class=" form-control " placeholder="Numero de Factura" />
                                    </div>
                                </div>


                            </form>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success btn-estilos" onclick="GuardarHerramienta()">Guardar</button>
                                <button type="submit" class="btn btn-default" data-dismiss="modal" onclick="LimpiarColorHerramienta()">Cerrar</button>


                            </div>
                        </div>
                    </div>
                </div>
            </div>

                        <!----  Modal Editar Herramienta ---->

                        <div id="ModalEidtarHerramienta" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <div class="modal-content">

                        <div id="modalheaderEditarHerramienta" class="modal-header headerModal">

                            <button type="button" class="close" data-dismiss="modal" onclick="LimpiarColorHerramienta()">&times;</button>

                            <h4 class="modal-title">Eidtar Herramientas</h4>
                        </div>

                        <div class="modal-body">
                            <form method="POST" name="frmInsertar" class="form-horizontal" action="">
                                <div class="form-group">
                                    <label class="col-lg-2">Código Mayor</label>
                                    <div class="col-lg-10">
                                        <div class="input-group col-md-7">
                                            <input type="text" class="form-control" value="<?php echo ObtenerConsecutivoHerramienta() ?>" name="txtCodigoH" id="txtCodigoH" disabled />
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2">Código</label>
                                    <div class="col-lg-10">
                                        <div class="input-group col-md-7">
                                        <input type="text" class="form-control" style="display:none" placeholder="Código" name="txtCodigoHerramientaActualEditar" id="txtCodigoHerramientaActualEditar" />                                            
                                        <input type="text" class="form-control" style="display:none" placeholder="Código" name="txtIdHerramientaEditar" id="txtIdHerramientaEditar" />
                                            <input type="text" class="form-control" placeholder="Código" name="txtCodigoEditar" id="txtCodigoEditar" />
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-lg-2">Descripcion</label>
                                    <div class="col-md-8">
                                        <textarea type="text" name="txtDescripcionEditar" id="txtDescripcionEditar" class="form-control " placeholder="Descripcion"></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2">Marca</label>
                                    <div class="col-md-8">
                                        <textarea type="text" name="txtMarcaEditar" id="txtMarcaEditar" class="form-control " placeholder="Marca"></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2">Precio</label>
                                    <div class="col-md-8">
                                        <input type="text" name="txtPrecioEditar" id="txtPrecioEditar" class="form-control "onkeypress="return AceptarSoloNumerosMonto(event);" onchange="Formateo_Monto(this)" placeholder="Precio" />
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

                                        <select id="cboTipoHerramientaEditar" name="cboTipoHerramientaEditar" class="form-control ">

                                            <option value="0" selected="">Seleccione el tipo de herramienta</option>

                                            <?php
                                            $conexion = new Conexion();
                                            $conn = $conexion->CrearConexion();
                                            $sql = "Select Descripcion,ID_Tipo from tbl_tipoherramienta";
                                            $rec = $conn->query($sql);
                                            $conn->close();
                                            if ($rec != null) {
                                                while ($row = mysqlI_fetch_array($rec, MYSQLI_ASSOC)) {
                                                    echo "<option value ='" . $row['ID_Tipo'], "'>";
                                                    echo $row['Descripcion'];
                                                    echo "</option>";
                                                }
                                            }
                                            ?>

                                        </select>


                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2">Num. Factura</label>
                                    <div class="col-md-6">
                                        <input type="text" name="txtNumFacturaEditar" id="txtNumFacturaEditar" class=" form-control " placeholder="Numero de Factura" />
                                    </div>
                                </div>


                            </form>
                            <div id="idResultadoActulizarHerramienta"></div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success btn-estilos" onclick="EditarHerramienta()">Guardar</button>
                                <button type="submit" class="btn btn-default" data-dismiss="modal" onclick="LimpiarColorHerramienta()">Cerrar</button>


                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!--Listar Reparaciones Enviar A reparaciones -->

            <div class="col-lg-12" id="reparaciones" style="display: none">
                <div id="btnreparaciones" >
                    <h2 id="tituloNoresponsive">Sección de Reparaciones</h2>
                    <h4 id="tituloResponsive">Reparaciones</h4>
                    <div class="col-lg-4">
                    <div class="input-group">
                        <input id="txtCodigoVista" name="txtCodigoVista" type="text" class="form-control" onclick="LimpiarRegistroReparaciones()" placeholder="Ingrese el código para ver el Historial">
                        <span class="input-group-btn">
                            <button id="btnBuscar" class="btn btn-default" type="button" onclick="MostrarHistorial()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt="" /></button>
                        </span>
                    </div>
                    </div>
                    <br />
                    <div class="col-lg-12" style="margin-top:0.6rem">
                    <button style="float: left" class="btn btn-danger" data-toggle='modal' data-target='#ModalEnviarReparacion' onclick="LimpiarBusquedaHerramienta()">Enviar Reparación</button>
                    <button style="float: left;margin-left: 5px" class="btn btn-success" onclick="MostraBoletasReparaciones()">Ver Boletas</button>
                    </div>
                    <br>
                </div>
                <br><br>
                <div id="panelreparaciones" class="col-lg-12">
                    <div class="panel panel-info" style=" width: auto">
                        <div class="panel-heading">
                            <h4>Herramientas en Reparación</h4>
                            <button id="btnImprimirHerramientas" style="float: right;position: relative;top: -35px" class="btn btn-default" onclick="Exportar_Excel('tablaReparaciones')"><img src="../resources/imagenes/Excel.png" alt="" width="20" /> </button>
                        </div>
                        <div class="panel-body">
                            <table class="tablatranslado">
                                <tr>
                                    <td class="translado">
                                        <div style="width: 50%;" class="form-group ">
                                            <div class="buscarHerramienta" style=" width: 100%;">
                                                <div class="input-group">
                                                    <input id="CodHerramientaReparacion" name="CodHerramientaReparacion" type="text" class="form-control" placeholder="Código" onclick="LimpiarBusquedaTipo()" onchange="FiltroInicio2()">
                                                    <span class="input-group-btn">
                                                        <button id="btnBuscarCodigoT" class="btn btn-default" type="button" onclick="BuscarHerramientaTablaReparaciones()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt="" /></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>


                                    <td class="translado">
                                        <div style="width: 70%;" class="form-group ">
                                            <div class="buscarHerramienta" style=" width: 100%;">
                                                <Select style="font-size: 15px" class="form-control" name="cbofiltrotipo" id="cbofiltrotipo" onclick="FiltroReparacionTipoc()" onchange="FiltroReparacionTipo()">
                                                    <option value="0">Filtrar por Tipo</option>
                                                    <?php
                                                    $conexion = new Conexion();
                                                    $conn = $conexion->CrearConexion();
                                                    $sql = "Select DISTINCT ID_Tipo,Descripcion from tbl_tipoherramienta";
                                                    $rec = $conn->query($sql);
                                                    $conn->close();
                                                    if ($rec != null) {
                                                        while ($row = mysqli_fetch_array($rec, MYSQLI_ASSOC)) {
                                                            echo "<option value ='" . $row['ID_Tipo'], "'>";
                                                            echo $row['Descripcion'];
                                                            echo "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>


                            <table class="table-bordered table-responsive tablasG" id='tablaReparaciones'>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th style=" width: 150px;">Código </th>
                                        <th>Tipo </th>
                                        <th>Fecha Salida </th>
                                        <th>Dias</th>
                                        <th>Proveedor Reparación</th>
                                        <th>NumBoleta</th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody id="HerramientasEnReparacion">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <!--MUESTRA EL HISTORIAL DE FACTURAS Y TRASLADOS DE LAS HERARAMIENTAS    MostrarHistorialHerramienta-->
            <form action="../BLL/Reportes/ReportesExcel.php" method="POST">
                <input id="codigo" type="hidden" name="codigo" value="" />
                <div class="MostrarHistorialHerramienta" style="display: none" id="">
                    <div class="panel panel-info" style=" width: auto">
                        <div class="panel-heading">
                            <h4>Historial de Gastos</h4>
                            <button class="btn btn-default" id="btnExportarExcelHistorialGastos" style="float: right">
                                <img src="../resources/imagenes/Excel.png" width="20" />
                            </button>
                        </div>
                        <div class="panel-body">

                            <div id="informacionHerramienta" style="width: 100%">

                                <table>
                                    <tr>
                                        <td class="translado">
                                            <h4>Herramienta:
                                                <span id="NombreHerramienta"> </span>
                                            </h4>

                                        <td>
                                        <td class="translado">
                                            <h4>Fecha Adquirida:
                                                <span id="FechaAdquisicion"> </span>
                                            </h4>

                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="translado">
                                            <h4>Marca:
                                                <span id="HerramientaMarca"> </span>
                                            </h4>

                                        <td>
                                        <td class="translado">
                                            <h4>Procedencia:
                                                <span id="ProcedenciaHerramienta"> </span>
                                            </h4>

                                        </td>
                                    </tr>

                                    <!-- ----------------Nuevoooo --------------------- !-->
                                    <tr>
                                        <td class="translado">
                                            <h4>Num Factura Herramienta:
                                                <span id="numFactHerramienta"> </span>
                                            </h4>

                                        <td>
                                        <td class="translado">
                                            <h4>Precio Herramienta:
                                                <span id="precioHerramienta"> </span>
                                            </h4>

                                        </td>
                                    </tr>

                                    <!-- --------------Nuevoooo --------------------- !-->

                                </table>

                            </div>

                            <div style="width: 100%">
                                <h4>Descripción:
                                    <span id="DescripcionHerramienta"> </span>
                                </h4>

                            </div>
                            <hr>



                            <h3>Reparaciones</h3>

                            <table class="table-bordered table-responsive tablasG" id='tablaReparacionesTotal'>
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Numero Factura</th>
                                        <th>Descripción</th>
                                        <th>Costo</th>
                                    </tr>
                                </thead>
                                <tbody id="tablareparacionestotales">
                                </tbody>
                            </table>

                            <br>
                            <hr>
                            <br>

                            <table>
                                <tr>
                                    <td class="translado">
                                        <h3 style="margin-top: 15px">Control de Traslados de Herramienta</h3>
                                    </td>
                                </tr>
                            </table>

                            <table class="table-bordered table-responsive tablasG" id='tablaTrasladosTotal'>
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>NºBoleta</th>
                                        <th>Ubicación</th>
                                        <th>Destino</th>

                                    </tr>
                                </thead>
                                <tbody id="tablatrasladostotales">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </form>

            <section id="BoletaReparacionHerramienta" class="BolestasReparacion" style="display: none">

                <h3>Boletas De Reparación</h3>
                <div id="contenidoBoletasReparacion">

                </div>


            </section>

        </div>



        <!--MODAL PARA FACTURAR Y ACTUALIZAR LA REPARACION    -->

        <div id="ModalRegistrarGastos" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div id="headermodalRegistroGastos" class="modal-header headerModal">

                        <button type="button" class="close" data-dismiss="modal" onclick="LimpiarBoletaFactura()">&times;</button>
                        <h4 id="tituloRegistrarGasto" class="modal-title">Factura de la Reparación</h4>

                    </div>

                    <div class="modal-body">
                        <div style="width: 100%">

                            <table>
                                <tr>
                                    <td class="translado">
                                        <h5>Numero Reparación:
                                            <span id="NumReparacion"> </span>
                                        </h5>

                                    <td>
                                    <td class="translado">
                                        <h5>Numero Boleta:
                                            <span id="NumBoletaF"> </span>
                                        </h5>

                                    </td>
                                </tr>
                            </table>

                            <h5>Código Herramienta:
                                <span id="CodHerramienta"> </span>
                            </h5>


                        </div>

                        <div style="width: 100%">
                            <h3>Herramienta:
                                <span id="NomHerramienta"> </span>
                            </h3>

                        </div>


                        <hr>
                        <form method="POST" name="frmInsertar" class="form-horizontal" action="mantenimientoUsuario.php">

                            <div class="form-group">
                                <label class="col-lg-3">Numero Factura</label>
                                <div class="col-lg-5">
                                    <input type="text" name="txtNunFactura" id="txtNunFactura" class="form-control" placeholder="Numero" onkeypress="return soloNumeros(event)" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3"> Fecha de la Factura</label>
                                <div class="col-lg-5">
                                    <input type="date" name="txtFechaFactura" id="txtFechaFactura" class="form-control " placeholder="Fecha" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-3">Descripcion</label>
                                <div class="col-lg-8">
                                    <textarea type="text" name="txtDescripcionFactura" id="txtDescripcionFactura" class="form-control " placeholder="Descripción Trabajo Efectuado"></textarea>
                                </div>
                            </div>




                            <div class="form-group">
                                <label class="col-lg-3">Costo de la Reparación</label>
                                <div class="col-lg-5">
                                    <input type="text" name="txtCantidadFactura" id="txtCantidadFactura" class="form-control" onkeypress="return soloNumeros(event)" placeholder="Costo" />
                                </div>
                            </div>



                        </form>


                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success btn-estilos" onclick="ElaborarFactura()">Guardar</button>
                            <button type="submit" class="btn btn-default" data-dismiss="modal" onclick="LimpiarBoletaFactura()">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!--MOdal pedido Sucessfull -->


        <div id="Mensajesucessfull" class="modal fade " role="dialog">
            <div class="modal-dialog">
                <div class="modal-content alert alert-success">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <strong id="MensajeSucessfull"></strong>

                </div>
            </div>
        </div>


        <!--Modal de Advertencia Codigo ERRONEO-->

        <div id="ModalDefaul" style="" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content" style="width: 20%;margin: auto">

                    <div class="modal-body  btn btn-default">

                        <h4 class="modal-title" id="ModalDefaul"></h4>

                        <div class="modal-footer">
                            <center>
                                <button type="submit" class="btn btn-danger btn-estilos" data-dismiss="modal">Aceptar</button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="ModalVerBoletaReparacion" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <div class="modal-content">
                    <div id="voletaVista" name="voletaVista" class="modal-header headerModal">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="tituloBoletaV" name="tituloBoletaV">Boleta de Reparación de la Herramienta</h4>
                    </div>




                    <div class="modal-body">

                        <header style="overflow: hidden" id="" class="headerPedidoMaterial">
                            <div class="col-xs-6" id="imgPedido">
                                <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt="" /> </a></h1>
                            </div>

                            <div id="headerBoletaPedido">
                                <div class="col-xs-6 text-right">
                                    <h2><small style="color: red">Boleta Nº <span id="consecutivoBoletaReparacionSeleccionado"></span></small></h2>
                                </div>

                                <div class="col-xs-6 text-right tableFechaPedido">
                                    <table class="talaFecha" id="tblFechaBoletaReparacion">
                                        <thead>
                                            <tr>
                                                <th>Dia</th>
                                                <th>Mes</th>
                                                <th>Año</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="dia"></td>
                                                <td id="mes"></td>
                                                <td id="anno"></td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>

                                <h4>Boleta: <strong id="TipoPedidoBoletaReparacion">Reparación Herramienta</strong></h4>
                                <h4>Generada Por: <strong id="generadaPorBoletaReparacion"></strong></h4>
                                <BR>
                            </div>
                        </header>

                        <div class="bodyPedido">
                            <div class="tableCuerpoPedido" id="ContenidoBoletaReparacion_Selecionado">
                                <table class="tablasG" id="tblBoletaReparacion">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Tipo</th>
                                            <th>Marca</th>
                                            <th>Proveedor reparación</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contenidoBoletaReparacion">

                                    </tbody>
                                </table>
                            </div>
                        </div><br>



                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-estilos" onclick="Exportar_Pdf('ContenidoBoletaReparacion_Selecionado', 'tblFechaBoletaReparacion', 'consecutivoBoletaReparacionSeleccionado', '', 'TipoPedidoBoletaReparacion', 'generadaPorBoletaReparacion')">
                                <img src="../resources/imagenes/print.png" alt="" width="30px" />
                            </button>
                            <button type="button" class="btn btn-danger btn-estilos" onclick="AnularBoletaMaterial()">Eliminar</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!--Modal de Enviar a REPARACION -->

        <div id="ModalEnviarReparacion" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div id="modalBoletaReparacionHerramienta" class="modal-header headerModal">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 id="mensajeBoletaReparacion" class="modal-title">Salida de Herramienta Reparación</h4>
                    </div>
                    <div class="modal-body">
                        <div id="PedidoHerramientastt">
                            <header id="headerPedidoMaterial" class="headerPedidoMaterial">
                                <div class="col-xs-6">
                                    <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt="" /> </a></h1>

                                </div>
                                <div class="col-xs-6 text-right">
                                    <h2><small style="color: red">Boleta Nº<span id="ConsecutivoPedidoHerramienta"><?php echo ConsecutivoReparacion() ?></span></small></h2>
                                </div>

                                <div class="col-xs-6 text-right tableFechaPedido">
                                    <table class="fecha">
                                        <thead>
                                            <tr style="border:1px  solid black;">
                                                <th>Dia</th>
                                                <th>Mes</th>
                                                <th>Año</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><?php echo date("d") ?></td>
                                                <td><?php echo date("m") ?></td>
                                                <td><?php echo date("Y") ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <h2>Bodega Proycon</h2>
                                <h4>Boleta: <strong>Reparaciones</strong></h4>

                                <BR>
                                <table style="width: 60%;">
                                    <tbody>
                                        <tr>
                                            <td style="padding-right: 5px" class="translado"><input type="text" name="txtCodigoHerramientaBuscar" id="txtCodigoHerramientaBuscar" class="form-control input-md" value="" placeholder="Código" /></td>
                                            <td style="padding-left: 5px" class="translado"><input type="submit" value="Agregar" onclick="BuscarHerramientaNombre()" class="btn btn-success" /></td>

                                        </tr>
                                    </tbody>
                                </table>

                            </header>

                            <div class="bodyPedido">
                                <div class="tableCuerpoPedido">
                                    <table class="tablas" id="tbl_R_Herramientas">
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Tipo</th>
                                                <th>Marca</th>
                                                <th>Estado</th>
                                                <th>Ubicacion</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="ContenidoReparaciones">

                                        </tbody>
                                    </table>
                                    <br>
                                    <p>Provedor Reparacion: <input type="text" class="form-control input-md" id="provedorReparacion" name="provedorReparacion" value="" />
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <!--  <button type="button" class="btn btn-default btn-estilos" onclick="">
                                            <img src="../resources/imagenes/print.png" alt="" width="30px"/>
                                        </button>-->
                                    <button type="button" class="btn btn-success btn-estilos" onclick="GuardarBoletaReparaciones()">Guardar</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Modal de Translado de Herramientas -->

        <div id="ModalTranslado" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div id="modalTransladoBoleta" class="modal-header headerModal">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 id="mensajeTranslado" class="modal-title">Traslado de Herramienta</h4>
                    </div>
                    <div class="modal-body">

                        <div id="PedidoHerramientastt">
                            <header id="headerPedidoMaterial" class="headerPedidoMaterial">
                                <div class="col-xs-6">
                                    <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt="" /> </a></h1>

                                </div>

                                <div class="col-xs-6 text-right">
                                    <h2><small style="color: red">Boleta Nº<span id="ConsecutivoPedidoHerramientaF"><?php echo ObtenerConsecutivoPedido() ?></span></small></h2>
                                </div>

                                <div class="col-xs-6 text-right tableFechaPedido">
                                    <table class="fecha">
                                        <thead>
                                            <tr style="border:1px  solid black;">
                                                <th>Dia</th>
                                                <th>Mes</th>
                                                <th>Año</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="idDia"><?php echo date("d") ?></td>
                                                <td id="idMes"><?php echo date("m") ?></td>
                                                <td id="idAño"><?php echo date("Y") ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <h4>Boleta: <strong>Traslado Herramientas</strong></h4>
                                <h4>Destino del Traslado:
                                    <span id="DestinoTrasl"> </span>
                                </h4>
                                <h5 style="display:none;">Fecha:
                                    <span id="FechaFinal" style="display:none;"> </span>
                                </h5>


                                <hr>


                                <div style="width: 100%">

                                    <table class="table-bordered table-responsive tablasG" id='tablaMostrarTraslado2'>
                                        <thead>
                                            <tr>
                                                <th>Código</th>
                                                <th>Ubicacion</th>
                                                <th>FechaIngreso</th>
                                                <th>Marca</th>
                                                <th>Descripcion</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaMostrarTraslado">
                                        </tbody>
                                    </table>

                                    <br>
                                    <h5>Ubicación Destino:
                                    </h5>

                                    <Select style="font-size: 15px" class="form-control" name="cboFiltroTipoHerramientaT" id="cboFiltroTipoHerramientaT" onchange="guardarNombreDestino()"">        
                                            <option value=" 0">Seleccione El Destino</option>
                                        <?php
                                        $conexion = new Conexion();
                                        $conn = $conexion->CrearConexion();
                                        $sql = "Select DISTINCT ID_Proyecto,Nombre from tbl_proyectos";
                                        $rec = $conn->query($sql);
                                        $conn->close();
                                        if ($rec != null) {
                                            while ($row = mysqli_fetch_array($rec, MYSQLI_ASSOC)) {
                                                echo "<option value ='" . $row['ID_Proyecto'], "'>";
                                                echo $row['Nombre'];
                                                echo "</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                    <br>
                                </div>

                            </header>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success btn-estilos" onclick="ElaborarTranslado()">Guardar</button>
                                <button type="submit" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Traslado de Herramientas  -->
    </div>

                    <!-- MODAL Eliminar Maquinaria -->
                    <div id="ModalEliminarHerramienta" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div id="modalTipo" class="modal-header headerModal">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Eliminar herramienta</h4>
                            </div>
                            <div class="modal-body">
                                <form method="POST" name="frmInsertar" class="form-horizontal" action="">

                                    <div class="form-group">
                                        <label class="col-lg-3">Motivo</label>
                                        <div class="col-lg-12">
                                            <input type="text" name="IdCodigo" id="IdCodigo" style="display:none" class="form-control " />
                                            <textarea type="text" rows="2" name="txtMotivoEliminarHerramienta" id="txtMotivoEliminarHerramienta" class="form-control " placeholder="Ingrese el motivo por el cual se elimina">

                                        </textarea>
                                        </div>
                                    </div>

                                </form>
                                <div id="idResultadoEliminarMaquinaria"></div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" onclick="EliminarHerramientaElectrica()">Eliminar</button>
                                    <button type="submit" class="btn btn-default" data-dismiss="modal" onclick="LimpiarColorTipoHerramienta()">Cerrar</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
</main>
<div id="infoResponse"></div>
</body>

</html>