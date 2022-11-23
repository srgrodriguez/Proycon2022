<?php
include '../DATA/Desecho.php';
include '../DAL/Interfaces/IDesecho.php';
include '../DAL/Metodos/MDesecho.php';
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
    }elseif ($_GET['opc'] == 'agregar') {
        AgregarDesecho();
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
                    . "<td>" . $fila['ID_Herramienta'] . "</td>"
                    . "<td>" . $fila['Codigo'] . "</td>"
                    . "<td>" . $fila['Cantidad'] . "</td>"
                    . "<td>" . $fila['Motivo'] . "</td>"
                    . "<td>" . $fila['FechaDesecho'] . "</td>"
                    . "<td>" . $fila['Usuario'] . "</td>"
                    . "<td>" . ObtenerDescripcionTipoHerramienta($fila['TipoDesecho']) . "</td>"
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
                    . "<td>" . $fila['ID_Herramienta'] . "</td>"
                    . "<td>" . $fila['Codigo'] . "</td>"
                    . "<td>" . $fila['Cantidad'] . "</td>"
                    . "<td>" . $fila['Motivo'] . "</td>"
                    . "<td>" . $fila['FechaDesecho'] . "</td>"
                    . "<td>" . $fila['Usuario'] . "</td>"
                    . "<td>" . ObtenerDescripcionTipoHerramienta($fila['TipoDesecho']) . "</td>"
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
                    . "<td>" . $fila['ID_Herramienta'] . "</td>"
                    . "<td>" . $fila['Codigo'] . "</td>"
                    . "<td>" . $fila['Cantidad'] . "</td>"
                    . "<td>" . $fila['Motivo'] . "</td>"
                    . "<td>" . $fila['FechaDesecho'] . "</td>"
                    . "<td>" . $fila['Usuario'] . "</td>"
                    . "<td>" . ObtenerDescripcionTipoHerramienta($fila['TipoDesecho']) . "</td>"
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

function AgregarDesecho()
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

        echo $resultadoConsulta = $MDesecho->AgregarDesecho($Desechos);
    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "AgregarDesecho");
    }
}

function ObtenerDescripcionTipoHerramienta($TipoDesecho){


    if($TipoDesecho == 0){
        return "Herramienta";        
    } else {
        return "Material";        

    }


}

function ConsecutivoPedido() {
    $bdProyecto = new MDesecho();
    $result = $bdProyecto->ObternerCosecutivoPedido();
    $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($fila["id"] != null) {
        return $fila["id"] + 1;
    } else {
        return 1;
    }
}


