<?php

include '../DAL/Conexion.php';
include '../DATA/Herramientas.php';
include '../DAL/Interfaces/IHerrramientas.php';
include '../DAL/Metodos/MHerramientas.php';
include '../DAL/FuncionesGenerales.php';
include '../DATA/Factura.php';
include 'traslado.php';
require_once 'Autorizacion.php';
require_once '../DAL/Constantes.php';


if (!isset($_SESSION)) {
    session_start();
}
Autorizacion();
if (isset($_GET['opc'])) {
    if ($_GET['opc'] == "buscarTiempoReal") {
        if (isset($_POST['consulta'])) {
            BuscarTiempoRealHerramienta($_POST['consulta']);
        }
    } elseif ($_GET['opc'] == 'listar') {
        listarTotalHerramientas();
    } elseif ($_GET['opc'] == "ListarTrasladoMo") {
        ListarTrasladoMo();
    } elseif ($_GET['opc'] == 'guardarTranslado') {      // GUARDA EL TRANSLADO
        guardarTranslado();
    } elseif ($_GET['opc'] == 'listarTranslado') { // MUESTRA LAS REPARACIONES TOTALES DE LA HERRAMIENTA
        listarTotalHerramientasTranslado();
    } elseif ($_GET['opc'] == 'guardar') {   // Registra las Herramientas
        RegistrarHerramientas();
    } elseif ($_GET['opc'] == "descripcion") {
        BuscarHerramientaNombre($_GET['Descripcion']);
    } elseif ($_GET['opc'] == "guardarTipo") {
        RegistrarTipoHerramienta();
    } elseif ($_GET['opc'] == "listarTipo") {
        listarTipoHerramientas();
    } elseif ($_GET['opc'] == "cambiarTipo") {
        cambiarTipo($_POST['ID_Tipo'], $_POST['DescripcionTipo']);
    } elseif ($_GET['opc'] == "totalReparaciones") {
        totalReparaciones();
    } elseif ($_GET['opc'] == "GuardarTrasladoT") {
        GuardarTrasladoT($_POST['CodigoT']);
    } elseif ($_GET['opc'] == "EliminarTraslado") {
        EliminarTraslado($_POST['CodigoTH']);
    } elseif ($_GET['opc'] == "reparacionesTotales") { ////////////////// CARGA EL TOTAL DE REPARACIONES DE UNA HERRAMIENTA
        reparacionesTotales($_GET['codigo']);
    } elseif ($_GET['opc'] == "trasladosTotales") { ////////////////// CARGA EL TOTAL DE TRASLADOS  DE UNA HERRAMIENTA
        trasladosTotales($_GET['codigo']);
    } elseif ($_GET['opc'] == "InfoHerramienta") { ////////////////// CARGA LA INFORMACION DE LA HERRAMIENTA
        InfoHerramienta($_GET['codigo']);
    } elseif ($_GET['opc'] == "eliminarBoletaR") { ////////////////// 
        eliminarBoletaR($_GET['eliboleta']);
    } elseif ($_GET['opc'] == "registrarReparacion") {
        GuardarReparacion($_POST['consecutivo'], $_POST['fecha'], $_POST['arreglo'], $_POST['proveedorReparacion']);
    } elseif ($_GET['opc'] == 'guardarFactura') {     //  GUARDA LA FACTURA      
        GuardarFactura();
    } elseif ($_GET['opc'] == "listarBoletasReparacion") {
        listarBoletasReparacion();
    } elseif ($_GET['opc'] == "VerBoletaReparacion") {
        VerBoletaReparacion($_GET['NumBoleta']);
    } elseif ($_GET['opc'] == "FiltrosHerramientas0") { // FILTROS DE ORDENAMIENTO DE LA HERRAMIENTA
        FiltrosHerramientas0();
    } elseif ($_GET['opc'] == "FiltrosHerramientas1") {
        FiltrosHerramientas1();
    } elseif ($_GET['opc'] == "FiltrosHerramientas2") {
        FiltrosHerramientas2();
    } elseif ($_GET['opc'] == "FiltrosHerramientas3") {
        FiltrosHerramientas3();
    } elseif ($_GET['opc'] == "FiltrosHerramientas4") {
        FiltrosHerramientas4();
    } elseif ($_GET['opc'] == "FiltrosHerramientas5") {
        FiltrarTipoTotalHerramienta5();
    } elseif ($_GET['opc'] == "FiltroTipoHerramientasT") {    // FILTRO DE TRASLADO POR TIPO
        FiltroTrasladoTipo($_POST['tipo']);
    } elseif ($_GET['opc'] == "listaEnviadas") {    // FILTRO DE TRASLADO POR TIPO
        listaEnviadas($_POST['codigo']);
    } elseif ($_GET['opc'] == "FiltrosHerramientasU") {    // FILTRO DE TRASLADO POR UBICACION
        FiltrosHerramientasU($_POST['ubicacion']);
    } elseif ($_GET['opc'] == "FiltroReparacionboleta") {
        FiltroReparacionboleta($_POST['boleta']);
    } elseif ($_GET['opc'] == "FiltroReparacionTipo") {
        FiltroReparacionTipo($_POST['tipo']);
    } elseif ($_GET['opc'] == "FiltroReparacionCodigo") {
        FiltroReparacionCodigo($_POST['codigo']);
    } elseif ($_GET['opc'] == "FiltroReparacionfecha") {
        FiltroReparacionfecha($_POST['fecha']);
    } elseif ($_GET['opc'] == "buscarherramienCodigo") {
        buscarherramienCodigo($_GET['codigo']);
    } elseif ($_GET['opc'] == "buscarTraslado") {
        buscarTraslado($_GET['codigo']);
    } elseif ($_GET['opc'] == "eliminarHerramienta") {
        EliminarHerramienta();
    }
}

