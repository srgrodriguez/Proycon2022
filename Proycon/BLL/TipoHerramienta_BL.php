<?php

require_once 'Autorizacion.php';
require_once '../DAL/Conexion.php';
require_once '../DAL/Log.php';
require_once '../DAL/Interfaces/IMaquinaria.php';
require_once '..//DAL/Metodos/MTipoHerramienta.php';
require_once '../DATA/Resultado.php';
require_once '../DATA/TipoMaquinaria.php';

//Autorizacion();
if (isset($_GET['opc'])) {
    $opc = $_GET['opc'];
    switch ($opc) {
        case "agregarTipoHerramienta":AgregarTipoMaquinaria();break;
        case "actualizarTipoHerramienta":ActualizarTipoHerramienta();break;
        case "listarTipoHerramienta":ListarTipoHerramientas();break;
        default:
            break;
    }
}


function AgregarTipoMaquinaria()
{
    $tipoMaquinaria = new TipoHerramienta();
    try {
        $bdMaquinaria = new MTipoHerramienta();
        $request  = json_decode(file_get_contents('php://input'));
        $tipoMaquinaria->Descripcion = $request->descripcion;
        $tipoMaquinaria->Precio = $request->precio;
        $tipoMaquinaria->TipoEquipo = "M"; //M= maquinaria; H= HERRAMIENTA
        $resultado =  $bdMaquinaria->AgregarTipoHerramienta($tipoMaquinaria);
        echo json_encode($resultado);
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "BuscarHerramientasPedido"));
    }
}

function ActualizarTipoHerramienta()
{
    $tipoMaquinaria = new TipoHerramienta();
    try {
        $bdMaquinaria = new MTipoHerramienta();
        $request  = json_decode(file_get_contents('php://input'));
        $tipoMaquinaria->IDTipo = $request->id;
        $tipoMaquinaria->Descripcion = $request->descripcion;
        $tipoMaquinaria->Precio = $request->precio;
        $tipoMaquinaria->TipoEquipo = $request->tipoEquipo; //M= maquinaria; H= HERRAMIENTA
        $resultado =  $bdMaquinaria->ActualizarTipoHerramienta($tipoMaquinaria);
        echo json_encode($resultado);
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "BuscarHerramientasPedido"));
    }
}

function ListarTipoHerramientas()
{
    try {

        $request  = json_decode(file_get_contents('php://input'));
        $dbHerramientas = new MTipoHerramienta();
        $resultado = $dbHerramientas->listarTipoHerramientas($request->tipoEquipo);
        if ($resultado != null && mysqli_num_rows($resultado) > 0) {
            $resultadoHTML = '';
            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {

                $resultadoHTML .= "<tr>
                            <td>" . $fila['ID_Tipo'] . "</td>
                            <td>" . $fila['Descripcion'] . "</td>
                            <td>" . $fila['PrecioEquipo'] . "</td>
                            <td>" . $fila['TipoEquipo'] . "</td>
                            <td style='text-align: center'>
                                    <button class='btn btn-default' onclick='EditarTipoHerramienta(this)'>
                                        <img src='../resources/imagenes/Editar.png' width='20px' alt=''/>
                                    </button>
                                    </td>
                            </tr>";
            }
            echo $resultadoHTML;
        }
        else
         echo "No hay datos para mostrar"; 

    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "BuscarHerramientasPedido"));
    }
}

