<?php

require_once '../DATA/Proyectos.php';
require_once '../DATA/Materiales.php';
require_once '../DAL/Interfaces/IProyectos.php';
require_once '../DAL/Metodos/MProyectos.php';
require_once '../DAL/Interfaces/IMateriales.php';
require_once '../DAL/Metodos/MMaterial.php';
require_once '../DAL/Interfaces/IHerrramientas.php';
require_once '../DAL/Metodos/MHerramientas.php';
require_once '../DAL/Conexion.php';
require_once 'Autorizacion.php';
require_once '../DAL/FuncionesGenerales.php';

Autorizacion();
if (isset($_GET['opc'])) {

    if ($_GET['opc'] == 'registrar') {
        RegistrarProyecto();
    } elseif ($_GET['opc'] == 'buscar') {
        BuscarProyecto($_POST['Nombre']);
    } elseif ($_GET['opc'] == 'listar') {
        ListarProyectos();
    } elseif ($_GET['opc'] == 'listMyH') {
        ObternerHyMProyecto($_POST['idProyecto']);
    } elseif ($_GET['opc'] == 'buscarM') {
        BuscaAgregaMaterial($_GET['id'], $_GET['cant']);
    } elseif ($_GET['opc'] == 'registrarPedido') {
        if (!isset($_SESSION)) {
            session_start();
        }
        GuardarPedido($_POST['ID_Proyecto'], $_SESSION['ID_Usuario'], $_POST['consecutivo'], $_POST['fecha'], $_POST['arreglo'], $_POST['TipoPedido']);
    } elseif ($_GET['opc'] == 'obtNboleta') {
        ConsecutivoPedido();
    } elseif ($_GET['opc'] == "listarPedidos") {
        MostrarPedidos($_GET['tipo'], $_GET['ID_Proyecto']);
    } elseif ($_GET['opc'] == "verpedido") {
        VerPedido($_GET['NBoleta'], $_GET['TipoPedido']);
    } elseif ($_GET['opc'] == 'buscarherramientapedido') {
        BuscarHerramientaPedido($_GET['codigo']);
    } elseif ($_GET['opc'] == 'eliminarpedido') {
        EliminarBoletaPedido($_GET['NBoleta'], $_GET['Tipo']);
    } elseif ($_GET['opc'] == "Fitrar") {
        if ($_GET['tipo'] == "material") {
            FitrosMaterialesProyecto($_POST['ID_Proyecto'], $_POST['Filtro']);
        } else {
            FitrosHerramientasProyecto($_POST['ID_Proyecto'], $_POST['Filtro']);
        }
    } elseif ($_GET['opc'] == "anularBoleta") {
        AnularBoletaMaterial($_GET['NBoleta']);
    } elseif ($_GET['opc'] == "procesarpedido") {
        MostrarPedidoProeveeduria();
    } elseif ($_GET['opc'] == "eliminarnotificacion") {
        EliminarNotificacion();
    } elseif ($_GET['opc'] == "devolucionmaterial") {
        DevolucionMaterial();
    } elseif ($_GET['opc'] == "listarDevoluciones") {
        ListarDevolucionesPorMaterial();
    } elseif ($_GET['opc'] == "totalmaterialenviado") {
        ObtenerTotalMaterialPrestadoProyecto();
    } elseif ($_GET['opc'] == "agregardevolucion") {
        DevolucionMaterial();
    } elseif ($_GET['opc'] == "buscarboleta") {
        BuscarBoletaPedido();
    } elseif ($_GET['opc'] == "anularboletaherramienta") {
        AnularBoletaHerramientas();
    } elseif ($_GET['opc'] == "BuscarProyectoid") {
        BuscarProyectoID($_GET['ID']);
    } elseif ($_GET['opc'] == "modificarProyecto") {
        ModificarProyecto();
    } elseif ($_GET['opc'] == "buscarHerramientaProyecto") {
        buscarHerramientaPorProyecto($_POST['idProyecto'], $_POST['idHerramienta']);
    } elseif ($_GET['opc'] == "listarSoloHerramienta") {
        listarSoloHerramienta($_POST['idProyecto']);
    }
}

function RegistrarProyecto() {
    $proyecto = new Proyectos();
    $bdProyectos = new MProyectos();
    $proyecto->Nombre = $_POST['Nombre'];
    $proyecto->Encargado = $_POST['Encargado'];
    $proyecto->FechaCreacion = $_POST['FechaInicio'];
    echo $bdProyectos->RegistrarProyecto($proyecto);
}

function ListarProyectos() {
    $bdProyectos = new MProyectos();
    $result = $bdProyectos->ListarProyectos();
    $concatenar = '';
    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $IdProyecto = $fila['ID_Proyecto'];
        $nombre = $fila['Nombre'];
        $onclick = "onclick='HerramientasMateriales($IdProyecto)'";
        $onclick2 = "onclick='BuscarProyectoID($IdProyecto)'";

        if ($IdProyecto == 1) {
            $concatenar .= '<section style = "display:none" class="proyecto"> ' .
                    '<h3><a id="' . $IdProyecto . '" href="javascript:void(0);"' . $onclick . '>' . $nombre . ' </a> </h3>' .
                    '</section>';
        } else {
            if ($fila['ID_Proyecto'] == $fila['noti']) {
                $concatenar .= '<section class="proyecto"> ' .
                        '<h4><strong><a id="' . $IdProyecto . '" href="javascript:void(0);"' . $onclick . ' style="color:red">' . $nombre . ' </a></strong> </h4>' .
                        '<img src="../resources/imagenes/Editar.png" ' . $onclick2 . ' class="imgEditarProyecto" width="20" title="Editar Proyecto"/>' .
                        '</section>';
            } else {
                $concatenar .= '<section class="proyecto"> ' .
                        '<h4><strong><a id="' . $IdProyecto . '" href="javascript:void(0);"' . $onclick . '>' . $nombre . ' </a></strong> </h4>' .
                        '<img src="../resources/imagenes/Editar.png" ' . $onclick2 . ' class="imgEditarProyecto" width="20" title="Editar Proyecto"/>' .
                        '</section>';
            }
        }
    }
    echo $concatenar;
}

