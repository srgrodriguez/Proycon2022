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

//Autorizacion();
if (isset($_GET['opc'])) {
    $opc = $_GET['opc'];
    switch ($opc) {
        case "trasladar":
            TrasdalarEquipo();
            break;
        default:
            break;
    }
} else {
    throw new Exception("Parametro no valido");
}
function TrasdalarEquipo()
{
    $equipoTrasladar = [];
    $restultado =  new Resultado();
    try {
        $bdTransladarEquipo = new MTrasladarEquipo();
        $request  = json_decode(file_get_contents('php://input'));
        foreach ($request as &$equipo) {
            $trasladar = new TrasladarEquipo();
            $trasladar->NumBoleta= $equipo->numBoleta;
            $trasladar->CodigoEquipo= $equipo->codigoEquipo;
            $trasladar->IdProyectoDestino = $equipo->idProyectoDestino;
            $trasladar->TipoEquipo = $equipo->tipoEquipo;
            $trasladar->IdUsuario = $equipo->idUsuario;
            array_push($equipoTrasladar,$trasladar);
        }

        $restultado =  $bdTransladarEquipo->TrasdalarEquipo($equipoTrasladar);    

    } catch (\Throwable $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "TrasdalarEquipo"));
    }

   echo json_encode($restultado);
}
