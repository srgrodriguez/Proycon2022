<?php
include '../DATA/Materiales.php';
include '../DATA/Desecho.php';
include '../DAL/Interfaces/IDesecho.php';
include '../DAL/Metodos/MDesecho.php';
include '../DAL/Interfaces/IMateriales.php';
include '../DAL/Interfaces/IHerrramientas.php';
include '../DAL/Metodos/MMaterial.php';
include '../DAL/Metodos/MHerramientas.php';
include '../DAL/Conexion.php';
include '../DAL/FuncionesGenerales.php';
require_once 'Autorizacion.php';
require_once '../DAL/Log.php';

Autorizacion();
if (isset($_GET['opc'])) {
    if ($_GET['opc'] == 'listar') {
        CrearTablaListarDesecho();
    }
    elseif ($_GET['opc'] == 'listarMaterial') {
        ListarDesechoMaterial();
    }
    elseif ($_GET['opc'] == 'listarHeramienta') {
        ListarDesechoHerramientas();
    }
    elseif ($_GET['opc'] == 'update') {
        updateDesecho();
    
    }elseif ($_GET['opc'] == 'registrarPedido') {
        if (!isset($_SESSION)) {
            session_start();
        }
        GuardarPedido($_SESSION['ID_Usuario'],$_POST['data'],$_POST['fecha'],$_POST['motivo'],$_POST['consecutivo']);
    }elseif ($_GET['opc'] == 'buscarherramientapedido') {
        BuscarHerramientaCodigo($_GET['codigo']);
    }elseif ($_GET['opc'] == 'buscarM') {
        BuscaAgregaMaterial($_GET['id'], $_GET['cant']);
    }elseif ($_GET['opc'] == 'registrarPedidoMateriales') {
        if (!isset($_SESSION)) {
            session_start();
        }
        GuardarPedidoMaterial($_SESSION['ID_Usuario'],$_POST['data'],$_POST['fecha'],$_POST['motivo'],$_POST['consecutivo']);
    } elseif ($_GET['opc'] == 'ConsultarDesecho') {
        ConsultarDesecho($_POST['boleta']);
    }
}



function CrearTablaListarDesecho()
{
    $MDesecho = new MDesecho();

    try {
        $resultado = $MDesecho->listarDesecho();

        if ($resultado <> null) {
            $concatenar = '';

            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $concatenar .=
                    "<tr>"
                    . "<td>" . $fila['id'] . "</td>"
                    . "<td>" . $fila['Descripcion'] . "</td>"
                    . "<td>" . $fila['Codigo'] . "</td>"
                    . "<td>" . $fila['Cantidad'] . "</td>"
                    . "<td>" . $fila['Motivo'] . "</td>"
                    . "<td>" . $fila['FechaDesecho'] . "</td>"
                    . "<td>" . $fila['Usuario'] . "</td>"
                    . "<td>" . ObtenerDescripcionTipoHerramienta($fila['TipoDesecho']) . "</td>"
                    . "<td> <a onclick='VerPedido(this)' href='#'>Ver</a> </td>"
                    . "</tr>";
            }
            echo $concatenar;
        }

    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "CrearTablaListarDesecho");
    }
}

function ListarDesechoMaterial()
{
    $MDesecho = new MDesecho();

    try {
        $resultado = $MDesecho->listarDesechoMateriales();

        if ($resultado <> null) {
            $concatenar = '';

            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $concatenar .=
                    "<tr>"
                        . "<td>" . $fila['id'] . "</td>"
                        . "<td>" . $fila['Descripcion'] . "</td>"
                        . "<td>" . $fila['Codigo'] . "</td>"
                        . "<td>" . $fila['Cantidad'] . "</td>"
                        . "<td>" . $fila['Motivo'] . "</td>"
                        . "<td>" . $fila['FechaDesecho'] . "</td>"
                        . "<td>" . $fila['Usuario'] . "</td>"
                        . "<td>" . ObtenerDescripcionTipoHerramienta($fila['TipoDesecho']) . "</td>"
                        . "<td>    <a onclick='VerPedido(this)' href='#'>Ver</a>  </td>"

                    . "</tr>";
            }
            echo $concatenar;
        } else {
            echo "<h2>No se encontraron Resultados :( </h2>";
        }
        
    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "BuscarTiempoRealDesechoMaterial");
    }
}