function BuscarProyecto($nombre) {
    $bdProyectos = new MProyectos();
    $resultado = $bdProyectos->BuscarProyecto($nombre);
    if (mysqli_num_rows($resultado) == 0) {
        echo 0;
    } else {
        echo json_encode(mysqli_fetch_array($resultado, MYSQLI_ASSOC));
    }
}

function BuscaAgregaMaterial($idMaterial, $cant) {
    $bdMateriales = new MMaterial();
    $result = $bdMateriales->BuscarMaterial($idMaterial, $cant);
    $material = new Materiales();
    $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($fila['Codigo'] != null) {
        $material->Codigo = $fila['Codigo'];
        $material->Nombre = $fila['Nombre'];
        $material->Cantidad = $fila['Cantidad'];
// $material->Disponibilidad=$fila['Diponibilidad'];                         
        if ($cant > $material->Cantidad) {
            echo $material->Cantidad;
        } else {
            $concatenar = "
                         <tr>
                             <td>$material->Codigo</td>
                            <td>$cant</td>
                            <td>$material->Nombre</td>
                           <td style='width: 25px;'>
                             <button title='Quitar Fila' class='btnRemoverFila' type='button'  onclick='Remover(this)'>
                                    <img title='Eliminar Fila' src='../resources/imagenes/remove.png' alt='' width='20px'/>
                                </button>
                          </td>
                         </tr>";
            echo $concatenar;
        }
    } else {
        echo 0;
    }
}

