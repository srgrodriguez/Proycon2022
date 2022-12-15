<?php
header("Content-Type: text/html;charset=utf-8");
require_once '../DAL/Metodos/MBoletasBodega.php';
require_once '../DAL/Conexion.php';
require_once 'Autorizacion.php';
require_once '../DAL/Constantes.php';
require_once '../DAL/FuncionesGenerales.php';
require_once '../DAL/Log.php';
require_once '../DATA/Resultado.php';

Autorizacion();

if (isset($_GET['opc'])) {

    if ($_GET['opc'] == 'mostrar') {
        ListarPedidos();
    }elseif ($_GET['opc'] == "buscarboleta") {
            BuscarBoletaPedido();
    } 
    elseif ($_GET['opc'] == "listarPedidos") {
        MostrarPedidos($_GET['tipo'], $_GET['ID_Proyecto']);
    }
}

function ListarPedidos() {
    try{
        $bdProyectos = new MBoletasBodega();
        $result = $bdProyectos->ListarBoletas();
        if ($result != null) {
            $concaternar = "";

            while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

                $concaternar .= "<div class='lospedido'>
                            <table>
                                <tbody>
                                <td hidden='true'>" . $fila['TipoPedido'] . "</td>
                                <td>" . $fila['Consecutivo'] . "</td>
                                <td>" . $fila['Fecha'] . "</td>
                                <td>" . $fila['Nombre'] . "</td>    
                                <td><a onclick='VerPedido(this)' href='#'>Ver</a></td>
                                </tbody>
                            </table>

                        </div>";
            }
            echo $concaternar;
        }
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "MostrarPedidos"));
    }
}

function MostrarPedidos($TipoPedido, $ID_Proyecto) {
    try{
        $bdProyectos = new MBoletasBodega();
        $result = $bdProyectos->MostrarPedidos($TipoPedido, $ID_Proyecto);
        if ($result != null) {
            $concaternar = "";

            while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

                $concaternar .= "<div class='lospedido'>
                            <table>
                                <tbody>
                                <td hidden='true'>" . $fila['TipoPedido'] . "</td>
                                <td>" . $fila['Consecutivo'] . "</td>
                                <td>" . $fila['Fecha'] . "</td>
                                <td>" . $fila['Nombre'] . "</td>    
                                <td><a onclick='VerPedido(this)' href='#'>Ver</a></td>
                                </tbody>
                            </table>

                        </div>";
            }
            echo $concaternar;
        }
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "MostrarPedidos"));
    }
}


function BuscarBoletaPedido() {
    try{
        $bdProyectos = new MBoletasBodega();
        $result = $bdProyectos->BuscarBoletaPedido($_GET['numBoleta'], $_GET['idProyecto']);
        if (mysqli_num_rows($result) > 0) {
            $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
            echo "<div class='lospedido'>
                            <table>
                                <tbody>
                                <td hidden='true'>" . $fila['TipoPedido'] . "</td>
                                <td>" . $fila['Consecutivo'] . "</td>
                                <td>" . $fila['Fecha'] . "</td>
                                <td>" . $fila['Nombre'] . "</td>    
                                <td><a onclick='VerPedido(this)' href='#'>Ver</a></td>
                                </tbody>
                            </table>

                        </div>";
        } else {
            echo "<h3> La boleta no existe.</h3>";
        }
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "BuscarBoletaPedido"));
    }
}