function FiltroReparacionfecha($fecha)
{
    $bdHerramientas = new MHerramientas();
    $result = $bdHerramientas->FiltroReparacionfecha($fecha);


    if ($result != null) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $concatenar .= "<tr>
     
						<td>" . $fila['ID_Reparacion'] . "</td>
                        <td>" . $fila['Codigo'] . "</td>
                        <td>" . $fila['Descripcion'] . "</td>
                        <td>" . $fila['FechaSalida'] . "</td>
                        <td>" . $fila['FechaEntrada'] . "</td>
                        <td>" . $fila['NumBoleta'] . "</td> 
						<td style='text-align: center'>
						<button onclick=FacturaReparacion(this)>
                        <img src='../resources/imagenes/Editar.png' width='15px' alt=''/>
                        </button>
                        </td>  
						</tr>";
        }
        echo $concatenar;
    }
}

function FiltroReparacionboleta($boleta)
{
    $bdHerramientas = new MHerramientas();
    $result = $bdHerramientas->FiltroReparacionboleta($boleta);


    if ($result != null) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $concatenar .= "<tr>
     
						<td>" . $fila['ID_Reparacion'] . "</td>
                        <td>" . $fila['Codigo'] . "</td>
                        <td>" . $fila['Descripcion'] . "</td>
                        <td>" . $fila['FechaSalida'] . "</td>
                        <td>" . $fila['FechaEntrada'] . "</td>
                        <td>" . $fila['NumBoleta'] . "</td> 
						<td style='text-align: center'>
						<button onclick=FacturaReparacion(this)>
                        <img src='../resources/imagenes/Editar.png' width='15px' alt=''/>
                        </button>
                        </td>
						</tr>";
        }
        echo $concatenar;
    }
}

function EliminarTraslado($CodigoTH)
{
    $bdHerramienta = new MHerramientas();
    $resultado = $bdHerramienta->EliminarTraslado($CodigoTH);
    if ($resultado != null) {
        return 1;
    }
}

function FiltroReparacionCodigo($codigo)
{

    $bdHerramientas = new MHerramientas();
    $result = $bdHerramientas->FiltroReparacionCodigo($codigo);


    if ($result != null) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

            $Fecha = date('d/m/Y', strtotime($fila['Fecha']));

            $concatenar .= "<tr>
                            <td>" . $fila['ID'] . "</td>
                           <td>" . $fila['Codigo'] . "</td>
                           <td>" . $fila['Descripcion'] . "</td>
                           <td>" . $Fecha . "</td>
                           <td style='color:red'>" . $fila['Dias'] . "</td>
                            <td>" . $fila['ProveedorReparacion'] . "</td>    
                           <td>" . $fila['Boleta'] . "</td>
                           <td style='text-align: center'>
                           <button onclick=FacturaReparacion(this)>
                           <img src='../resources/imagenes/Editar.png' width='15px' alt=''/>
                           </button>
                           </td>
			</tr>";
        }
        echo $concatenar;
    }
}

