<?php

require_once 'Autorizacion.php';
require_once '../DAL/Conexion.php';
require_once '../DAL/Log.php';
require_once '../DAL/Interfaces/ITipoHerramienta.php';
require_once '..//DAL/Metodos/MTipoHerramienta.php';
require_once '../DATA/Resultado.php';
require_once '../DATA/TipoMaquinaria.php';
require_once '../DAL/FuncionesGenerales.php';
require_once '../DAL/Constantes.php';

//Autorizacion();
if (isset($_GET['opc'])) {
    $opc = $_GET['opc'];
    switch ($opc) {
        case "agregar":
            AgregarTipoHerramienta();
            break;
        case "actualizar":
            ActualizarTipoHerramienta();
            break;
        case "listar":
            ListarTipoHerramientas();
            break;
        case "eliminar":
            EliminarTipoHerramienta();
            break;
        default:
            break;
    }
}


function AgregarTipoHerramienta()
{
    $tipoMaquinaria = new TipoHerramienta();
    try {
        $bdMaquinaria = new MTipoHerramienta();
        $request  = json_decode(file_get_contents('php://input'));
        $tipoMaquinaria->Descripcion = $request->descripcion;
        $tipoMaquinaria->Precio = $request->precio;
        $tipoMaquinaria->TipoEquipo = Constantes::TipoEquipoMaquinaria; //M= maquinaria; H= HERRAMIENTA
        $tipoMaquinaria->CodigoFormadeCobro =$request->codigoFormadeCobro;
        $tipoMaquinaria->MonedaCobro =$request->monedaCobro;

        $resultado =  $bdMaquinaria->AgregarTipoHerramienta($tipoMaquinaria);
        echo json_encode($resultado);
    } catch (\Throwable $ex) {
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
        $tipoMaquinaria->CodigoFormadeCobro=$request->codigoFormadeCobro;
        $tipoMaquinaria->MonedaCobro= $request->monedaCobro;
        $resultado =  $bdMaquinaria->ActualizarTipoHerramienta($tipoMaquinaria);
        echo json_encode($resultado);
    } catch (\Throwable $ex) {
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
                            <td>" . $fila['DescripcionFormaDeCobro'] . "</td>
                            <td>" . ObtenerDescripcionMoneda($fila['CodigoMonedaCobro'] ). "</td>
                            <td style='display:none'>" . $fila['TipoEquipo'] . "</td>                           
                            <td style='display:none'>" . $fila['CodigoMonedaCobro'] . "</td>
                            <td style='text-align: center'>
                                    <button class='btn btn-default' onclick='EditarTipoHerramienta(this)'>
                                        <img src='../resources/imagenes/Editar.png' width='20px' alt=''/>
                                    </button>
                                    </td>
                            </tr>";
            }
            echo $resultadoHTML;
        } else
            echo "No hay datos para mostrar";
    } catch (\Throwable $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "BuscarHerramientasPedido"));
    }
}

function EliminarTipoHerramienta()
{

    try {
        $bdMaquinaria = new MTipoHerramienta();
        $request  = json_decode(file_get_contents('php://input'));
        $resultado =  $bdMaquinaria->EliminarTipoHerramienta($request->id);
        echo json_encode($resultado);
    } catch (\Throwable $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "BuscarHerramientasPedido"));
    }
}

function ObtenerDescripcionMoneda($codigoMoneda){

    if($codigoMoneda != null && $codigoMoneda != "")
    {
      if($codigoMoneda == "C")
        return "Colones";
      else
        return "Dólares";
    }
    return "";
}