function ObternerHyMProyecto($idProyecto) {

    $_COOKIE['ID_Proyecto'] = $idProyecto;
    $bdProyectos = new MProyectos();
    /* Seccion de Materiales */

    $materiales = $bdProyectos->ListaMaterialesProyecto($idProyecto);



    $form = "<form action='../BLL/ReportesExcel.php' method='POST'>";
    $inputOcultos = "<input type='hidden' name='txtID_ProyectoMateriales' value='$idProyecto' /> <input type='hidden' name='txtReporteMaterilesP' value='1' />";
    $concatenar = '<section id="materiales" class="materiales">';
    $combobox = '<div class="form-group">
           <label class="col-md-4 control-label" for="Empresa">Ordenar por</label>  
           
            <select onchange="FiltrarMateriales()" name = "cboFitrarMateriales"  id="cboFitrarMateriales" class ="form-control" name="opciones" placeholder="Filtrar por...">
            <option value = "Filtrar por..." >Filtrar por...</option>
            <option  value = "Nombre" >Nombre</option>
            <option value = "Fecha" >Fecha</option>
             <option value = "Pedientes" >Pedientes</option>
            <option value = "Ver Totales" >Ver Totales</option>
        </select>
        </div>';
    $btnImprimir = "<button class='btnImprimir btn btn-default' title='Exportar Excel' type='submit' id='btnImprimirHerramienas' value='tbl_herramientasProyecto' onclick='MostarMoldalLoanding()'><img src='../resources/imagenes/Excel.png' width='20px' alt=''/></button>";
    ;
//$btnImprimir = "<a title ='Exportar Excel' class='btnImprimir' href='../BLL/ReportesExcel.php?reporteMaterieles=1&ID_Proyecto=$idProyecto'><img src='../resources/imagenes/Excel.png' width='25px' alt=''/></a>";
    ;
    $concatenar .= '<div class="contenidoProyecto">';
    $concatenar .= '<button class=" btn btn-default btnExpandir" type="submit" value="" onclick="ExpandirMateriales(1)" >Expandir  <img src="../resources/imagenes/Expandir.png" width="20px" alt=""/></button>' . '<div class="titulomaterialesherramienta"><h4>Materiales</h4> </div>' . $form . $inputOcultos . $btnImprimir . $combobox . "</form>";
    ;
    $concatenar .= "<div id='tablaMateriales'> <table class=' table table-bordered table-responsive tablasG' id='tbl_MaterialesProyecto'>"
            . "<thead>"
            . " <tr>"
            . " <th>Codigo</th>"
            . " <th>Nombre</th>"
            . " <th class='centrar'> Cantidad </th>"
            . "<th class='centrar'> Fecha </th>"
            . "<th class='centrar'> N°BOLETA</th>"
            . "<th></th>"
            . " </tr>"
            . "</thead>"
            . "<tbody>";
    if ($materiales != null) {
        while ($fila = mysqli_fetch_array($materiales, MYSQLI_ASSOC)) {

            $Fecha = date('d/m/Y', strtotime($fila['Fecha']));



            if ($fila['Devolucion'] == 1) {
                $concatenar .= "<tr><td>" . $fila['Codigo'] . "</td><td>" . $fila['Nombre'] . "</td><td>" . $fila['Cantidad'] . "</td><td>" . $Fecha . "</td><td>" . $fila['Consecutivo'] . "</td><td><a href='javascript:void(0);' onclick='DevolucionMaterial(this)'> <img src='../resources/imagenes/Editar.png' width='25px'/></a></td></tr>";
            } else {
                $concatenar .= "<tr><td>" . $fila['Codigo'] . "</td><td>" . $fila['Nombre'] . "</td><td>" . $fila['Cantidad'] . "</td><td>" . $Fecha . "</td><td>" . $fila['Consecutivo'] . "</td><td></td></tr>";
            }
        }
    }
    $concatenar .= "</tbody></table></div></div>";
    $concatenar .= '</section>';

    /* Seccion de Herramientas */
    $bdProyectos = new MProyectos();

    $herramientas = $bdProyectos->ListaHerramientaProyecto($idProyecto);

    $btnImprimir = "<button class='btnImprimir btn btn-default' title='Exportar Excel' type='submit'><img src='../resources/imagenes/Excel.png' width='20px' alt=''/></button></form>";
    ;
    $combobox = '<div class="form-group">
                            <label class="col-md-4 control-label" for="Empresa">Ordenar por</label>  
                             <select name="cboFiltrarHerramientas" id="cboFiltrarHerramientas" onchange="FiltrarHerramientas()" class ="form-control" name="opciones" placeholder="Filtrar por...">
                                 <option value = "Filtrar por...">Filtrar por...</option>
                                 <option value = "Fecha">Fecha</option>
                                 <option value ="Ver Totales">Ver Totales</option>
                                 <option value ="Reparacion">Reparacion</option>
                                 <option value ="Tipo">Tipo</option>
                                 
                             </select>
                        </div>';
    $buscarHerramientatxt = '<div class="input-group">
					  
                          <input id="txtCodigoHerraP" name="txtCodigoHerraP" type="text" class="form-control" placeholder="Código" onclick="" onchange="FiltroInicioHerramienta()">
                          <span class="input-group-btn">
                                <button id="btnBuscarCodigo" class="btn btn-default" type="button" onclick="BuscarHerramientasPorCodigoP()"><img src="../resources/imagenes/icono_buscar.png" width="18px" alt=""/></button>
			  </span>							
                       </div>';

    $form = "<form action='../BLL/ReportesExcel.php' method='POST'>";

    $concatenar .= $form . '<section id="herramientas" class="materiales">';
    $concatenar .= "<input type='hidden' name='txtID_Proyecto' id='txtID_Proyecto' value='$idProyecto' />"
            . "<input type='hidden' name='txtReporteHerramientasP' value='1' />";
    $concatenar .= '<button class=" btn btn-default btnExpandir" type="button" value="" onclick="ExpandirMateriales(0)" >Expandir  <img src="../resources/imagenes/Expandir.png" width="20px" alt=""/></button>' . ' <div class="titulomaterialesherramienta"><h4>Herramientas</h4></div> ' .
            $btnImprimir . $combobox .
            $buscarHerramientatxt;

    $concatenar .= " <div id='tablaHerramientas'> <table class=' table table-bordered table-responsive tablasG' id='tbl_herramientasProyecto'>"
            . "<thead>"
            . " <tr>"
            . " <th>Codigo</th>"
            . " <th class='centrar'> Tipo </th>"
            . "<th class='centrar'> Fecha </th>"
            . "<th class='centrar'>NBoleta </th>"
            . "<th class='centrar'>Estado</th>"
            . "</tr>"
            . "</thead>"
            . "<tbody>"
    ;
    if ($herramientas != null) {
        $imagen = '';

        while ($fila = mysqli_fetch_array($herramientas, MYSQLI_ASSOC)) {

            $Fecha = date('d/m/Y', strtotime($fila['FechaSalida']));

            if ($fila['Estado'] == 1) {
                $imagen = "Bueno";
                $concatenar .= "<tr><td>" . $fila['Codigo'] . "</td><td>" . $fila['Descripcion'] . "</td><td>" . $Fecha . "</td><td>" . $fila['NBoleta'] . "</td><td>" . $imagen . "</td></tr>";
            } else {

                $imagen = "En Reparacion";
                $concatenar .= "<tr><td class='usuarioBolqueado'>" . $fila['Codigo'] . "</td><td class='usuarioBolqueado' >" . $fila['Descripcion'] . "</td><td class='usuarioBolqueado' >" . $Fecha . "</td><td class='usuarioBolqueado' >" . $fila['NBoleta'] . "</td><td class='usuarioBolqueado' >" . $imagen . "</td></tr>";
            }
        }
    }
    $concatenar .= "</tbody></table> </div></section></form>";

    echo $concatenar;
}

function ModificarProyecto() {
    $proyecto = new Proyectos();
    $bdProyecto = new MProyectos();
    $proyecto->Nombre = $_POST['nombre'];
    $proyecto->Encargado = $_POST['encargado'];
    $proyecto->FechaCreacion = $_POST['fecha'];
    $ID = $_POST['ID'];
    echo $bdProyecto->ModificarProyecto($proyecto, $ID);
}

function ConsecutivoPedido() {
    $bdProyecto = new MProyectos();
    $result = $bdProyecto->ObternerCosecutivoPedido();
    $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($fila["Consecutivo"] != null) {
        return $fila["Consecutivo"] + 1;
    } else {
        return 1;
    }
}

function GuardarPedido($idProyecto, $idUsuario, $consecutivo, $fecha, $datos, $TipoPedido) {
    $arreglo = json_decode($datos, true);
    $tam = sizeof($arreglo);
    $bdProyectos = new MProyectos();
    $bdProyectos->RegistrarPedido($consecutivo, $idProyecto, $fecha, $idUsuario, $TipoPedido);
    $result = 0;
    if ($TipoPedido == 1) {
        $values = "";
        for ($i = 0; $i < $tam; $i++) {
            for ($j = 0; $j < 2; $j++) {
                $values .= "('" . $arreglo[$i][$j] . "'," . $arreglo[$i][$j + 1] . "," . $arreglo[$i][$j + 1] . ",null," . $consecutivo . "),";
                $j = 2;
            }
        }
        $result = $bdProyectos->RegistrarPedidoMaterial($values);
    } else {
        // echo $tam;
        $values = "";
        for ($i = 0; $i < $tam; $i++) {
            for ($j = 0; $j < 2; $j++) {
                $values .= "(" . $consecutivo . "," . $idProyecto . ",'" . $arreglo[$i][$j + 1] . "',1,'" . $fecha . "'," . $arreglo[$i][$j] . "),";
                $j = 2;
            }
        }

        $result = $bdProyectos->RegistrarPedidoHerramientas($values);
    }

    if ($result == 1) {
        echo ConsecutivoPedido();
    } else {
        echo 0;
    }
}

