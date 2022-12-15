<?php
header("Content-Type: text/html;charset=utf-8");
include '..//DATA/Usuarios.php';
include '..//DAL/Interfaces/IUsuarios.php';
include '..//DAL/Metodos/MUsuarios.php';
include '../DAL/Conexion.php';
include '../DAL/FuncionesGenerales.php';
include '../DATA/Resultado.php';
require_once 'Autorizacion.php';
require_once '../DAL/Log.php';

Autorizacion();
if (isset($_GET['opc'])) {
    if ($_GET['opc']=='cambioPass') { 
        CambiarPassword($_POST['viejoPass'],$_POST['ID_Usuario'], $_POST['nuevoPass']);
    }
    if($_GET['opc'] == "registrar"){
        registrarUsuario();  
    }else if($_GET['opc'] == "update"){
        actualizarUsuario();
    }else if($_GET['opc'] == "updEstatus"){
        actualizarEstado();
    }
    else if ($_GET['opc']=="cambioNom") {
        CambiarNombre($_POST['ID_Usuario'], $_POST['nuevoNombre']);
    }
    elseif($_GET['opc']=="getUser"){
        ObtenerDatosUsuarioGetJson();
    }
}


function ObtenerDatosUsuario($ID_Usuario){
    try{
        $bdUsuario = new MUsuarios();
        $result = $bdUsuario->ObtenerDatosUsuario($ID_Usuario);
        if ($result != null)  {
            $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $ID = $fila['ID_Usuario'];
            $Rol =$fila['Rol'];
            $Nombre = $fila['Nombre'];
            $Usuario = $fila['Usuario'];
            $Pass = $fila['Pass'];
            
            $concatenar = "<span id='ID_UsuarioCuenta' style='display:none'>".$ID."</span>            
                        <div class='contieneDatos'>
                            <div class='flotarIz tma'><h3>Nombre</h3></div>
                            <div class='flotarIz'><h3 id='NombreCuenta'>".$Nombre."</h3></div>
                            <div class='flotarIz'><h3><a href='#' onclick='abrirModalCambiarNombre()' >Editar</a></h3></div>
                        </div> 
                        <div class='contieneDatos'>
                            <div class='flotarIz tma'><h3>Usuario</h3></div>
                            <div class='flotarIz'><h3 id='NombreCuenta'>".$Usuario."</h3></div>
                        </div>
                        <div class='contieneDatos'>
                            <div class='flotarIz tma'><h3>Contraseña</h3></div>
                            <div class='flotarIz'><h3 id='PassCuenta'>".$Pass."</h3></div>
                            <div class='flotarIz'><h3><a href='#' data-toggle='modal' data-target='#ModalCambioPassword' >Editar</a></h3></div>
                        </div>  
                        <div class='contieneDatos'>
                            <div class='flotarIz tma'><h3>Rol</h3></div>
                            <div class='flotarIz'><h3 id='id='PassCuenta>".$Rol."</h3></div>
                            
                        </div> 

                                    ";
            
            echo $concatenar;
            
        } 
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "llamarFuncion"));
    }  
}
 function CambiarPassword($viejoPass,$ID_Usuario, $ContraNueva) {
    
    try{
        // traer el usuario para comparar contraseña anterior y procede con el cambio
        
        $bdUsuario = new MUsuarios();   
        $result = $bdUsuario->ObtenerDatosUsuario($ID_Usuario);    
        $Resultado = new Resultado();
        
        if ($result != null)  {        
            
            $row = mysqlI_fetch_array($result, MYSQLI_ASSOC);        
            
            if (password_verify($viejoPass,$row['Pass'])) {
                // si coincidencia del passowrd acutal
                $bdUsuario = new MUsuarios();
                $result = $bdUsuario->CambiarPassword($ID_Usuario, $ContraNueva);
                
                if ($result == "1") {
                    $Resultado->codigo = "1";
                    $Resultado->mensaje = "Actualizado Correctamente";
                }else{
                    $Resultado->codigo = "0";
                    $Resultado->mensaje = "Problemas al actualizar";                
                }
                echo json_encode($Resultado);
                    
                
            }else{
                // no coindice el password actual por lo tanto no se puede cambiar la contraseña  
                $Resultado->codigo = "0";
                $Resultado->mensaje = "Contrasenna actual no coincide";
                echo json_encode($Resultado);
            }
            
            
        }else{
            // no existe el usuario
            
        $Resultado->codigo = "0";
                $Resultado->mensaje = "No existe el usuario";
                echo json_encode($Resultado);
        }    
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "CambiarPassword"));
    }
 }
 
 
 
 function CambiarNombre($ID_Usuario,$NomNuevo){
    try{
        $bdUsuarios = new MUsuarios();
        $result =  $bdUsuarios->CambiarNombre($ID_Usuario, $NomNuevo);
        if ($result == 1) {
            $_SESSION["Nombre"] = $NomNuevo;
        }
         $_SESSION["Nombre"] = $NomNuevo;
        echo $result;  
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "CambiarNombre"));
    }
 }
 function actualizarUsuario(){
    try{
        $Usuarios = new Usuarios();
        $bdUsuarios = new MUsuarios();
        $Usuarios->ID_Usuarios = $_POST['id'];
        $Usuarios->Nombre = $_POST['nombre'];
        $Usuarios->Usuario = $_POST['usuario'];
        $Usuarios->Password = $_POST['pass'];
        $Usuarios->ID_Rol = $_POST['rol'];
        $Usuarios->Estado = $_POST['status'];
        $Usuarios->IdProyecto =$_POST['idProyecto'];
        
        $bdUsuarios->ModificarUsuario($Usuarios);  
        crearTabla();
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "actualizarUsuario"));
    }
}

