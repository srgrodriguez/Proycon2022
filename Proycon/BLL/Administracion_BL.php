<?php

require_once '../DAL/Conexion.php';
require_once '../DATA/Herramientas.php';
require_once '../DAL/Interfaces/IHerrramientas.php';
include'../DAL/Metodos/MHerramientas.php';
require_once 'Autorizacion.php';

Autorizacion();
if (isset($_GET['opc'])) {
    $opc = $_GET['opc'];
    switch ($opc) {
        case "buscarHCod": BuscarHerramientaCodigo($_GET['codigo']);
            break;

        default:
            break;
    }
}

function BuscarHerramientaCodigo($codigo) {
    try {
        $bdHerramienta = new MHerramientas();
        $result = $bdHerramienta->BuscarHerramientaPorCodigo($codigo);
        $concatenar = "0";
        if (mysqli_num_rows($result) > 0) {
            $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $concatenar = "<div class='form-group'>    
                                <label class='col-lg-2'>Codigo</label> 
                                                                    <div class='col-md-5'>
                                          <div class='input-group'>
                                            <input id='txtCodigoH2'  name='Codigo' type='text' value=" . $fila['Codigo'] . " class='form-control' placeholder='Codigo'>
                                            <span class='input-group-btn'>
                                                <button id='btnBuscarMaterialCodigo' onclick='BuscarHerramientaCodigo()' class='btn btn-default' type='button'><img src='../resources/imagenes/icono_buscar.png' width='18px' alt=''/></button>
                                            </span>
                                        </div>
                                    </div>

                            </div>

                            <div class='form-group'>    
                                <label class='col-lg-2'>Descripcion</label> 
                                <div class='col-md-8'>
                                    <textarea type='text' name='txtDescripcionH' id='txtDescripcionH' class='form-control value=" . $fila['DesH'] . " ' placeholder='Descripcion'>" . $fila['DesH'] . "</textarea>
                                </div>
                            </div>
                            <div class='form-group'>    
                                <label class='col-lg-2'>Marca</label> 
                                <div class='col-md-8'>
                                    <textarea type='text' name='txtMarcaH' id='txtMarcaH' value=" . $fila['Marca'] . " class='form-control ' placeholder='Marca'>" . $fila['Marca'] . "</textarea>
                                </div>
                            </div>

                            <div class='form-group'>    
                                <label class='col-lg-2'>Precio</label> 
                                <div class='col-md-8'>
                                    <input type='text'  name='txtPrecioH' id='txtPrecioH' value=" . $fila['Precio'] . " class='form-control ' onkeypress='return soloNumeros(event)' placeholder='Precio'/>
                                </div>
                            </div>

                            <div class='form-group'>    
                                <label class='col-lg-2'>Fecha</label> 
                                <div class='col-md-6'>
                                    <input type='date' name='txtFechaRegistroH' value=" . $fila['FechaIngreso'] . " id='txtFechaRegistroH' class='form-control ' placeholder='Fecha'/>

                                </div>
                            </div>

                            <div class='form-group'>    
                                <label class='col-lg-2'>Procedencia</label> 
                                <div class='col-md-6'>
                                    <input type='text' name='txtProcedenciaH' value=" . $fila['Procedencia'] . " id='txtProcedenciaH' class=' form-control ' placeholder='Procedencia'/>
                                </div>
                            </div>

                            <div class='form-group'>    
                                <label class='col-lg-2'>Tipo</label> 
                                <div class='col-md-6'>

                                    <select id='comboHerramientaTipoH' name='comboHerramientaTipoH' class='form-control ' > 

                                        <option value=" . $fila['ID_Tipo'] . " selected=''>" . $fila['Descripcion'] . "</option>" . ObtenerTipo($fila['ID_Tipo']) . "

                                    </select>

                                </div>
                            </div>

";
            echo $concatenar;
        } else {
            echo $concatenar;
        }
    } catch (\Throwable $ex) {
        echo "-1" . $ex;
    }
}

function ActualizarHerramienta() {
    try{
        $h = new Herramientas();
        $h->codigo = $_POST['codigo'];
        $h->descripcion = $_POST['des'];
        $h->fechaIngreso = $_POST['fecha'];
        $h->marca = $_POST['marca'];
        $h->precio = $_POST['precio'];
        $h->procedencia = $_POST['proced'];
        $h->tipo = $_POST['tipo'];
    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "ActualizarHerramienta");
    }

}

function ObtenerTipo($id) {
    try{
        $conexion = new Conexion();
        $conn = $conexion->CrearConexion();
        $sql = "Select Descripcion,ID_Tipo from tbl_tipoherramienta";
        $rec = $conn->query($sql);
        $conn->close();
        $concat = "";
        if ($rec != null) {
            while ($row = mysqlI_fetch_array($rec, MYSQLI_ASSOC)) {
                if ($row['ID_Tipo'] != $id) {

                    $concat .= "<option value ='" . $row['ID_Tipo'] . "'>";
                    $concat .= $row['Descripcion'];
                    $concat .= "</option>";
                }
            }
            return $concat;
        }
    } catch (Exception $ex) {
        echo Log::GuardarEvento($ex, "ObtenerTipo");
    }
    
}
