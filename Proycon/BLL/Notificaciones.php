<?php
/*require_once '..//DAL/Conexion.php';
require_once '..//DAL/Interfaces/INotificaciones.php';
require_once '..//DAL/Metodos/MNotificaciones.php';

if (isset($_GET['opc'])) {
  
        switch ($_GET['opc']) {
        case "notificarBodega":NotificarUsuarioBodega() ;break;
        default:
            break;
    }
}

function NotificarUsuarioBodega(){
    $bdNotificaciones = new MNotificaciones();
    $result = $bdNotificaciones->NotificarUsuarioBodega();
    $arreglo=null;
    while($fila = mysqli_fetch_array($result,MYSQLI_ASSOC)){
 
 $arreglo[]= array(
    "numPedio"=>$fila['numPeidos'],
    "ID_Proyecto"=>$fila['ID_Proyecto'],
    "NBoleta"=>$fila['NBoleta']
);    

    }
    print json_encode($arreglo);      
}
?