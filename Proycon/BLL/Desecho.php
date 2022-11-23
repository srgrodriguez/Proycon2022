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
    }elseif ($_GET['opc'] == 'registrarPedido') {
        if (!isset($_SESSION)) {
            session_start();
        }
        GuardarPedido($_SESSION['ID_Usuario'],$_POST['data'],$_POST['fecha'],$_POST['motivo'],$_POST['consecutivo']);
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
                    . "<td class='hidden'>" . $fila['id'] . "</td>"
                    . "<td>" . $fila['Descripcion'] . "</td>"
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
                    . "<td class='hidden'>" . $fila['id'] . "</td>"
                    . "<td>" . $fila['Descripcion'] . "</td>"
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
                    . "<td class='hidden'>" . $fila['id'] . "</td>"
                    . "<td>" . $fila['Descripcion'] . "</td>"
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
        $data = $item['codigo'];
    }; 


}
   
    
    
    /*if ($TipoPedido == 1) {
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
    }*/
//}