function MostrarPedidos($TipoPedido, $ID_Proyecto) {
    $bdProyectos = new MProyectos();
    $result = $bdProyectos->MostrarPedidos($TipoPedido, $ID_Proyecto);
    if ($result != null) {
        $concaternar = "";

        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

            $concaternar .= "<div class='lospedido'>
                        <table>
                            <tbody>
                            <td hidden='true'>" . $fila['TipoPedido'] . "</td>
                            <td>" . $fila['Consecutivo'] . "</td>
                            <td>" . $fila['Fecha'] . "</td>
                            <td>" . $fila['Nombre'] . "</td>    
                            <td><a onclick='VerPedido(this)' href='#'>Ver</a></td>
                            </tbody>
                        </table>

                    </div>";
        }
        echo $concaternar;
    }
}

function VerPedido($NumPedido, $TipoPedido) {
    $bdProyectos = new MProyectos();
    $result = $bdProyectos->VerPeidido($NumPedido, $TipoPedido);
    if ($TipoPedido == 1) {
        if ($result != null) {
            $concaternar = "<table id='tbl_P_Materiales_Selecionado' class='tablasG'>
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Cantidad</th>
                                    <th>Decripcion</th>
                                    <th>Devolver</th>
                                </tr>
                            </thead>
                            <tbody>";

            while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                if ($fila['Devolucion'] == 0) {
                    $concaternar .= "<tr>";
                    $tdDevolver = "<td style='width: 25px;'>NO</td>";
                } else {
                    $concaternar .= "<tr style='background:#c7e195'>";
                    $tdDevolver = "<td style='width: 25px;background:#c7e195'>SI</td>";
                }
                $concaternar .= "<td>" . $fila['ID_Material'] . "</td>
                             <td>" . $fila['Cantidad'] . "</td>
                             <td>" . $fila['Nombre'] . "</td>";
                $concaternar .= $tdDevolver;
                $concaternar .= "</tr>";
            }
            $concaternar .= "</tbody></table>";
            echo $concaternar;
        }
    } else {
        if ($result != null) {
            $concaternar = "<table id='tbl_P_Materiales_Selecionado' class='tablasG'>
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Tipo</th>
                                    <th>Marca</th>
                                </tr>
                            </thead>
                            <tbody>";
            while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $concaternar .= "<tr>
                               <td>" . $fila['Codigo'] . "</td>
                               <td>" . $fila['Descripcion'] . "</td>
                               <td>" . $fila['Marca'] . "</td>
                               
                           </tr>";
            }
            $concaternar .= "</tbody></table>";
            echo $concaternar;
        }
    }
}

function BuscarHerramientaPedido($codigo) {
    $bdHerramienta = new MHerramientas();
    $restultado = $bdHerramienta->BuscarHerramientaPorCodigo($codigo);
    $concatenar = "";
    $filasAfectadas = mysqli_num_rows($restultado);
    if ($filasAfectadas > 0) {
        $fila = mysqli_fetch_array($restultado, MYSQLI_ASSOC);
        $concatenar = "
                         <tr>
                             <td hidden='true' >" . $fila['ID_Tipo'] . "</td>
                             <td>" . $fila['Codigo'] . "</td>
                             <td>" . $fila['Descripcion'] . "</td>
                             <td>" . $fila['Marca'] . "</td>
                             <td style='width: 25px;'>
                             <button title='Quitar Fila' class='btnRemoverFila' type='button'  onclick='Remover(this)'>
                                    <img title='Eliminar Fila' src='../resources/imagenes/remove.png' alt='' width='20px'/>
                                </button>
                          </td>
                         </tr>";
        echo $concatenar;
    } else {
        echo 0;
    }
}

function EliminarBoletaPedido($NBoleta, $Tipo) {
    $bdProyectos = new MProyectos();
    $result = $bdProyectos->EliminarBoletaPedido($NBoleta, $Tipo);
    echo $result;
}

