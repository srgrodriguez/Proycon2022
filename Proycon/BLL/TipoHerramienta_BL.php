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

Autorizacion();
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
        case "consultar":
                ConsultarTipoHerramientaPorID();
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
        $tipoMaquinaria->Precio = str_replace(",","",$request->precio);
        $tipoMaquinaria->TipoEquipo =$request->tipoEquipo; //M= maquinaria; H= HERRAMIENTA
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
                  $tipoEquipo = $fila['TipoEquipo'];
                  $idTipo = $fila['ID_Tipo'];

                $resultadoHTML .= "<tr>
                            <td style='display:none'>" . $idTipo . "</td>
                            <td>" . $fila['Descripcion'] . "</td>
                            <td>".number_format($fila['PrecioEquipo'], 2, ",", ".")."</td>
                            <td>" .ObtenerDescripcionMoneda($fila['CodigoMonedaCobro'] )  . "</td>
                            <td>" . $fila['DescripcionFormaDeCobro']. "</td>
                            <td style='display:none'>" . $tipoEquipo . "</td>                           
                            <td style='display:none'>" . $fila['CodigoMonedaCobro'] . "</td>
                            <td style='text-align: center'>
                                    <button class='btn btn-default' onclick=\"EditarTipo($idTipo)\">
                                        <img src='../resources/imagenes/Editar.png' width='15px' alt=''/>
                                    </button>
                                    </td>
                                    <td style='text-align: center'>
                                    <button type='button' class='btn btn-danger' onclick=\"EliminarTipoHerramienta($idTipo,'$tipoEquipo')\" >
                                    <img src='../resources/imagenes/Eliminar.png' width='15px' alt=''/>
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

function ConsultarTipoHerramientaPorID(){
    
    try {
        $bdMaquinaria = new MTipoHerramienta();
        $request  = json_decode(file_get_contents('php://input'));
        $resultado =  $bdMaquinaria->ConsultarTipoHerramientaPorID($request->id);
        $tipoHerramienta =  mysqli_fetch_array($resultado);
        echo json_encode($tipoHerramienta);
    } catch (\Throwable $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "ConsultarTipoHerramientaPorID"));
    }
}

 function CargarComboBoxTipoHerramienta($tipoEquipo)
 {

    $bdMaquinaria = new MTipoHerramienta();
   $resultado =  $bdMaquinaria->CargarComboBoxTipoHerramienta($tipoEquipo);
   $resultadoHTML = "<select id='comboHerramientaTipo' name='comboHerramientaTipo' class='form-control ' > 
                       <option value='0' selected='true'>Seleccione el tipo de herramienta</option>";
   if ($resultado != null && mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $resultadoHTML.= "<option value='".$fila["ID_Tipo"]."' >".$fila['Descripcion']."</option>";
    }
   }
   $resultadoHTML.= "</select>";

   echo $resultadoHTML;

 }

 function CargarComboBoxFormaCobroHerramienta()
 {

    $bdMaquinaria = new MTipoHerramienta();
   $resultado =  $bdMaquinaria->CargarComboBoxFormaCobroHerramienta();
   $resultadoHTML = "<select id='cboFormaCobro' name='cboFormaCobro' class='form-control ' > 
                       <option value='0' selected='true'>Seleccione la forma de cobro</option>";
   if ($resultado != null && mysqli_num_rows($resultado) > 0) {
    while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $resultadoHTML.= "<option value='".$fila["CodigoFormaCobro"]."' >".$fila['DescripcionFormaDeCobro']."</option>";
    }
   }
   $resultadoHTML.= "</select>";

   echo $resultadoHTML;

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