function FiltroReparacionTipo($tipo)
{

    $bdHerramientas = new MHerramientas();
    $result = $bdHerramientas->FiltroReparacionTipo($tipo);


    if ($result != null) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $Fecha = date('d/m/Y', strtotime($fila['Fecha']));

            $concatenar .= "<tr>
                         <td>" . $fila['ID'] . "</td>
                        <td>" . $fila['Codigo'] . "</td>
                        <td>" . $fila['Descripcion'] . "</td>
                        <td>" . $Fecha . "</td>
                        <td style='color:red'>" . $fila['Dias'] . "</td>
                        <td>" . $fila['ProveedorReparacion'] . "</td>  
                        <td>" . $fila['Boleta'] . "</td>
						<td style='text-align: center'>
						<button onclick=FacturaReparacion(this)>
                        <img src='../resources/imagenes/Editar.png' width='15px' alt=''/>
                        </button>
						</tr>";
        }
        echo $concatenar;
    }
}

function listaEnviadas($codigo)
{
    $bdHerramientas = new MHerramientas();
    $resultado = $bdHerramientas->listaEnviadas($codigo);

    $valor = mysqli_fetch_array($resultado, MYSQLI_ASSOC);

    echo $valor[0];
}

// FILTRO DE TRASLADO POR TIPO

function FiltroTrasladoTipo($tipo)
{
    $bdHerramientas = new MHerramientas();
    $resultado = $bdHerramientas->FiltroTrasladoTipo($tipo);
    if ($resultado != null) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            $Fecha = date('d/m/Y', strtotime($fila['FechaIngreso']));
            $concatenar .= "<tr>
                        <td>" . $fila['Codigo'] . "</td>
                        <td>" . $fila['Tipo'] . "</td>
                        <td>" . $Fecha . "</td>
                        <td>" . $fila['Disposicion'] . "</td>
                        <td>" . $fila['Nombre'] . "</td>
					
                        <td>" . $fila['Estado'] . "</td>
						<td style='text-align: center'>
						<button onclick=TransladoHerramienta(this)>
                        <img src='../resources/imagenes/Editar.png' width='15px' alt=''/>
                        </button>
						
                       </tr>";
        }
    }
    echo $concatenar;
}

// FILTRO DE TRASLADO POR TIPO

function FiltrosHerramientasU($ubicacion)
{
    $bdHerramientas = new MHerramientas();
    $resultado = $bdHerramientas->FiltrosHerramientasU($ubicacion);
    if ($resultado != null) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            $Fecha = date('d/m/Y', strtotime($fila['FechaIngreso']));
            $concatenar .= "<tr>
                        <td>" . $fila['Codigo'] . "</td>
                        <td>" . $fila['Tipo'] . "</td>
                        <td>" . $Fecha . "</td>
                        <td>" . $fila['Disposicion'] . "</td>
                        <td>" . $fila['Nombre'] . "</td>
					
                        <td>" . $fila['Estado'] . "</td>
						<td style='text-align: center'>
						<button onclick=TransladoHerramienta(this)>
                        <img src='../resources/imagenes/Editar.png' width='15px' alt=''/>
                        </button>
						
                       </tr>";
        }
    }
    echo $concatenar;
}

function FiltrosHerramientas0()
{
    $bdHerramientas = new MHerramientas();
    $result = $bdHerramientas->FiltrosHerramientas0();
    if (mysqli_num_rows($result) > 0) {
      echo   GenerarResultadoHTMLTablaPrincipal($result);
    } else {
        echo '<h2>Nose encontraron Resultdos</h2>';
    }
}

function FiltrosHerramientas1()
{
    $bdHerramientas = new MHerramientas();
    $result = $bdHerramientas->FiltrosHerramientas1();
    if (mysqli_num_rows($result) > 0) {
      echo  GenerarResultadoHTMLTablaPrincipal($result);
    } else {
        echo '<h2>Nose encontraron Resultdos</h2>';
    }
}

function FiltrosHerramientas2()
{
    $bdHerramientas = new MHerramientas();
    $result = $bdHerramientas->FiltrosHerramientas2();
    if (mysqli_num_rows($result) > 0) {
       echo GenerarResultadoHTMLTablaPrincipal($result);
    } else {
        echo '<h2>Nose encontraron Resultdos</h2>';
    }
}

