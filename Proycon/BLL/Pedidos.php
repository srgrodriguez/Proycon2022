<?php
require_once '..//DAL/Conexion.php';
require_once '..//DAL/Interfaces/IPedidos.php';
require_once '..//DAL/Metodos/MPedidos.php';
require_once '..//DATA/PedidoProveeduria.php';
require_once 'Autorizacion.php';
require_once '../DAL/Log.php';
require_once '../DAL/FuncionesGenerales.php';
require_once '../DAL/Log.php';

Autorizacion();
if (isset($_GET['opc'])) {
    $opc = $_GET['opc'];
    switch ($opc) {    
        case "listarpedidos": ListarPedidosProyecto($_POST['idproyecto']);break;
        case "generarpedido":GenerarPedido();            break;
        case "obtenerconsecutivo":ObternerCosecutivoPedido();break;
        case "buscarCorreo":BuscarCorreoElectronico($_POST['datos']);break;
        case "adjuntarSiempre":AdjuntarCorreoSiempre();break;
        case "obtenerCorreos":ObtenerCorreosAdjuntadosSiempre();break;
        case "desadjuntar":DesAdjuntarCorreo();break;
        case "verpedido":VerPedido();break;
        case "buscarpedido":BuscarPedido();break;
        case "anularboleta":AnularBoletaPedido();break;
        default:
            break;
    }
    if ($_GET['opc']== 'bherramientapedido') {
        if (isset($_POST['consulta'])) {
          BuscarHerramientasPedido($_POST['consulta']);  
        }
       
    } if ($_GET['opc']== 'bMaquinariapedido')
    {
        if (isset($_POST['consulta'])) {
          BuscarMaquinariaPedido($_POST['consulta']);  
        }
       
    }
    
}


function BuscarHerramientasPedido($consulta){
    $bdPedidos = new MPedidos();
    try {
        $result = $bdPedidos->ContarHerramientaDisponible($consulta);
    if (mysqli_num_rows($result)>0) {
        $concatenar ="<table class='tablasG' id='tbl_Peido_Proveeduria' style='margin-top: 10px;'>
                        <thead>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Stock</th>
                            <th>Cant Agregar</th>
                             <th></th>
                        </thead>
                        <tbody>";
        
        while ($fila = mysqli_fetch_array($result,MYSQLI_ASSOC)){
            $concatenar.="<tr>"
                            . "<td>".$fila['ID_Tipo']."</td>"
                            . "<td>".$fila['Descripcion']."</td>"
                            . "<td>".$fila['Total']."</td>"
                            . "<td style='width: 100px;'><input width='20px' type='text' id='txtcantiSolicitadaHerramientas' name='txtcantiSolicitadaMaterial' class='form-control' value='' placeholder='Cantidad' /></td>
                              <td style='text-align: center'>
                                <button class='btn btn-default' onclick='AgregarHerramientaBuscadoPTipo(this)'>
                                    <img src='../resources/imagenes/add_icon.png' width='20px' alt=''/>
                                </button>
                             </td>";         
        }
        echo $concatenar;
    }
    else{   
     echo "<h2>No se encontraron resultado :(</h2>";
    } 
    } catch (\Throwable $ex) {
        echo Log::GuardarEvento($ex, "BuscarHerramientasPedido");
    }
   
}





function BuscarMaquinariaPedido($consulta){
    $bdPedidos = new MPedidos();
    try {
        $result = $bdPedidos->ContarMaquinariaDisponible($consulta);
    if (mysqli_num_rows($result)>0) {
        $concatenar ="<table class='tablasG' id='tbl_Peido_Proveeduria' style='margin-top: 10px;'>
                        <thead>
                            <th>ID</th>
                            <th>Tipo</th>
                            <th>Stock</th>
                            <th>Cant Agregar</th>
                             <th></th>
                        </thead>
                        <tbody>";
        
        while ($fila = mysqli_fetch_array($result,MYSQLI_ASSOC)){
            $concatenar.="<tr>"
                            . "<td>".$fila['ID_Tipo']."</td>"
                            . "<td>".$fila['Descripcion']."</td>"
                            . "<td>".$fila['Total']."</td>"
                            . "<td style='width: 100px;'><input width='20px' type='text' id='txtcantiSolicitadaHerramientas' name='txtcantiSolicitadaMaterial' class='form-control' value='' placeholder='Cantidad' /></td>
                              <td style='text-align: center'>
                                <button class='btn btn-default' onclick='AgregarHerramientaBuscadoPTipo(this)'>
                                    <img src='../resources/imagenes/add_icon.png' width='20px' alt=''/>
                                </button>
                             </td>";         
        }
        echo $concatenar;
    }
    else{   
     echo "<h2>No se encontraron resultado :(</h2>";
    } 
    } catch (\Throwable $ex) {
        echo Log::GuardarEvento($ex, "BuscarHerramientasPedido");
    }
   
}