function FitrosMaterialesProyecto($ID_Proyecto, $Filtro) {
    $bdProyectos = new MProyectos();
    if ($Filtro == "Ver Totales") {
        $sql = "SELECT m.Codigo,m.Devolucion, m.Nombre,SUM(pm.Cantidad) as Suma FROM tbl_boletaspedido bp, tbl_prestamomateriales pm,tbl_materiales m where bp.Consecutivo = pm.NBoleta and bp.ID_Proyecto = $ID_Proyecto and pm.ID_Material = m.Codigo  GROUP BY m.ID_Material ;";
        $result = $bdProyectos->FitrosMaterialesProyecto($sql);
        if ($result != null) {
            $concatenar = "<table class='tablasG' id='tbl_MaterialesProyecto'>
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Nombre</th>
                                    <th>Cantidad</th>
                                    <th></th>
                                </tr>

                            </thead>
                            <tbody>";
            while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $concatenar .= " <tr>
                                        <td>" . $fila['Codigo'] . "</td>
                                        <td>" . $fila['Nombre'] . "</td>
                                        <td>" . $fila['Suma'] . "</td>";
                if ($fila['Devolucion'] == 1) {
                    $concatenar .= "<td><a href='javascript:void(0);' onclick='DevolucionMaterial(this)'> <img src='../resources/imagenes/Editar.png' width='25px'/></a></td>";
                } else {
                    $concatenar .= "<td></td>";
                }
                $concatenar .= "</tr>";
            }
        }
        $concatenar .= "    </tbody>
                                </table>";
        echo $concatenar;
    }

    if ($Filtro == "Nombre") {
        $sql = "SELECT m.Codigo, m.Devolucion, m.Nombre,pm.Cantidad,pm.Pendiente,bp.Fecha,bp.Consecutivo FROM tbl_boletaspedido bp, tbl_prestamomateriales pm,tbl_materiales m where bp.Consecutivo = pm.NBoleta and bp.ID_Proyecto = $ID_Proyecto and pm.ID_Material = m.Codigo ORDER BY m.Nombre ASC";
        $result = $bdProyectos->FitrosMaterialesProyecto($sql);
        if (mysqli_num_rows($result) > 0) {
            CrearTablaFiltros($result);
        }
    }
    if ($Filtro == "Fecha") {
        $sql = "SELECT m.Codigo, m.Devolucion,m.Nombre,pm.Cantidad,pm.Pendiente,bp.Fecha,bp.Consecutivo FROM tbl_boletaspedido bp, tbl_prestamomateriales pm,tbl_materiales m where bp.Consecutivo = pm.NBoleta and bp.ID_Proyecto = $ID_Proyecto and pm.ID_Material = m.Codigo ORDER BY bp.Fecha ASC";
        $result = $bdProyectos->FitrosMaterialesProyecto($sql);
        if (mysqli_num_rows($result) > 0) {
            CrearTablaFiltros($result);
        }
    }
    if ($Filtro == "Pedientes") {
        $sql = "SELECT tp.ID_Material,m.Nombre,SUM(tp.Cantidad) as Prestamo,(SELECT SUM(Cantidad) FROM tbl_devolucionmateriales WHERE ID_Proyecto = $ID_Proyecto AND Codigo = tp.ID_Material GROUP BY Codigo)as Devolucion from tbl_prestamomateriales tp, tbl_materiales m,tbl_boletaspedido p where tp.ID_Material = m.Codigo and p.ID_Proyecto = $ID_Proyecto and tp.NBoleta = p.Consecutivo AND m.Devolucion = 1 GROUP by tp.ID_Material, m.Nombre ";
        $result = $bdProyectos->FitrosMaterialesProyecto($sql);
        if (mysqli_num_rows($result) > 0) {
            $concatenar = " <table class='tablasG' id='tbl_MaterialesProyecto'>"
                    . "<thead>"
                    . " <tr>"
                    . "<th>Codigo</th>"
                    . " <th>Nombre</th>"
                    . " <th> Prestamo </th>"
                    . "<th> Devolucion </th>"
                    . "<th>Pendiente</th>"
                    . "<th></th>"
                    . " </tr>"
                    . "</thead>"
                    . "<tbody>";

            while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $pendiente = $fila['Prestamo'] - $fila['Devolucion'];
                $concatenar .= "<tr><td>" . $fila['ID_Material'] . "</td><td>" . $fila['Nombre'] . "</td><td>" . $fila['Prestamo'] . "</td><td>" . $fila['Devolucion'] . "</td><td>" . $pendiente . "</td>";
                $concatenar .= "<td><a href='javascript:void(0);' onclick='DevolucionMaterial(this)'> <img src='../resources/imagenes/Editar.png' width='25px'/></a></td>";
                $concatenar .= "</tr>";
            }
            $concatenar .= "</tbody> </table>";

            echo $concatenar;
        }
    }
}