function FiltrosHerramientas3()
{
    $bdHerramientas = new MHerramientas();
    $result = $bdHerramientas->FiltrosHerramientas3();
    if (mysqli_num_rows($result) > 0) {
      echo  GenerarResultadoHTMLTablaPrincipal($result);
    } else {
        echo '<h2>Nose encontraron Resultdos</h2>';
    }
}

function FiltrosHerramientas4()
{
    $bdHerramientas = new MHerramientas();
    $result = $bdHerramientas->FiltrosHerramientas4();
    if (mysqli_num_rows($result) > 0) {
       echo GenerarResultadoHTMLTablaPrincipal($result);
    } else {
        echo '<h2>Nose encontraron Resultdos</h2>';
    }
}

function FiltrarTipoTotalHerramienta5()
{
    $bdHerramientas = new MHerramientas();
    $result = $bdHerramientas->FiltrarTipoTotalHerramienta();
    if (mysqli_num_rows($result) > 0) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $concatenar .= "<tr>
                          <td>" . $fila['Descripcion'] . "</td>
                          <td>" . $fila['Cantidad'] . "</td>" .
                "</tr>";
        }
        echo $concatenar;
    }
}

// GUARDA EL TRANSLADO DE LA HERRAMIENTA A OTRO PROYECTO


function GuardarTranslado()
{

    $ID_Usuario = $_SESSION['ID_Usuario'];
    $Destino = $_POST['Destino'];
    $NumBoleta = $_POST['NumBoleta'];
    $FechaFinal = $_POST['FechaFinal'];
    $resultado = llamarFuncion($Destino, $NumBoleta, $FechaFinal, $ID_Usuario);

    echo ObtenerConsecutivoPedido();
}

// GUARDA LA FACTURA DE LA HERRAMIENTA EN REPACION


function GuardarFactura()
{
    $Facturacion = new Factura();
    $bdFacturacion = new MHerramientas();
    $Facturacion->Codigo = $_POST['Codigo'];
    $Facturacion->ID_Reparacion = $_POST['ID_Reparacion'];
    $Facturacion->NumFactura = $_POST['NumFactura'];
    $Facturacion->FechaEntrada = $_POST['FechaEntrada'];
    $Facturacion->DescripcionFactura = $_POST['DescripcionFactura'];
    $Facturacion->CostoFactura = $_POST['CostoFactura'];
    $Facturacion->NumBoleta = $_POST['NumBoleta'];
    echo $bdFacturacion->FacturacionReparacion($Facturacion);
}

function facturarherramienta($idReparacion)
{
    $dbFacturacionHerramienta = new MHerramientas();
    $resultado = $dbFacturacionHerramienta->FacturaReparacion($idReparacion);
}

function totalReparaciones()
{
    $dbHerramientas = new MHerramientas();
    $resultado = $dbHerramientas->totalReparaciones();
    if ($resultado != null) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            $Fecha = date('d/m/Y', strtotime($fila['Fecha']));

            $concatenar .= "<tr>
                        <td>" . $fila['ID'] . "</td>
                        <td>" . $fila['Codigo'] . "</td>
                        <td>" . $fila['Descripcion'] . "</td>
                        <td>" . $Fecha . "</td>
                        <td style='color:red'>" . $fila['Dias'] . "</td>
                        <td>" . $fila['ProveedorReparacion'] . "</td>  
                        <td>" . $fila['Boleta'] . "</td>
                            
						<td style='text-align: center'>
						<button onclick=FacturaReparacion(this)>
                        <img src='../resources/imagenes/Editar.png' width='15px' alt=''/>
                        </button>
                        </td>
                       
						</tr>";
        }
        echo $concatenar;
    }
}

function cambiarTipo($ID_Tipo, $DescripcionTipo)
{

    $DBCambio = new MHerramientas();
    $resultado = $DBCambio->cambiarTipo($ID_Tipo, $DescripcionTipo);
}

function GuardarReparacion($consecutivo, $fecha, $arreglo, $provedorReparacion)
{
    $arreglo = json_decode($arreglo, true);
    $tam = sizeof($arreglo);
    if (!isset($_SESSION)) {
        session_start();
    }
    $ID_Usuario = $_SESSION['ID_Usuario'];
    $bdherramientas = new MHerramientas();
    $bdherramientas->RegistrarReparacion($consecutivo, $fecha, $ID_Usuario, $provedorReparacion);

    for ($i = 0; $i < $tam; $i++) {
        $codigoHerramienta = $arreglo[$i];
        $result = $bdherramientas->RegistrarReparacionHerramienta($consecutivo, $codigoHerramienta, $fecha);
    }

    if ($result == 1) {
        echo ConsecutivoReparacion();
    }
}