function ObternerCosecutivoPedido(){
    $bdPedidos = new MPedidos();

    try {
        $result = $bdPedidos->ObternerCosecutivoPedido();
    if (mysqli_num_rows($result)> 0) {
        $fila = mysqli_fetch_array($result,MYSQLI_ASSOC);
         echo 1+$fila['Consecutivo'];    
    }
    else{
        echo 1;
    }
    }catch (\Throwable $ex) {
        echo Log::GuardarEvento($ex, "ObternerCosecutivoPedido");
    }
  
}

/*Esta funcion se comunica con DAL/MProyectos ya que es la misma consulta que ocupo*/
function ListarProyectos(){
    $bdPedidos = new MPedidos();
    try {
        $result =  $bdPedidos->ListarProyectos(); 
        if (mysqli_num_rows($result)> 0) {
            $concatenar="";
            while ($fila = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
              $concatenar.= "<section class='proyecto'>
                              <h3> <a href='javascript:void(0);' id='".$fila['ID_Proyecto']."' onclick='MostrarPedidosProyecto(".$fila['ID_Proyecto'].")'>".$fila['Nombre']."</a></h3>  
                             </section>";  
            }
            echo $concatenar;         
        }
        else{
            //echo '<script>alert("Ocurrio un error a la Hora de LISTAR LOS Proyectos")</script>';  
            
        }
    } catch (\Throwable $ex) {
        echo Log::GuardarEvento($ex, "ListarProyectos");
    }

}

function ListarPedidosProyecto($ID_Proyecto){
    $bdPedidos = new MPedidos();
    try {
        $result = $bdPedidos->ListarPedidosProyecto($ID_Proyecto);
        if (mysqli_num_rows($result)>0) {
            $concatenar = "";
           $concatenar.="<table class='tablasG'>"
                        . "<thead>"
                        . "<th>#Boleta</th>"
                         ."<th>Generada por</th>"
                        . "<th>Fecha</th>"
                        . "<th></th>"
                        . "</thead><tbody>";
            while ($fila = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
             $concatenar.="<tr><td>".$fila['Consecutivo']."</td><td>".$fila['Nombre']."</td><td>".$fila['Fecha']."</td><td><a href='javascript:void(0);' onclick='VerPedido(this)'>Ver</a></td></tr>";   
                        
            }
            $concatenar.="</tbody></table>";
            echo $concatenar;
            
        }
           else{
           echo '<h3>No hay pedidos registrados</h3>';    
       }
    } catch (\Throwable $ex) {
        echo Log::GuardarEvento($ex, "ListarPedidosProyecto");
    }   
}

function GenerarPedido(){
    try {
        session_start();
        $arreglo = json_decode($_POST['arreglo'], true);
        $headerPedido = new HeaderPedido();
        $contenido = new CuerpoPedido();
        $bdPedidos = new MPedidos();
        $headerPedido->Comentarios =$_POST['comentarios'];
        $headerPedido->Consecutivo=$_POST['consecutivo'];
        $headerPedido->Fecha=$_POST['fecha'];
        $headerPedido->ID_Proyecto=$_POST['idProyecto'];
        $headerPedido->ID_Usuairo=$_SESSION['ID_Usuario'];
        $tam = sizeof($arreglo);
        $result1 = $bdPedidos->GenerarPedido($headerPedido);
        $contenidoPedido="";
          for ($i = 0; $i < $tam; $i++) {
                for ($j = 0; $j <= 3; $j++) {
                    $contenido->Tipo=$arreglo[$i][$j];
                    $contenido->Codigo= $arreglo[$i][$j + 1];
                    $contenido->Cantidad=$arreglo[$i][$j + 2];
                    $contenidoPedido.="<tr>".
                    "<td>".$arreglo[$i][$j + 1]."</td>".
                    "<td>".$arreglo[$i][$j + 2]."</td>".
                    "<td>".$arreglo[$i][$j + 3]."</td>"."</tr>";
                    $j = 4;
                }
                 $bdPedidos = new MPedidos();
                $contenido->Consecutivo=$_POST['consecutivo'];
                $result2 = $bdPedidos->ContenidoPedido($contenido);
            }
          //Descomentariar esta funcion cuando se suba al hosting;
           EnviarCorreoElectronico($_POST['consecutivo'],$_POST['nombreProyecto'],$_SESSION['Nombre'],$contenidoPedido,$_POST['mensajeCorreo'],$_SESSION['Usuario']);
           if ($result1 == 1 && $result2== 1) {
                ECHO 1;
            }
           else {
                echo 0;
                }
        
    } catch (\Throwable $ex) {
        echo Log::GuardarEvento($ex, "GenerarPedido");
    }   
   
    
}
function BuscarCorreoElectronico($busqueda){
   $bdPedidos = new MPedidos();

   try {
    $result = $bdPedidos->BuscarCorreoElectronico($busqueda);
   if (mysqli_num_rows($result)>0) {
       $concatenar="<table  class='tablasG' id='tblCorreosBuscados' style='margin-top: 10px;'>
                            <thead>
                            <th>ID</th>
                            <th>Correo</th>
                            <th>Adjuntar</th>
                            <th>Adjuntar Siempre</th>
                            </thead>
                            <tbody id='tbl_body_buscarCorreo'>";
       while($fila = mysqli_fetch_array($result,MYSQLI_ASSOC)){
         $concatenar.="<tr>
                            <td>".$fila['ID_Usuario']."</td>
                            <td>".$fila['Usuario']."</td>
                            <td> <button class='btn btn-success' onclick='AgregarCorrego(this)'><span class='glyphicon glyphicon-plus'></span></button></td>";
         if ($fila['Adjuntarcorreo']==1) {
            $concatenar.="<td><input type='checkbox' onclick='AdjuntarCorreoElectronicoSiempre(this)' checked='checked' name='chkAdjuntarSiempre' value='ON' /></td>";
        }
        else {
           $concatenar.="<td><input type='checkbox' onclick='AdjuntarCorreoElectronicoSiempre(this)' name='chkAdjuntarSiempre' value='ON' /></td>"; 
        }
                            
        $concatenar.="</tr>";  
       }
       $concatenar.="</tbody></table>";
       echo $concatenar;
       
   
   }else{
       echo "<h3>No se encontraro Resultado :( </h3>";
   }
   } catch (\Throwable $ex) {
    echo Log::GuardarEvento($ex, "BuscarCorreoElectronico");
}   
  
    
}