function FitrosHerramientasProyecto($ID_Proyecto, $Filtro) {
    $bdProyectos = new MProyectos();
    if ($Filtro == "Fecha") {
        $sql = "SELECT tp.Codigo,tt.Descripcion,tp.FechaSalida, th.Estado, tp.NBoleta FROM tbl_prestamoherramientas tp, tbl_herramientaelectrica th, tbl_tipoherramienta tt where 
 tp.ID_Proyecto = $ID_Proyecto and tp.ID_Tipo = tt.ID_Tipo and tp.Codigo =  th.Codigo ORDER by tp.FechaSalida ASC;";
        /* $sql ="sELECT th.Codigo,tt.Descripcion,th.FechaSalida,th.Estado,th.NBoleta from tbl_prestamoherramientas th, tbl_tipoherramienta tt
          where th.ID_Proyecto = $ID_Proyecto and th.ID_Tipo = tt.ID_Tipo ORDER by th.FechaSalida ASC;"; */
        $result = $bdProyectos->FiltrosHerramientasProyecto($sql);
        if ($result != null) {
            $concatenar = " <table class='tablasG' id='tbl_herramientasProyecto'>"
                    . "<thead>"
                    . " <tr>"
                    . " <th>Codigo</th>"
                    . " <th class='centrar'> Tipo </th>"
                    . "<th class='centrar'> Fecha </th>"
                    . "<th class='centrar'>NBoleta </th>"
                    . "<th class='centrar'>Estado</th>"
                    . "</tr>"
                    . "</thead>"
                    . "<tbody>";
            $imagen = '';

            while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $Fecha = date('d/m/Y', strtotime($fila['FechaSalida']));

                if ($fila['Estado'] == 1) {
                    $imagen = "Bueno";
                    $concatenar .= "<tr><td>" . $fila['Codigo'] . "</td><td>" . $fila['Descripcion'] . "</td><td>" . $Fecha . "</td><td>" . $fila['NBoleta'] . "</td><td>" . $imagen . "</td></tr>";
                } else {
                    $imagen = "En reparacion";
                    $concatenar .= "<tr><td class='usuarioBolqueado'>" . $fila['Codigo'] . "</td><td class='usuarioBolqueado' >" . $fila['Descripcion'] . "</td><td class='usuarioBolqueado' >" . $Fecha . "</td><td class='usuarioBolqueado' >" . $fila['NBoleta'] . "</td><td class='usuarioBolqueado' >" . $imagen . "</td></tr>";
                }
            }

            $concatenar .= "</tbody></table></section>";

            echo $concatenar;
        }
    }
    if ($Filtro == "Ver Totales") {
        $sql = "SELECT tt.Descripcion,COUNT(*) as Cantidad from tbl_prestamoherramientas th, tbl_tipoherramienta tt where th.ID_Proyecto = $ID_Proyecto and th.ID_Tipo = tt.ID_Tipo GROUP by tt.Descripcion;";
        $result = $bdProyectos->FiltrosHerramientasProyecto($sql);
        if ($result != null) {
            $concatenar = "<table class='tablasG' id = 'tbl_herramientasProyecto'>
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                </tr>

                            </thead>
                            <tbody>";
            while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $concatenar .= " <tr>
                                        <td>" . $fila['Descripcion'] . "</td>
                                        <td>" . $fila['Cantidad'] . "</td>
                                    </tr>";
            }
            $concatenar .= "    </tbody>
                                </table>";
            echo $concatenar;
        }
    }

    if ($Filtro == "Reparacion") {
        $sql = "SELECT tp.Codigo,tt.Descripcion,tp.FechaSalida, th.Estado, tp.NBoleta FROM tbl_prestamoherramientas tp, tbl_herramientaelectrica th, tbl_tipoherramienta tt where 
 tp.ID_Proyecto = $ID_Proyecto and tp.ID_Tipo = tt.ID_Tipo and tp.Codigo =  th.Codigo and th.Estado = 0 ORDER by tp.FechaSalida ASC;";
        /* $sql ="sELECT th.Codigo,tt.Descripcion,th.FechaSalida,th.Estado,th.NBoleta from tbl_prestamoherramientas th, tbl_tipoherramienta tt
          where th.ID_Proyecto = $ID_Proyecto and th.ID_Tipo = tt.ID_Tipo ORDER by th.FechaSalida ASC;"; */
        $result = $bdProyectos->FiltrosHerramientasProyecto($sql);
        if ($result != null) {
            $concatenar = " <table class='tablasG' id='tbl_herramientasProyecto'>"
                    . "<thead>"
                    . " <tr>"
                    . " <th>Codigo</th>"
                    . " <th class='centrar'> Tipo </th>"
                    . "<th class='centrar'> Fecha </th>"
                    . "<th class='centrar'>NBoleta </th>"
                    . "<th class='centrar'>Estado</th>"
                    . "</tr>"
                    . "</thead>"
                    . "<tbody>";
            $imagen = '';

            while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $Fecha = date('d/m/Y', strtotime($fila['FechaSalida']));
                $imagen = "En reparacion";
                $concatenar .= "<tr><td class='usuarioBolqueado'>" . $fila['Codigo'] . "</td><td class='usuarioBolqueado' >" . $fila['Descripcion'] . "</td><td class='usuarioBolqueado' >" . $Fecha . "</td><td class='usuarioBolqueado' >" . $fila['NBoleta'] . "</td><td class='usuarioBolqueado' >" . $imagen . "</td></tr>";
            }

            $concatenar .= "</tbody></table></section>";

            echo $concatenar;
        }
    }
    if ($Filtro == "Tipo") {
        $sql = "SELECT tp.Codigo,tt.Descripcion,tp.FechaSalida, th.Estado, tp.NBoleta FROM tbl_prestamoherramientas tp, tbl_herramientaelectrica th, tbl_tipoherramienta tt where 
 tp.ID_Proyecto = $ID_Proyecto and tp.ID_Tipo = tt.ID_Tipo and tp.Codigo =  th.Codigo ORDER BY tt.Descripcion;";

        $result = $bdProyectos->FiltrosHerramientasProyecto($sql);
        if ($result != null) {
            $concatenar = " <table class='tablasG' id='tbl_herramientasProyecto'>"
                    . "<thead>"
                    . " <tr>"
                    . " <th>Codigo</th>"
                    . " <th class='centrar'> Tipo </th>"
                    . "<th class='centrar'> Fecha </th>"
                    . "<th class='centrar'>NBoleta </th>"
                    . "<th class='centrar'>Estado</th>"
                    . "</tr>"
                    . "</thead>"
                    . "<tbody>";
            $imagen = '';

            while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $Fecha = date('d/m/Y', strtotime($fila['FechaSalida']));
                if ($fila['Estado'] == 1) {
                    $imagen = "Bueno";
                    $concatenar .= "<tr><td>" . $fila['Codigo'] . "</td><td>" . $fila['Descripcion'] . "</td><td>" . $Fecha . "</td><td>" . $fila['NBoleta'] . "</td><td>" . $imagen . "</td></tr>";
                } else {
                    $imagen = "En reparacion";
                    $concatenar .= "<tr><td class='usuarioBolqueado'>" . $fila['Codigo'] . "</td><td class='usuarioBolqueado' >" . $fila['Descripcion'] . "</td><td class='usuarioBolqueado' >" . $fila['FechaSalida'] . "</td><td class='usuarioBolqueado' >" . $fila['NBoleta'] . "</td><td class='usuarioBolqueado' >" . $imagen . "</td></tr>";
                }
            }

            $concatenar .= "</tbody></table></section>";

            echo $concatenar;
        }
    }
}

