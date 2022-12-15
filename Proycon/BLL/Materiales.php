<?php
header("Content-Type: text/html;charset=utf-8");
include '../DATA/Materiales.php';
include '../DAL/Interfaces/IMateriales.php';
include '../DAL/Metodos/MMaterial.php';
include '../DAL/Conexion.php';
include '../DAL/FuncionesGenerales.php';
require_once 'Autorizacion.php';
require_once '../DAL/Log.php';

Autorizacion();
if (isset($_GET['opc'])) {
    if ($_GET['opc'] == "bnombre") {
        BuscarMaterialNombre($_GET['Nombre']);
    }
    if ($_GET['opc'] == "agregar") {
        AgregarMateriales();
    }
    if ($_GET['opc'] == "verificar") {
        verificarMaterialExistente();
    }
    if ($_GET['opc'] == "update") {
        updateMateriales();
    }
    if ($_GET['opc'] == "buscarTiempoReal") {
        if (isset($_POST['consulta'])) {
            BuscarTiempoRealMaterial($_POST['consulta']);
        }
    }
    if ($_GET['opc'] == 'listar') {
        CrearTablaListarMateriales();
    }
    if ($_GET['opc'] == "buscarcodigo") {
        BuscarMaterialCodigo();
    }
}

function BuscarMaterialNombre($nombre)
{
        try {
        $bdMateriales = new MMaterial();

        $resultado = $bdMateriales->BuscarMaterialNombre($nombre);

        if ($resultado <> null) {
            $concatenar = "";
            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $concatenar .= " <tr>
                                 <td>" . $fila['Codigo'] . "</td>
                                 <td>" . $fila['Nombre'] . "</td>
                                 <td>" . $fila['Cantidad'] . "</td>
                                 <td style='width: 100px;'><input width='20px' type='text' id='txtcantiSolicitadaMaterial' name='txtcantiSolicitadaMaterial' class='form-control txtcantiSolicitadaMaterial' value='' placeholder='Cantidad' /></td>
                                 <td style='text-align: center'>
                                 <button class='btn btn-success' title='Agregar' onclick='AgregarMaterialBuscadoPNombre(this)'>
                                     <span class='glyphicon glyphicon-plus'></span>
                                 </button>
                                 </td>
                         </tr>";
            }

            echo $concatenar;
        } else {
            echo 0;
        }
    } catch (\Throwable $ex) {
        echo  Log::GuardarEvento($ex, "BuscarMaterialNombre");
    }
}

function AgregarMateriales()
{
    $Materiales = new Materiales();
    $MMaterial = new MMaterial();

    try {
        $Materiales->Codigo = $_POST['codigo'];
        $Materiales->Nombre = $_POST['nombre'];
        $Materiales->Cantidad = $_POST['cantidad'];
        $Materiales->Disponibilidad = $_POST['disponibilidad'];
        $Materiales->Devolucion = $_POST['devolucion'];

        echo $resultadoConsulta = $MMaterial->AgregarMateriales($Materiales);
    } catch (\Throwable $ex) {
        echo Log::GuardarEvento($ex, "AgregarMateriales");
    }
}

function verificarMaterialExistente()
{

    $MMaterial = new MMaterial();
    try {
        $resutado = $MMaterial->VerificarDisponibilidad($_POST['codigo']);

        if ($resutado <> NULL) {
            $fila = mysqli_fetch_array($resutado, MYSQLI_ASSOC);
            echo json_encode($fila);
        } else {
            echo 0;
        }
    } catch (\Throwable $ex) {
        echo Log::GuardarEvento($ex, "verificarMaterialExistente");
    }
}

function updateMateriales()
{
    $Materiales = new Materiales();
    $MMaterial = new MMaterial();

    try {

        $Materiales->idHerramienta = $_POST['idHerramienta'];
        $Materiales->Codigo = $_POST['codigo'];
        $Materiales->Nombre = $_POST['nombre'];
        $Materiales->Cantidad = $_POST['cantidad'];
        $Materiales->Devolucion = $_POST['devolucion'];
        $Materiales->Stock = $_POST['stock'];

        $resultado = $MMaterial->UpdateMateriales($Materiales);
        echo $resultado;
    } catch (\Throwable $ex) {
        echo Log::GuardarEvento($ex, "updateMateriales");
    }
}

function CrearTablaListarMateriales()
{
    $MMaterial = new MMaterial();

    try {
        $resultado = $MMaterial->listarTotalMateriales();

        if ($resultado <> null) {
            $concatenar = '';

            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $concatenar .=
                    "<tr>"
                    . "<td>" . $fila['codigo'] . "</td>"
                    . "<td style='text-align:left'>" . $fila['nombre'] . "</td>"
                    . "<td>" . $fila['cantidad'] . "</td>"
                    . "</tr>";
            }
            echo $concatenar;
        }
    } catch (\Throwable $ex) {
        echo Log::GuardarEvento($ex, "CrearTablaListarMateriales");
    }
}


function BuscarTiempoRealMaterial($consulta)
{


    $bdMateria = new MMaterial();

    try {
        $result = $bdMateria->BuscarTiempoRealHerramienta($consulta);
        if (mysqli_num_rows($result) > 0) {
            $concatenar = "";
            while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $concatenar .=
                    "<tr>"
                    . "<td>" . $fila['codigo'] . "</td>"
                    . "<td>" . $fila['nombre'] . "</td>"
                    . "<td>" . $fila['cantidad'] . "</td>"
                    . "</tr>";
            }
            echo $concatenar;
        } else {
            echo "<h2>No se encontraron Resultados :( </h2>";
        }
    } catch (\Throwable $ex) {
        echo Log::GuardarEvento($ex, "BuscarTiempoRealMaterial");
    }
}
function BuscarMaterialCodigo()
{
    $bdMateriales = new MMaterial();

    try {
        $result = $bdMateriales->BuscarMaterialCodigo($_GET['codigo']);
        if (mysqli_num_rows($result) > 0) {
            $concatenar = "";
            while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $concatenar .=
                    "<tr>"
                    . "<td>" . $fila['codigo'] . "</td>"
                    . "<td>" . $fila['nombre'] . "</td>"
                    . "<td>" . $fila['cantidad'] . "</td>"
                    . "</tr>";
            }
            echo $concatenar;
        } else {
            echo "<h3>El codigo Ingresado no Existe</h3>";
        }
    } catch (\Throwable $ex) {
        echo Log::GuardarEvento($ex, "BuscarMaterialCodigo");
    }
}