function actualizarEstado(){
    try{
        $bdUsuarios = new MUsuarios();
        $estado =  $_POST['estado'];
        
        $id =  $_POST['id'];
        
        if($estado== '0'){
            $estado = '1';
        }else{
            $estado = '0';
        }
    
        $bdUsuarios->DesactivarUsuario($estado, $id);
        crearTabla();
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "actualizarEstado"));
    }
}


function registrarUsuario(){
    try{
        $Usuarios = new Usuarios();
        $bdUsuarios = new MUsuarios();
        
        $Usuarios->Nombre = $_POST['nombre'];
        $Usuarios->Usuario = $_POST['usuario'];
        $Usuarios->Password = $_POST['pass'];
        $Usuarios->ID_Rol = $_POST['rol'];
        $Usuarios->Estado = $_POST['status'];
        $Usuarios->IdProyecto =$_POST['idProyecto'];
        $result = $bdUsuarios ->ValidarUsuarioRegistro( $Usuarios->Usuario);
        if (mysqli_num_rows($result)> 0 ) {
        return 0; 
        }else{
        $bdUsuarios->RegistrarUsuario($Usuarios);
        
        crearTabla();
        }
    
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "actualizarEstado"));
    }
}

function crearTabla(){ 
    try{
    $bdUsuarios = new MUsuarios();
    $usuarios = $bdUsuarios->ListarUsuarios();
    $concatenar = '';
    if($usuarios != NULL){
        while ($fila = mysqli_fetch_array($usuarios, MYSQLI_ASSOC)){
            $CheckStatus = '';
            if($fila['Estado'] == 1){
                if ($fila['rol'] == "Administrador") {
                    $CheckStatus = ' <img class="imgEstado" src="../resources/imagenes/correcto.png" width="25px" alt="" onclick="updateEstado(this,1)" />';
                    
                    //<input onclick="updateEstado(this)" disabled ="true" type="checkbox"  checked name="" value="1" />
                }else{
                $CheckStatus = ' <img class="imgEstado" src="../resources/imagenes/correcto.png" width="25px" alt="" onclick="updateEstado(this,1)" />';
                }
                $concatenar  .=
                "<tr>"
                    . "<td>".$fila['ID_Usuario'] . "</td>"
                    . "<td>".$fila['Usuario'] . "</td>"
                    . "<td>".$fila['Nombre'] . "</td>"
                    . "<td>".$fila['rol'] . "</td>"
                    . "<td>".$CheckStatus. "</td>"
                    . "<td>".'<a href="javascript:void(0);" onclick="MostrarFormUsuario(this,1)"> <img src="../resources/imagenes/Editar.png" width="25px"/></a>'."</td>"
               ."</tr>"; 
                      
            
                
            }else if($fila['Estado'] == 0){
                
                  if ($fila['rol'] == "Administrador") {
                    $CheckStatus = ' <img class="imgEstado" src="../resources/imagenes/Malo.png" width="25px" alt="" onclick="updateEstado(this,0)" />';
                }else{
                $CheckStatus = ' <img class="imgEstado" src="../resources/imagenes/Malo.png" width="25px" alt="" onclick="updateEstado(this,0)" />';
                }
          $concatenar  .=
                "<tr>"
                    . "<td class='usuarioBolqueado'>".$fila['ID_Usuario'] . "</td>"
                    . "<td class='usuarioBolqueado'>".$fila['Usuario'] . "</td>"
                    . "<td class='usuarioBolqueado'>".$fila['Nombre'] . "</td>"
                    . "<td class='usuarioBolqueado'>".$fila['rol'] . "</td>"
                    . "<td class='usuarioBolqueado'>".$CheckStatus. "</td>"
                    . "<td class='usuarioBolqueado'>".'<a href="#" onclick="MostrarFormUsuario(this,0)"> <img src="../resources/imagenes/Editar.png" width="25px"/></a>'."</td>"
               ."</tr>"; 
                
                
            }
            
        }       
    }
    print $concatenar;
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "crearTabla"));
    }
}

function cargarComboBox(){
    try{
        //cargar el combobox del modal
        $bdUsuarios = new MUsuarios();
        $datosRoles =  $bdUsuarios->ComboBox();
        
        if($datosRoles != NULL){
            while($fila = mysqli_fetch_array ($datosRoles, MYSQLI_ASSOC)){
            echo "<option value=".$fila['ID_Rol'].">".$fila['Nombre'] . "</option>";            
            }
        }
    } catch (Exception $ex) {
        echo  json_encode(Log::GuardarEvento($ex, "cargarComboBox"));
    }
}

function ObtenerDatosUsuarioGetJson(){
    $ID_Usuario = $_GET["idUsuario"];
     $bdUsuario = new MUsuarios();
     $result = $bdUsuario->ObtenerDatosUsuario($ID_Usuario);
     if ($result != null)  {
         $infoUsuario = mysqli_fetch_array($result, MYSQLI_ASSOC);
         echo json_encode($infoUsuario);
     }
 }



