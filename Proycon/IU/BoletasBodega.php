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
    <title>Boletas</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos.css" />
    <link href="../css/responsivecss.css" rel="stylesheet" type="text/css" />
    <link href="../fonts/icon/style.css" rel="stylesheet" type="text/css" />
    <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script src="../js/FuncionesGenerales.js" type="text/javascript"></script>
    <script src="../js/jsTraslado.js" type="text/javascript"></script>
    <script src="../js/jquery.table2excel.js" type="text/javascript"></script>
    <script src="../js/jsMenu.js" type="text/javascript"></script>
    <script src="../js/jsLogin.js" type="text/javascript"></script>
    <script src="../js/jsBoletasBodega.js" type="text/javascript"></script>
    <script src="../js/jspdf.debug.js" type="text/javascript"></script>
    <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
   
</head>

<body>
    <header id="header">
        <?php
        include 'Menu.php';
        require_once '../BLL/BoletasBodega_BL.php';
        Crearmenu();
        ?>
    </header>

    <main id="contenedor">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3>Maquinaria</h3>
                <button style="float: right;margin-right: 20px;position: relative;top:-40px" type="button" class="btn btn-default" id="btnAtrasNoResponsive" onclick="window.location.href='Maquinaria.php'">
                    <img src="../resources/imagenes/regresar.png" alt="" width="20px;" />
                </button>

            </div>
            <div class="panel-body">
                <div class="col-lg-12">

                    <div id="idBoletas">
                        <div class="panel panel-info" style=" width: auto">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h4>Boletas Bodegas</h4>
                                    </div>
                                </div>
                            </div>


                            <div id="pdotituloBtnA" style="padding:1%">

                                <br>
                                <select class="cboPedidos" id="cboPedidos" onchange="ActulizarSeccionPedidos()">
                                   
                                    <option value="2">Herramientas</option>
                                    <option value="3">Maquinaria</option>
                                </select>

                                <div id="buscarPedido">
                                    <div class="input-group">
                                        <input id="txtBoletaPedido" name="txtBoletaPedido" type="text" class="form-control" placeholder="Buscar Pedido">
                                        <span class="input-group-btn">
                                            <button id="btnBuscar" class="btn btn-default" type="button" onclick="BuscarBoletaPedido()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt="" /></button>
                                        </span>

                                    </div>
                                </div>
                            </div>








                            <div class="panel-body">
                                <div id="contienePedidos">
                                    <?php                                    

                                        ListarPedidos();
                                    ?>
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



<div id="ModalVerPedido" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <div class="modal-content">
                            <div id="mostrarMesajeHeaderModalPedido" class="modal-header headerModal">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 id="MensajeModalVerPedido" class="modal-title"></h4>
                            </div>
                            <div class="modal-body">
                                <section class="" id="">
                                    <div id="MuestraContenidoPeido">
                                        <header style="overflow: hidden" id="" class="headerPedidoMaterial">
                                            <div class="col-xs-6" id="imgPedido">
                                                <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt="" /> </a></h1>

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
                                                <h4><strong id="nomProyectoPedidoSelecionado"></strong></h4>
                                                <h4>Boleta: <strong id="TipoPedido">Materiales</strong></h4>
                                                <h4>Generada Por: <strong id="generadaPor">Steven</strong></h4>
                                                <BR>
                                            </div>
                                        </header>

                                        <div class="bodyPedido">
                                            <div class="tableCuerpoPedido" id="ContenidoPedido_Selecionado">


                                            </div>

                                        </div>

                                    </div>
                                </section>


                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default btn-estilos" onclick="Exportar_Pdf('ContenidoPedido_Selecionado')">
                                        <img src="../resources/imagenes/print.png" alt="" width="30px" />
                                    </button>                                   

                                </div>
                            </div>
                        </div>
                    </div>
                </div>