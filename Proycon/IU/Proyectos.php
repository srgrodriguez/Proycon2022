<?php
session_start();require_once '../BLL/Autorizacion.php';ValidarIniciodeSession();
?>
<!DOCTYPE html>
<html>
   
    <head>
	<link rel="icon" href="resources\imagenes\favicon.ico"  type="image/x-icon">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta charset="UTF-8">
        <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
        <title>Proyectos</title>
        <link rel="stylesheet" type="text/css" href="../css/estilos.css"/>
        <link href="../css/responsivecss.css" rel="stylesheet" type="text/css"/>
        <link href="../fonts/icon/style.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>        
        <script type="text/javascript" src="../js/jquery.js"></script> 
        <script src="../js/jspdf.debug.js" type="text/javascript"></script>
        <script src="../js/jquery.table2excel.js" type="text/javascript"></script>
        <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../js/jsMenu.js" type="text/javascript"></script>
        <script src="../js/jsLogin.js" type="text/javascript"></script>
        <script src="../js/jsProyectos.js" type="text/javascript"></script>
       <!-- <script src="../js/Notificaciones.js" type="text/javascript"></script>-->
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    
    </head>
    
 
    

    
    <body class="body"> 
        <header id="header">
            <?php
            include 'Menu.php';
            include '../BLL/Proyectos.php';
            Crearmenu();
            ?>
        </header>

        <main id="contenedor">


            <div class="panel panel-info">

                <div class="panel-heading"><h3>Proyectos</h3>
                    <button style="float: right;margin-right: 20px;position: relative;top:-40px" type="button" class="btn btn-default" id="btnAtrasNoResponsive" onclick="Atras()">
                        <img src="../resources/imagenes/regresar.png" alt="" width="20px;"/>
                    </button>
                    
                </div>
                <div class="panel-body">


                    <div class="formularioMenuHerramientas">
                        <div class="formHerramientas">


                            <div class="panel panel-info" id="pnlListarProyectos">

                                <div class="panel-heading"><h4><strong>Lista de Proyectos</strong></h4></div>
                                <div class="panel-body">
                                    <?php if ($_SESSION['ID_ROL']== 4 ||$_SESSION['ID_ROL']== 5)  { ?><!--Esta opcion solo es visible para el usuario de bodega -->
                                    <h3 style="float: right;margin-right: 50px;"><a data-toggle="modal" data-target="#ModalAgregarProyecto" href="Javascript:void(0)" onclick="agregarProyecto()">+ Agregar Proyecto</a></h3>
                                    <?php } ?>
                                            <div class="mostrarProyectos" id="mostrarProyectos">

                                        <?php
                                        ListarProyectos();
                                        ?> 
                                    </div>
                                </div>


                            </div>
                         





                        </div> 
                        <section class="btnsHerramientas" style="display: none">
                            <button  name="btnAgregarProyecto" class="btnProyectos" data-toggle="modal" data-target="#ModalAgregarProyecto" onclick="agregarProyecto()"><img src="../resources/imagenes/add_icon.png" alt="" width="25"/>
                                Nuevo Proyecto
                            </button>
                            <button class="btnProyectos" onclick="listarProyectos()">
                                <img src="../resources/imagenes/atras.png" alt="" width="25"/> 
                                Listar Proyectos 
                            </button>
                        </section>  
                    </div>



                    <div class="MostrarBusquedaHerramienta">

                    </div>

                    <div class="informacionProyecto">
                        <article class="nombreProyecto">

                            <h3 id='nomProyecto'> </h3> 
                            <?php if ($_SESSION['ID_ROL'] == 4 ||$_SESSION['ID_ROL']==5) {?><!-- Buestra los botones de opciones en proyctos-->
                            <div style="display: inline; width: 100%;">
                                <input type="submit" class=" btn btnEnviarMateriales btnOredenar" onclick="MostrarPedidos()" value="Ver Pedidos" id="btnEnviarMateriales" name="btnEnviarMateriales" />
                                <input style="" type="submit" class="btn btn-info btnOredenar" onclick="MostrarSolicitud()" value="Solicitud Pedido" id="btnSolicitudPedido" name="btnSolicitudPedido" />
                                <input type="button" class="btn btn-danger btnOredenar" onclick="FinalizarProyecto()" value="Finalizar Proyecto">
                            </div>
                            <?php }?>
                        </article>



                        <div style="padding: 1%;"> 
                                <input type="button" class=" btn btn-success" onclick="ActualizarMaterialesHerramientaProyectoDos()" value="Listar Material" />
                                <input type="button" class="btn btn-success" onclick="listarHerramientaTabla()" value="Listar Herramienta" />
                                <input type="button" class="btn btn-success" onclick="listarMaquinariaTabla()" value="Listar Maquinaria">
                        </div>            


                        <div id="mhProyectos">
                            <div id="cargando">
                            </div>

                            <section id="materiales" class="materiales">
                            </section>

                            <section id="herramientas" class="materiales d-none" style='display: none;'>
                            </section> 

                            <section id="maquinaria" class="materiales  d-none" style='display: none !important;'>
                            </section> 
                        </div>


                        <section class="materiales" id="pedidoProveduria">
                            <h3 style="color: red">Solicitud De Pedido</h3>
                            <div class="nuevoPedido">
                                <header id="headerPedidoMaterial" class="headerPedidoMaterial">
                                    <div class="col-xs-6">
                                        <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt=""/> </a></h1>

                                    </div>

                                    <div class="col-xs-6 text-right">

                                        <h2><small style="color: red">Pedido Nº<span id="consecutivoPedido"></span></small></h2>
                                    </div>

                                    <div class="col-xs-6 text-right tableFechaPedido">
                                        <table style="width: 65%; float: right">
                                            <thead>
                                                <tr style="border:1px  solid black;">
                                                    <th class="th">Dia</th>
                                                    <th class="th">Mes</th>
                                                    <th class="th">Año</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="td"><?php echo date("d") ?></td>
                                                    <td class="td"><?php echo date("m") ?></td>
                                                    <td class="td"><?php echo date("Y") ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>                     
                                    <h2>Proyecto: <span id="Proyecto"><strong>Distrito 4</strong></span></h2>
                                    <h4>Pedido: <strong>Materiales Y Equipo</strong></h4>
                                    <h4>Solicita: <strong>Manuel Retana</strong></h4>
                                    <BR>


                                </header>

                                <div class="bodyPedido">
                                    <div class="tableCuerpoPedido">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th class="th">Codigo</th>                                     
                                                    <th class="th">Cantidad</th>
                                                    <th class="th">Material</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="width: 10px" class="td">CB1</td>
                                                    <td class="td" style="width: 50px;">
                                                        <p>20</p>
                                                    </td>
                                                    <td class="td">
                                                        <p>Cemento Blanco</p>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td style="width: 10px" class="td">V26</td>
                                                    <td class="td" style="width: 50px;">
                                                        <p>20</p>
                                                    </td>
                                                    <td class="td">
                                                        <p>Varillas #2 en 6m</p>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td class="td"></td>
                                                    <td class="td" style="width: 50px;">
                                                        <p>2</p>
                                                    </td>
                                                    <td class="td">
                                                        <p>Taladros</p>
                                                    </td>

                                                </tr>
                                                <tr>
                                                    <td class="td"></td>
                                                    <td class="td" style="width: 50px;">
                                                        <p>1</p>
                                                    </td>
                                                    <td class="td">
                                                        <p>Rotamartillo</p>
                                                    </td>

                                                </tr>
                                            </tbody>
                                        </table>
                                        <section id="comentarios">
                                            Los taladros que sean los mejores que tengan
                                            ya que se necesitan para unas picas pide Oscar Navarro
                                        </section>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-estilos" onclick="">
                                            <img src='../resources/imagenes/print.png'  width='30px'/>
                                        </button>
                                    </div>
                                </div>


                            </div>
                        </section>

                        <section class="pedidos" id="pedidos">


                            <div class="panel panel-info" id="pnlListaPedidos">

                                <div class="panel-heading"><h4><strong id="tipolista">Lista de Pedidos</strong></h4></div>
                                <div class="panel-body">

                                    <header id="headPedidos">
                                        <span id="pdotituloBtnA">

                                            <button class="btn btn-default" id="btnAgregarPedido" style="" onclick="MostrarFormPedidos()">
                                                <img src="../resources/imagenes/add_icon.png" alt="" width="20px"/>
                                                Agregar Pedido</button>
                                            


                                            <br>
                                            <select class="cboPedidos" id="cboPedidos" onchange="ActulizarSeccionPedidos()">
                                                <option value="1">Materiales</option>
                                                <option value="2">Herramientas</option>
                                                <option value="3">Maquinaria</option>
                                            </select>

                                            <div id="buscarPedido">
                                                <div class="input-group">
                                                    <input id="txtBoletaPedido" name="txtBoletaPedido" type="text" class="form-control" placeholder="Buscar Pedido">
                                                    <span class="input-group-btn">
                                                        <button id="btnBuscar" class="btn btn-default" type="button" onclick="BuscarBoletaPedido()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt=""/></button>
                                                    </span>

                                                </div>
                                            </div>
                                        </span>
  

                                    </header>
                                    <div id='contienePedidos'>

                                    </div>


                                </div>

                            </div>


                            <div class="panel panel-info" id="pnlnuevoPedido">

                                <div class="panel-heading"><h4><strong>Generar Boleta Pedido</strong></h4></div>
                                <div class="panel-body">
                                          <select class="cboPedidos" id="cboPedidos2" onchange="CambiarPeido()">
                                                <option value="1">Materiales</option>
                                                <option value="2">Herramientas</option>
                                                <option value="3">Maquinaria</option>
                                           </select>
                                    <div class="nuevoPedido">

                                        <div id="PedidoMateriales">
                                            <header id="headerPedidoMaterial" class="headerPedidoMaterial">
                                                <div class="col-xs-6">
                                                    <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt=""/> </a></h1>

                                                </div>

                                                <div class="col-xs-6 text-right">

                                                    <h2><small style="color: red">Boleta Nº<span id="consecutivoPedidoM">  <?php echo ConsecutivoPedido() ?></span></small></h2>
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
                                                <input  data-toggle='modal' data-target='#ModalBuscarMaterial' type="submit" class="btn btn-default" value="Buscar Material" />
                                                <BR>
                                                <table id="tbl_agregarMaterialPedido">
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="text" name="txtCodigoMaterial" id="txtCodigoMaterial" class="form-control input-md" value="" placeholder="Codigo" /></td>
                                                            <td><input type="text" name="txtCantidadMaterial" id="txtCantidadMaterial" class="form-control input-md" value="" placeholder="Cantidad" /></td>
                                                            <td><input type="submit" value="Agregar" onclick="AgregarMaterialPedido()" class="btn btn-success" /></td>

                                                    <div class="btnRemover" style="width: 10%; display: none; float: right; height: 25px;margin-top: 1px;">

                                                        <button class="btn btn-danger" onclick="Remover()"><span style="font-size: 20px;color: red;"><img src="../resources/imagenes/Eliminar.png" width="25px" alt=""/> </span></button>
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
                                                    <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt=""/> </a></h1>

                                                </div>
                                                <div class="col-xs-6 text-right">

                                                    <h2><small style="color: red">Boleta Nº<span id="consecutivoPedidoH"> <?php echo ConsecutivoPedido() ?></span></small></h2>
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

                                                <h2 id='proyectoHerramientas'></h2>
                                                <h4>Boleta: <strong>Herramientas</strong></h4>
                                                <input  data-toggle='modal' data-target='#ModalBuscarHerramienta' type="submit" class="btn btn-default" value="Buscar Herramientas" />
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
                                                        <img src="../resources/imagenes/print.png" alt="" width="30px"/>
                                                    </button>
                                                    <button type="button" class="btn btn-success btn-estilos" onclick="GuardarBoletaPedido(1)">Guardar</button>

                                                </div>
                                            </div>
                                        </div>




                                        <div id="PedidoMaquinaria">
                                            <header id="headerPedidoMaterial" class="headerPedidoMaterial">
                                                <div class="col-xs-6">
                                                    <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt=""/> </a></h1>

                                                </div>
                                                <div class="col-xs-6 text-right">

                                                    <h2><small style="color: red">Boleta Nº<span id="consecutivoPedidoH"> <?php echo ConsecutivoPedido() ?></span></small></h2>
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

                                                <h2 id='proyectoHerramientas'></h2>
                                                <h4>Boleta: <strong>Maquinaria</strong></h4>
                                                <input  data-toggle='modal' data-target='#ModalBuscarMaquinaria' type="submit" class="btn btn-default" value="Buscar Maquinaria" />
                                                <BR>
                                                <table style="width: 60%;">
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="text" id='txtCodigoMaquinaria' name="txtCodigoMaquinaria" class="form-control input-md" value="" placeholder="Codigo" /></td>
                                                            <td><input type="submit" value="Agregar" class="btn btn-success" onclick="AgregarMaquinariaPedido()" /></td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </header>
                                            <div class="bodyPedido">
                                                <div class="tableCuerpoPedido">
                                                    <table id="tbl_P_Maquinaria" class="tablaPedidos">
                                                        <thead>
                                                            <tr>

                                                                <th>Codigo</th>
                                                                <th>Tipo</th>
                                                                <th>Marca</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="cuerpoPedidoMaquinaria">

                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default btn-estilos" onclick="">
                                                        <img src="../resources/imagenes/print.png" alt="" width="30px"/>
                                                    </button>
                                                    <button type="button" class="btn btn-success btn-estilos" onclick="GuardarBoletaPedido()">Guardar</button>

                                                </div>
                                            </div>
                                        </div>



















                                    </div> 


                                </div>

                            </div>



                        </section>


                        
                    </div>



            <div  id="ModalDevolucion" class="modal fade" role="dialog">   
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="modal"class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Historial de Devoluciones</h4>
                            <h5 class="modal-title">Material RT</h5>
                            <h5>Cantidad Solicitada: 25</h5>
                            <br>
                            <button class="btn btn-default" onclick="MostrarAgregarDevol()"><img src="../resources/imagenes/add_icon.png" width="25px" alt=""/> Agregar</button>
                            <br>
                            <table class="tablasG" id="tablaInsertarDevol" style="margin-top: 5px;display: none">
                                <thead>
                                <th class="centrar" >N Boleta</th>
                                <th class="centrar">Cantidad</th>
                                <th class="centrar">Fecha Devolucion</th>

                                <th class="centrar" ></th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding-top: 3px;padding-right: 2px;text-align: center"><input id="txtBoletaDevol" class="form-control" type="text"></td>
                                        <td style="padding-top: 3px;padding-right: 2px;text-align: center"><input id="txtCantidadDevol" class="form-control" type="text"></td>
                                        <td style="padding-top: 3px;padding-right: 2px;text-align: center"><input id="txtFechaDevol" class="form-control" type="date"></td>
                                        <td style="padding-top: 3px;padding-right: 2px;text-align: center">
                                            <button class="btn btn-default" id="btnAgregarDevolucion">
                                                <img src="../resources/imagenes/add_icon.png" width="25px" alt=""/>
                                            </button>
                                            <button class="btn btn-success" id="btnActualizarDevolucion" style="display: none">
                                                Guardar
                                            </button>                           
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <div class="modal-body">

                            <table class="tablasG">
                                <thead>
                                <th class="centrar">N Boleta</th>
                                <th class="centrar">Cantidad</th>
                                <th class="centrar">Fecha Devolucion</th>

                                <th></th>

                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="centrar">123</td>
                                        <td class="centrar">10</td>
                                        <td class="centrar">20/12/2015</td>

                                        <td><a href="#" onclick="MostrarActualizarDevol(this)"><img src="../resources/imagenes/Editar.png" width="25px" alt=""/></a></td>
                                    </tr>
                                    <tr>
                                        <td class="centrar">124</td>
                                        <td class="centrar">10</td>
                                        <td class="centrar">20/12/2016</td>

                                        <td><a href="#" onclick="MostrarActualizarDevol(this)" ><img src="../resources/imagenes/Editar.png" width="25px" alt=""/></a></td>
                                    </tr>
                                    <tr>
                                        <td class="centrar">125</td>
                                        <td class="centrar">1</td>
                                        <td class="centrar">20/12/2018</td>

                                        <td><a onclick="MostrarActualizarDevol(this)" href="#"><img src="../resources/imagenes/Editar.png" width="25px" alt=""/></a></td>
                                    </tr>


                                </tbody>



                            </table>
                            <br>
                            <div style="width: 45%">
                                <table class="tablasG">
                                    <tbody>
                                        <tr>
                                            <td>Total Devuelto</td>
                                            <td>21</td>
                                        </tr>                               
                                        <tr>
                                            <td>Pendiente</td>
                                            <td>4</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-default">Exportar <img src="../resources/imagenes/Excel.png" width="7%" alt=""/></button>

                                <button type="submit" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div  id="ModalBuscarMaterial" class="modal fade" role="dialog">   
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="modal"class="modal-header headerModal">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Buscar Materiales</h4>

                        </div>
                        <div class="modal-body">
                            <div class="input-group">
                                <input id="txtBuscarMaterialP" name="txtBuscarMaterialP" type="text" class="form-control" placeholder="Nombre Material">
                                <span class="input-group-btn">
                                    <button id="btnBuscar" class="btn btn-default" type="button" onclick="BuscarMaterialNombre()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt=""/></button>
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


            <div  id="ModalBuscarHerramienta" class="modal fade" role="dialog">   
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="modal"class="modal-header headerModal">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Buscar Herramientas</h4>

                        </div>
                        <div class="modal-body">
                            <div class="input-group">
                                <input id="txtBuscarHerramienta" name="txtBuscarHerramienta" type="text" class="form-control" placeholder="Buscar Por Tipo De Herrmienta">
                                <span class="input-group-btn">
                                    <button id="btnBuscar" class="btn btn-default" type="button" onclick="BuscarHerramientas()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt=""/></button>
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


            <div  id="ModalBuscarMaquinaria" class="modal fade" role="dialog">   
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="modal"class="modal-header headerModal">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Buscar Maquinaria</h4>

                        </div>
                        <div class="modal-body">
                            <div class="input-group">
                                <input id="txtBuscarMaquinaria" name="txtBuscarMaquinaria" type="text" class="form-control" placeholder="Buscar Por Tipo De Maquinaria">
                                <span class="input-group-btn">
                                    <button id="btnBuscar" class="btn btn-default" type="button" onclick="BuscarHerramientas()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt=""/></button>
                                </span>

                            </div>
                            <div id='ResultadoBusqudaMaquinaria'>

                            </div>                         
                            <div class="modal-footer">

                                <button type="submit" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>












            <!-- Modal Agregar Proyecto -->
            <div id="ModalAgregarProyecto" class="modal fade" role="dialog">   
                <div class="modal-dialog"  >
                    <div class="modal-content">
                        <div id="headerInsertarP"class="modal-header headerModal">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="MostrarMensajeSucess">Agregar Nuevo Proyecto</h4>
                        </div>
                        <div class="modal-body">

                            <form method="POST" id="frmRegistrarProyecto" name="frmInsertar" class="form-horizontal" action="">
                                <div class="form-group">    
                                    <label class="col-lg-2"> Nombre</label> 
                                    <div class="col-lg-8">
                                        <div class="input-group">
                                            <input id="txtNombreProyecto"  name="txtNombreProyecto" type="text" class="form-control" placeholder="Nombre">
                                            <span class="input-group-btn">
                                                <button id="btnBuscarMaterialCodigo"  class="btn btn-default" type="button"  onclick="BuscarProyecto()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt=""/></button>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">    
                                    <label class="col-lg-2">Fecha</label> 
                                    <div class="col-lg-5">
                                        <input type="date"  name="txtFechaCreacionProyecto" id="txtFechaCreacionProyecto" class="form-control " pla04-18 00:00:00ceholder="Fecha"/>
                                    </div>
                                </div>
                                <div class="form-group">    
                                    <label class="col-lg-2">Encargado proyecto</label> 
                                    <div class="col-lg-5">
                                        <input type="text" name="txtEncargadoProyecto" id="txtEncargadoProyecto" class="form-control " placeholder="Encargado"/>
                                    </div>
                                </div>
                            </form>


                            <div class="modal-footer">

                                <button type="submit" class="btn btn-success btn-estilos" id="btnInsertarProyecto" onclick="InsertarProyecto();">Guardar</button>
                                <button type="submit" class="btn btn-success btn-estilos" style="display: none" id="btnEditarProyecto" onclick="ModificarProyecto();">Guardar Cambios</button>

                                <button type="submit" class="btn btn-default" data-dismiss="modal">Cerrar</button>

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
                                <button  type="button" class="btn btn-default btn-estilos" onclick="Exportar_Pdf('ContenidoPedido_Selecionado')">
                                    <img  src="../resources/imagenes/print.png" alt="" width="30px"/>
                                </button>
                                <button id="btnAnularBoletaMaterial" style="display: none" type="button" class="btn btn-danger btn-estilos" onclick="AnularBoletaMaterial()">Anular Boleta</button>
                                <button id="btnAnularBoletaHerramientas" hidden="true" type="button" class="btn btn-danger btn-estilos" onclick="AnularBoletaHerramientas()">Anular Boleta</button>
                                <button id="btnAnularBoletaMaquinaria" style="display: none" type="button" class="btn btn-danger btn-estilos" onclick="AnularBoletaMaquinaria()">Anular Boleta</button>

                            
                            
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

            <!--Modal cargando mientras se procesan los excel -->
            
            <div id="ModalLoanding" style="" class="modal fade" role="dialog">   
                <div class="modal-dialog">
                    <div class="modal-content" style="width: 20%;margin: auto">

                        <div class="modal-body  btn btn-default">
                            <h3>Procesando...</h3>
                            <img src="../resources/imagenes/loanding.gif" alt="" width="60px"/>
                           
                            <br>
                            <div class="modal-footer">

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

            
            <!-- -->
        <div  id="ModalDevolucionMaterial" class="modal fade" role="dialog">   
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="headermodalDevoluciones" class="modal-header headerModal">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title" id="textoheaderDevoluciones">Historial de Devoluciones</h4>
                        </div>

                        <div class="modal-body">

                           <h5 id="txttipomaterial" class="modal-title"></h5>
                           <?php if ($_SESSION['ID_ROL']== 4 || $_SESSION['ID_ROL']==5) { ?><!-- Esta opcion va hacer visile unicamente para el usuairo de bodega -->
                            <h4><a href="javascript:void(0);" onclick="mostrartblDevoluvion()"> + Devolucion</a></h4>
                            <?php } ?>
                            <div id="tblDevoluvion">
                                <table id="tblDevoluvionMat">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span style="display:none" id="idmaterial"></span>
                                                <input type="text" id="txtCantidad" class="form-control " name="" placeholder="Cantidad" />
                                            </td>
                                            <td>
                                                <input type="date" id="txtFecha" class="form-control " name="" placeholder="Cantidad" />
                                            </td>
                                            <td>
                                                <input type="text" id="txtBoleta" class="form-control " name="" placeholder="# Boleta" />
                                            </td> 
                                            <td>
                                                <button class="btn btn-success" title="Agregar" onclick="AgregarDevolucion()"><span class='glyphicon glyphicon-plus'></span></button> 
                                            </td>
                                    
                                        </tr>
                                    </tbody>
                                    
                                </table>
                            </div>
                            <div  id="mostarContenidoDevoluciones">
                                
                            </div>


                                    

                             
                            <div class="modal-footer">
                                <form action="../BLL/Reportes/ReportesExcel.php" method="POST" style="display: inline-block">
                                    <input type="hidden" id ='txtIDProyectotblDevolucion' name="txtIDProyectotblDevolucion" />
                                    <input type="hidden" id ='txtIDMaterial' name="txtIDMaterial" />
                                    <input type="hidden" id ='txtNombreMaterialD' name="txtNombreMaterialD" />
                                    <button type="submit" class="btn btn-default"><img src="../resources/imagenes/Excel.png" alt="" width="25px"/></button>
                                
                                </form>   

                                <button type="submit" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

                </div>


                <!--  -->


            </div>




        </main>
        <span hidden="true"  id='idProecto'></span>
        <span hidden="true"  id='idUsuario'><?php echo $_SESSION['ID_Usuario'] ?></span>
    </body>
</html>
