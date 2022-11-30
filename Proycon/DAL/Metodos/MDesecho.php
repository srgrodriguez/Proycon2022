<?php

class MDesecho implements IDesecho {

    var $conn;

    public function __construct() {

        $conexion = new Conexion();
        $this->conn = $conexion->CrearConexion();
    }


    public function listarDesecho(){
              
        $sql = "SELECT Boleta,Motivo, FechaDesecho, u.Usuario, TipoDesecho  FROM tbl_bitacoradesecho a, tbl_usuario u WHERE a.ID_usuario = u.ID_Usuario";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;         

    }

    public function listarDesechoHerramienta(){
        // El id Para Listar el desecho de herramienta es 1
        $sql = "SELECT Boleta,Motivo, FechaDesecho, u.Usuario, TipoDesecho FROM tbl_bitacoradesecho a, tbl_usuario u WHERE a.ID_usuario = u.ID_Usuario AND TipoDesecho = 1;";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;     


    }

    public function listarDesechoMateriales(){
        // El id Para Listar el desecho de Materiales es 1
        $sql = "SELECT Boleta,Motivo, FechaDesecho, u.Usuario, TipoDesecho  FROM tbl_bitacoradesecho a, tbl_usuario u WHERE a.ID_usuario = u.ID_Usuario AND TipoDesecho = 0;";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;     
    }


    public  function ActualizarDesecho(Desecho $desecho){

        $sql = "UPDATE tbl_bitacoradesecho SET Motivo=?,FechaDesecho=?,ID_Usuario=?,TipoDesecho=?  WHERE id = ?";;


        if ($stmt = $this->conn->prepare($sql)) {

            $desecho->Id = LimpiarCadenaCaracter($this->conn, $desecho->id);
            $desecho->Motivo = LimpiarCadenaCaracter($this->conn, $desecho->Motivo);
            $desecho->FechaDesecho = LimpiarCadenaCaracter($this->conn, $desecho->FechaDesecho);
            $desecho->ID_Usuario = LimpiarCadenaCaracter($this->conn, $desecho->ID_Usuario);
            $desecho->TipoDesecho = LimpiarCadenaCaracter($this->conn, $desecho->TipoDesecho);


            $stmt->bind_param("ssii", $desecho->Motivo ,  $desecho->FechaDesecho, $desecho->ID_Usuario,$desecho->TipoDesecho);
               
        $stmt->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }   

    }


    public  function ConsultarDesecho($id){

        $sql = "SELECT IdDetalle, Boleta, ID_Herramienta, Codigo, Cantidad, Descripcion FROM tbl_bitacoradesechodetalles WHERE Boleta= '$id'";
        $resultado = $this->conn->query($sql);
        $this->conn->close();
        return $resultado;
    }


    public function ObternerCosecutivoPedido() {
        $sql = "SELECT Boleta FROM tbl_bitacoradesecho ORDER BY Boleta DESC LIMIT 1;";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }


    public function RegistrarDesecho($arreglo,$fechaDesecho,$idUsuario,$motivo,  $consecutivo) {
       

        try {



// 1 - Insertar en la tabla  bitacoradesecho

        $TipoDesecho = 1; // 1 HERRAMIENTA
        $sql = "INSERT INTO tbl_bitacoradesecho(Motivo, FechaDesecho, ID_Usuario, TipoDesecho) VALUES  (?,?,?,?);";
        $stmte = $this->conn->prepare($sql);     
        $stmte = mysqli_prepare($this->conn, $sql);
        $stmte->bind_param("ssii", $motivo, $fechaDesecho, $idUsuario, $TipoDesecho); 
        if (mysqli_stmt_execute($stmte)) {
            $ok = mysqli_stmt_insert_id($stmte);
        } else {
            $errorAgregarArchivo = "Falló el registro del archivo";
            $error = mysqli_stmt_error($stmte);
            Log::GuardarEventoString($error, "Guardar archivo");
            $errorAgregarArchivo .= $error;
        }

        mysqli_stmt_close($stmte);
        //  mysqli_close($this->conn);

    // 2 - Insertar en tabla Detalles
        // Obtener numero boleta
        $Nuevaboleta = 0;       

        $boleta = "SELECT Boleta FROM tbl_bitacoradesecho ORDER BY Boleta DESC LIMIT 1;";
        $result = $this->conn->query($boleta);

        $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
        $count = $result->num_rows;
    
        if ($count > 0) {
            $Nuevaboleta = $fila["Boleta"];
        } else {
            $Nuevaboleta = 1;
        }



// Insertar los detalles
        $arreglo = json_decode($arreglo, true);
        $tam = sizeof($arreglo);
       
    
        foreach($arreglo as $item){
                // ******* Obtener la ubicacion de la herramienta que se va a desechar ***************            
                        $sqlDos = "SELECT Ubicacion, ID_Herramienta,Descripcion  FROM tbl_herramientaelectrica  WHERE Codigo = ?";
                        
                        if ($stmtp = $this->conn->prepare($sqlDos)) {
                            $stmtp->bind_param("s", $item['codigo']);
                
                            $stmtp->execute();
                        } else {
                            echo "Error de sintaxis en consulta SQL ";
                        }
                        $resultado = $stmtp->get_result();
                        
                        $fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC);
                
                        $idUbicacion = $fila['Ubicacion']; 
                        $idHerramienta = $fila['ID_Herramienta'];
                        $descripcion = $fila['Descripcion'];


                // ******** Se insertan los detalles de la herramienta *****************

                        
                        $sqlD = "INSERT INTO tbl_bitacoradesechodetalles(Boleta, ID_Herramienta, Codigo, Cantidad, Descripcion) VALUES  (?,?,?,?,?);";
                        $stmtD = $this->conn->prepare($sqlD);      

                        $stmtD = mysqli_prepare($this->conn, $sqlD);
                        $stmtD->bind_param("iisis", $Nuevaboleta,$idHerramienta,$item['codigo'] ,$item['cantidad'],$descripcion);
                        if (mysqli_stmt_execute($stmtD)) {
                            $ok = mysqli_stmt_insert_id($stmtD);
                        } else {
                            $errorAgregarArchivo = "Falló el registro del archivo";
                            $error = mysqli_stmt_error($stmtD);
                            Log::GuardarEventoString($error, "Guardar archivo");
                            $errorAgregarArchivo .= $error;
                        }


            // 3 - Insertar en la tabla historial 


                        $ubicacionDestino = 13; // Correspondiente a al proyecto Desecho

                        $sqlHistorial = "INSERT INTO tbl_historialherramientas(Codigo, Ubicacion, Destino, NumBoleta, Fecha) VALUES (?,?,?,?,?);";
                        $stmtDos = mysqli_prepare($this->conn, $sqlHistorial);
                        $stmtDos->bind_param("siiis", $item['codigo'],$idUbicacion,$ubicacionDestino,$Nuevaboleta,$fechaDesecho);
                        if (mysqli_stmt_execute($stmtDos)) {
                            $ok = mysqli_stmt_insert_id($stmtDos);
                        } else {
                            $errorAgregarArchivo = "Falló el registro del archivo";
                            $error = mysqli_stmt_error($stmtDos);
                            Log::GuardarEventoString($error, "Guardar archivo");
                            $errorAgregarArchivo .= $error;
                        }


                // 4- Actualizar el estadoDesecho de la herramienta a 0

                        $sql3 = "UPDATE tbl_herramientaelectrica SET EstadoDesecho = 0 WHERE Codigo = '" . $item['codigo'] . "'";
                        $this->conn->query($sql3);

        }; 
    






        } catch (\Throwable $th) {
            mysqli_close($this->conn);
            $idArchivo = 0;
            Log::GuardarEvento($th, "Guardar archivo");
        }



    }