function BuscarHerramientaNombre($descripcion)
{
    $bdHerramienta = new MHerramientas();
    $resultado = $bdHerramienta->BuscarHerramientaNombre($descripcion);

    if ($resultado <> null) {
        $concatenar = "";
        while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            $concatenar .= " <tr>
                                <td>" . $fila['Codigo'] . "</td>
                                <td>" . $fila['Descripcion'] . "</td>
				<td>" . $fila['Marca'] . "</td>
                                <td>" . $fila['Estado'] . "</td>
							    <td>" . $fila['Nombre'] . "</td>
								<td style='text-align: center'>
								<button onclick=EliminarLista(this)>
                        <img src='../resources/imagenes/remove.png' width='15px' alt=''/>
                        </button>
                        </tr>";
        }

        echo $concatenar;
    } else {
        echo 0;
    }
}

// LISTA TODAS LAS HERRAMIENTAS Y LAS GUARDA EN LA TABLA DE HERRAMIENTAS

function listarTotalHerramientas()
{
    $dbHerramientas = new MHerramientas();
    $resultado = $dbHerramientas->listarTotalHerramientas();
    if ($resultado != null) {
        echo GenerarResultadoHTMLTablaPrincipal($resultado);
    }
}

function GuardarTrasladoT($CodigoT)
{
    $bdHerramienta = new MHerramientas();
    $resultado = $bdHerramienta->GuardarTrasladoT($CodigoT);
    echo $resultado == 1 ? 1 : 0;
}

function ListarTrasladoMo()
{
    $dbHerramientas = new MHerramientas();
    $resultado = $dbHerramientas->ListarTrasladoMo();
    if ($resultado != null) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            $Fecha = date('d/m/Y', strtotime($fila['FechaIngreso']));
            $concatenar .= "<tr>
                        <td>" . $fila['Codigo'] . "</td>
                        <td>" . $fila['Ubicacion'] . "</td>
                        <td>" . $Fecha . "</td>
                        <td>" . $fila['Marca'] . "</td>
                        <td>" . $fila['Descripcion'] . "</td>
						<td style='text-align: center'>
						<button onclick=EliminarTraslado(this)>
                        <img src='../resources/imagenes/remove.png' width='15px' alt=''/>
                        </button>
                        </tr>";
        }
    }
    echo $concatenar;
}

// LISTA LAS HERRAMIENTAS QUE ESTAN BUENAS PARA UN TRASLADO

function listarTotalHerramientasTranslado()
{
    $dbHerramientas = new MHerramientas();
    $resultado = $dbHerramientas->listarTotalHerramientasTranslado();
    if ($resultado != null) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            $Fecha = date('d/m/Y', strtotime($fila['FechaIngreso']));

            $concatenar .= "<tr>
                        <td>" . $fila['Codigo'] . "</td>
                        <td>" . $fila['Tipo'] . "</td>
                        <td>" . $Fecha . "</td>
                        <td>" . $fila['Disposicion'] . "</td>
                        <td>" . $fila['Nombre'] . "</td>
                        <td>" . $fila['Estado'] . "</td>
						<td style='text-align: center'>
						<button onclick=TransladoHerramienta(this)>
                        <img src='../resources/imagenes/Editar.png' width='15px' alt=''/>
                        </button>
						
                       </tr>";
        }
    }
    echo $concatenar;
}

// COLOCA EL LISTADO DE LOS TIPOS DE HERRAMIENTAS

function listarTipoHerramientas()
{
    $dbHerramientas = new MHerramientas();
    $resultado = $dbHerramientas->listarTipoHerramientas();
    if ($resultado != null) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {

            $concatenar .= "<tr>
                        <td>" . $fila['ID_Tipo'] . "</td>
                        <td>" . $fila['Descripcion'] . "</td>
						<td style='text-align: center'>
                                <button class='btn btn-default' onclick='EditarTipoHerramienta(this)'>
                                    <img src='../resources/imagenes/Editar.png' width='20px' alt=''/>
                                </button>
                                </td>
                        </tr>";
        }
        echo $concatenar;
    }
}

// Obtiene el Consecutivo de la Herramienta

