<?php
require_once 'Autorizacion.php';
require_once '../DAL/Conexion.php';
require_once '../DAL/Log.php';
require_once '../DAL/Interfaces/IMaquinaria.php';
require_once '..//DAL/Metodos/MMaquinaria.php';
require_once '../DATA/Resultado.php';
require_once '../DATA/Herramientas.php';
require_once '../DAL/FuncionesGenerales.php';
require_once '../DAL/Constantes.php';

//Autorizacion();
if (isset($_GET['opc'])) {
    $opc = $_GET['opc'];
    switch ($opc) {
        case "agregar":
            AgregarMaquinaria();
            break;
        case "actualizar":
            ActualizarMaquinaria();
            break;
        case "listar":
            ListarTotalMaquinaria();
            break;
        case "eliminar":
            EliminarMaquinaria();
            break;
        case "buscarTiempoReal":
            BuscarMaquinariaEnTiempoReal();
            break;
        default:
            break;
    }
} else {
    throw new Exception("Parametro no valido");
}

function AgregarMaquinaria()
{
    $maquinaria = new Herramientas();
    try {
        $bdMaquinaria = new MMaquinaria();
        $request  = json_decode(file_get_contents('php://input'));
        $maquinaria->codigo =  $request->codigo;
        $maquinaria->tipo =  $request->tipo;
        $maquinaria->marca = $request->marca;;
        $maquinaria->descripcion = $request->descripcion;;
        $maquinaria->fechaIngreso = $request->fechaIngreso;;
        $maquinaria->estado = 1;
        $maquinaria->disposicion = 1;
        $maquinaria->procedencia = $request->procedencia;
        $maquinaria->ubicacion = $request->ubicacion;
        $maquinaria->precio = $request->precio;
        $maquinaria->numFactura = $request->numFactura;
        $existeMaquinaria = $bdMaquinaria->BuscarMaquinariaPorCodigo($maquinaria->codigo);
        if (mysqli_num_rows($existeMaquinaria) > 0) {
            $resultado = new Resultado();
            $resultado->esValido = false;
            $resultado->mensaje = "La maquinaria que intenta agregar ya existe";
            echo json_encode($resultado);
        } else {
            $bdMaquinaria = new MMaquinaria(); //Se vuelve a generar la instancia para abrir la conexion con la BD
            $resultado =  $bdMaquinaria->AgregarMaquinaria($maquinaria);
            echo json_encode($resultado);
        }
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "AgregarMaquinaria"));
    }
}

function ActualizarMaquinaria()
{
    $maquinaria = new Herramientas();
    try {
        $bdMaquinaria = new MMaquinaria();
        $request  = json_decode(file_get_contents('php://input'));
        $maquinaria->idHerramienta =  $request->idHerramienta;
        $maquinaria->codigo =  $request->codigo;
        $maquinaria->tipo =  $request->tipo;
        $maquinaria->marca = $request->marca;
        $maquinaria->descripcion = $request->descripcion;
        $maquinaria->fechaIngreso = $request->fechaIngreso;
        $maquinaria->estado = $request->estado;
        $maquinaria->disposicion = $request->disposicion;
        $maquinaria->procedencia = $request->procedencia;
        $maquinaria->ubicacion = $request->ubicacion;
        $maquinaria->precio = $request->precio;
        $maquinaria->numFactura = $request->numFactura;
        $resultado =  $bdMaquinaria->ActualizarMaquinaria($maquinaria);
        echo json_encode($resultado);
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "ActualizarMaquinaria"));
    }
}

