<?php
require_once 'Autorizacion.php';
require_once '../DAL/Conexion.php';
require_once '../DAL/Log.php';
require_once '../DAL/Metodos/MHistoria_Y_ReparacionesMaquinaria.php';
require_once '../DATA/Resultado.php';
require_once '../DATA/Factura.php';
require_once '../DAL/FuncionesGenerales.php';
require_once '../DAL/Constantes.php';
require_once 'Maquinaria_BL.php';



if (!isset($_SESSION))
    session_start();

Autorizacion();
if (isset($_GET['opc'])) {
    $opc = $_GET['opc'];
    switch ($opc) {
        case "getHistorial":
            ConsultarHistorialMaquinaria();
            break;
        case "getReparaciones":
            ConsultarReparacionesMaquinaria();
            break;
        case "getObtenerInfoMaquinaria":
            ConsultarMaquinariaPorCodigo();
            break;
        case "estaEnReparacion":
            EstaEnReparacion();
            break;
        case "enviarReparacion":
            EnviarMaquinariaReparacion();
            break;
        case "facturarReparacion":
            FacturacionReparacionMaquinaria();
            break;
        case "consultarReparacion":
            ConsultarTodaMaquinariaReparacion();
            break;
        case "consultarReparacionPorCodigo":
            ConsultarMaquinariaReparacionPorCodigo();
            break;
        case "filtrarPorTipoMaquinaria":
            ConsultarMaquinariaReparacionPorTipo();
            break;
        case "consultarBoletasReparacion":
            ConsultarTodasLasBoletasReparacionMaquinaria();
            break;
        case "consultarBoletasReparacionPorNumBoleta":
            ConsultarTodasLasBoletasReparacionMaquinariaPorNumBoleta();
            break;
        case "VerBoletaReparacion":
             VerBoletaReparacion();
            break;
            case "AnularBoletaReparacion":
                AnularBoletaReparacion();
               break;
        default:
            break;
    }
}
function ConsultarHistorialMaquinaria()
{
    try {
        $bd = new MHistoria_Y_ReparacionesMaquinaria();
        $codigo =   $_GET["codigo"];
        $resultado = $bd->ConsultarHistorialMaquinaria($codigo);
        if ($resultado != null) {
            $resultadoHTML = '';
            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $Fecha = date('d/m/Y', strtotime($fila['Fecha']));
                if ($fila['Destino'] == "") {
                    $resultadoHTML .= "<tr>
                        <td>" . $Fecha . "</td>
                        <td>" . $fila['NumBoleta'] . "</td>
                        <td>" . $fila['Ubicacion'] . "</td>
                        <td>" . "En reparacion" . "</td>       
						</tr>";
                } else {

                    if ($fila['Ubicacion'] == "") {
                        $resultadoHTML .= "<tr>
                        <td>" . $Fecha . "</td>
                        <td>" . $fila['NumBoleta'] . "</td>
                        <td>" . "En reparacion" . "</td>
                        <td>" . $fila['Destino'] . "</td>       
						</tr>";
                    } else {
                        $resultadoHTML .= "<tr>
                        <td>" . $Fecha . "</td>
                        <td>" . $fila['NumBoleta'] . "</td>
                        <td>" . $fila['Ubicacion'] . "</td>
                        <td>" . $fila['Destino'] . "</td>       
						</tr>";
                    }
                }
            }
            echo $resultadoHTML;
        }

        //code...
    } catch (\Throwable $th) {
        echo  json_encode(Log::GuardarEvento($th, "ConsultarHistorialMaquinaria"));
    }
}

function ConsultarReparacionesMaquinaria()
{
    try {
        $bd = new MHistoria_Y_ReparacionesMaquinaria();
        $codigo =   $_GET["codigo"];
        $resultado = $bd->ConsultarReparacionesMaquinaria($codigo);
        if ($resultado != null) {
            $concatenar = '';
            $total = 0;
            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $Monto = "¢" . number_format($fila['MontoReparacion'], 2, ",", ".");
                $Fecha = date('d/m/Y', strtotime($fila['FechaEntrada']));
                $concatenar .= "<tr>
                             <td>" . $Fecha . "</td>
                            <td>" . $fila['ID_FacturaReparacion'] . "</td>
                            <td>" . $fila['Descripcion'] . "</td>
                            <td>" . $Monto . "</td>         
                            </tr>";
                $total = $total + $fila['MontoReparacion'];
            }
            $MontoTotal = "¢" . number_format($total, 2, ",", ".");
            $concatenar .= "<tr>
                             <td></td>
                            <td></td>
                            <td><strong>TOTAL</strong></td>
                            <td><strong>$MontoTotal</strong></td>";
            echo $concatenar;
        }
    } catch (\Throwable $th) {
        echo  json_encode(Log::GuardarEvento($th, "ConsultarReparacionesMaquinaria"));
    }
}