function CrearTablaFiltros($result) {
    $concatenar = " <table class='tablasG' id='tbl_MaterialesProyecto'>"
            . "<thead>"
            . " <tr>"
            . "<th>Codigo</th>"
            . " <th>Nombre</th>"
            . " <th class='centrar'> Cantidad </th>"
            . "<th class='centrar'> Fecha </th>"
            . "<th class='centrar'> N°BOLETA</th>"
            . "<th></th>"
            . " </tr>"
            . "</thead>"
            . "<tbody>";

    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $Fecha = date('d/m/Y', strtotime($fila['Fecha']));
        $concatenar .= "<tr><td>" . $fila['Codigo'] . "</td><td>" . $fila['Nombre'] . "</td><td>" . $fila['Cantidad'] . "</td><td>" . $Fecha . "</td><td>" . $fila['Consecutivo'] . "</td>";
        if ($fila['Devolucion'] == 1) {
            $concatenar .= "<td><a href='javascript:void(0);' onclick='DevolucionMaterial(this)'> <img src='../resources/imagenes/Editar.png' width='25px'/></a></td>";
        }
        $concatenar .= "<td></td></tr>";
    }
    $concatenar .= "</tbody> </table>";

    echo $concatenar;
}

function AnularBoletaMaterial($NBoleta) {

    $bdProyectos = new MProyectos();
    $bdProyectos->AnularBoletaMaterial($NBoleta);
    echo 1;
}

function ColaPeidos($ID_Proyecto) {
    $bdProyectos = new MProyectos();
    $result = $bdProyectos->ColaPedidos($ID_Proyecto);
    if (mysqli_num_rows($result) > 0) {
        $concatenar = "";
        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

            $Fecha = date('d/m/Y', strtotime($fila['Fecha']));
            $concatenar .= "     <tr>
                         <td>" . $fila['Consecutivo'] . "</td>
                         <td>" . $Fecha . "</td>
                         <td>" . $fila['Nombre'] . "</td>
                         <td><button type='button' onclick='procesarPeido(this)' class='btn btn-success'>Procesar</button></td>
                     </tr>";
        }
        echo $concatenar;
    } else {
        echo "<h3>No hay pedidos pendientes</>";
    }
}

function MostrarPedidoProeveeduria() {
    $bdProyectos = new MProyectos();
    $result = $bdProyectos->MostrarPedidoProeveeduria($_GET['boleta']);
    if ($result != null) {
        echo $result;
    } else {
        echo "ERROR";
    }
}

function EliminarNotificacion() {
    $bdProyectos = new MProyectos();
    $result = $bdProyectos->EliminarNotificacion($_GET['id']);
    if ($result == 1) {
        echo ColaPeidos($_GET['ID_Proyecto']);
    }
}

function DevolucionMaterial() {
    $bdProyectos = new MProyectos();
    $devolucion = new DevolucionMateriales();
    $devolucion->Codigo = $_POST['Codigo'];
    $devolucion->Cantidad = $_POST['Cantidad'];
    $devolucion->fecha = $_POST['Fecha'];
    $devolucion->NBoleta = $_POST['Boleta'];
    $devolucion->ID_Proyecto = $_POST['idProyecto'];
    $result = $bdProyectos->DevolucionMaterial($devolucion);
    echo $result;
}

function ListarDevolucionesPorMaterial() {
    $bdProyectos = new MProyectos();
    $result = $bdProyectos->ListarDevolucionesPorMaterial($_GET['ID_Material'], $_GET['idProyecto']);
    if (mysqli_num_rows($result) > 0) {
        $totalDevuelto = 0;
        $concatenar = "<table class='tablasG'>
                                <thead>   
                                <th>Cantidad</th>
                                 <th>Fecha Devolucion</th>
                                  <th>N Boleta</th>
                                </thead>
                                <tbody>";
        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $totalDevuelto = $totalDevuelto + $fila['Cantidad'];
            $concatenar .= " <tr>
                                <td>" . $fila['Cantidad'] . "</td>
                                <td>" . $fila['fecha'] . "</td>
                                <td>" . $fila['NBoleta'] . "</td>
                            </tr>";
        }
        $concatenar .= "</tbody>
                       </table><br><br>";
         $bdProyectos = new MProyectos();
        $result = $bdProyectos->ObtenerTotalMaterialPrestadoProyecto($_GET['ID_Material'], $_GET['idProyecto']);
        $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $cantaidadPrestada = $fila['CantidadIngreso'];
        $pendiente = $cantaidadPrestada - $totalDevuelto;

        $concatenar .= "<table class='tablasG' style=' width:50%' id = 'tblresumenDevolucion'>
                                <thead>   
                                <th>Total Prestado</th>
                                 <th>Devolucion</th>
                                  <th style='color:red'>Pendiente</th>
                                </thead>
                                <tbody>
                                <tr>
                                    <td id = 'cantidadPrestada'>$cantaidadPrestada</td>                              
                                    <td id  = 'totalDevuelto'>$totalDevuelto</td>
                                    <td class='usuarioBolqueado' id='pendienteDevolver'>$pendiente</td>
                                </tr>
                                </tbody></table>";

        echo $concatenar;
    } else {
        echo "<h3>No se a realizado niguna devolucion</h3>";
    }
}

function ObtenerTotalMaterialPrestadoProyecto() {
    $bdProyectos = new MProyectos();
    $result = $bdProyectos->ObtenerTotalMaterialPrestadoProyecto($_GET['ID_Material'], $_GET['idProyecto']);
    $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
    echo $fila['CantidadIngreso'];
}

function BuscarBoletaPedido() {
    $bdProyectos = new MProyectos();
    $result = $bdProyectos->BuscarBoletaPedido($_GET['numBoleta'], $_GET['idProyecto']);
    if (mysqli_num_rows($result) > 0) {
        $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
        echo "<div class='lospedido'>
                        <table>
                            <tbody>
                            <td hidden='true'>" . $fila['TipoPedido'] . "</td>
                            <td>" . $fila['Consecutivo'] . "</td>
                            <td>" . $fila['Fecha'] . "</td>
                            <td>" . $fila['Nombre'] . "</td>    
                            <td><a onclick='VerPedido(this)' href='#'>Ver</a></td>
                            </tbody>
                        </table>

                    </div>";
    } else {
        echo "<h3> La boleta no existe.</h3>";
    }
}

