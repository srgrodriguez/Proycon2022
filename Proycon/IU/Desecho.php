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
    <script src="../js/jsReportesDesecho.js" type="text/javascript"></script>
    <script src="../js/FuncionesGenerales.js" type="text/javascript"></script>
    <?php if ($_SESSION['ID_ROL'] == 4 || $_SESSION['ID_ROL'] == 5) { ?>
        <script src="../js/Notificaciones.js" type="text/javascript"></script>
        <script src="../js/push.min.js" type="text/javascript"></script>
    <?php } ?>

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
                <button style="float: right;margin-right: 20px;position: relative;top:-40px" type="button" class="btn btn-default" id="btnAtrasNoResponsive" onclick="AtrasP()">
                    <img src="../resources/imagenes/regresar.png" alt="" width="20px;" />
                </button>

            </div>
            <div class="panel-body">


                </select>

                <div class="panel panel-info pnlGeneral" style=" width: auto" id="panelContienetblDesecho">

                    <div id="headertextopnlMateriales" class="panel-heading">
                        <h4 id="textoHeaderPanelListadoDesechos">Listado Desechos</h4>

                        <input type="hidden" name="txtTotalMateriales" value="">
                        <button id="btnImprimirHerramientas" style="right:-90%;position: relative;top: -35px" class="btn btn-default" onclick="GenerarReporteDesecho()">
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
                                    <th>Boleta</th>                                
                                    <th>Motivo</th>
                                    <th>FechaDesecho</th>
                                    <th>Usuario</th>
                                    <th>TipoDesecho</th>
                                    <th></th>
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

                <!-- ************************************************************************************************************************** -->

                <div class="panel panel-info" id="pnlnuevoPedido" style="display:none;">

                    <div class="panel-heading">
                        <h4><strong>Generar Boleta Pedido</strong></h4>
                    </div>


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
                                <h4>Boleta: <strong>Materiales</strong></h4>
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
                                        <tbody id="tbl_Materiales">


                                        </tbody>
                                    </table>

                                    <div class="form-group col-10" style="padding: 1%;">
                                        <label for="exampleFormControlTextarea1">Motivo del desechos</label>
                                        <textarea class="form-control" id="motivoDesechoM" name="motivoDesechoM" rows="2"></textarea>
                                    </div>

                                </div>
                                <div class="modal-footer">

                                    <button type="button" class="btn btn-success btn-estilos" onclick="GuardarBoletaMateriales()">Guardar</button>

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

                                    <div class="form-group col-10" style="padding: 1%;">
                                        <label for="exampleFormControlTextarea1">Motivo del desecho</label>
                                        <textarea class="form-control" id="motivoDesechoG" name="motivoDesechoG" rows="2"></textarea>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default btn-estilos" onclick="">
                                        <img src="../resources/imagenes/print.png" alt="" width="30px" />
                                    </button>
                                    <button type="button" class="btn btn-success btn-estilos" onclick="GuardarBoletaHerramientas()">Guardar</button>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- ************************************************************************************************************************** -->


            </div>







        </div>
    </main>


</body>

</html>


<!--MOdal pedido Sucessfull -->
<div id="Mensajesucessfull" class="modal fade " role="dialog">
    <div class="modal-dialog">
        <div class="modal-content alert alert-success">

            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <strong id="MensajeSucessfull"></strong>

        </div>
    </div>
</div>


<div id="ModalBuscarMaterial" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="modal" class="modal-header headerModal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Buscar Materiales</h4>

            </div>
            <div class="modal-body">
                <div class="input-group">
                    <input id="txtBuscarMaterialP" name="txtBuscarMaterialP" type="text" class="form-control" placeholder="Nombre Material">
                    <span class="input-group-btn">
                        <button id="btnBuscar" class="btn btn-default" type="button" onclick="BuscarMaterialNombre()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt="" /></button>
                    </span>

                </div>
                <table class="tablasG" style="margin-top: 10px;">
                    <thead>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Stock</th>
                        <th>Cant Agregar</th>
                        <th></th>
                    </thead>
                    <tbody id="tbl_body_buscarMaterial">


                    </tbody>


                </table>

                <div class="modal-footer">

                    <button type="submit" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>

        </div>
    </div>
</div>


