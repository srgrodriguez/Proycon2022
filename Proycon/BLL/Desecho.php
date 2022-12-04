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
    $MDesecho = new MDesecho();

    try {
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
    $MDesecho = new MDesecho();

    try {
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
    $MDesecho = new MDesecho();

    try {
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
        echo Log::GuardarEvento($ex, "BuscarTiempoRealDesechoHerramientas");
    }
}

function updateDesecho()
{
    $Desechos = new Desecho();
    $MDesecho = new MDesecho();

    try {

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


    if($TipoDesecho == 1){
        return "Herramienta";        
    } else {
        return "Material";        

    }


}

function ConsecutivoPedido() {
    $bdProyecto = new MDesecho();
    $result = $bdProyecto->ObternerCosecutivoPedido();
    $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    $count = $result->num_rows;

    if ($count > 0) {
        return $fila["Boleta"] + 1;
    } else {
        return 1;
    }
}



function GuardarPedido($ID_Usuario,$arreglo, $fecha,$motivo, $consecutivo)  {

    $bdDesechos = new MDesecho();
    

    $bdDesechos->RegistrarDesecho($arreglo,$fecha,$ID_Usuario,$motivo, $consecutivo);
        
   

    echo ConsecutivoPedido();
}


function GuardarPedidoMaterial($ID_Usuario,$arreglo, $fecha,$motivo, $consecutivo)  {

    $bdDesechos = new MDesecho();   
    $bdDesechos->RegistrarDesechoMaterial($arreglo, $fecha,$ID_Usuario,$motivo, $consecutivo);
       
    //EnviarCorreoElectronico($consecutivo,$ID_Usuario,$arreglo,$motivo, "ajcg1995@hotmail.com");

    echo ConsecutivoPedido();
}
   

function BuscarHerramientaCodigo($codigo) {
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
}
    

function BuscaAgregaMaterial($idMaterial, $cant) {
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
                                <td class='codTablaHD'>$material->Codigo</td>
                                <td class='cantidadTabla'>$cant</td>
                                <td>$material->Nombre</td>
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
}


function ConsultarDesecho($id) {
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
    
}


function ObtenerCorreosAdjuntadosSiempre(){
    $bdDesechos = new MDesecho();
    $result = $bdDesechos->ObtenerCorreosAdjuntadosSiempre();
    if (mysqli_num_rows($result)>0) {
        $concatenar = "";
        while ($fila = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
          $concatenar.= "<tr><td>".$fila['ID_Usuario']."</td><td>".$fila['Usuario']."</td><td><img src='../resources/imagenes/remove.png' class='cursor' width='20px' title='Quitar Correo' onclick='RemoverCorreo(this)' alt=''/></td></tr>";  
        }
        echo $concatenar;
    }
    
}



function EnviarCorreoElectronico($boleta,$usuario,$contenido,$mensajeCorreo,$UsuarioCorreo) {


    $arreglo = json_decode($contenido, true);
    $tam = sizeof($arreglo);
    $concatenar = "";

    foreach($arreglo as $item){

        $concatenar .=
        "<tr>"
            . "<td>" . $item['codigo'] . "</td>"
            . "<td>" . $item['cantidad'] . "</td>"
            . "<td>" . $item['descripcion'] . "</td>"


        . "</tr>";
    }


    $dia = date('d');
     $mes = date('m');
     $anno = date('y');
    /*$arregloCorreos = json_decode($_POST['correos'], true);
    $tam = sizeof($arregloCorreos);
    $to="";
    if ($tam>0) {
    for($i = 0; $i<$tam;$i++){
        $to.="$arregloCorreos[$i],";
    }
    }*/
    $bdPedidos = new MDesecho();
    $result = $bdPedidos ->ObtenerCorreosAdjuntadosSiempre();
    if (mysqli_num_rows($result)> 0) {
        while ($fila = mysqli_fetch_array($result,MYSQLI_ASSOC)){
           $to.= $fila['Usuario'].",";
        }
    }
   $subject = "Pedido de Materiales";
   $headers = "From: $usuario <$UsuarioCorreo> \r\n";
   //$headers ="";
   $headers .= "MIME-Version: 1.0" . "\r\n";
   $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
   $message = "
   <html>
       <head>
           <meta charset='UTF-8'>
           <meta name='viewport' content='width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'>
           <title></title>
           <style>
          .boleta{
                width: 60%;
                margin: 0 auto;
                border: solid #000 1px;
               }
      .fecha{
       width: 25%;
       float: right;
       border: solid 1px black;
   }
   .fecha thead tr th{
        border: solid 1px black;
        text-align: center;
        color: white;
        background: black;
   }
   .fecha tbody tr td{
       border: solid 1px black;
       text-align: center;
       color: black;
       background: white;
   }
   #boletaCorreo{
       margin: 0 auto;
       width: 80%;
   }
   #boletaCorreo thead tr td{
      border: none;
      border-style: none;
       text-align: center;
       color: white;
       background: black;
   }
   #boletaCorreo tbody tr{
       border: none; 
   }
   #boletaCorreo tbody tr td{
       text-decoration: none;
       padding: 8px;
       margin: 0px;
       border: solid 1px black;
       text-align: center;  
   }
   .hederBoleta{
       padding: 10px;
   }
   @media screen and (max-width:600px){
      #boletaCorreo{
       width: 100%;
   } 
   .boleta{
          width: 100%;
          }
   }
           </style>
       </head>
       <body>
           <div class='boleta'>
               <div class='hederBoleta'>
                   <div style='width: 100%;height:auto;overflow: hidden'>
                       <h2 style=' float: right;margin-right: 10px;color: red'>NBoleta $boleta </h2>
                   </div>
                   <div>
                       <table class='fecha'>
                           <thead>
                               <tr>
                                   <th>Dia</th>
                                    <th>Mes</th>
                                     <th>Anno</th>
                               </tr>
                           </thead>
                           <tbody>
                               <tr>
                                   <td>$dia</td>
                                   <td>$mes</td>
                                   <td>$anno</td>
                               </tr>
                           </tbody>
                       </table>
                       
                   </div> 
                    <img src='http://proycon.com/~proycon/wp-content/uploads/2014/08/logo-proycon-slider.png' alt='' width='150px;'/>
                   <div>
                       <h3><strong>Pedido: </strong>Desecho Herramientas y Materiales</h3>
                       <h3><strong>Realizado por: </strong> $usuario</h3>
                       
                   </div>
                   <hr>  
               </div>
                <br> 
                <div id='CuerpoBoleta'>
                    <table id='boletaCorreo'>
                        <thead>
                            <tr>
                                <td>Codigo</td>
                                <td>Cantidad</td>
                                <td>Descripcion</td>
                            </tr>
                        </thead>
                        <tbody>
                        $concatenar

               
                          
                        </tbody>
                    </table>  
                    <br><br>
                </div>    
           </div>
       </body>
   </html>
   ".$mensajeCorreo;
   if($to != "") {mail($to, $subject, $message,$headers);}
   
       }