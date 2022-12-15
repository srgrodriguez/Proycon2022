<?php
include '../DATA/Materiales.php';
include '../DATA/Desecho.php';
include '../DAL/Interfaces/IDesecho.php';
include '../DAL/Metodos/MDesecho.php';
include '../DAL/Interfaces/IMateriales.php';
include '../DAL/Interfaces/IHerrramientas.php';
include '../DAL/Metodos/MMaterial.php';
include '../DAL/Metodos/MHerramientas.php';
include '../DAL/Conexion.php';
include '../DAL/FuncionesGenerales.php';
require_once 'Autorizacion.php';
require_once '../DAL/Log.php';

Autorizacion();
if (isset($_GET['opc'])) {
    if ($_GET['opc'] == 'listar') {
        CrearTablaListarDesecho();
    }
    elseif ($_GET['opc'] == 'listarMaterial') {
        ListarDesechoMaterial();
    }
    elseif ($_GET['opc'] == 'listarHeramienta') {
        ListarDesechoHerramientas();
    }
    elseif ($_GET['opc'] == 'update') {
        updateDesecho();
    
    }elseif ($_GET['opc'] == 'registrarPedido') {
        if (!isset($_SESSION)) {
            session_start();
        }
        GuardarPedido($_SESSION['ID_Usuario'],$_POST['data'],$_POST['fecha'],$_POST['motivo'],$_POST['consecutivo']);
    }elseif ($_GET['opc'] == 'buscarherramientapedido') {
        BuscarHerramientaCodigo($_GET['codigo']);
    }elseif ($_GET['opc'] == 'buscarM') {
        BuscaAgregaMaterial($_GET['id'], $_GET['cant']);
    }elseif ($_GET['opc'] == 'registrarPedidoMateriales') {
        if (!isset($_SESSION)) {
            session_start();
        }
        GuardarPedidoMaterial($_SESSION['ID_Usuario'],$_POST['data'],$_POST['fecha'],$_POST['motivo'],$_POST['consecutivo']);
    } elseif ($_GET['opc'] == 'ConsultarDesecho') {
        ConsultarDesecho($_POST['boleta']);
    }
}



function CrearTablaListarDesecho()
{
      try {
        $MDesecho = new MDesecho();

  
        $resultado = $MDesecho->listarDesecho();

        if ($resultado <> null) {
            $concatenar = '';

            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $concatenar .=
                    "<tr>"
                    . "<td>" . $fila['Boleta'] . "</td>"
                    . "<td>" . $fila['Motivo'] . "</td>"
                    . "<td>" . $fila['FechaDesecho'] . "</td>"
                    . "<td>" . $fila['Usuario'] . "</td>"
                    . "<td>" . ObtenerDescripcionTipoHerramienta($fila['TipoDesecho']) . "</td>"
                    . "<td> <a onclick='VerPedido(this)' href='#'>Ver</a> </td>"
                    . "</tr>";
            }
            echo $concatenar;
        }

    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "CrearTablaListarDesecho");
    }
}

function ListarDesechoMaterial()
{
       try {
        $MDesecho = new MDesecho(); 
        $resultado = $MDesecho->listarDesechoMateriales();

        if ($resultado <> null) {
            $concatenar = '';

            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $concatenar .=
                    "<tr>"
                        . "<td>" . $fila['Boleta'] . "</td>"
                        . "<td>" . $fila['Motivo'] . "</td>"
                        . "<td>" . $fila['FechaDesecho'] . "</td>"
                        . "<td>" . $fila['Usuario'] . "</td>"
                        . "<td>" . ObtenerDescripcionTipoHerramienta($fila['TipoDesecho']) . "</td>"
                        . "<td>    <a onclick='VerPedido(this)' href='#'>Ver</a>  </td>"

                    . "</tr>";
            }
            echo $concatenar;
        } else {
            echo "<h2>No se encontraron Resultados :( </h2>";
        }
        
    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "BuscarTiempoRealDesechoMaterial");
    }
}

function ListarDesechoHerramientas()
{
       try { 
        $MDesecho = new MDesecho();


        $resultado = $MDesecho->listarDesechoHerramienta();

        if ($resultado <> null) {
            $concatenar = '';

            while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
                $concatenar .=
                    "<tr>"
                    . "<td>" . $fila['Boleta'] . "</td>"
                    . "<td>" . $fila['Motivo'] . "</td>"
                    . "<td>" . $fila['FechaDesecho'] . "</td>"
                    . "<td>" . $fila['Usuario'] . "</td>"
                    . "<td>" . ObtenerDescripcionTipoHerramienta($fila['TipoDesecho']) . "</td>"
                    . "<td>    <a onclick='VerPedido(this)' href='#'>Ver</a>  </td>"
                    . "</tr>";
            }
            echo $concatenar;
        } else {
            echo "<h2>No se encontraron Resultados :( </h2>";
        }
        
    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "ListarDesechoHerramientas");
    }
}

function updateDesecho()
{
    
    try {
        $Desechos = new Desecho();
        $MDesecho = new MDesecho();
        $Desechos->Id = $_POST['id'];
        $Desechos->ID_Herramienta = $_POST['iD_Herramienta'];
        $Desechos->Codigo = $_POST['codigo'];
        $Desechos->Motivo = $_POST['motivo'];
        $Desechos->FechaDesecho = $_POST['fechaDesecho'];
        $Desechos->ID_Usuario = $_POST['iD_Usuario'];
        $Desechos->TipoDesecho = $_POST['tipoDesecho'];
        $Desechos->Cantidad = $_POST['cantidad'];


        $resultado = $MDesecho->ActualizarDesecho($Desechos);
        echo $resultado;
    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "updateDesecho");
    }
}