public function RegistrarDesechoMaterial($arreglo,$fechaDesecho,$idUsuario,$motivo) {
       
    //Consultar para obtener el Id del material

    try {      

        // 1 - Insertar en la tabla  bitacoradesecho
            $TipoDesecho = 0; // 1 MATERIALES
            $sql = "INSERT INTO tbl_bitacoradesecho(Motivo, FechaDesecho, ID_Usuario, TipoDesecho) VALUES  (?,?,?,?);";
            $stmte = $this->conn->prepare($sql);     
            $stmte = mysqli_prepare($this->conn, $sql);
            $stmte->bind_param("ssii", $motivo, $fechaDesecho, $idUsuario, $TipoDesecho); 

            if (mysqli_stmt_execute($stmte)) {
                $ok = mysqli_stmt_insert_id($stmte);
            } else {
                $errorAgregarArchivo = "Falló el registro del archivo";
                $error = mysqli_stmt_error($stmte);
                Log::GuardarEventoString($error, "Guardar archivo");
                $errorAgregarArchivo .= $error;
            }



                // Obtener numero boleta
                $Nuevaboleta = 0;       

                $boleta = "SELECT Boleta FROM tbl_bitacoradesecho ORDER BY Boleta DESC LIMIT 1;";
                $result = $this->conn->query($boleta);

                $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);

                $count = $result->num_rows;

                if ($count > 0) {
                    $Nuevaboleta = $fila["Boleta"];
                } else {
                    $Nuevaboleta = 1;
                }


      
            // Insertar los detalles
            $arreglo = json_decode($arreglo, true);
            $tam = sizeof($arreglo);


            foreach($arreglo as $item){
                
                $sql = "SELECT ID_Material,Nombre  FROM tbl_materiales  WHERE Codigo = ?";
        
                if ($stmtp = $this->conn->prepare($sql)) {
                    $stmtp->bind_param("s", $item['codigo']);        
                    $stmtp->execute();
                } else {
                    echo "Error de sintaxis en consulta SQL ";
                }
                $resultado = $stmtp->get_result();                
                $fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC);       
        
                $idHerramienta = $fila['ID_Material'];
                $descripcion = $fila['Nombre'];
                $cantidad = $item['cantidad'];


                $sqlD = "INSERT INTO tbl_bitacoradesechodetalles(Boleta, ID_Herramienta, Codigo, Cantidad, Descripcion) VALUES  (?,?,?,?,?);";
                $stmtD = $this->conn->prepare($sqlD);      

                $stmtD = mysqli_prepare($this->conn, $sqlD);
                $stmtD->bind_param("iisis", $Nuevaboleta,$idHerramienta,$item['codigo'] ,$item['cantidad'],$descripcion);
                if (mysqli_stmt_execute($stmtD)) {
                    $ok = mysqli_stmt_insert_id($stmtD);
                } else {
                    $errorAgregarArchivo = "Falló el registro del archivo";
                    $error = mysqli_stmt_error($stmtD);
                    Log::GuardarEventoString($error, "Guardar archivo");
                    $errorAgregarArchivo .= $error;
                }

                // 2 - Actualizar la cantidad del material en la tabla tbl_materiales

                $sql3 = "UPDATE tbl_materiales set Cantidad = Cantidad - $cantidad WHERE ID_Material = '" .$idHerramienta. "'";


                $this->conn->query($sql3);


            }

        


    } catch (\Throwable $th) {
        mysqli_close($this->conn);
        $idArchivo = 0;
        Log::GuardarEvento($th, "RegistrarDesechoMaterial");
    }



}



public function ObtenerCorreosAdjuntadosSiempre() {
    $sql ="Select ID_Usuario,Usuario from tbl_usuario where Adjuntarcorreo = 1";
    $result =  $this-> conn->query($sql);
    $this-> conn->close();
    return $result;    
}

















}
