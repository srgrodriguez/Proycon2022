<?php
session_start();require_once '../BLL/Autorizacion.php';ValidarIniciodeSession();
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="resources\imagenes\favicon.ico"  type="image/x-icon">
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>Materiales</title>    
        <link rel="stylesheet" type="text/css" href="../css/estilos.css"/>
        <link href="../css/responsivecss.css" rel="stylesheet" type="text/css"/>
        <link href="../fonts/icon/style.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>        
        <script type="text/javascript" src="../js/jquery.js"></script>
        <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../js/jsMateriales.js" type="text/javascript"></script>
        <script src="../js/jsMenu.js" type="text/javascript"></script>
                <script src="../js/jsLogin.js" type="text/javascript"></script>
        <script src="../js/push.min.js" type="text/javascript"></script>
        <?php if ($_SESSION['ID_ROL'] == 4 ||$_SESSION['ID_ROL']==5) {?>
      <script src="../js/Notificaciones.js" type="text/javascript"></script>
        <?php }?>
    </head>
    <header id="header">
        <?php
        include 'Menu.php';
        Crearmenu();
        ?>
    </header>

    <main id="contenedor">
        
        

<div class="panel panel-info">
    <div class="panel-heading"><h3>Materiales</h3></div>
	<?php
	if ($_SESSION['ID_ROL'] == 4 || $_SESSION['ID_ROL']==5) {
		echo "<img src='../resources/imagenes/opciones.png' id='imgOpciones' onclick='mostrarOpciones()' width='30px'/>";
	}
	?>
    <div class="panel-body" id="pblContieneMateriales">
        <form action="../BLL/ReportesExcel.php" method="POST">  
        <div class="form-group codigoHerramienta">
               <div class="buscarHerramienta">
                   <div class="input-group">
                       <input id="txtBuscar" name="Codigo" type="text" class="form-control" placeholder="Ingrese el Material que desea Buscar">
                       <span class="input-group-btn">
                           <button id="btnBuscar" class="btn btn-default" type="button" onclick="buscarMateriales()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt=""/></button>
                       </span>

                   </div>
               </div>
        </div>
      
        <div class="formularioMenuHerramientas">
            <?php 
            if ($_SESSION['ID_ROL'] == 4 || $_SESSION['ID_ROL']==5) {
                echo "<section class='btnsHerramientas'>
                                <center>
                                  <ul class='listaMenu'>
                                  <li><h4><a onclick='MostrarFormAgregarHerramientas()' href='Javascript:void(0)'>+ Agregar Material</a></h4></li>
                                  <li><h4><a onclick='listarTotalMateriales()' href='Javascript:void(0)'>Listar Total Materiales </a></h4></li>
                                  <li style='display:none'><h4><a onclick='MostrarSeccionMaterialesProy()' href='Javascript:void(0)'>Materiales por Proyecto </a></h4></li>
                              </ul>
                              </center>
                        </section> ";
            }
            ?>
 
        </div>
      
     
      
    <div class="MostrarBusquedaHerramienta" id="MostrarBusquedaMateriales" style="display: block;">
        <div class="panel panel-info pnlGeneral" style=" width: auto" id="panelContienetblMateriales">

            <div id="headertextopnlMateriales" class="panel-heading"><h4 id="textoHeaderPanelListadoMaterilaes">Listado Total De Materiales</h4>
               
                <input type="hidden" name="txtTotalMateriales" value="" />
                <button id="btnImprimirHerramientas" style="right:-90%;position: relative;top: -35px" class="btn btn-default" >
                    <img src="../resources/imagenes/Excel.png" alt="" width="20">
                </button>
                
            </div>
            <div class="panel-body pnlGeneral">
                <div id="buscarCodigo" class="form-group ">
                    <div class="buscarHerramienta2">
                          <div class="input-group">
                              <input id="txtCodigoMaterial" name="txtCodigoMaterial" class="form-control" placeholder="Código Material" type="text">
                              <span class="input-group-btn">
                                  <button id="btnBuscarCodigo" class="btn btn-default" type="button" onclick="BuscarMaterialCodigo()"><img src="../resources/imagenes/icono_buscar.png" alt="" width="18px"></button>
                              </span>

                          </div>
                      </div>
                </div>

                      <table class="table-bordered table-responsive tablasG" id="tbl_total_herramientas">
                          <thead>
                              <tr>
                                  <th>Código</th> 
                                  <th>Nombre</th>
                                  <th>Cantidad</th>     
                              </tr>
                          </thead>

                          <tbody id="listadoMateriales">
                              
                              
                          </tbody>
                      </table>
            </div>

        </div>
    </div>
 </form>
    <!------------- Modal Agregar--------------------- -->
        <div id="ModalAgregarMaterial" class="modal fade" role="dialog">   
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="modalheaderAgregarMaterial"class="modal-header headerModal ">
                          <button type="button" class="close" data-dismiss="modal">×</button>
                          <h4 id="textoHeaderAgregarMaterial"> Agregar Materiales </h4>  
                            
                        </div>
                        <div class="modal-body">
                            
                            <form class="form-horizontal">

                                <!-- Form Name -->

                                <!-- Text input-->
                               <div class="rboUsuarios">
                                   <input onclick="IngresoDeMateriales()" type="radio" id="rboTipo1" name="Estado" value="1" /> Actualizar Material Existente<br>
                                   <input onclick="IngresoDeMateriales()" type="radio" id="rboTipo0" name="Estado" value="0" /> Agregar Nuevo Material<br>

                                </div>
                                
                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="Nombre">Id</label>  
                                    <div class="col-md-5">
                                        <input disabled="disabled" id="txtIdHerramienta" name="txtIdHerramienta" type="text" placeholder="ID Herramienta" class="form-control input-md" required="">

                                    </div>
                                </div>

                               <div class="form-group">
                                    <label class="col-md-4 control-label" for="Nombre">Código</label>  
                                    <div class="col-md-5">
                                          <div class="input-group">
                                            <input id="txtCodigo" disabled="disabled" name="Codigo" type="text" class="form-control" placeholder="Código">
                                            <span class="input-group-btn">
                                                <button id="btnBuscarMaterialCodigo" onclick="VerificarHerramientaExistente()" disabled="disabled" class="btn btn-default" type="button"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt=""/></button>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                                  <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="Nombre">Nombre</label>  
                                    <div class="col-md-5">
                                        <input disabled="disabled" id="txtNombreMaterial" name="txtNombreMaterial" type="text" placeholder="Nombre" class="form-control input-md" required="">

                                    </div>
                                </div>

                                <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="Empresa">Cantidad en stock</label>  
                                    <div class="col-md-5">
                                        <?php  if ($_SESSION['ID_ROL']==4) { ?>
                                        <input disabled="disabled" id="txtCantidadStock" name="txtCantidadStock" type="text" placeholder="Cantidad en stock" class="form-control input-md" required="">
                                        <?php } else{ ?> 
                                         <input  id="txtCantidadStock2" name="txtCantidadStock" type="text" placeholder="Cantidad en stock" class="form-control input-md" required="">
                                        <?php } ?> 
                                    </div>
                                </div>

                                                    <!-- Text input-->
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="Empresa">Cantidad que Ingresa</label>  
                                    <div class="col-md-5">
                                        <input disabled="disabled" id="txtCantidadIngresa" name="txtCantidadIngresa" type="text" placeholder="Cantidad que Ingresa" class="form-control input-md" required="">

                                    </div>
                                </div>

                                <!-- Text input
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="fecha">Disponibilidad</label>  
                                    <div class="col-md-5">
                                        <input disabled="disabled" id="txtDisponibilidad" name="txtDisponibilidad" type="text" placeholder="Disponibilidad" class="form-control input-md">

                                    </div>
                                </div>-->
                                
                                <label class="col-lg-2">Devolución</label>
                                
                                <div class="col-lg-10">
                                        <label for="subscribeNews"> 
                                            <input id="chkDevolusion" type="checkbox"> 
                                                Requiere devolución</label>
                                    </div>

                                </div>
                        <br>
                                <!-- Button -->                                   
                                      <div class="modal-footer">
                                          <button type="button" id="btnAdd" onclick="ajaxAgregarMateriales()" class="btn btn-success btn-estilos">Guardar</button>
                                          <button type="button" id="btnUpd" onclick="UpdateMateriales()" class="btn btn-default">Actualizar</button>
                                        </div>

                            </form> 
                        </div>
                    </div>
                </div>
            </div>
     <!------------------- Termina Modal Agregar--------------------- -->  
      
      

     
     <!-- 
        <div id="BusquedaHerramientas" class="MostrarBusquedaHerramienta">
            <button id="btnImprimir" class="btnImprimir" >Exportar <img src="../resources/imagenes/Excel.png" alt="" width="20"/> </button>
            <table class="tablasG">
                <thead>
                    <tr>
                        <th>
                            Codigo<br>
                            <div style="width: 100%;">
                                <input style="width: 80px" type="text" name="" value="" />
                                <button style="display: inline-block">
                                    <img src="../resources/imagenes/buscar.png" width="20px" alt=""/>
                                </button>
 
                            </div>
                            </th> 
                        <th>Nombre</th> 
                        <th>Cantidad Disponible</th>        

                    </tr>


                </thead>

                <tbody id="tablaListadoMateriales">
                    
                    
                    
                </tbody>
            </table>
        </div>
     
     -->
     
     
     
        <section id="materalesPorProyecto" class="MostrarBusquedaHerramienta">
            <div style="width:60%">
                <h3>Ingreso de materiales por Proyecto</h3>
                <select id="cboMaterialesProyecto" onchange="BuscarMateriallesProyecto()" name="cboProyctos" class="form-control">
                    <option value="1">Seleccione el Proyecto</option>
                    <option>Distrito 4</option>
                    <option>Ribera Laureles</option>
                    <option>Plataforma de Parqueos</option>
                </select>
            </div>
            
            <div id="infoMaterialProyecto" style="display: none">
                <h2>Proyecto: Distrito 4</h2>
                <button id="btnImprimir" class="btnImprimir" >Exportar <img src="../resources/imagenes/Excel.png" alt="" width="20"/> </button>
                <table class="tablasG">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Material</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>CB1</td>
                            <td>Cemento Blanto</td>
                            <td>10</td>
                        </tr>
                        <tr>
                            <td>V26</td>
                            <td>Varilla #2 en 6m</td>
                            <td>200</td>
                        </tr>
                        <tr>
                            <td>GA1</td>
                            <td>Gasas 1/4</td>
                            <td>300</td>
                        </tr>
                    </tbody>
                </table>
                
            </div>
            
            
        </section> 
   
  </div>
 
</div>

    </main>
    <div id="infoResponse"></div>
</body>
</html>
