<?php
session_start();require_once '../BLL/Autorizacion.php';ValidarIniciodeSession();
?>
<!DOCTYPE html>
<html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
        <link rel="icon" href="resources\imagenes\favicon.ico"  type="image/x-icon">
        
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>Usuarios</title>
        <link rel="stylesheet" type="text/css" href="../css/estilos.css"/>
        <link href="../css/responsivecss.css" rel="stylesheet" type="text/css"/>
        <link href="../fonts/icon/style.css" rel="stylesheet" type="text/css"/>        
        <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="../js/jquery.js"></script>
        <script src="../js/jsMenu.js" type="text/javascript"></script>
                <script src="../js/jsLogin.js" type="text/javascript"></script>
        <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>

        <script src="../js/jsUsuarios.js" type="text/javascript"></script>
    </head>
    <body class="body">

        <header id="header">
            <?php
            require_once 'Menu.php';

            Crearmenu();
            ?>
        </header>

        <main id="contenedor">


            <div class="panel panel-info" id="pnlcontienetblusuarios">

                <div class="panel-heading"><h2>Usuarios</h2></div>
                <div class="panel-body">
               
                </div>
                <div class="formUsuarios">
                    <div style="width: 100%; margin: auto; height: 50px;">
                        
                        
                        <h3> <a style="float:right" href="javascript:void(0);" onclick="abrirModal()"> + Agregar Usuario</a></h3>


                    </div>
                    <table class="table table-bordered table-responsive tablasG" id="tblListausuarios">
                        <thead>
                            <tr>
                                <th>Id </th>
                                <th>Usuario</th> 
                                <th>Nombre</th> 
                                <th>Rol</th> 
                                <th>Estado</th>
                                <th></th>

                            </tr>
                        </thead>

                        <tbody id="tablaUsuarios">

                            <?php
                            include '..//BLL/Usuario.php';
                            crearTabla();
                            ?>

                        </tbody>
                    </table>

                </div>

            </div>

           
            


            <!-- Seccion de Modals-->
            <div id="ModalAgregarUsuario" class="modal fade" role="dialog">   
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="modalheaderUsuario"class="modal-header headerModal">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 id="usuarioCorrecto"class="modal-title"></h4>
                        </div>
                        <div class="modal-body">
                            <form method="POST" name="frmInsertar" class="form-horizontal" action="mantenimientoUsuario.php">
                                <div class="form-group">    

                                    <div class="col-lg-8">
                                        <input type="hidden" name="txtId" id="txtId" class="form-control " placeholder="" />
                                    </div>
                                </div>

                                <div class="form-group">    
                                    <label class="col-lg-2">Nombre</label> 
                                    <div class="col-lg-8">
                                        <input type="text" name="txtNombre" id="txtNombreU" class="form-control " placeholder="Nombre"/>
                                    </div>
                                </div>
                                <div class="form-group">    
                                    <label class="col-lg-2">Usuario</label> 
                                    <div class="col-lg-8">
                                        <input type="text" name="txtUsuario" id="txtUsuario" class="form-control " placeholder="usuario@proycon.com"/>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-lg-2">Contraseña</label>
                                    <div class="col-lg-8">
                                        <input type="password" name="txtContra" id="txtContra"  class="form-control" placeholder="Contraseña" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2">Rol</label>
                                    <div class="col-lg-5">
                                        <select id="cboRol" class="form-control">
                                            <?php
                                            cargarComboBox();
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2">Estado</label>
                                    <div class="col-lg-10">
                                        <label class="radio-inline"><input type="radio" id="activo" name="rbo" value="1">Activo</label>
                                        <label class="radio-inline"><input type="radio" id="noactivo" name="rbo" value="0">Inactivo</label>
                                    </div>
                                </div>



                            </form>


                            <div class="modal-footer">
                                <button id="btnInsert"type="submit" class="btn btn-success btn-estilos" onclick="AgregarUsuario()">Guardar</button>
                                <button id="btnUpdate" type="submit" class="btn btn-success btn-estilos" style='display:none;' onclick="ActualizarUsuario()">Actualizar</button>
                                <button type="submit" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </body>
</html>
