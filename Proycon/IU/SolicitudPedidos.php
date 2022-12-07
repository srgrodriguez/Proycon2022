<?php
session_start();require_once '../BLL/Autorizacion.php';ValidarIniciodeSession();
?>
<!DOCTYPE html>
<html>
    <head> 
        <meta charset="UTF-8">
		<link rel="icon" href="resources\imagenes\favicon.ico"  type="image/x-icon">
        <title>Solicitud Pedido</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta charset="UTF-8">
        <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../css/estilos.css"/>
        <link href="../css/responsivecss.css" rel="stylesheet" type="text/css"/>
        <link href="../fonts/icon/style.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>        
        <script type="text/javascript" src="../js/jquery.js"></script> 
        <script src="../js/jspdf.debug.js" type="text/javascript"></script>
        <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../js/jsProyectos.js" type="text/javascript"></script>
        <script src="../js/jsMenu.js" type="text/javascript"></script>
        <script src="../js/jsLogin.js" type="text/javascript"></script>
        <script src="../js/jsSolicitudPedidos.js" type="text/javascript"></script>
        
    </head>
    <body>
        <header id="header">
            <?php
            include 'Menu.php';
            require_once '../BLL/Proyectos.php';
            Crearmenu();
            ?>
        </header>
        <main id="contenedor">  
            
            <span id="idProecto" hidden="true"><?php echo $_GET['id']; ?></span>
            <div class="panel panel-info" >
                

                <div class="panel-heading"><h2>Solicitud de Pedidos <?php echo  NombreProyecto($_GET['id']); ?> </h2>
                     <button  id="btnAtrasNoResponsive" style="float: right;margin-right: 20px;position: relative;top:-40px" type="button" class="btn btn-default" onclick="AtrasSolicitudPedido()">
                        <img src="../resources/imagenes/regresar.png" alt="" width="20px;"/>
                    </button>
                </div>
                <div class="panel-body">
                    <div id="colaPedidos">
                        <div class="panel panel-default" id="pnlColaPedidos">

                            <div class="panel-heading"><h3>Cola de pedidos</h3></div>
                            <div class="panel-body">
                                <table class="table tablasG">
                                    <thead class="thead-inverse">
                                        <tr>
                                            <th>Boleta</th>
                                            <th>Fecha</th>
                                            <th>Solicita</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="solicitudPedidos">
                                        <?php
                                        ColaPeidos($_GET['id']);
                                        ?>
                                    </tbody>


                                </table>

                            </div>


                        </div>


                    </div>


                    <div class="panel panel-default" hidden="true" id="pnlBoletasPedidos">

                        <div class="panel-heading">Generar Boleta de salida de material</div>
                        <div class="panel-body">

                            <div id="GeneraBoletaSalida">
                                <div class="nuevoPedidoProveduria">
                                    <p style="color:red">Nueva Solicitud de Pedido</p>
                                    <header id="headerPedidoMaterial" class="headerPedidoMaterial">
                                        <div class="col-xs-6">
                                            <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt=""/> </a></h1>
                                        </div>

                                        <div class="col-xs-6 text-right">
                                            <h2><small style="color: red">Pedido Nº <span id="consecutivoPedidoProveeduria"></span></small></h2>
                                        </div>

                                        <div class="col-xs-6 text-right tableFechaPedido">
                                            <div style="" id="tblFechaPedidoProveeduria">
                                           
                                            <table id="tblFechaPedidoProveedu" style="width: 65%; float: right">
                                                <thead>
                                                    <tr style="border:1px  solid black;">
                                                        <th class="th">Dia</th>
                                                        <th class="th">Mes</th>
                                                        <th class="th">Año</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="td" id="dia"></td>
                                                        <td class="td" id="mes"></td>
                                                        <td class="td"id="anno"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>                     
                                        <h4>Proyecto: <strong id="NombreProyectoBoletaProve"><?php echo  NombreProyecto($_GET['id']); ?></strong></h4>
                                        <h4>Pedido: <strong>Materiales Y Equipo</strong></h4>
                                        <h4>Solicita: <strong id="solicita"></strong></h4>
                                        <BR>


                                    </header>

                                    <div class="bodyPedido">
                                        <div class="tableCuerpoPedido" id='tableCuerpoPedidoProveeduria'>

                                            
                                            
                                            
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default btn-estilos" onclick="ExportarPdfBoletaProveduria('tableCuerpoPedidoProveeduria')">
                                                <img src='../resources/imagenes/print.png'  width='30px'/>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
 
                                    <div class="BoletaBodega">
                                    <select class="cboPedidos" id="cboPedidos2" onchange="CambiarPeido()">
                                        <option value="1">Materiales</option>
                                        <option value="2">Herramientas</option>
                                    </select>
                                        <div id="PedidoMateriales">
                                            <header id="headerPedidoMaterial" class="headerPedidoMaterial">
                                                <div class="col-xs-6">
                                                    <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt=""/> </a></h1>

                                                </div>

                                                <div class="col-xs-6 text-right">

                                                    <h2><small style="color: red">Boleta Nº<span id="consecutivoPedidoM">  <?php echo ConsecutivoPedido() ?></span></small></h2>
                                                </div>

                                                <div class="col-xs-6 text-right tableFechaPedido">
                                                    <table class="fecha" id="tbl_fechaPedidoM">
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
                                                <h4 id="">Proyecto: <strong id="nomProyectoPedidoMateriales"><?php echo NombreProyecto($_GET['id']) ?></strong></h4>
                                                <h4>Generada por: <strong id="generaBoletaSalidaMateriales"><?php echo $_SESSION['Nombre'] ?></strong></h4>
                                                <h4>Boleta: <strong id="tipoBoletaMateriales">Materiales</strong></h4>
                                                <input  data-toggle='modal' data-target='#ModalBuscarMaterial' type="submit" class="btn btn-default" value="Buscar Material" />
                                                <BR>
                                                <table style="background: white" class="tbl_txt_btn">
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

                                                    <button type="button" class="btn btn-success btn-estilos" onclick="GuardarBoletaPedido(1)">Guardar</button>

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
                                                    <table id="tblFechaBoletaH" class="fecha">
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

                                                <h4 id=''>Proyecto: <strong id="proyectoHerramientas"><?php echo NombreProyecto($_GET['id']) ?></strong></h4>
                                                <h4>Generada por: <strong id="generaBoletaSalidaHerramientas"><?php echo $_SESSION['Nombre'] ?></strong></h4>
                                                <h4>Boleta: <strong>Herramientas</strong></h4>
                                                <input  data-toggle='modal' data-target='#ModalBuscarHerramienta' type="submit" class="btn btn-default" value="Buscar Herramientas" />
                                                <BR>
                                                <table style="width: 60%;background: white" class="tbl_txt_btn">
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="text" id='txtCodigoHerramienta' name="txtCodigoHerramienta" class="form-control input-md" value="" placeholder="Codigo" /></td>
                                                            <td><input type="submit" value="Agregar" class="btn btn-success" onclick="AgregarHerramientaPedido()" /></td>
                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </header>
                                            <div class="bodyPedido">
                                                <div class="tableCuerpoPedido" id="tbl_contenidoBoletaHerramientas">
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
                                                    <button type="button" class="btn btn-success btn-estilos" onclick="GuardarBoletaPedido(0)">Guardar</button>

                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                             
                            </div>

                        </div>

                    </div>




                </div>
            </div>
        </main>
        
        
        
        
        <!--  Seccion de modal -->
        
        <!-- Modal Buscar Material -->
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
        
        <!--  Modal buscar HEERRAMIENTAS -->
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
        
                    <!--MOdal pedido Sucessfull -->
            <div id="Mensajesucessfull" class="modal fade " role="dialog">   
                <div class="modal-dialog">
                    <div class="modal-content alert alert-success">

                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <strong id="MensajeSucessfull"></strong>

                    </div>
                </div>
            </div>


        <?php
        // put your code here
        ?>
    </body>
</html>