function ObtenerDescripcionTipoHerramienta($TipoDesecho){

    try {
        if($TipoDesecho == 1){
            return "Herramienta";        
        } else {
            return "Material";        

        }

    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "ObtenerDescripcionTipoHerramienta");
    }
}


function ConsecutivoPedido() {
     try{
        $bdProyecto = new MDesecho();

       
                   $result = $bdProyecto->ObternerCosecutivoPedido();
            $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
            
            $count = $result->num_rows;

            if ($count > 0) {
                return $fila["Boleta"] + 1;
            } else {
                return 1;
            }
        } catch (Exception $ex) {
            echo Log::GuardarEvento($ex, "ConsecutivoPedido");
        }   
}



function GuardarPedido($ID_Usuario,$arreglo, $fecha,$motivo, $consecutivo)  {
    try{
        $bdDesechos = new MDesecho();
   
        $bdDesechos->RegistrarDesecho($arreglo,$fecha,$ID_Usuario,$motivo, $consecutivo);  
        echo ConsecutivoPedido();
    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "GuardarPedido");
    }
}


function GuardarPedidoMaterial($ID_Usuario,$arreglo, $fecha,$motivo, $consecutivo)  {

    try{
    $bdDesechos = new MDesecho();   
    $bdDesechos->RegistrarDesechoMaterial($arreglo, $fecha,$ID_Usuario,$motivo, $consecutivo);
       
    //EnviarCorreoElectronico($consecutivo,$ID_Usuario,$arreglo,$motivo, "ajcg1995@hotmail.com");

    echo ConsecutivoPedido();
    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "GuardarPedidoMaterial");
    }
}
   

function BuscarHerramientaCodigo($codigo) {
    try{ 
        $bdHerramienta = new MDesecho();
   
        $restultado = $bdHerramienta->BuscarHerramientaPorCodigo($codigo);
        $concatenar = "";
        $filasAfectadas = mysqli_num_rows($restultado);
        if ($filasAfectadas > 0) {
            $fila = mysqli_fetch_array($restultado, MYSQLI_ASSOC);
            $concatenar = "
                            <tr>
                                <td hidden='true' >" . $fila['ID_Tipo'] . "</td>
                                <td class='codTablaHD'>" . $fila['Codigo'] . "</td>
                                <td>" . $fila['Descripcion'] . "</td>
                                <td>" . $fila['Marca'] . "</td>
                                <td style='width: 25px;'>
                                <button title='Quitar Fila' class='btnRemoverFila' type='button'  onclick='Remover(this)'>
                                        <img title='Eliminar Fila' src='../resources/imagenes/remove.png' alt='' width='20px'/>
                                    </button>
                            </td>
                            </tr>";
            echo $concatenar;
        } else {
            echo 0;
        }
    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "BuscarHerramientaCodigo");
    }
}
    

function BuscaAgregaMaterial($idMaterial, $cant) {
       try{
        $bdMateriales = new MMaterial();
 
        $result = $bdMateriales->BuscarMaterial($idMaterial, $cant);
        $material = new Materiales();
        $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);

        $count = $result->num_rows;

        if ($count > 0) {
            if ($fila['Codigo'] != null) {
                $material->Codigo = $fila['Codigo'];
                $material->Nombre = $fila['Nombre'];
                $material->Cantidad = $fila['Cantidad'];
        // $material->Disponibilidad=$fila['Diponibilidad'];                         
                if ($cant > $material->Cantidad) {
                    echo $material->Cantidad;
                } else {
                    $concatenar = "
                                <tr>
                                    <td class='codTabla'>$material->Codigo</td>
                                    <td class='cantidadTabla'>$cant</td>
                                    <td class='descripcionTabla'>$material->Nombre</td>
                                <td style='width: 25px;'>
                                    <button title='Quitar Fila' class='btnRemoverFila' type='button'  onclick='Remover(this)'>
                                            <img title='Eliminar Fila' src='../resources/imagenes/remove.png' alt='' width='20px'/>
                                        </button>
                                </td>
                                </tr>";
                    echo $concatenar;
                }
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "BuscaAgregaMaterial");
    }
}


function ConsultarDesecho($id) {
     try{
         $MDesecho = new MDesecho();
  
        $result = $MDesecho->ConsultarDesecho($id);

            if ($result != null) {
                $concaternar = "<table id='tbl_P_Materiales_Selecionado' class='tablasG'>
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Cantidad</th>
                                        <th>Decripcion</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>";

                while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                
                    $concaternar .= 
                                "<tr>".
                                    "<td>" . $fila['Codigo'] . "</td>
                                    <td>" . $fila['Cantidad'] . "</td>
                                    <td>" . $fila['Descripcion'] . "</td>".
                                
                                "</tr>";

                }
                $concaternar .= "</tbody></table>";
                echo $concaternar;
            }
        } catch (Exception $ex) {
            echo Log::GuardarEvento($ex, "ConsultarDesecho");
        }
}


function ObtenerCorreosAdjuntadosSiempre(){
   
    try{
    $bdDesechos = new MDesecho();
   
        $result = $bdDesechos->ObtenerCorreosAdjuntadosSiempre();
        if (mysqli_num_rows($result)>0) {
            $concatenar = "";
            while ($fila = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
            $concatenar.= "<tr><td>".$fila['ID_Usuario']."</td><td>".$fila['Usuario']."</td><td><img src='../resources/imagenes/remove.png' class='cursor' width='20px' title='Quitar Correo' onclick='RemoverCorreo(this)' alt=''/></td></tr>";  
            }
            echo $concatenar;
        }
    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "ObtenerCorreosAdjuntadosSiempre");
    }
}
