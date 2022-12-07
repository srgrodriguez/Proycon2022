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
    <title>Trasladar herramienta electrica</title>
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
    <script src="../js/jspdf.debug.js" type="text/javascript"></script>
    <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
    <?php if ($_SESSION['ID_ROL'] == 4 || $_SESSION['ID_ROL'] == 5) { ?>
        <script src="../js/Notificaciones.js" type="text/javascript"></script>
        <script src="../js/push.min.js" type="text/javascript"></script>
    <?php } ?>
</head>

<body>
    <header id="header">
        <?php
        include 'Menu.php';
        include '../BLL/Maquinaria_BL.php';
        require_once '../BLL/TipoHerramienta_BL.php';
        require_once '../BLL/TrasladarEquipo_BL.php';
        Crearmenu();
        ?>
    </header>

    <main id="contenedor">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3>Herramienta eléctrica</h3>
                <button style="float: right;margin-right: 20px;position: relative;top:-40px" type="button" class="btn btn-default" id="btnAtrasNoResponsive" onclick="window.location.href='Herramientas.php'">
                    <img src="../resources/imagenes/regresar.png" alt="" width="20px;" />
                </button>

            </div>
            <div class="panel-body">
                <div class="col-lg-12">

                    <div id="idTrasladarMaquinaria">
                        <div class="panel panel-info" style=" width: auto">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h4>Trasladar herramienta eléctrica</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-body">
                            <div>
                                    <div class="k-grid-toolbar">
                                        <?php if ($_SESSION['ID_ROL'] == Constantes::RolBodega || $_SESSION['ID_ROL'] == Constantes::RolAdminBodega) { ?>

                                            <ul class="lista-accion">
                                                <li>
                                                    <button type="button" class="btn-accion btn btn-info btn-sm" onclick="MostrarBoletaTraslado()">Generar boleta</button>
                                                </li>
                                            </ul>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="col-lg-12 " >
                                   
                                    <div class="col-lg-6">
                                        <h4>Filtros de busqueda</h4>
                                        <div class="input-group">
                                            <input id="txtTrasladoCodigo" name="txtTrasladoCodigo" type="text" class="form-control" placeholder="Código">
                                            <span class="input-group-btn">
                                                <button id="btnBuscarCodigo" class="btn btn-default" type="button" onclick="ConsultarEquipoTrasladar('codigo','H')"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt="" /></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12" style="margin-top:2rem;margin-bottom:1rem">
                                <div class="col-lg-6">
                                  <?php CargarComboBoxTipoHerramienta('H', "cboTipoMaquinariaTraslado", "ConsultarEquipoTrasladar(\"tipo\",\"H\")") ?>
                                </div>
                                <div class="col-lg-6">
                                <Select style="font-size: 15px" class="form-control" name="cboUbicacionTraslado" id="cboUbicacionTraslado" onchange="ConsultarEquipoTrasladar('ubicacion','H')" onclick="LimpiarComboTraslado1()">
                                                        <option value="0">Filtrar por Ubicación</option>
                                                        <?php
                                                        $conexion = new Conexion();
                                                        $conn = $conexion->CrearConexion();
                                                        $sql = "Select DISTINCT a.Ubicacion,b.Nombre from tbl_herramientaelectrica a, tbl_proyectos b where a.Ubicacion = b.ID_Proyecto;";
                                                        $rec = $conn->query($sql);
                                                        $conn->close();
                                                        if ($rec != null) {
                                                            while ($row = mysqli_fetch_array($rec, MYSQLI_ASSOC)) {
                                                                echo "<option value ='" . $row['Ubicacion'], "'>";
                                                                echo $row['Nombre'];
                                                                echo "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                </div>
                                </div>
                                <table class="table-bordered table-responsive tablasG" id='tbl_translado_H'>
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Tipo</th>
                                            <th>Ubicacion</th>
                                            <th>Seleccionar</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listadoTranslado">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!---Inicio Modals-->

                <!--Modal de Translado de Herramientas -->

                <div id="ModalTranslado" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div id="modalTransladoBoleta" class="modal-header headerModal">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 id="mensajeTranslado" class="modal-title">Traslado de maquinaria</h4>
                            </div>
                            <div class="modal-body">
                                <div id="PedidoHerramientastt">
                                    <header class="headerPedidoMaterial">
                                        <div class="col-xs-6">
                                            <h1><a href=" "> <img src="../resources/imagenes/proycon-slider.png" width="100px;" alt="" /> </a></h1>

                                        </div>

                                        <div class="col-xs-6 text-right">
                                        <h2><small style="color: red">Boleta Nº<span id="ConsecutivoPedidoHerramientaF"> <?php echo ObternerCosecutivoBoleta(); ?></span>            
                                        </small></h2>
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
                                                        <td id="idDia"><?php echo date("d") ?></td>
                                                        <td id="idMes"><?php echo date("m") ?></td>
                                                        <td id="idAño"><?php echo date("Y") ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <h4>Boleta: <strong>Traslado Herramientas</strong></h4>
                                        <h4>Destino del Traslado:
                                            <span id="DestinoTrasl"> </span>
                                        </h4>
                                        <h5 style="display:none;">Fecha:
                                            <span id="FechaFinal" style="display:none;"> </span>
                                        </h5>


                                        <hr>


                                        <div style="width: 100%">

                                            <table class="table-bordered table-responsive tablasG">
                                                <thead>
                                                    <tr>
                                                        <th>Código</th>
                                                        <th>Tipo</th>
                                                        <th>Ubicacion</th>
                                                        <th>Eliminar</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tablaMostrarTraslado">
                                                </tbody>
                                            </table>

                                            <br>
                                            <h5>Ubicación Destino:
                                            </h5>

                                            <Select style="font-size: 15px" class="form-control" name="cboProyectoDestino" id="cboProyectoDestino">        
                                            <option value=" 0">Seleccione El Destino</option>
                                                <?php
                                                $conexion = new Conexion();
                                                $conn = $conexion->CrearConexion();
                                                $sql = "Select DISTINCT ID_Proyecto,Nombre from tbl_proyectos";
                                                $rec = $conn->query($sql);
                                                $conn->close();
                                                if ($rec != null) {
                                                    while ($row = mysqli_fetch_array($rec, MYSQLI_ASSOC)) {
                                                        echo "<option value ='". $row['ID_Proyecto'], "'>";
                                                        echo $row['Nombre'];
                                                        echo "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <br>
                                        </div>

                                    </header>
                                    <div id="mensajesResultadoTraslado"></div>
                                    <div class="modal-footer">
                                        <button type="submit" id="btnGuardarTraslado" class="btn btn-success btn-estilos" onclick="TrasladarEquipo()">Guardar</button>
                                        <button type="submit" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    </div>

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