function ConsultarMaquinariaPorCodigo()
{
    try {
        $codigo =   $_GET["codigo"];
        echo  BuscarPorCodigoOuputJson($codigo);
    } catch (\Throwable $th) {
        echo  json_encode(Log::GuardarEvento($th, "ConsultarMaquinariaPorCodigo"));
    }
}

function EnviarMaquinariaReparacion()
{
    try {
        $bd = new MHistoria_Y_ReparacionesMaquinaria();
        $numBoleta = ConsecutivoReparacion();
        $idUsuario = $_SESSION["ID_Usuario"];
        $request  = json_decode(file_get_contents('php://input'));
        $resultado =    $bd->EnviarMaquinariaReparacion($numBoleta, $idUsuario, $request->proveedor, $request->datosReparacion);
        echo json_encode($resultado);
    } catch (\Throwable $th) {
        echo  json_encode(Log::GuardarEvento($th, "EnviarMaquinariaReparacion"));
    }
}


function ConsultarTodaMaquinariaReparacion()
{

    try {
        $bd = new MHistoria_Y_ReparacionesMaquinaria();
        $resultado = $bd->ConsultarTodaMaquinariaReparacion();
        if ($resultado != null) {
            $resultadoHTML = '';
            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $Fecha = date('d/m/Y', strtotime($fila['Fecha']));

                $resultadoHTML .= "<tr>
                            <td>" . $fila['ID'] . "</td>
                            <td>" . $fila['Codigo'] . "</td>
                            <td>" . $fila['Descripcion'] . "</td>
                            <td>" . $Fecha . "</td>
                            <td style='color:red'>" . $fila['Dias'] . "</td>
                            <td>" . $fila['ProveedorReparacion'] . "</td>  
                            <td>" . $fila['Boleta'] . "</td>
                                
                            <td style='text-align: center'>
                            <button onclick=AbrirMondalDatosReparacion(this)>
                            <img src='../resources/imagenes/Editar.png' width='15px' alt=''/>
                            </button>
                            </td>
                           
                            </tr>";
            }
            echo $resultadoHTML;
        }
    } catch (\Throwable $th) {
        echo  json_encode(Log::GuardarEvento($th, "ConsultarTodaMaquinariaReparacion"));
    }
}

function ConsultarMaquinariaReparacionPorCodigo()
{

    try {
        $bd = new MHistoria_Y_ReparacionesMaquinaria();
        $codigo =   $_GET["codigo"];
        $resultado = $bd->ConsultarMaquinariaReparacionPorCodigo($codigo);
        if ($resultado != null) {
            $resultadoHTML = '';
            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $Fecha = date('d/m/Y', strtotime($fila['Fecha']));

                $resultadoHTML .= "<tr>
                            <td>" . $fila['ID'] . "</td>
                            <td>" . $fila['Codigo'] . "</td>
                            <td>" . $fila['Descripcion'] . "</td>
                            <td>" . $Fecha . "</td>
                            <td style='color:red'>" . $fila['Dias'] . "</td>
                            <td>" . $fila['ProveedorReparacion'] . "</td>  
                            <td>" . $fila['Boleta'] . "</td>
                                
                            <td style='text-align: center'>
                            <button onclick=AbrirMondalDatosReparacion(this)>
                            <img src='../resources/imagenes/Editar.png' width='15px' alt=''/>
                            </button>
                            </td>
                           
                            </tr>";
            }
            echo $resultadoHTML;
        }
    } catch (\Throwable $th) {
        echo  json_encode(Log::GuardarEvento($th, "ConsultarTodaMaquinariaReparacion"));
    }
}

function ConsultarMaquinariaReparacionPorTipo()
{
    try {
        $bd = new MHistoria_Y_ReparacionesMaquinaria();
        $idTipo =   $_GET["idTipo"];
        $resultado = $bd->ConsultarMaquinariaReparacionPorTipo($idTipo);
        if ($resultado != null) {
            $resultadoHTML = '';
            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $Fecha = date('d/m/Y', strtotime($fila['Fecha']));

                $resultadoHTML .= "<tr>
                            <td>" . $fila['ID'] . "</td>
                            <td>" . $fila['Codigo'] . "</td>
                            <td>" . $fila['Descripcion'] . "</td>
                            <td>" . $Fecha . "</td>
                            <td style='color:red'>" . $fila['Dias'] . "</td>
                            <td>" . $fila['ProveedorReparacion'] . "</td>  
                            <td>" . $fila['Boleta'] . "</td>
                                
                            <td style='text-align: center'>
                            <button onclick=AbrirMondalDatosReparacion(this)>
                            <img src='../resources/imagenes/Editar.png' width='15px' alt=''/>
                            </button>
                            </td>
                           
                            </tr>";
            }
            echo $resultadoHTML;
        }
    } catch (\Throwable $th) {
        echo  json_encode(Log::GuardarEvento($th, "ConsultarTodaMaquinariaReparacion"));
    }
}

