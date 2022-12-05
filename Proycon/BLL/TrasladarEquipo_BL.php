<?php
require_once 'Autorizacion.php';
require_once '../DAL/Conexion.php';
require_once '../DAL/Log.php';
require_once '../DAL/Interfaces/ITrasladarEquipo.php';
require_once '..//DAL/Metodos/MTrasladarEquipo.php';
require_once '../DATA/Resultado.php';
require_once '../DATA/TrasladarEquipo.php';
require_once '../DAL/FuncionesGenerales.php';
require_once '../DAL/Constantes.php';

if (!isset($_SESSION))
    session_start();

Autorizacion();
if (isset($_GET['opc'])) {
    $opc = $_GET['opc'];
    switch ($opc) {
        case "trasladar":
            TrasdalarEquipo();
            break;
        case "consultarEquipo":
            ConsultarEquipoTrasladar();
            break;
        case "consecutivoBoleta":
           echo ObternerCosecutivoBoleta();
            break;
        default:
            break;
    }
}

function TrasdalarEquipo()
{
    $equipoTrasladar = [];
    $restultado =  new Resultado();
    try {
        $consecutivoBoleta = ObternerCosecutivoBoleta();
        $bdTransladarEquipo = new MTrasladarEquipo();
        $request  = json_decode(file_get_contents('php://input'));
        foreach ($request as &$equipo) {
            $trasladar = new TrasladarEquipo();
            $trasladar->NumBoleta =  $consecutivoBoleta;
            $trasladar->CodigoEquipo = $equipo->codigo;
            $trasladar->IdProyectoDestino = $equipo->idUbicacionDestino;
            $trasladar->IdUsuario = $_SESSION['ID_Usuario'];
            array_push($equipoTrasladar, $trasladar);
        }

        $restultado =  $bdTransladarEquipo->TrasdalarEquipo($equipoTrasladar);
    } catch (\Throwable $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "TrasdalarEquipo"));
    }

    echo json_encode($restultado);
}

function ConsultarEquipoTrasladar()
{
    try {
        $bdTransladarEquipo = new MTrasladarEquipo();
        $request  = json_decode(file_get_contents('php://input'));
        $resultado =  $bdTransladarEquipo->ConsultarMaquinariaTrasladar($request->codigo, $request->idTipo, $request->ubicacion, $request->tipoEquipo);
        if ($resultado != null && mysqli_num_rows($resultado) > 0) {
            $resultadoHTML = '';
            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $resultadoHTML .= "<tr>
            <td>" . $fila["Codigo"] . "</td>
            <td>" . $fila["Tipo"] . "</td>
            <td>" . $fila["Ubicacion"] . "</td>
            <td><input type='checkbox' onClick='SeleccionarEquipoTrasladar(this)'></td>
            </tr>";
            }
            echo $resultadoHTML;
        } else {
            echo "No se encontraron resultados";
        }
    } catch (\Throwable $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "TrasdalarEquipo"));
    }
}

function ObternerCosecutivoBoleta()
{
    $bdHerramientas = new MTrasladarEquipo();
    $result = $bdHerramientas->ObternerCosecutivoBoleta();
    if (mysqli_num_rows($result) > 0) {
        $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $fila['Consecutivo'] + 1;
    } else {
        return 1;
    }
}