function ListarDesechoHerramientas()
{
    $MDesecho = new MDesecho();

    try {
        $resultado = $MDesecho->listarDesechoHerramienta();

        if ($resultado <> null) {
            $concatenar = '';

            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $concatenar .=
                    "<tr>"
                    . "<td>" . $fila['id'] . "</td>"
                    . "<td>" . $fila['Descripcion'] . "</td>"
                    . "<td>" . $fila['Codigo'] . "</td>"
                    . "<td>" . $fila['Cantidad'] . "</td>"
                    . "<td>" . $fila['Motivo'] . "</td>"
                    . "<td>" . $fila['FechaDesecho'] . "</td>"
                    . "<td>" . $fila['Usuario'] . "</td>"
                    . "<td>" . ObtenerDescripcionTipoHerramienta($fila['TipoDesecho']) . "</td>"
                    . "<td>    <a onclick='VerPedido(this)' href='#'>Ver</a>  </td>"
                    . "</tr>";
            }
            echo $concatenar;
        } else {
            echo "<h2>No se encontraron Resultados :( </h2>";
        }
        
    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "BuscarTiempoRealDesechoHerramientas");
    }
}

function updateDesecho()
{
    $Desechos = new Desecho();
    $MDesecho = new MDesecho();

    try {

        $Desechos->Id = $_POST['id'];
        $Desechos->ID_Herramienta = $_POST['iD_Herramienta'];
        $Desechos->Codigo = $_POST['codigo'];
        $Desechos->Motivo = $_POST['motivo'];
        $Desechos->FechaDesecho = $_POST['fechaDesecho'];
        $Desechos->ID_Usuario = $_POST['iD_Usuario'];
        $Desechos->TipoDesecho = $_POST['tipoDesecho'];
        $Desechos->Cantidad = $_POST['cantidad'];


        $resultado = $MDesecho->ActualizarDesecho($Desechos);
        echo $resultado;
    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "updateDesecho");
    }
}



function ObtenerDescripcionTipoHerramienta($TipoDesecho){


    if($TipoDesecho == 1){
        return "Herramienta";        
    } else {
        return "Material";        

    }


}

function ConsecutivoPedido() {
    $bdProyecto = new MDesecho();
    $result = $bdProyecto->ObternerCosecutivoPedido();
    $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    $count = $result->num_rows;

    if ($count > 0) {
        return $fila["id"] + 1;
    } else {
        return 1;
    }
}



function GuardarPedido($ID_Usuario,$arreglo, $fecha,$motivo, $consecutivo)  {
    $arreglo = json_decode($arreglo, true);
    $tam = sizeof($arreglo);
    $bdDesechos = new MDesecho();
    

    foreach($arreglo as $item){
        $bdDesechos->RegistrarDesecho($item['codigo'], $item['tipo'],$item['cantidad'], $fecha,$ID_Usuario,$motivo, $consecutivo);
        
    }; 

    echo ConsecutivoPedido();
}


function GuardarPedidoMaterial($ID_Usuario,$arreglo, $fecha,$motivo, $consecutivo)  {
    $arreglo = json_decode($arreglo, true);
    $tam = sizeof($arreglo);
    $bdDesechos = new MDesecho();
    

    foreach($arreglo as $item){
        $bdDesechos->RegistrarDesechoMaterial($item['codigo'], $item['tipo'],$item['cantidad'], $fecha,$ID_Usuario,$motivo, $consecutivo);
        
    }; 

    echo ConsecutivoPedido();
}
   

function BuscarHerramientaCodigo($codigo) {
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
    

function BuscaAgregaMaterial($idMaterial, $cant) {
    $bdMateriales = new MMaterial();
    $result = $bdMateriales->BuscarMaterial($idMaterial, $cant);
    $material = new Materiales();
    $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $count = $result->num_rows;

    if ($count > 0) {
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
                                <td class='codTabla'>$material->Codigo</td>
                                <td class='cantidadTabla'>$cant</td>
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
    } else {
        echo 0;
    }
}


function ConsultarDesecho($id) {
    $MDesecho = new MDesecho();
    $result = $MDesecho->ConsultarDesecho($id);

        if ($result != null) {
            $concaternar = "<table id='tbl_P_Materiales_Selecionado' class='tablasG'>
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Cantidad</th>
                                    <th>Decripcion</th>
                                    <th>Fecha Desecho</th>
                                </tr>
                            </thead>
                            <tbody>";

            while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
               
                $concaternar .= 
                            "<tr>".
                                "<td>" . $fila['Codigo'] . "</td>
                                <td>" . $fila['Cantidad'] . "</td>
                                <td>" . $fila['Descripcion'] . "</td>".
                                "<td>" . $fila['FechaDesecho'] . "</td>".
                            "</tr>";

            }
            $concaternar .= "</tbody></table>";
            echo $concaternar;
        }
    
}