function AnularBoletaHerramientas() {
    $bdProyectos = new MProyectos();
    $bdProyectos->AnularBoletaHerramientas($_GET['NBoleta']);
}

function NombreProyecto($id) {
    $bdProyectos = new MProyectos();
    $result = $bdProyectos->NombreProyecto($id);
    if (mysqli_num_rows($result) > 0) {
        $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $fila['nombre'];
    }
}

function FinalizarProyecto($ID_Proyecto) {
    $bdProyectos = new MProyectos();
    $bdProyectos->FinalizarProyecto($ID_Proyecto);
}

function BuscarProyectoID($ID) {
    $bdProyecto = new MProyectos();
    $result = $bdProyecto->BuscarProyectoID($ID);
    if (mysqli_num_rows($result) > 0) {
        $fila = mysqli_fetch_assoc($result);
        $nombre = $fila['Nombre'];
        $director = $fila['DirectorProyecto'];
        $fecha = $fila['FechaCreacion'];
        $concatenar = "<div class='form-group'>    
                                    <label class='col-lg-2'> Nombre</label> 
                                    <div class='col-lg-8'>
                                        <div class='input-group'>
                                            <input id='txtNombreProyecto'  name='txtNombreProyecto' type='text' value='$nombre' class='form-control' placeholder='Nombre'>
                                            <span class='input-group-btn'>
                                                <button id='btnBuscarMaterialCodigo'  class='btn btn-default' type='button'  onclick='BuscarProyecto()'><img src='../resources/imagenes/icono_buscar.png' width='18px' alt=''/></button>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class='form-group'>    
                                    <label class='col-lg-2'>Fecha</label> 
                                    <div class='col-lg-5'>
                                        <input type='date' value='$fecha' name='txtFechaCreacionProyecto' id='txtFechaCreacionProyecto'  class='form-control ' placeholder='Fecha'/>
                                    </div>
                                </div>
                                <div class='form-group'>    
                                    <label class='col-lg-2'>Encargado proyecto</label> 
                                    <div class='col-lg-5'>
                                        <input type='text' value='$director' name='txtEncargadoProyecto' id='txtEncargadoProyecto' class='form-control ' placeholder='Encargado'/>
                                    </div>
                                </div>";
        echo $concatenar;
    } else {
        echo"<script>alert('Ocurrio un Error')</script>";
    }
}

function buscarHerramientaPorProyecto($idProyecto, $idHerramienta) {
    $bdProyecto = new MProyectos();
    $result = $bdProyecto->HerramientaPorId($idProyecto, $idHerramienta);

    if ($result != null) {


        $concatenar = " <table class='tablasG' id='tbl_herramientasProyecto'>"
                . "<thead>"
                . " <tr>"
                . " <th>Codigo</th>"
                . " <th class='centrar'> Tipo </th>"
                . "<th class='centrar'> Fecha </th>"
                . "<th class='centrar'>NBoleta </th>"
                . "<th class='centrar'>Estado</th>"
                . "</tr>"
                . "</thead>"
                . "<tbody>";

        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $Fecha = date('d/m/Y', strtotime($fila['FechaSalida']));
            if ($fila['Estado'] == 1) {
                $imagen = "Bueno";
                $concatenar .= "<tr><td>" . $fila['Codigo'] . "</td><td>" . $fila['Descripcion'] . "</td><td>" . $Fecha . "</td><td>" . $fila['NBoleta'] . "</td><td>" . $imagen . "</td></tr>";
            } else {
                $imagen = "En reparacion";
                $concatenar .= "<tr><td class='usuarioBolqueado'>" . $fila['Codigo'] . "</td><td class='usuarioBolqueado' >" . $fila['Descripcion'] . "</td><td class='usuarioBolqueado' >" . $Fecha . "</td><td class='usuarioBolqueado' >" . $fila['NBoleta'] . "</td><td class='usuarioBolqueado' >" . $imagen . "</td></tr>";
            }
        }
        $concatenar .= "</tbody></table></section>";
        echo $concatenar;
    }
}

function listarSoloHerramienta($idProyecto) {
    $bdProyecto = new MProyectos();
    $result = $bdProyecto->ListarSoloHerramienta($idProyecto);

    if ($result != null) {


        $concatenar = " <table class='tablasG' id='tbl_herramientasProyecto'>"
                . "<thead>"
                . " <tr>"
                . " <th>Codigo</th>"
                . " <th class='centrar'> Tipo </th>"
                . "<th class='centrar'> Fecha </th>"
                . "<th class='centrar'>NBoleta </th>"
                . "<th class='centrar'>Estado</th>"
                . "</tr>"
                . "</thead>"
                . "<tbody>";

        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $Fecha = date('d/m/Y', strtotime($fila['FechaSalida']));
            if ($fila['Estado'] == 1) {
                $imagen = "Bueno";
                $concatenar .= "<tr><td>" . $fila['Codigo'] . "</td><td>" . $fila['Descripcion'] . "</td><td>" . $Fecha . "</td><td>" . $fila['NBoleta'] . "</td><td>" . $imagen . "</td></tr>";
            } else {
                $imagen = "En reparacion";
                $concatenar .= "<tr><td class='usuarioBolqueado'>" . $fila['Codigo'] . "</td><td class='usuarioBolqueado' >" . $fila['Descripcion'] . "</td><td class='usuarioBolqueado' >" . $Fecha . "</td><td class='usuarioBolqueado' >" . $fila['NBoleta'] . "</td><td class='usuarioBolqueado' >" . $imagen . "</td></tr>";
            }
        }
        $concatenar .= "</tbody></table></section>";
        echo $concatenar;
    }
}
