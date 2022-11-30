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
    <script src="../js/jsReportesMaquinari.js" type="text/javascript"></script>
    <script src="../js/jquery.table2excel.js" type="text/javascript"></script>
    <script src="../js/jsMenu.js" type="text/javascript"></script>
    <script src="../js/jsHistorial_Y_Reparaciones.js" type="text/javascript"></script>
    <script src="../js/jspdf.debug.js" type="text/javascript"></script>
    <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
</head>

<body>
    <header id="header">
        <?php
        include 'Menu.php';
        require_once '../BLL/Historia_Y_ReparacionesMaquinaria_BL.php';
        Crearmenu();
        ?>
    </header>

    <main id="contenedor">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3>Hitorial y reparaciones</h3>
                <button style="float: right;margin-right: 20px;position: relative;top:-40px" type="button" class="btn btn-default" id="btnAtrasNoResponsive" onclick="Atras()">
                    <img src="../resources/imagenes/regresar.png" alt="" width="20px;" />
                </button>

            </div>
            <div class="panel-body">
                <div class="col-lg-12" id="equipoEnReparacion">
                    <div id="btnreparaciones">
                        <div class="col-lg-4">
                            <div class="input-group">
                                <input id="txtCodigoMaquinaria" name="txtCodigoMaquinaria" type="text" class="form-control" onclick="LimpiarRegistroReparaciones()" placeholder="Ingrese el código para ver el Historial">
                                <span class="input-group-btn">
                                    <button id="btnBuscar" class="btn btn-default" type="button" onclick="ConsultarHistoria_Y_ReparacionesMaquinaria()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt="" /></button>
                                </span>
                            </div>
                            <br />
                        </div>
                        <br />
                        <div class="col-lg-9">
                            <button style="float: left" class="btn btn-danger" data-toggle='modal' data-target='#ModalEnviarReparacion' onclick="LimpiarBoletaReparacion()">Enviar Reparación</button>
                            <button style="float: left;margin-left: 5px" class="btn btn-success" onclick="MostraBoletasReparaciones()">Ver Boletas</button>
                        </div>
                        <br>
                    </div>
                    <br><br>
                    <div class="col-lg-12" style="margin-top:3rem">
                        <div class="panel panel-info" style=" width: auto">
                            <div class="panel-heading">
                                <h4>Maquinaria en reparación</h4>
                                <button id="btnExportarMaquinariaReparacion" style="float: right;position: relative;top: -35px" class="btn btn-default" onclick="GenerarReporteMaquinariaEnReparacion()"><img src="../resources/imagenes/Excel.png" alt="" width="20" /> </button>
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
                                                            <button id="btnBuscarCodigoT" class="btn btn-default" type="button" onclick="ConsultarMaquinariaReparacionPorCodigo()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt="" /></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>


                                        <td class="translado">
                                            <div style="width: 70%;" class="form-group ">
                                                <div class="buscarHerramienta" style=" width: 100%;">
                                                    <Select style="font-size: 15px" class="form-control" name="cbofiltrotipo" id="cbofiltrotipo" onchange="ConsultarMaquinariaReparacionPorTipo()">
                                                        <option value="0">Filtrar por Tipo</option>
                                                        <?php
                                                        $conexion = new Conexion();
                                                        $conn = $conexion->CrearConexion();
                                                        $sql = "Select DISTINCT ID_Tipo,Descripcion from tbl_tipoherramienta where TipoEquipo ='M'";
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
                                        <?php echo ConsultarTodaMaquinariaReparacion(); ?>
                                    </tbody>
                                </table>
                                <br>
                                <div id="idResultados"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12" id="historialGastos" style="display:none ;">
                    <div class="col-lg-12" id="">
                        <div class="panel panel-info" style=" width: auto">
                            <div class="panel-heading ">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <h4>Historial de Gastos</h4>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <button class="btn btn-default" id="btnExportarExcelHistorialGastos" onclick="GenerarReporteHistorial_Y_ReparacionesMaquinaria()" style="float: right">
                                            <img src="../resources/imagenes/Excel.png" width="20" />
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <div class="panel-body">

                                <div id="informacionHerramienta" style="width: 100%">

                                    <table>
                                        <tr>
                                            <td class="translado">
                                                <h4>Codigo Maquinaria:
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
                                                <h4>Precio compra:
                                                    <span id="precioHerramienta"> </span>
                                                </h4>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="translado">
                                                <h4>Precio alquiler equipo:
                                                    <span id="txtPrecioAlquiler"> </span>
                                                </h4>

                                            <td>
                                            <td class="translado">
                                                <h4>
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
                                            <h3 style="margin-top: 15px">Control de traslado de maquinaria</h3>
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
                                    <tbody id="tblHistorialMaquinaria">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-12" id="boletasReparacionMaquinaria" style="display:none ;">
                    <div class="col-lg-12" id="">
                        <div class="panel panel-info" style=" width: auto">
                            <div class="panel-heading ">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <h4>Boletas de reparación</h4>
                                    </div>
                                </div>

                            </div>
                            <div class="panel-body">
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <input id="txtNumBoletaBuscar" name="txtNumBoletaBuscar" type="text" class="form-control" placeholder="Número de boleta">
                                        <span class="input-group-btn">
                                            <button id="btnBuscarCodigoT" class="btn btn-default" type="button" onclick="ConsultarTodasLasBoletasReparacionMaquinariaPorNumBoleta()">
                                            <img src="../resources/imagenes/icono_buscar.png" width="18px" alt="" />
                                        </button>
                                        </span>
                                    </div>
                                </div>
                                <br>
                            <div class="col-lg-12" style="margin-top:2rem">
                                    <table class="table-bordered table-responsive tablasG">
                                        <thead>
                                            <th>Número</th>
                                            <th>Fecha</th>
                                            <th>Generada Por</th>
                                            <th></th>
                                        </thead>
                                        <tbody id="mostrarBoletasReparacion">
                                            <?php ConsultarTodasLasBoletasReparacionMaquinaria() ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
                                            <td style="padding-right: 5px" class="translado"><input type="text" name="txtCodigoBuscarEnviarReparacion" id="txtCodigoBuscarEnviarReparacion" class="form-control input-md" value="" placeholder="Código" /></td>
                                            <td style="padding-left: 5px" class="translado"><input type="submit" value="Agregar" onclick="ObtenerMaquinariaEnviarReparacion()" class="btn btn-success" /></td>

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
                                    <button type="button" id="btnEnviarReparacion" class="btn btn-success btn-estilos" onclick="EnviarMaquinariaReparacion()">Guardar</button>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="idResultadoEnviarReparacion"></div>
                </div>
            </div>
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
                            <h3>
                                <span id="NomHerramienta"> </span>
                            </h3>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-lg-12">Numero Factura</label>
                                <div class="col-lg-12">
                                    <input type="text" name="txtNunFactura" id="txtNunFactura" class="form-control" placeholder="Numero" onkeypress="return soloNumeros(event)" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-12"> Fecha de la Factura</label>
                                <div class="col-lg-12">
                                    <input type="date" name="txtFechaFactura" id="txtFechaFactura" class="form-control " placeholder="Fecha" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-12">Descripcion</label>
                                <div class="col-lg-12">
                                    <textarea type="text" name="txtDescripcionFactura" id="txtDescripcionFactura" class="form-control " placeholder="Descripción Trabajo Efectuado"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-12">Costo de la Reparación</label>
                                <div class="col-lg-12">
                                    <input type="text" name="txtCantidadFactura" id="txtCantidadFactura" class="form-control" onkeypress="return AceptarSoloNumerosMonto(event);" onchange="Formateo_Monto(this)" placeholder="Costo" />
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="modal-footer">
                            <button type="submit" id="btnRegistrarDatosFacturarReparacion" class="btn btn-success btn-estilos" onclick="FacturacionReparacionMaquinaria()">Guardar</button>
                            <button type="submit" class="btn btn-default" data-dismiss="modal" onclick="LimpiarBoletaFactura()">Cerrar</button>
                        </div>
                    </div>
                    <div id="idResultadoFacturarReparacion"></div>
                </div>
            </div>
        </div>
  <!--MODAL PARA Ver las boletas de reparación    -->
        <div id="ModalVerBoletaReparacion" class="modal fade" role="dialog">   
                <div class="modal-dialog">

                    <div class="modal-content">
                        <div id="voletaVista" name ="voletaVista" class="modal-header headerModal">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id = "tituloBoletaV" name ="tituloBoletaV">Boleta de Reparación de la Herramienta</h4>
                        </div>
                        <div class="modal-body">

                            <header style="overflow: hidden" id="" class="headerPedidoMaterial">
                                <div class="col-xs-6" id="imgPedido">
                                    <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt=""/> </a></h1>
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

                            <div class="bodyPedido" >
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
                                <button  type="button" class="btn btn-default btn-estilos" onclick="Exportar_Pdf('ContenidoBoletaReparacion_Selecionado', 'tblFechaBoletaReparacion', 'consecutivoBoletaReparacionSeleccionado', '', 'TipoPedidoBoletaReparacion', 'generadaPorBoletaReparacion')">
                                    <img  src="../resources/imagenes/print.png" alt="" width="30px"/>
                                </button>
                                <button type="button" class="btn btn-danger btn-estilos" onclick="AnularBoletaReparacion()">Eliminar</button>

                            </div>
                        </div>
                        <div id="mensajesResultadoBoletaReparacion"></div>
                    </div>
                </div>
            </div>
    </main>
</body>

</html>