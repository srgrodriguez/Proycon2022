<?php
session_start();
require_once '../BLL/Autorizacion.php';
ValidarIniciodeSession();
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="icon" href="resources\imagenes\favicon.ico" type="image/x-icon">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta charset="UTF-8">
    <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
    <title>Desechos</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css" />
    <link href="../css/responsivecss.css" rel="stylesheet" type="text/css" />
    <link href="../fonts/icon/style.css" rel="stylesheet" type="text/css" />
    <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script src="../js/jspdf.debug.js" type="text/javascript"></script>
    <script src="../js/jquery.table2excel.js" type="text/javascript"></script>
    <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../js/jsMenu.js" type="text/javascript"></script>
    <script src="../js/jsLogin.js" type="text/javascript"></script>
    <script src="../js/jsDesecho.js" type="text/javascript"></script>
    <!-- <script src="../js/Notificaciones.js" type="text/javascript"></script>-->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>





<body class="body">
    <header id="header">
        <?php
        include 'Menu.php';
        include '../BLL/Desecho.php';
        Crearmenu();
        ?>
    </header>

    <main id="contenedor">
        <div class="panel panel-info">

            <div class="panel-heading">
                <h3>Desecho</h3>
                <button style="float: right;margin-right: 20px;position: relative;top:-40px" type="button" class="btn btn-default" id="btnAtrasNoResponsive" onclick="Atras()">
                    <img src="../resources/imagenes/regresar.png" alt="" width="20px;" />
                </button>

            </div>
            <div class="panel-body">

                <div class="panel panel-info pnlGeneral" style=" width: auto" id="panelContienetblDesecho">

                    <div id="headertextopnlMateriales" class="panel-heading">
                        <h4 id="textoHeaderPanelListadoDesechos">Listado Desechos</h4>

                        <input type="hidden" name="txtTotalMateriales" value="">
                        <button id="btnImprimirHerramientas" style="right:-90%;position: relative;top: -35px" class="btn btn-default">
                            <img src="../resources/imagenes/Excel.png" alt="" width="20">
                        </button>

                    </div>
                    <div class="panel-body pnlGeneral">
                        <div id="buscarCodigo" class="form-group ">
                            <div class="buscarHerramienta2">
                                <div class="input-group">
                                    <!--  <input id="txtCodigoMaterial" name="txtCodigoMaterial" class="form-control" placeholder="Código Material" type="text">
                                    <span class="input-group-btn">
                                        <button id="btnBuscarCodigo" class="btn btn-default" type="button" onclick="BuscarMaterialCodigo()"><img src="../resources/imagenes/icono_buscar.png" alt="" width="18px"></button>
                                    </span> -->
                                    <select onchange="FiltrarDesecho()" name="cboFitrarDesecho" id="cboFitrarDesecho" class="form-control" placeholder="Filtrar por...">
                                        <option value="listar">Todas</option>
                                        <option value="listarMaterial">Materiales</option>
                                        <option value="listarHeramienta">Herramientas</option>
                                    </select>
                                </div>


                                <button class="btn btn-default mt-2" id="btnAgregarPedido" style="" onclick="MostrarFormHerramienta()">
                                    <img src="../resources/imagenes/add_icon.png" alt="" width="20px">
                                    Desechar Herramienta
                                </button>

                                
                                <button class="btn btn-default mt-2" id="btnAgregarPedidoMateriales" style="" onclick="MostrarFormMaterial()">
                                    <img src="../resources/imagenes/add_icon.png" alt="" width="20px">
                                    Desechar Materiales
                                </button>
                              

                            </div>
                        </div>

                        <table class="table-bordered table-responsive tablasG" id="tbl_total_herramientas">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Material/Herramienta</th>
                                    <th>Codigo</th>
                                    <th>Motivo</th>
                                    <th>FechaDesecho</th>
                                    <th>Usuario</th>
                                    <th>TipoDesecho</th>
                                </tr>
                            </thead>

                            <tbody id="TablalistadoDesecho">
                                <?php
                                CrearTablaListarDesecho();
                                ?>

                            </tbody>
                        </table>

                    </div>
                </div>



                <div class="panel panel-info" id="pnlnuevoPedido" style="display: none;">

                    <div class="panel-heading" style="background-color: #eaf1ba;">
                        <h4><strong>Generar Boleta Desecho Herramienta</strong></h4>
                    </div>
                    <div class="panel-body">
                      
                        <div class="nuevoPedido">
                            <div id="PedidoMateriales">
                                <header id="headerPedidoMaterial" class="headerPedidoMaterial">
                                    <div class="col-xs-6">
                                        <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt="" /> </a></h1>

                                    </div>

                                    <div class="col-xs-6 text-right">

                                        <h2><small style="color: red">Boleta Nº<span id="consecutivoPedidoM"> <?php echo ConsecutivoPedido() ?></span></small></h2>
                                    </div>

                                    <div class="col-xs-6 text-right tableFechaPedido">
                                        <table style="width: 65%; float: right">
                                            <thead>
                                                <tr style="border:1px  solid black;">
                                                    <th style="border:1px  solid black;">Dia</th>
                                                    <th style="border:1px  solid black;">Mes</th>
                                                    <th style="border:1px  solid black;">Año</th>
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
                                    <h2 id="nomProyectoPedido"></h2>
                                    <h4>Generada por: <strong><?php echo $_SESSION['Nombre'] ?></strong></h4>
                                    <h4>Boleta: <strong>Desecho Herramienta</strong></h4>
                                    <input data-toggle='modal' data-target='#ModalBuscarMaterial' type="submit" class="btn btn-default" value="Buscar Herramienta" />
                                    <BR>
                                    <table id="tbl_agregarMaterialPedido">
                                        <tbody>
                                            <tr>
                                                <td><input type="text" name="txtCodigoMaterial" id="txtCodigoMaterial" class="form-control input-md" value="" placeholder="Codigo" /></td>
                                                <td><input type="submit" value="Agregar" onclick="AgregarMaterialPedido()" class="btn btn-success" /></td>

                                                <div class="btnRemover" style="width: 10%; display: none; float: right; height: 25px;margin-top: 1px;">

                                                    <button class="btn btn-danger" onclick="Remover()"><span style="font-size: 20px;color: red;"><img src="../resources/imagenes/Eliminar.png" width="25px" alt="" /> </span></button>
                                                </div>
                                            </tr>

                                        </tbody>
                                    </table>

                                </header>

                                <div class="bodyPedido" id="contenidoDelPedido">
                                    <div class="tableCuerpoPedido" id="tablaPedidoMateriales">
                                        <table class="tablaPedidos" id="tbl_P_Materiales">
                                            <thead>
                                                <tr>
                                                    <th>Codigo</th>
                                                    <th>Cantidad</th>
                                                    <th>Decripcion</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="ContenidoPedido">


                                            </tbody>
                                        </table>

                                    </div>
                                    <div class="modal-footer">

                                        <button type="button" class="btn btn-success btn-estilos" onclick="GuardarBoletaPedido(0)">Guardar</button>

                                    </div>
                                </div>
                            </div>

                            <div id="PedidoHerramientas">
                                <header id="headerPedidoMaterial" class="headerPedidoMaterial">
                                    <div class="col-xs-6">
                                        <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt="" /> </a></h1>

                                    </div>
                                    <div class="col-xs-6 text-right">

                                        <h2><small style="color: red">Boleta Nº<span id="consecutivoPedidoH"> <?php echo ConsecutivoPedido() ?></span></small></h2>
                                    </div>

                                    <div class="col-xs-6 text-right tableFechaPedido">
                                        <table class="fecha">
                                            <thead>
                                                <tr>
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

                                    <h2 id='proyectoHerramientas'></h2>
                                    <h4>Boleta: <strong>Herramientas</strong></h4>
                                    <input data-toggle='modal' data-target='#ModalBuscarHerramienta' type="submit" class="btn btn-default" value="Buscar Herramientas" />
                                    <BR>
                                    <table style="width: 60%;">
                                        <tbody>
                                            <tr>
                                                <td><input type="text" id='txtCodigoHerramienta' name="txtCodigoHerramienta" class="form-control input-md" value="" placeholder="Codigo" /></td>
                                                <td><input type="submit" value="Agregar" class="btn btn-success" onclick="AgregarHerramientaPedido()" /></td>
                                            </tr>

                                        </tbody>
                                    </table>

                                </header>
                                <div class="bodyPedido">
                                    <div class="tableCuerpoPedido">
                                        <table id="tbl_P_Herramientas" class="tablaPedidos">
                                            <thead>
                                                <tr>

                                                    <th>Codigo</th>
                                                    <th>Tipo</th>
                                                    <th>Marca</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="cuerpoPedidoHerramientas">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-estilos" onclick="">
                                            <img src="../resources/imagenes/print.png" alt="" width="30px" />
                                        </button>
                                        <button type="button" class="btn btn-success btn-estilos" onclick="GuardarBoletaPedido()">Guardar</button>

                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>






                
                <div class="panel panel-info" id="pnlnuevoPedidoMaterial" style="display: none;">

                    <div class="panel-heading" style="background-color: #d9f7e3;">
                        <h4><strong>Generar Boleta Desecho Matariales</strong></h4>
                    </div>
                    <div class="panel-body">
                      
                        <div class="nuevoPedidoMaterial">
                            <div id="PedidoMateriales">
                                <header id="headerPedidoMaterial" class="headerPedidoMaterial">
                                    <div class="col-xs-6">
                                        <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt="" /> </a></h1>

                                    </div>

                                    <div class="col-xs-6 text-right">

                                        <h2><small style="color: red">Boleta Nº<span id="consecutivoPedidoM"> <?php echo ConsecutivoPedido() ?></span></small></h2>
                                    </div>

                                    <div class="col-xs-6 text-right tableFechaPedido">
                                        <table style="width: 65%; float: right">
                                            <thead>
                                                <tr style="border:1px  solid black;">
                                                    <th style="border:1px  solid black;">Dia</th>
                                                    <th style="border:1px  solid black;">Mes</th>
                                                    <th style="border:1px  solid black;">Año</th>
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
                                    <h4>Generada por: <strong><?php echo $_SESSION['Nombre'] ?></strong></h4>
                                    <h4>Boleta: <strong>Desecho Herramienta</strong></h4>
                                    <input data-toggle='modal' data-target='#ModalBuscarMaterial' type="submit" class="btn btn-default" value="Buscar Material" />
                                    <BR>
                                    <table id="tbl_agregarMaterialPedido">
                                        <tbody>
                                            <tr>
                                                <td><input type="text" name="txtCodigoMaterial" id="txtCodigoMaterial" class="form-control input-md" value="" placeholder="Codigo" /></td>
                                                <td><input type="text" name="txtCantidadMaterial" id="txtCantidadMaterial" class="form-control input-md" value="" placeholder="Cantidad" /></td>

                                                <td><input type="submit" value="Agregar" onclick="AgregarMaterialPedido()" class="btn btn-success" /></td>

                                                <div class="btnRemover" style="width: 10%; display: none; float: right; height: 25px;margin-top: 1px;">

                                                    <button class="btn btn-danger" onclick="Remover()"><span style="font-size: 20px;color: red;"><img src="../resources/imagenes/Eliminar.png" width="25px" alt="" /> </span></button>
                                                </div>
                                            </tr>

                                        </tbody>
                                    </table>

                                </header>

                                <div class="bodyPedido" id="contenidoDelPedido">
                                    <div class="tableCuerpoPedido" id="tablaPedidoMateriales">
                                        <table class="tablaPedidos" id="tbl_P_Materiales">
                                            <thead>
                                                <tr>
                                                    <th>Codigo</th>
                                                    <th>Cantidad</th>
                                                    <th>Decripcion</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="ContenidoPedido">


                                            </tbody>
                                        </table>

                                    </div>
                                    <div class="modal-footer">

                                        <button type="button" class="btn btn-success btn-estilos" onclick="GuardarBoletaPedido(0)">Guardar</button>

                                    </div>
                                </div>
                            </div>




                            <div id="PedidoHerramientas">
                                <header id="headerPedidoMaterial" class="headerPedidoMaterial">
                                    <div class="col-xs-6">
                                        <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt="" /> </a></h1>

                                    </div>
                                    <div class="col-xs-6 text-right">

                                        <h2><small style="color: red">Boleta Nº<span id="consecutivoPedidoH"> <?php echo ConsecutivoPedido() ?></span></small></h2>
                                    </div>

                                    <div class="col-xs-6 text-right tableFechaPedido">
                                        <table class="fecha">
                                            <thead>
                                                <tr>
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

                                    <h2 id='proyectoHerramientas'></h2>
                                    <h4>Boleta: <strong>Herramientas</strong></h4>
                                    <input data-toggle='modal' data-target='#ModalBuscarHerramienta' type="submit" class="btn btn-default" value="Buscar Herramientas" />
                                    <BR>
                                    <table style="width: 60%;">
                                        <tbody>
                                            <tr>
                                                <td><input type="text" id='txtCodigoHerramienta' name="txtCodigoHerramienta" class="form-control input-md" value="" placeholder="Codigo" /></td>
                                                <td><input type="submit" value="Agregar" class="btn btn-success" onclick="AgregarHerramientaPedido()" /></td>
                                            </tr>

                                        </tbody>
                                    </table>

                                </header>
                                <div class="bodyPedido">
                                    <div class="tableCuerpoPedido">
                                        <table id="tbl_P_Herramientas" class="tablaPedidos">
                                            <thead>
                                                <tr>

                                                    <th>Codigo</th>
                                                    <th>Tipo</th>
                                                    <th>Marca</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody id="cuerpoPedidoHerramientas">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-estilos" onclick="">
                                            <img src="../resources/imagenes/print.png" alt="" width="30px" />
                                        </button>
                                        <button type="button" class="btn btn-success btn-estilos" onclick="GuardarBoletaPedido()">Guardar</button>

                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>



            </div>
    </main>

</body>

</html>