<div id="ModalBuscarHerramienta" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="modal" class="modal-header headerModal">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Buscar Herramientas</h4>

            </div>
            <div class="modal-body">
                <div class="input-group">
                    <input id="txtBuscarHerramienta" name="txtBuscarHerramienta" type="text" class="form-control" placeholder="Buscar Por Tipo De Herrmienta">
                    <span class="input-group-btn">
                        <button id="btnBuscar" class="btn btn-default" type="button" onclick="BuscarHerramientas()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt="" /></button>
                    </span>

                </div>
                <div id='ResultadoBusqudaHerramienta'>

                </div>
                <div class="modal-footer">

                    <button type="submit" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>

        </div>
    </div>
</div>


<!--Modal de Advertencia Codigo ERRONEO-->

<div id="ModalDefaul" style="" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 20%;margin: auto">

            <div class="modal-body  btn btn-default">

                <h4 class="modal-title" id="MensajeErrorMaterial"></h4>
                <h4 class="modal-title" id="CantMaterialExistente"></h4>
                <br>
                <div class="modal-footer">
                    <center>
                        <button type="submit" class="btn btn-danger btn-estilos" data-dismiss="modal">Aceptar</button>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="ModalVerPedido" class="modal fade" role="dialog">   

    
                <div class="modal-dialog">

                    <div class="modal-content">
                        <div id="mostrarMesajeHeaderModalPedido"class="modal-header headerModal">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 id="MensajeModalVerPedido" class="modal-title"></h4>
                        </div>
                        <div class="modal-body">
                            <section class="" id="">
                                <div id="MuestraContenidoPeido">
                                    <header style="overflow: hidden" id="" class="headerPedidoMaterial">
                                        <div class="col-xs-6" id="imgPedido">
                                            <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt=""/> </a></h1>

                                        </div>
                                        <div id="headerBoletaPedido">
                                            <div class="col-xs-6 text-right">
                                                <h2><small style="color: red">Boleta Nº <span id="consecutivoPedidoSeleccionado"></span></small></h2>
                                                <span hidden="true" id="tipoPedidoSeleccionado"></span>
                                            </div>

                                            <div class="col-xs-6 text-right tableFechaPedido">
                                                <table class="talaFecha" id="tblFecha">
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
                                            <h4><strong  id="nomProyectoPedidoSelecionado"></strong></h4>
                                            <h4>Boleta: <strong id="TipoPedido">Materiales</strong></h4>
                                            <h4>Generada Por: <strong id="generadaPor">Steven</strong></h4>

                                            <h4>Motivo: <strong id="MotivoModal"></strong></h4>

                                           

                                            <BR>
                                        </div>
                                    </header>

                                    <div class="bodyPedido" >
                                        <div class="tableCuerpoPedido" id="ContenidoPedido_Selecionado">


                                        </div>

                                    </div>

                                    

                                </div>
                            </section>


                            <div class="modal-footer">
                                <button  type="button" class="btn btn-default btn-estilos" onclick="Exportar_PdfDesecho('tbl_P_Materiales_Selecionado')">
                                    <img  src="../resources/imagenes/print.png" alt="" width="30px"/>
                                </button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


                    <!--Modal Correo Electronico-->  
        <div  id="ModalAdjuntarCorreo" class="modal fade" role="dialog">   
            <div class="modal-dialog">
                <div class="modal-content">
                    <div id="headerModalCorreo"class="modal-header headerModal">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="textoEncabezadoCorreo">Enviar pedido por Email</h4>

                    </div>
                    <div class="modal-body">

                        <div class="input-group">

                            <input id="txtBuscarCorreo" name="txtBuscarCorreo" type="text" class="form-control" placeholder="Buscar Correo...">
                            <span class="input-group-btn">
                                <button id="btnBuscar" class="btn btn-default" type="button" onclick="BuscarMaterialNombreP()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt=""/></button>
                            </span>

                        </div>
                        <div id="mostrarTablaCorreosBuscados"></div>
                        


                        <br>
                        <h3>Para</h3>
                        <div id="destinariosCorreo" style="width: 60%">
                        <table class="tablasG">
                            <tbody id="listaCorreos">
                                
                                
                            </tbody>
                        </table>
                        </div>  
                        <br>
                        <textarea id="mensajeCorreo" class="form-control" style="width: 100%;height: 89px" placeholder="Mensaje"></textarea>
                        
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!--FIN Modal Correo Electronico-->  

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