function ObtenerConsecutivoHerramienta()
{
    $bdHerramienta = new MHerramientas();
    $result = $bdHerramienta->ObtenerConsecutivoHerramienta();
    $valor = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($valor["mayor"] != null) {
        $nuevo = $valor["mayor"];
        $final = "P" . $nuevo;
        return $final;
    } else {
        $final = "P1";
        return $final;
    }
}

// Obtiene el Consecutivo de lOS TIPOS HerramientaSA

function ObtenerConsecutivoTipo()
{
    $bdHerramienta = new MHerramientas();
    $result = $bdHerramienta->ObtenerConsecutivoTipo();
    $valor = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($valor["ID_Tipo"] != null) {
        $final = $valor["ID_Tipo"] + 1;
        return $final;
    } else {
        $final = "1";
        return $final;
    }
}

function RegistrarTipoHerramienta()
{
    $Tipo = new Herramientas();
    $bdTipo = new MHerramientas();
    $Tipo->tipo = $_POST['ID_Tipo'];
    $Tipo->descripcion = $_POST['DescripcionTipo'];
    echo $bdTipo->RegistrarTipoHerramienta($Tipo);
}

// Registra las Herramientas

function RegistrarHerramientas()
{
    $herramienta = new Herramientas();
    $bdHerramienta = new MHerramientas();
    $herramienta->descripcion = $_POST['Descripcion'];
    $herramienta->codigo = $_POST['Codigo'];
    $herramienta->fechaIngreso = $_POST['Fecha'];
    $herramienta->estado = "1";
    $herramienta->disposicion = "1";
    $herramienta->tipo = $_POST['Tipo'];
    $herramienta->marca = $_POST['Marca'];
    $herramienta->procedencia = $_POST['Procedencia'];
    $herramienta->ubicacion = "1";
    $herramienta->precio = $_POST['Precio'];
    $herramienta->numFactura = $_POST['NumFactura'];
    $resultado =  $bdHerramienta->RegistrarHerramientas($herramienta);
    echo $resultado;
}

function ConsecutivoReparacion()
{

    $bdHerramienta = new MHerramientas();
    $result = $bdHerramienta->ObternerCosecutivoReparacion();
    $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($fila["NumBoleta"] != null) {

        return $fila["NumBoleta"] + 1;
    } else {
        return 1;
    }
}

function listarBoletasReparacion()
{
    $bdHerramienta = new MHerramientas();
    $result = $bdHerramienta->listarBoletasReparacion();
    if ($result != null) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

            $concatenar .= "<div class='lospedido'>
                <table>
                    <tbody>
                          <td>" . $fila['NumBoleta'] . "</td>
						  <td>" . $fila['Fecha'] . "</td>
						  <td>" . $fila['Nombre'] . "</td>
                          <td><a href='#' onclick = 'VerBoletaReparacion(this)'>Ver</a></td>  
					</tbody>
                    </table>
            </div>";
        }
        echo $concatenar;
    }
}

function VerBoletaReparacion($NumBoleta)
{
    $bdHerramienta = new MHerramientas();
    $result = $bdHerramienta->VerBoletaReparacion($NumBoleta);
    if ($result != null) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

            $concatenar .= "  <tr>
                                                        <td>" . $fila['Codigo'] . "</td>
                                                       <td>" . $fila['Descripcion'] . "</td>
                                                       <td>" . $fila['Marca'] . "</td>
                                                      <td>" . $fila['proveedor'] . "</td>
                                                    </tr>";
        }
        echo $concatenar;
    }
}

function buscarherramienCodigo($Cod)
{
    $bdHerramienta = new MHerramientas();
    $result = $bdHerramienta->buscarherramienCodigo($Cod);
    if (mysqli_num_rows($result) > 0) {
      echo  GenerarResultadoHTMLTablaPrincipal($result);
    } else {
        echo 'No se se encontraron resultados ';
    }
}

// MUESTRA EL TOTAL DE REPARACIONES DE LA HERRAMIENTA

function reparacionesTotales($codigo)
{
    $bdHerramienta = new MHerramientas();
    $resultado = $bdHerramienta->reparacionesTotales($codigo);

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
}

function eliminarBoletaR($eliboleta)
{
    $bdHerramienta = new MHerramientas();
    $resultado = $bdHerramienta->eliminarBoletaR($eliboleta);

    if ($resultado != null) {
        return 1;
    }
}

// MUESTRA EL TOTAL DE TRASLADOS DE LA HERRAMIENTA