function EnviarCorreoElectronico($boleta,$proyecto,$usuario,$contenido,$mensajeCorreo,$UsuarioCorreo) {
 $dia = date('d');
  $mes = date('m');
  $anno = date('y');
 $arregloCorreos = json_decode($_POST['correos'], true);
 $tam = sizeof($arregloCorreos);
 $to="";
 if ($tam>0) {
 for($i = 0; $i<$tam;$i++){
     $to.="$arregloCorreos[$i],";
 }
 }
 $bdPedidos = new MPedidos();
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
                    <h2><strong>Proyecto: </strong> $proyecto</h2>
                    <h3><strong>Pedido: </strong>Herramientas y Materiales</h3>
                    <h3><strong>Solicita: </strong> $usuario</h3>
                    
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
                             <td>Material</td>
                         </tr>
                     </thead>
                     <tbody>
                        $contenido
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
    
function AdjuntarCorreoSiempre(){
    $bdPedido = new MPedidos();
    echo $bdPedido->AdjuntarCorreoSiempre($_GET['idUsuario']);   
}

function ObtenerCorreosAdjuntadosSiempre(){
    $bdPedidos = new MPedidos();
    $result = $bdPedidos->ObtenerCorreosAdjuntadosSiempre();
    if (mysqli_num_rows($result)>0) {
        $concatenar = "";
        while ($fila = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
          $concatenar.= "<tr><td>".$fila['ID_Usuario']."</td><td>".$fila['Usuario']."</td><td><img src='../resources/imagenes/remove.png' class='cursor' width='20px' title='Quitar Correo' onclick='RemoverCorreo(this)' alt=''/></td></tr>";  
        }
        echo $concatenar;
    }
    
}

function DesAdjuntarCorreo() {
    $bdPedidos = new MPedidos();
  ECHO $bdPedidos->DesAdjuntarCorreo($_GET['id']);    
}
function VerPedido(){
    $bdPedidos = new MPedidos();
    echo $bdPedidos ->VerPedido($_GET['boleta']);           
}
function BuscarPedido() {
    $bdPedidos = new MPedidos();
    $result = $bdPedidos->BuscarPedido($_GET['boleta']);
          $concatenar="<table class='tablasG'>"
                    . "<thead>"
                    . "<th>#Boleta</th>"
                    . "<th>Fecha</th>"
                    . "<th></th>"
                    . "</thead><tbody>";
    if (mysqli_num_rows($result) > 0 ) {       
        while ($fila = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
         $concatenar.="<tr><td>".$fila['Consecutivo']."</td><td>".$fila['Fecha']."</td><td><a href='javascript:void(0);' onclick='VerPedido(this)'>Ver</a></td></tr>";           
        }
        $concatenar.="</tbody></table>";  
      
    }
    echo $concatenar;
}
function AnularBoletaPedido(){
    $bdPedidos = new  MPedidos();
   $result = $bdPedidos ->VerificarProcesoDeBoleta($_GET['boleta']);
    if (mysqli_num_rows($result) > 0) {
       echo $bdPedidos->AnularBoletaPedido($_GET['boleta']);   
    }
    else{
        echo 0;
    }
}

