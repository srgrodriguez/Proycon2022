<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
require_once '../BLL/Herramientas.php';
require_once '../BLL/Autorizacion.php';
session_start();
ValidarIniciodeSession();
?>
<html>
    <head>
        <link rel="icon" href="resources\imagenes\favicon.ico"  type="image/x-icon">
        <title>Administracion</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../css/estilos.css"/>
        <link href="../css/responsivecss.css" rel="stylesheet" type="text/css"/>
        <link href="../fonts/icon/style.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="../js/jquery.js"></script>
         <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../js/jsAdministracion.js" type="text/javascript"></script>
        <script src="../js/jsMenu.js" type="text/javascript"></script>
        <script src="../js/jsLogin.js" type="text/javascript"></script>
        <style>
            .AntesResultado{
                display: none;
                float: left;
                width: 90%;
                margin: auto;
                height: 100px;
                    
            }
            .MenuLateral{
                width: 10%;
                left: 0px;
                height: 1000px;
                background: #003DA6;
                float: left;

            }
            .MenuLateral ul{
                text-decoration: none;
            }
            .MenuLateral ul li{
                text-decoration: none;
                padding: 15px;
            }
            .MenuLateral ul li:hover{
                background: #ccc;
            }
            .MenuLateral ul li a{
                color: white;
                font-size: 15px
            }
            .Admin{
                width: 90%;
                float: left;


            }
            #AdminHerramientas{
                display:none;
            }
            #AdminMateriales{
                display:none;
            }

            #submenu {
                position:absolute;
                text-decoration: none;
                display: none;

            }
            #flechasubmenu{

                position: relative;
                font-size: 150%;
                width: 10%;
                color: white;
                float: right;
                margin-right: 5%;
                top: -30px;
                
            }
        </style>
        <script>
            $(document).ready(main);
            function main() {
                $('.mostrarsubmenu').click(function () {
                    $(this).children('#submenu').slideToggle();
                });

            }
            ;
        </script>
    </head>
    <body>
        <header id="header">
            <?php
            require_once 'Menu.php';
            Crearmenu();
            ?>

        </header>


        <main id="contenedor">
            <div class="panel panel-info" id="pnlFinalizarProyecto">

                <div class="panel-heading"><h3>Administracion Inventario</h3></div>
                <div class="panel-body">
                    <div class="MenuLateral">
                        <ul>
                            <li><a href="Javascript:void(0)" onclick="">Herramientas</a>
                                <span  id="flechasubmenu"><a href="#"> > lsdjaflajsdlf</a> </span>
                                <ul id="submenu1">
                                    <li><a href="Javascript:void(0)" data-toggle="modal" data-target="#ModalAgregarHerramienta" >Opciones herramienta</a></li>
                                    <li><a href="Javascript:void(0)" onclick="listarTotalHerramientas()">Listar Total</a></li>
                                    <li><a href="Javascript:void(0)" onclick="MostrarListaReparaciones()">Reparaciones</a></li>
                                </ul>
                            </li>
                            <li><a href="Javascript:void(0)">Materiales</a></li>
                        </ul>

                    </div> 
                    <div class="AntesResultado"></div>
                    <div class="Admin" id="AdminHerramientas">
                        <div id="lHerramientas">
                            <table class="table-bordered table-responsive tablasG" id='tbl_total_herramientas'>
                                <thead>
                                    <tr>
                                        <th>Codigo</th> 
                                        <th>Tipo</th>
                                        <th>Fecha Registro</th>
                                        <th>Precio</th>
                                        <th>Disposicion</th>       
                                        <th>Ubicacion</th>
                                        <th>Estado</th> 
                                    </tr>
                                </thead>
                                <tbody id="listadoHerramientas">
                                </tbody>
                            </table> 

                        </div>
                        <div id="adminReparaciones">
                              <table class="table-bordered table-responsive tablasG" id='tablaReparaciones'>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th style=" width: 150px;">Código </th>
                                            <th>Tipo </th>
                                            <th>Fecha Salida </th>
                                            <th>Dias</th>
                                            <th>NumBoleta</th>
                                            <th></th>

                                        </tr>
                                    </thead>
                                    <tbody id="HerramientasEnReparacion">
                                    </tbody>
                                </table>
                            
                        </div>>


                    </div>
                    <div class="Admin" id="AdminMateriales"></div>
                </div>


            </div>

        </main>>

        <!-- seccion de Modal --> 
        <div id="ModalAgregarHerramienta" class="modal fade" role="dialog">   
            <div class="modal-dialog">

                <div class="modal-content">

                    <div id="modalheaderAgregarHerramienta"class="modal-header headerModal">

                        <button type="button" class="close" data-dismiss="modal" onclick="LimpiarColorHerramienta()">&times;</button>

                        <h4 class="modal-title" id="tituloModalAgregarHerramienta">Registrar Herramientas</h4>
                    </div>

                    <div class="modal-body">
                        <form method="POST" name="frmInsertar" id="frmInsertar" class="form-horizontal" action="">


                            <div class="form-group"> 							
                                <label class="col-lg-2">Codigo Mayor</label> 
                                <div class="col-lg-10">
                                    <div class="input-group col-md-7">
                                        <input type="txtCodigoH" class="form-control" value="<?php echo ObtenerConsecutivoHerramienta() ?>" name="txtCodigoH" id="txtCodigoH" disabled />
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">    
                                <label class="col-lg-2">Codigo</label> 
                                                                    <div class="col-md-5">
                                          <div class="input-group">
                                            <input id="txtCodigoH2"  name="Codigo" type="text" class="form-control" placeholder="Codigo">
                                            <span class="input-group-btn">
                                                <button id="btnBuscarMaterialCodigo" onclick="BuscarHerramientaCodigo()" class="btn btn-default" type="button"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt=""/></button>

                                            </span>
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
                                    <input type="text"  name="txtPrecioH" id="txtPrecioH" class="form-control " onkeypress="return soloNumeros(event)" placeholder="Precio"/>
                                </div>
                            </div>

                            <div class="form-group">    
                                <label class="col-lg-2">Fecha</label> 
                                <div class="col-md-6">
                                    <input type="date" name="txtFechaRegistroH" id="txtFechaRegistroH" class="form-control " placeholder="Fecha"/>

                                </div>
                            </div>

                            <div class="form-group">    
                                <label class="col-lg-2">Procedencia</label> 
                                <div class="col-md-6">
                                    <input type="text" name="txtProcedenciaH" id="txtProcedenciaH" class=" form-control " placeholder="Procedencia"/>
                                </div>
                            </div>

                            <div class="form-group">    
                                <label class="col-lg-2">Tipo</label> 
                                <div class="col-md-6">

                                    <select id="comboHerramientaTipoH" name="comboHerramientaTipoH" class="form-control " > 

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


                        </form>
                        <div class="modal-footer">
                            <button type="submit" disabled="true" id="btnActualizarH" class="btn btn-primary btn-estilos" onclick="ActualizarHerramienta()">Actualizar</button>
                            <button type="submit" id="btnGuardarH" class="btn btn-success btn-estilos" onclick="GuardarHerramienta()">Guardar</button>
                            <button type="submit" class="btn btn-default" data-dismiss="modal" onclick="LimpiarColorHerramienta()">Cerrar</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