function FacturacionReparacionMaquinaria()
{
    $class = new Factura();

    try {
        $bd = new MHistoria_Y_ReparacionesMaquinaria();
        $request  = json_decode(file_get_contents('php://input'));
        $datosProcesar =  $class->set($request);
        $request->CostoFactura = str_replace(",", "", $request->CostoFactura);
        $resultado =  $bd->FacturacionReparacionMaquinaria($datosProcesar);
        echo json_encode($resultado);
    } catch (\Throwable $th) {
        echo  json_encode(Log::GuardarEvento($th, "FacturacionReparacionMaquinaria"));
    }
}

function EstaEnReparacion($codigo = "")
{
    $codigo =   $_GET["codigo"];
    $bd = new MHistoria_Y_ReparacionesMaquinaria();
    echo  $bd->EstaEnReparacion($codigo);
}

function ConsecutivoReparacion()
{

    $bdHerramienta = new MHistoria_Y_ReparacionesMaquinaria();
    $result = $bdHerramienta->ObternerCosecutivoReparacion();
    $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($fila["NumBoleta"] != null) {
        return $fila["NumBoleta"] + 1;
    } else {
        return 1;
    }
}

function ConsultarTodasLasBoletasReparacionMaquinaria()
{
    try {
        $bd = new MHistoria_Y_ReparacionesMaquinaria();
        $resultado = $bd->ConsultarTodasLasBoletasReparacionMaquinaria();
        if ($resultado != null) {
            $resultadoHTML = '';
            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $Fecha = date('d/m/Y', strtotime($fila['Fecha']));

                $resultadoHTML .= "<tr>
                    <td>" . $fila['NumBoleta'] . "</td>
                    <td>" . $Fecha . "</td>
                    <td>" . $fila['Nombre'] . "</td>                        
                    <td style='text-align: center'>
                        <button class='btn btn-success' onclick=VerBoletaReparacion(this)>
                         Abrir
                        </button>
                    </td>                   
                    </tr>";
            }
            echo $resultadoHTML;
        }
    } catch (\Throwable $th) {
        echo  json_encode(Log::GuardarEvento($th, "ConsultarTodasLasBoletasReparacionMaquinaria"));
    }
}

function ConsultarTodasLasBoletasReparacionMaquinariaPorNumBoleta($numBoleta = "")
{
    try {
        $numBoleta =   $_GET["numBoleta"];
        $bd = new MHistoria_Y_ReparacionesMaquinaria();


        $resultado = $bd->ConsultarTodasLasBoletasReparacionMaquinariaPorNumBoleta($numBoleta);
        if ($resultado != null) {
            $resultadoHTML = '';
            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $Fecha = date('d/m/Y', strtotime($fila['Fecha']));

                $resultadoHTML .= "<tr>
                     <td>" . $fila['NumBoleta'] . "</td>
                     <td>" . $Fecha . "</td>
                     <td>" . $fila['Nombre'] . "</td>                        
                     <td style='text-align: center'>
                     <button class='btn btn-success' onclick=VerBoletaReparacion(this)>
                     Abrir
                    </button>
                     </td>                   
                     </tr>";
            }
            echo $resultadoHTML;
        }
    } catch (\Throwable $th) {
        echo  json_encode(Log::GuardarEvento($th, "ConsultarTodasLasBoletasReparacionMaquinariaPorNumBoleta"));
    }
}

function VerBoletaReparacion($numBoleta = "")
{
    try {
        $bd = new MHistoria_Y_ReparacionesMaquinaria();
        $numBoleta =   $_GET["numBoleta"];
        $result = $bd->VerBoletaReparacion($numBoleta);
        if ($result != null) {
            $resultadoHTML = '';
            while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $resultadoHTML .= "<tr>
                                    <td>" . $fila['Codigo'] . "</td>
                                    <td>" . $fila['Descripcion'] . "</td>
                                    <td>" . $fila['Marca'] . "</td>
                                    <td>" . $fila['proveedor'] . "</td>
                                </tr>";
            }
            echo $resultadoHTML;
        }
    } catch (\Throwable $th) {
        echo  json_encode(Log::GuardarEvento($th, "VerBoletaReparacion"));
    }
}

function AnularBoletaReparacion($numBoleta = "")
{
    try {
        $bd = new MHistoria_Y_ReparacionesMaquinaria();
        $numBoleta =   $_GET["numBoleta"];
        $resultado = $bd->AnularBoletaReparacion($numBoleta);
        echo json_encode($resultado);
    } catch (\Throwable $th) {
        echo  json_encode(Log::GuardarEvento($th, "AnularBoletaReparacion"));
    }
}