function trasladosTotales($codigo)
{
    $bdHerramienta = new MHerramientas();
    $resultado = $bdHerramienta->trasladosTotales($codigo);

    if ($resultado != null) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            $Fecha = date('d/m/Y', strtotime($fila['Fecha']));

            if ($fila['Destino'] == "") {

                // Cuando el destino o la ubicacion es nulo es porque viene de bodega

                $concatenar .= "<tr>
                        <td>" . $Fecha . "</td>
                        <td>" . $fila['NumBoleta'] . "</td>
                        <td>" . $fila['Ubicacion'] . "</td>
                        <td>" . "En reparacion" . "</td>       
						</tr>";
            } else {

                if ($fila['Ubicacion'] == "") {
                    $concatenar .= "<tr>
                        <td>" . $Fecha . "</td>
                        <td>" . $fila['NumBoleta'] . "</td>
                        <td>" . "En reparacion" . "</td>
                        <td>" . $fila['Destino'] . "</td>       
						</tr>";
                } else {
                    $concatenar .= "<tr>
                        <td>" . $Fecha . "</td>
                        <td>" . $fila['NumBoleta'] . "</td>
                        <td>" . $fila['Ubicacion'] . "</td>
                        <td>" . $fila['Destino'] . "</td>       
						</tr>";
                }
            }
        }
        echo $concatenar;
    }
}

function InfoHerramienta($codigo)
{

    $bdHerramienta = new MHerramientas();
    $resultado = $bdHerramienta->InfoHerramienta($codigo);


    if ($resultado != null) {
        $herramienta = '';
        while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            $Fecha = date('d/m/Y', strtotime($fila['FechaIngreso']));
            $herramienta = $fila['Codigo'] . ";" . $fila['Marca'] . ";" . $fila['Descripcion'] . ";" . $Fecha . ";" . $fila['Procedencia'] . ";" . $fila['Precio'] . ";" . $fila['NumFactura'] . ";";
        }
        echo $herramienta;
    }
}

function buscarTraslado($Cod)
{
    $bdHerramienta = new MHerramientas();
    $resultado = $bdHerramienta->buscarTraslado($Cod);

    if ($resultado != null) {
        $concatenar = '';
        while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            $Fecha = date('d/m/Y', strtotime($fila['FechaIngreso']));

            $concatenar .= "<tr>
                        <td>" . $fila['Codigo'] . "</td>
                        <td>" . $fila['Tipo'] . "</td>
                        <td>" . $Fecha . "</td>
                        <td>" . $fila['Disposicion'] . "</td>
                        <td>" . $fila['Nombre'] . "</td>
                        <td>" . $fila['Estado'] . "</td>
						<td style='text-align: center'>
						<button onclick=TransladoHerramienta(this)>
                        <img src='../resources/imagenes/Editar.png' width='15px' alt=''/>
                        </button>
						
                       </tr>";
            echo $concatenar;
        }
    } else {
        echo 'No se se encontraron resultados ';
    }
}

function BuscarTiempoRealHerramienta($consulta)
{
    $bdHerramienta = new MHerramientas();
    $result = $bdHerramienta->BuscarTiempoRealHerramienta($consulta);
    //echo $result;
    if (mysqli_num_rows($result) > 0) {
       echo GenerarResultadoHTMLTablaPrincipal($result);
    } else {
        echo "<h2>No se encontraron Resultados :( </h2>";
    }
}

function ObtenerConsecutivoPedido()
{
    $bdHerramientas = new MHerramientas();
    $result = $bdHerramientas->ObternerCosecutivoPedido();
    if (mysqli_num_rows($result) > 0) {
        $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
        return $fila['Consecutivo'] + 1;
    } else {

        return 1;
    }
}

function EliminarHerramienta()
{
    try {

        $bdMaquinaria = new MHerramientas();
        $request  = json_decode(file_get_contents('php://input'));
        $resultado =  $bdMaquinaria->EliminarHerramienta($request->codigo, $request->motivo);
        echo json_encode($resultado);
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "ActualizarMaquinaria"));
    }
}