function ListarTotalMaquinaria()
{

    try {
        $bdMaquinaria = new MMaquinaria();
        $resultado = $bdMaquinaria->ListarTotalMaquinaria();
        if ($resultado != null) {
            $resultadoHTML = '';
            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $Monto = "¢" . number_format($fila['Precio'], 2, ",", ".");
                $Fecha = date('d/m/Y', strtotime($fila['FechaIngreso']));
                if ($fila['numEstado'] == Constantes::EstadoOK) {

                    $resultadoHTML .= "<tr>
                            <td>" . $fila['Codigo'] . "</td>
                            <td style='text-align: left'>" . $fila['Tipo'] . "</td>
                            <td style='text-align: left'>" . $fila['Descripcion'] . "</td>    
                            <td>" . $Fecha . "</td>
                            <td style='text-align: right'>" . $Monto . "</td>
                            <td>" . $fila['Disposicion'] . "</td>
                            <td>" . $fila['Ubicacion'] . "</td>
                            <td>" . $fila['Estado'] . "</td>
                            <td style='text-align: center'>
                                    <button class='btn btn-default' onclick='VerDetalleHerramienta(this)'>
                                      Ver detalle
                                    </button>
                            </td>
                           </tr>";
                } else {
                    $resultadoHTML .= "<tr>
                            <td class='usuarioBolqueado'>" . $fila['Codigo'] . "</td>
                            <td class='usuarioBolqueado' style='text-align: left'>" . $fila['Tipo'] . "</td>
                            <td class='usuarioBolqueado' style='text-align: left'>" . $fila['Descripcion'] . "</td> 
                            <td class='usuarioBolqueado'>" . $Fecha . "</td>
                <td class='usuarioBolqueado' style='text-align: right'>" . $Monto . "</td>
                            <td class='usuarioBolqueado'>" . $fila['Disposicion'] . "</td>
                            <td class='usuarioBolqueado'>" . $fila['Ubicacion'] . "</td>
                            <td class='usuarioBolqueado'>" . $fila['Estado'] . "</td>
                            <td class='usuarioBolqueado' style='text-align: center'>
                            <button class='btn btn-default' onclick='VerDetalleHerramienta(this)'>
                               Ver detalle
                            </button>
                </td>
                           </tr>";
                }
            }
            echo $resultadoHTML;
        } else
            echo 'No hay datos para mostara';
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "listarTotalMaquinaria"));
    }
}

function EliminarMaquinaria()
{
    try {

        $bdMaquinaria = new MMaquinaria();
        if (isset($_GET['codigo'])) {
            $codigo = $_GET['codigo'];
            $existeMaquinaria = $bdMaquinaria->BuscarMaquinariaPorCodigo($codigo);
            if (mysqli_num_rows($existeMaquinaria)  < 1) {
                $resultado = new Resultado();
                $resultado->esValido = false;
                $resultado->mensaje = "No existen registros para eliminar";
                echo json_encode($resultado);
            } else {
                $bdMaquinaria = new MMaquinaria();
                $resultado =  $bdMaquinaria->EliminarMaquinaria($codigo);
                echo json_encode($resultado);
            }
        } else {
            throw new Exception("Codigo vacio");
        }
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "ActualizarMaquinaria"));
    }
}

function BuscarMaquinariaEnTiempoReal()
{
    try {

        $bdMaquinaria = new MMaquinaria();
        $request  = json_decode(file_get_contents('php://input'));
        $strConsulta = $request->consulta;
        $resultado =  $bdMaquinaria->BuscarMaquinariaEnTiempoReal($strConsulta);

        if (mysqli_num_rows($resultado) > 0) {
            $resultadoHTML = "";
            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {

                $Fecha = date('d/m/Y', strtotime($fila['FechaIngreso']));
                $Monto = "¢" . number_format($fila['Precio'], 2, ",", ".");
                $resultadoHTML .= "<tr>
                        <td>" . $fila['Codigo'] . "</td>
                        <td>" . $fila['Tipo'] . "</td>
                         <td>" . $fila['Descripcion'] . "</td>
                        <td>" . $Fecha . "</td>
                        <td>" . $Monto . "</td>
                        <td>" . $fila['Disposicion'] . "</td>
                        <td>" . $fila['Nombre'] . "</td>
                        <td>" . $fila['Estado'] . "</td>
                        <td style='text-align: center'>
                                    <button class='btn btn-default' onclick='VerDetalleHerramienta(this)'>
                                       Ver detalle
                                    </button>
                        </td>
                    </tr>";
            }
            echo $resultadoHTML;
        } else {
            echo "<h2>No se encontraron Resultados :( </h2>";
        }
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "listarTotalMaquinaria"));
    }
}
