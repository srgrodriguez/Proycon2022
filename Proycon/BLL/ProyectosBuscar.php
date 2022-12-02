<?php
include '../DAL/Conexion.php';
require_once 'Autorizacion.php';
require_once '../DAL/Log.php';
require_once '../DAL/FuncionesGenerales.php';

Autorizacion();



if (isset($_GET['opc'])) {

    if ($_GET['opc'] == 'Herramienta') {
        buscarHerramienta();
    }else if ($_GET['opc'] == 'Maquinaria') {
        buscarMaquinaria();
    }  
}


function buscarHerramienta(){

    $conn = new Conexion();
    $conexion = $conn->CrearConexion();
     //$connect = new mysqli("localhost", "root", "", "Proycon");    
    if(isset($_POST['consulta'])){
        $q=$_POST['consulta'];
        $sql ="Select th.ID_Tipo, th.Codigo,tt.Descripcion,th.Marca from tbl_herramientaelectrica th, tbl_tipoherramienta tt "
                . "where tt.Descripcion LIKE '%".$q."%'  and th.ID_Tipo = tt.ID_Tipo  and th.Estado = 1 and th.Disposicion = 1 and th.Ubicacion = 1 and th.EstadoDesecho = 0 and tt.TipoEquipo = 'H'";  
    
        $resultado =$conexion->query($sql);
        $conexion->close();
        $totalfilas = mysqli_num_rows($resultado);
        if($totalfilas > 0){
            $concatenar ="<table class='tablasG' style='margin-top: 10px;'>
                                    <thead>
                                    <th>Codigo</th>
                                    <th>Tipo</th>
                                    <th>Marca</th>
                                    <th></th>
                                    </thead>
                                    <tbody id='tbl_body_buscarHerramienta'>";
             while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
               $concatenar .=" <tr>         <td  hidden='true'>".$fila['ID_Tipo']."</td>
                                            <td>".$fila['Codigo']."</td>
                                            <td>".$fila['Descripcion']."</td>
                                            <td>".$fila['Marca']."</td>
                                            <td style='text-align: center'>
                                                <button class='btn btn-success' title='Agregar' onclick = 'AgregarHerramientaBuscadoPNombre(this)'>
                                                      <span class='glyphicon glyphicon-plus'></span>
                                                </button>
    
                                            </td>
                                        </tr>";  
                 
             }
             $concatenar.="</tbody> </table>";
             echo $concatenar;
        }
        else {
            echo "<h2>No se encontraron resultado :(</h2>";
        }
    }
    

}


function buscarMaquinaria(){

    $conn = new Conexion();
    $conexion = $conn->CrearConexion();
     //$connect = new mysqli("localhost", "root", "", "Proycon");    
    if(isset($_POST['consulta'])){
        $q=$_POST['consulta'];
        $sql ="Select th.ID_Tipo, th.Codigo,tt.Descripcion,th.Marca from tbl_herramientaelectrica th, tbl_tipoherramienta tt "
                . "where tt.Descripcion LIKE '%".$q."%'  and th.ID_Tipo = tt.ID_Tipo  and th.Estado = 1 and th.Disposicion = 1 and th.Ubicacion = 1 and th.EstadoDesecho = 0 and tt.TipoEquipo = 'M'";  
    
        $resultado =$conexion->query($sql);
        $conexion->close();
        $totalfilas = mysqli_num_rows($resultado);
        if($totalfilas > 0){
            $concatenar ="<table class='tablasG' style='margin-top: 10px;'>
                                    <thead>
                                    <th>Codigo</th>
                                    <th>Tipo</th>
                                    <th>Marca</th>
                                    <th></th>
                                    </thead>
                                    <tbody id='tbl_body_buscarMaquinaria'>";
             while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
               $concatenar .=" <tr>         <td  hidden='true'>".$fila['ID_Tipo']."</td>
                                            <td>".$fila['Codigo']."</td>
                                            <td>".$fila['Descripcion']."</td>
                                            <td>".$fila['Marca']."</td>
                                            <td style='text-align: center'>
                                                <button class='btn btn-success' title='Agregar' onclick = 'AgregarMaquinariaBuscadoPNombre(this)'>
                                                      <span class='glyphicon glyphicon-plus'></span>
                                                </button>
    
                                            </td>
                                        </tr>";  
                 
             }
             $concatenar.="</tbody> </table>";
             echo $concatenar;
        }
        else {
            echo "<h2>No se encontraron resultado :(</h2>";
        }
    }
    

}