function ActualizarHerramientaElectrica()
{
    $maquinaria = new Herramientas();
    try {
        $bdHerramienta = new MHerramientas();
        $bdMaquinaria = new MMaquinaria();
        $maquinaria->codigo = $_POST["codigoNuevo"];
        $maquinaria->tipo = $_POST["tipo"];
        $maquinaria->marca = $_POST["marca"];
        $maquinaria->descripcion = $_POST["descripcion"];
        $maquinaria->fechaIngreso = $_POST["fechaIngreso"];
        $maquinaria->procedencia = $_POST["procedencia"];
        $maquinaria->ubicacion = Constantes::Bodega;
        $maquinaria->precio = str_replace(",", "", $_POST["precio"]);;
        $maquinaria->numFactura = $_POST["numFactura"];
        $maquinaria->monedaCompra = $_POST["monedaCompra"];
        $maquinaria->idHerramienta = $_POST["idHerramienta"];

        if ($_POST["codigoNuevo"] != $_POST["codigoActual"]) {
            $existeMaquinaria = $bdMaquinaria->BuscarMaquinariaPorCodigo($maquinaria->codigo);
            if (mysqli_num_rows($existeMaquinaria) > 0) {
                $resultado = new Resultado();
                $resultado->esValido = false;
                $resultado->mensaje = "Código ya existe, debe ingresar un código diferente";
                echo json_encode($resultado);
            } else {
                $resultado =  $bdHerramienta->ActualizarHerramientaElectrica($maquinaria);
                echo json_encode($resultado);
            }
        } else {
            $resultado =  $bdHerramienta->ActualizarHerramientaElectrica($maquinaria);
            echo json_encode($resultado);
        }
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "ActualizarMaquinaria"));
    }
}

function GenerarResultadoHTMLTablaPrincipal($result)
{
    $resultadoHTML = "";
    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $Monto = "¢" . number_format($fila['Precio'], 2, ",", ".");
        $monedaAlquiler = $fila['CodigoMonedaCobro'] == 'D' ? "$":"¢"; 
        $precioAlquiler = $monedaAlquiler.($fila['PrecioAlquiler']!= ""? number_format($fila['PrecioAlquiler'], 2, ",", "."):"0.00");

         $Fecha = date('d/m/Y', strtotime($fila['FechaIngreso']));
        $btnEditar = " <button type='button' class='btn btn-default' disabled  onclick='OpenModalEditarHerramienta(\"" . $fila['Codigo'] . "\")'>
    <img src='../resources/imagenes/Editar.png' width='15px' alt=''/>
            </button>";
        $btnEliminar = "<button type='button' disabled class='btn btn-danger' onclick=\"AbrirModalEliminarHerramienta('" . $fila['Codigo'] . "')\" >
            <img src='../resources/imagenes/Eliminar.png' width='15px' alt=''/>
            </button>";

        if ($_SESSION['ID_ROL'] == Constantes::RolAdminBodega)
            $btnEliminar =  str_replace("disabled", "", $btnEliminar);

        if ($_SESSION['ID_ROL'] == Constantes::RolAdminBodega || $_SESSION['ID_ROL'] == Constantes::RolBodega)
            $btnEditar =  str_replace("disabled", "", $btnEditar);

        if ($fila['Estado'] == 'Buena') {
            $resultadoHTML .= "<tr>
                <td>" . $fila['Codigo'] . "</td>
                <td>" . $fila['Tipo'] . "</td>
                <td>" . $precioAlquiler . "</td>
                <td>" . $Fecha . "</td>
        <td>" . $Monto . "</td>
                <td>" . $fila['Disposicion'] . "</td>
                <td>" . $fila['Nombre'] . "</td>
                <td>" . $fila['Estado'] . "</td>
                <td>" . $btnEditar . "</td>
                <td>" . $btnEliminar. "</td>
            </tr>";
        } else {
            $resultadoHTML .= "<tr>
                <td class='usuarioBolqueado'>" . $fila['Codigo'] . "</td>
                <td class='usuarioBolqueado'>" . $fila['Tipo'] . "</td>
                 <td class='usuarioBolqueado'>" .$precioAlquiler . "</td>    
                <td class='usuarioBolqueado'>" . $Fecha . "</td>
        <td class='usuarioBolqueado'>" . $Monto . "</td>
                <td class='usuarioBolqueado'>" . $fila['Disposicion'] . "</td>
                <td class='usuarioBolqueado'>" . $fila['Nombre'] . "</td>
                <td class='usuarioBolqueado'>" . $fila['Estado'] . "</td>
                <td class='usuarioBolqueado' >" . $btnEditar . "</td>
                <td class='usuarioBolqueado' >" . $btnEliminar. "</td>
            </tr>";
        }
    }
    return $resultadoHTML;
}
