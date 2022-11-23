<?php
require_once 'Autorizacion.php';
require_once '../DAL/Conexion.php';
require_once '../DAL/Log.php';
require_once '../DAL/Interfaces/IMaquinaria.php';
require_once '../DAL/Metodos/MMaquinaria.php';
require_once '../DAL/Metodos/MBitacora.php';
require_once '../DATA/Resultado.php';
require_once '../DATA/Herramientas.php';
require_once '../DAL/FuncionesGenerales.php';
require_once '../DAL/Constantes.php';
require_once '../DAL/Metodos/MArchivos.php';

if (!isset($_SESSION)) {
    session_start();
}
Autorizacion();
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
        case "bucarPorCodigoGetHtml":
            BuscarPorCodigoOuputHrml();
            break;
        case "fichaTecnica":
            ConultarFichaTecinica();
            break;
        default:
            break;
    }
} else {
    //throw new Exception("Parametro no valido");
}

function AgregarMaquinaria()
{
    $maquinaria = new Herramientas();
    try {
        $bdMaquinaria = new MMaquinaria();
        $archivo_nombre = $_FILES['archivo']['name'];
        $archivo_temp =  $_FILES['archivo']['tmp_name'];
        $archivo_binario = (file_get_contents($archivo_temp));
        $maquinaria->codigo = $_POST["codigo"];
        $maquinaria->tipo = $_POST["tipo"];
        $maquinaria->marca = $_POST["marca"];
        $maquinaria->descripcion = $_POST["descripcion"];
        $maquinaria->fechaIngreso = $_POST["fechaIngreso"];
        $maquinaria->estado = 1;
        $maquinaria->disposicion = 1;
        $maquinaria->procedencia = $_POST["procedencia"];
        $maquinaria->ubicacion = Constantes::Bodega;
        $maquinaria->precio = $_POST["precio"];
        $maquinaria->numFactura = $_POST["numFactura"];
        $maquinaria->nombreArchivo = $archivo_nombre == null ? "" : $archivo_nombre;
        $maquinaria->archivoBinario = $archivo_nombre == null ? "" :   $archivo_binario;
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
            echo  GenerarHtmlListaMaquinaria($resultado);
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
        $request  = json_decode(file_get_contents('php://input'));
        $existeMaquinaria = $bdMaquinaria->BuscarMaquinariaPorCodigo($request->codigo);
        if (mysqli_num_rows($existeMaquinaria)  < 1) {
            $resultado = new Resultado();
            $resultado->esValido = false;
            $resultado->mensaje = "No existen registros para eliminar";
            echo json_encode($resultado);
        } else {
            $bdMaquinaria = new MMaquinaria();
            $resultado =  $bdMaquinaria->EliminarMaquinaria($request->codigo, $request->motivo);
            echo json_encode($resultado);
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
            echo  GenerarHtmlListaMaquinaria($resultado);
        } else {
            echo "<h2>No se encontraron Resultados :( </h2>";
        }
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "listarTotalMaquinaria"));
    }
}

function BuscarPorCodigoOuputHrml()
{
    try {

        $bdMaquinaria = new MMaquinaria();
        $request  = json_decode(file_get_contents('php://input'));
        $resultado = $bdMaquinaria->BuscarMaquinariaPorCodigo($request->codigo);
        $resultadoHTML = '';
        if ($resultado != null && mysqli_num_rows($resultado)  < 1) {
            $resultadoHTML .= "<h3>El codigo ingresado no corresponde al de un equipo registrado</h3>";
        } else {
            echo  GenerarHtmlListaMaquinaria($resultado);
        }
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "ActualizarMaquinaria"));
    }
}
function BuscarPorCodigoOuputJson()
{

}

function ConultarFichaTecinica()
{
 $bdArchivo = new MArchivos();
   $resultado = $bdArchivo ->ConsultarArchivo($_GET['Id']);
    while($row = mysqli_fetch_array($resultado)){
        $archivo= $row[0]; //obtener el archivo
        $nombre=$row[1]; //obtener el nombre del archivo
      }
      

      
      //header para tranformar la salida en el tipo de archivo que hemos guardado
      header("Content-type: application/pdf"); 
      header('Content-Disposition: attachment; filename="'.$nombre.'"');
      
      //imprimir el archivo
      echo strval($archivo);
}

function GenerarHtmlListaMaquinaria($resultado)
{
    $resultadoHTML = '';
    while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $Monto = "¢" . number_format($fila['Precio'], 2, ",", ".");
        $monedaCorbo = $fila['CodigoMonedaCobro'] == "C" ? "¢" : "$";
        $PrecioAlquiler =  number_format($fila['PrecioEquipo'], 2, ",", ".");
        $Fecha = date('d/m/Y', strtotime($fila['FechaIngreso']));

        $btnVerFichaTecnica = " <button class='btn btn-default' disabled onclick='VerFichaTecnica(\"" . $fila['Codigo'] . "\"," . $fila['ID_Archivo'] . ")'>
                                 Ficha técnica
                                 </button>";

        if ($fila['ID_Archivo'] != null || $fila['ID_Archivo'] != "")
               $btnVerFichaTecnica =  str_replace("disabled", "", $btnVerFichaTecnica);

        if ($fila['numEstado'] == Constantes::EstadoOK) {
            if ($_SESSION['ID_ROL'] == Constantes::RolBodega || $_SESSION['ID_ROL'] == Constantes::RolAdminBodega) {
                $eliminarMaquinaria =  "<td style='text-align: center'>
             <button type='button' class='btn btn-danger' onclick=\"AbrirModalEliminarMaquinaria('" . $fila['Codigo'] . "')\" >
             <img src='../resources/imagenes/Eliminar.png' width='15px' alt=''/>
             </button>
              </td>";
            }
            $resultadoHTML .= "<tr>
                    <td>" . $fila['Codigo'] . "</td>
                    <td style='text-align: left'>" . $fila['Tipo'] . "</td>
                    <td style='text-align: right'>" .  $monedaCorbo . $PrecioAlquiler . "</td>    
                    <td>" . $Fecha . "</td>
                    <td style='text-align: right'>" . $Monto . "</td>
                    <td>" . $fila['Disposicion'] . "</td>
                    <td>" . $fila['Ubicacion'] . "</td>
                    <td>" . $fila['Estado'] . "</td>
                    <td style='text-align: center'>
                          ". $btnVerFichaTecnica."
                    </td>
                             $eliminarMaquinaria
                   </tr>";
        } else {
            $resultadoHTML .= "<tr>
                    <td class='usuarioBolqueado'>" . $fila['Codigo'] . "</td>
                    <td class='usuarioBolqueado' style='text-align: left'>" . $fila['Tipo'] . "</td>
                    <td class='usuarioBolqueado' style='text-align: right'>" .  $monedaCorbo . $PrecioAlquiler . "</td>
                    <td class='usuarioBolqueado'>" . $Fecha . "</td>
                    <td class='usuarioBolqueado' style='text-align: right'>" . $Monto . "</td>
                    <td class='usuarioBolqueado'>" . $fila['Disposicion'] . "</td>
                    <td class='usuarioBolqueado'>" . $fila['Ubicacion'] . "</td>
                    <td class='usuarioBolqueado'>" . $fila['Estado'] . "</td>
                    <td class='usuarioBolqueado' style='text-align: center'>
                      ". $btnVerFichaTecnica."
                     </td>
                   </tr>";
        }
    }
    return $resultadoHTML;
}
