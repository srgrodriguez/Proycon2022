<?php
session_start();require_once '../BLL/Autorizacion.php';ValidarIniciodeSession();
?>
<!DOCTYPE html>
<html>
    
    <head>
		<link rel="icon" href="resources\imagenes\favicon.ico"  type="image/x-icon">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>Pedidos</title>
        <link rel="stylesheet" type="text/css" href="../css/estilos.css"/>
        <link href="../css/responsivecss.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="../fonts/icon/style.css" rel="stylesheet" type="text/css"/>        
        <script type="text/javascript" src="../js/jquery.js"></script>
        <script src="../js/jsPedidos.js" type="text/javascript"></script>
        <script src="../js/jsMenu.js" type="text/javascript"></script>
        <script src="../js/jspdf.debug.js" type="text/javascript"></script>
                <script src="../js/jsLogin.js" type="text/javascript"></script>
        <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body class="body">
        <header id="header">
            <?php
            require_once '..//BLL/Pedidos.php';
            require_once 'Menu.php';
            Crearmenu();
            ?>         
        </header>
        <main id="contenedor">
        <div class="panel panel-info">

            <div class="panel-heading"><h3>Pedidos</h3>
                <button id="btnAtrasNoResponsive" class="btn btn-default" style="float: right;margin-right: 20px;position: relative;top:-40px" onclick="Regresar()">
                    <img src="../resources/imagenes/regresar.png" alt="" width="20px;"/>
                </button>
            </div>
            <div class="panel-body">

                <div class="contenidoPedidos">

                    <div class="formHerramientas">


                        <div class="panel panel-info" id="pnlProyectos">
                            <div class="panel-heading"><h4>Lista de  Proyectos</h4></div>
                            <div class="panel-body">
                                <div class="mostrarProyectos" id="mostrarPedidosProveeduria">
                                    <?php
                                    //require_once '..//BLL/Pedidos.php';
                                    //EnviarCorreoElectronico();
                                    ListarProyectos();
                                    ?>
                                </div>

                            </div>


                        </div>



                    </div> 

                    <section class="pedidos" id="pedidosProveduria">


                        <header id="headPedidosProveeduria">

                            <span id="pdotituloBtnA">
                                <strong> <h2 id="ProyectoProveeduria"></h2></strong>
                                <hr>
                                <button class="btn btn-default" id="btnMostrarBoletaSolicitudPedido" style="" onclick="MostrarFormPedidos()">
                                    <img src="../resources/imagenes/add_icon.png" alt="" width="20px"/>
                                    Solicitar Pedido</button>


                                <br>

                                <div id="buscarPedido" style="">
                                    <div class="input-group">
                                        <input id="txtnumPedido" name="txtnumPedido" type="text" class="form-control" placeholder="Buscar Pedido">
                                        <span class="input-group-btn">
                                            <button id="btnBuscar" class="btn btn-default" type="button" onclick="BuscarPedido()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt=""/></button>
                                        </span>

                                    </div>
                                </div>
                                <h3>Lista de Pedidos</h3>
                            </span>
                        </header>
                        <div id="contienePedidos">


                        </div>



                    </section>
                    <div id="pp">
                    
                        <div id="generarPedidoProveeduria">
                            <header id="headerPedidoMaterial" class="headerPedidoMaterial">
                                <span id="ID_Proyecto" hidden="true"></span>
                                <div class="col-xs-6">
                                    <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt=""/> </a></h1>

                                </div>
                                <div class="col-xs-6 text-right">

                                    <h2><small style="color: red;">Boleta Nº<span id="consecutivoPedido"><?php ObternerCosecutivoPedido() ?></span></small></h2>
                                </div>

                                <div class="col-xs-6 text-right tableFechaPedido">
                                    <table class="fecha">
                                        <thead>
                                            <tr >
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

                                <h2>Proyecto: <span id="Proyecto"><strong></strong></span></h2>
                                <h4>Pedido: <strong>Herramientas,Materiales, Maquinaria</strong></h4>
                                <h4>Solicita: <strong><?php echo ' ' . $_SESSION['Nombre'] ?></strong></h4>
                                <div style="width: 100%;margin-bottom: 10px;">
                                    <input  data-toggle='modal' data-target='#ModalBuscarHerramienta' type="submit" class="btn btn-success btnFuncionesBoletaProveeduria" value="Agregar Herramienta"style="float: left" /> 
                                    <input  data-toggle='modal' data-target='#ModalBuscarMaterial' type="submit" class="btn btn-success btnFuncionesBoletaProveeduria" value="Agregar Material"style="float: left;margin-left: 10px;margin-bottom: 10px" /> 
                                    <input  data-toggle='modal' data-target='#ModalBuscarMaquinaria' type="submit" class="btn btn-success btnFuncionesBoletaProveeduria" value="Agregar Maquinaria"style="float: left;margin-left: 10px;margin-bottom: 10px" /> 

                                    <img  data-toggle='modal' data-target='#ModalAdjuntarCorreo' onclick="ModalAdjuntarCorreo()" id="imgCorreo" src="../resources/imagenes/correo.png" alt="" width="45" />

                                </div>

                                <BR>

                            </header>
                            <div class="bodyPedido">
                                <div class="tableCuerpoPedido">
                                    <table id="tbl_P_Proveeduria" class="tablaPedidos">
                                        <thead>
                                            <tr>
                                                <th class="">Tipo</th>
                                                <th class="">Codigo</th>                                     
                                                <th class="">Cantidad</th>
                                                <th class="">Material</th>
                                                <th class=""></th>

                                            </tr>
                                        </thead>
                                        <tbody>


                                        </tbody>    
                                    </table>
                                    <br>
                                    <button class="btn btn-default" onclick="MostrartxtComentarios()">Agregar Comentarios</button>
                                    <section id="comentarios" class="cometarioProveeduria">
                                        <textarea class="form-control" id="comentariosPedido" style="width: 100%;" rows="4" placeholder="Agregar Comentarios">
                                  
                                        </textarea>
                                    </section>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default btn-estilos" onclick="">
                                        <img src="../resources/imagenes/print.png" alt="" width="30px"/>
                                    </button>
                                    <button type="button" class="btn btn-default btn-estilos" onclick="GuardarBoletaPedidoProveeduria()"> 
                                        Enviar
                                        <img src="../resources/imagenes/Enviar.png" width="25px" alt=""/>
                                    </button>

                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>


        </div>





        <!--
        De aqui en adenlante siguen todos los modal (Ventanas Emergentes)
        --> 
       
        <div  id="ModalBuscarHerramienta" class="modal fade" role="dialog">   
            <div class="modal-dialog">
                <div class="modal-content">
                    <div id="headerModalBuscarHerramienta"class="modal-header headerModal">
                        <button  type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="titulModalBuscarHerramienta">Buscar Herramientas</h4>

                    </div>
                    <div class="modal-body">
                        <div class="input-group">
                            <input id="txtBuscarHerramientasPedido" name="txtBuscarHerramientasPedido" type="text" class="form-control" placeholder="Buscar Por Tipo De Herrmienta">
                            <span class="input-group-btn">
                                <button id="btnBuscar" class="btn btn-default" type="button" onclick="BuscarHerramientas()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt=""/></button>
                            </span>

                        </div>
                        <div id="resultadoBusquedaHerramientasPedidos">

                        </div>

                        <div class="modal-footer">

                            <button type="submit" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>



         <!--Modal BuscarMaquinaria -->

        <div  id="ModalBuscarMaquinaria" class="modal fade" role="dialog">   
            <div class="modal-dialog">
                <div class="modal-content">
                    <div id="headerModalBuscarMaquinaria"class="modal-header headerModal">
                        <button  type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="titulModalBuscarMaquinaria">Buscar Maquinaria</h4>

                    </div>
                    <div class="modal-body">
                        <div class="input-group">
                            <input id="txtBuscarMaquinariaPedido" name="txtBuscarMaquinariaPedido" type="text" class="form-control" placeholder="Buscar Por Tipo De Maquinaria">
                            <span class="input-group-btn">
                                <button id="btnBuscar" class="btn btn-default" type="button" onclick="BuscarMaquinaria()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt=""/></button>
                            </span>

                        </div>
                        <div id="resultadoBusquedaMaquinariaPedidos">

                        </div>

                        <div class="modal-footer">

                            <button type="submit" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!--Modal BuscarMaterial-->  
        <div  id="ModalBuscarMaterial" class="modal fade" role="dialog">   
            <div class="modal-dialog">
                <div class="modal-content">
                    <div id="headerModalBuscarMP"class="modal-header headerModal">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title" id="tituloModalBuscarMaterialP">Buscar Materiales</h4>

                    </div>
                    <div class="modal-body">
                        <div class="input-group">
                            <input id="txtBuscarMaterialP" name="txtBuscarMaterialP" type="text" class="form-control" placeholder="Nombre Material">
                            <span class="input-group-btn">
                                <button id="btnBuscar" class="btn btn-default" type="button" onclick="BuscarMaterialNombreP()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt=""/></button>
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


        <!--MOdal pedido Sucessfull -->
        <div id="Mensajesucessfull" class="modal fade " role="dialog">   
            <div class="modal-dialog">
                <div class="modal-content alert alert-success">

                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <strong id="MensajeSucessfull"></strong>

                </div>
            </div>
        </div>


        <!-- -->
        
        
        <!-- Modal ver pedido-->
        
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
                                            <h4>Proyecto <span  id="nomProyectoPedidoSelecionado"></span></h4>
                                            <h4>Boleta: <strong id="TipoPedido">Materiales</strong></h4>
                                            <h4>Generada Por: <strong id="generadaPor">Steven</strong></h4>
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
                                <button  type="button" class="btn btn-default btn-estilos" onclick="Exportar_Pdf()">
                                    <img  src="../resources/imagenes/print.png" alt="" width="30px"/>
                                </button>
                                <button id="btnAnularBoletaMaterial" type="button" class="btn btn-danger btn-estilos" onclick="AnularBoleta()">Anular Boleta</button>
                                <button id="btnAnularBoletaHerramientas" style="display: none" type="button" class="btn btn-danger btn-estilos" onclick="AnularBoletaHerramientas()">Anular Boleta</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!-- fin modal ver pedido-->
        </main>
    </body>


</html>


