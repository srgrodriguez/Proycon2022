<?php

class MDesecho implements IDesecho {

    var $conn;

    public function __construct() {

        $conexion = new Conexion();
        $this->conn = $conexion->CrearConexion();
    }


    public function listarDesecho(){
              
        $sql = "SELECT id, ID_Herramienta, Codigo, Motivo, FechaDesecho, u.Usuario, TipoDesecho, Cantidad, Descripcion  FROM tbl_bitacoradesecho a, tbl_usuario u WHERE a.ID_usuario = u.ID_Usuario";
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
        // El id Para Listar el desecho de herramienta es 0
        $sql = "SELECT id, ID_Herramienta, Codigo, Motivo, FechaDesecho, u.Usuario, TipoDesecho, Cantidad, Descripcion  FROM tbl_bitacoradesecho a, tbl_usuario u WHERE a.ID_usuario = u.ID_Usuario AND TipoDesecho = 0;";
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
        $sql = "SELECT id, ID_Herramienta, Codigo, Motivo, FechaDesecho, u.Usuario, TipoDesecho, Cantidad, Descripcion  FROM tbl_bitacoradesecho a, tbl_usuario u WHERE a.ID_usuario = u.ID_Usuario AND TipoDesecho = 1;";
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

    public  function AgregarDesecho(Desecho $desecho){

        $sql = "INSERT INTO tbl_bitacoradesecho(ID_Herramienta, Codigo, Motivo, FechaDesecho, ID_Usuario, TipoDesecho,Cantidad, Descripcion ) VALUES (?,?,?,?,?,?,?,?)";
       
         if ($stmt = $this->conn->prepare($sql)) {
             
             $desecho->ID_Herramienta = LimpiarCadenaCaracter($this->conn, $desecho->ID_Herramienta);
             $desecho->Codigo = LimpiarCadenaCaracter($this->conn, $desecho->Codigo);    
             $desecho->Motivo = LimpiarCadenaCaracter($this->conn, $desecho->Motivo);  
             $desecho->FechaDesecho = LimpiarCadenaCaracter($this->conn, $desecho->FechaDesecho);           
             $desecho->ID_Usuario = LimpiarCadenaCaracter($this->conn, $desecho->ID_Usuario);           
             $desecho->TipoDesecho = LimpiarCadenaCaracter($this->conn, $desecho->TipoDesecho);           


             $stmt->bind_param("isssiii", $desecho->ID_Herramienta, $desecho->Codigo, $desecho->Motivo, $desecho->FechaDesecho, $desecho->ID_Usuario, $desecho->TipoDesecho,$desecho->Cantidad);
             $OK = $stmt->execute();
             
        } else {
                echo "Error de sintaxis en consulta SQL ";
        }
            $stmt->close();
            $this->conn->close();
            return $OK ? 1 : 0;

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

        $sql = "SELECT id, ID_Herramienta, Codigo, Motivo, FechaDesecho, ID_Usuario, TipoDesecho,Cantidad, Descripcion FROM tbl_bitacoradesecho WHERE id= '$id'";
        $resultado = $this->conn->query($sql);
        $this->conn->close();
        return $resultado;
    }


    public function ObternerCosecutivoPedido() {
        $sql = " SELECT id FROM tbl_bitacoradesecho ORDER BY id DESC LIMIT 1;";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }


    public function RegistrarDesecho($codigo,$TipoDesecho,$cantidad,$fechaDesecho,$idUsuario,$motivo,  $consecutivo) {
       
        //Consultar de que proyecto de donde viene la herramienta

        try {

        $sqlDos = "SELECT Ubicacion, ID_Herramienta,Descripcion  FROM tbl_herramientaelectrica  WHERE Codigo = ?";
        
        if ($stmtp = $this->conn->prepare($sqlDos)) {
            $stmtp->bind_param("s", $codigo);

            $stmtp->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmtp->get_result();
        
        $fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC);

        $idUbicacion = $fila['Ubicacion']; 
        $idHerramienta = $fila['ID_Herramienta'];
        $descripcion = $fila['Descripcion'];

// 1 - Insertar en la tabla  bitacoradesecho
        $codigo = LimpiarCadenaCaracter($this->conn, $codigo);
        $sql = "INSERT INTO tbl_bitacoradesecho(ID_Herramienta, Codigo, Motivo, FechaDesecho, ID_Usuario, TipoDesecho, ID_Proyecto,Cantidad,Descripcion) VALUES  (?,?,?,?,?,?,?,?,?);";
        $stmt = $this->conn->prepare($sql);
        
        //$stmt->bind_param("isssiiiis", $idHerramienta, $codigo, $motivo, $fechaDesecho, $idUsuario, $TipoDesecho, $idUbicacion,$cantidad,$descripcion);
        //$ok = $stmt->execute();
        //$stmt->close();
        $stmt = mysqli_prepare($this->conn, $sql);
        $stmt->bind_param("isssiiiis", $idHerramienta, $codigo, $motivo, $fechaDesecho, $idUsuario, $TipoDesecho, $idUbicacion,$cantidad,$descripcion);
        if (mysqli_stmt_execute($stmt)) {
            $ok = mysqli_stmt_insert_id($stmt);
        } else {
            $errorAgregarArchivo = "Falló el registro del archivo";
            $error = mysqli_stmt_error($stmt);
            Log::GuardarEventoString($error, "Guardar archivo");
            $errorAgregarArchivo .= $error;
        }

        mysqli_stmt_close($stmt);
        //  mysqli_close($this->conn);


// 2 - Insertar en la tabla historial 


      /*)  $sqlHistorial = "INSERT INTO tbl_historialherramientas(Codigo, Ubicacion, Destino, NumBoleta, Fecha) VALUES (?,?,?,?,?);";
        $stmt = $this->conn->prepare($sqlHistorial);
        $stmt->bind_param("siiis", $codigo,13,$idUbicacion,$consecutivo,$fechaDesecho);
        $ok = $stmt->execute();
        $stmt->close();
        $this->conn->close();
        return $ok ? 1 : 0;
        */

        $ubicacionDestino = 13; // Correspondiente a al proyecto Desecho

        $sqlHistorial = "INSERT INTO tbl_historialherramientas(Codigo, Ubicacion, Destino, NumBoleta, Fecha) VALUES (?,?,?,?,?);";
        $stmtDos = mysqli_prepare($this->conn, $sqlHistorial);
        $stmtDos->bind_param("siiis", $codigo,$idUbicacion,$ubicacionDestino,$consecutivo,$fechaDesecho);
        if (mysqli_stmt_execute($stmtDos)) {
            $ok = mysqli_stmt_insert_id($stmtDos);
        } else {
            $errorAgregarArchivo = "Falló el registro del archivo";
            $error = mysqli_stmt_error($stmtDos);
            Log::GuardarEventoString($error, "Guardar archivo");
            $errorAgregarArchivo .= $error;
        }


// 3- Actualizar el estadoDesecho de la herramienta a 0

        $sql3 = "UPDATE tbl_herramientaelectrica SET EstadoDesecho = 0 
        WHERE Codigo = '$codigo'";

        $this->conn->query($sql3);

       // mysqli_close($this->conn);


        } catch (\Throwable $th) {
            mysqli_close($this->conn);
            $idArchivo = 0;
            Log::GuardarEvento($th, "Guardar archivo");
        }



    }



public function RegistrarDesechoMaterial($codigo,$TipoDesecho,$cantidad,$fechaDesecho,$idUsuario,$motivo) {
       
    //Consultar para obtener el Id del material

    try {

        $sql = "SELECT ID_Material,Nombre  FROM tbl_materiales  WHERE Codigo = ?";
        
        if ($stmtp = $this->conn->prepare($sql)) {
            $stmtp->bind_param("s", $codigo);

            $stmtp->execute();
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmtp->get_result();
        
        $fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC);


        $idHerramienta = $fila['ID_Material'];
        $descripcion = $fila['Nombre'];

    // 1 - Insertar en la tabla  bitacoradesecho
        $codigo = LimpiarCadenaCaracter($this->conn, $codigo);
        $sqlDos = "INSERT INTO tbl_bitacoradesecho(ID_Herramienta, Codigo, Motivo, FechaDesecho, ID_Usuario, TipoDesecho, Cantidad,Descripcion) VALUES  (?,?,?,?,?,?,?,?);";
        $stmt = $this->conn->prepare($sqlDos);
        
        //$stmt->bind_param("isssiiiis", $idHerramienta, $codigo, $motivo, $fechaDesecho, $idUsuario, $TipoDesecho, $idUbicacion,$cantidad,$descripcion);
        //$ok = $stmt->execute();
        //$stmt->close();
        $stmt = mysqli_prepare($this->conn, $sqlDos);
        $stmt->bind_param("isssiiis", $idHerramienta, $codigo, $motivo, $fechaDesecho, $idUsuario, $TipoDesecho,$cantidad,$descripcion);
        if (mysqli_stmt_execute($stmt)) {
            $ok = mysqli_stmt_insert_id($stmt);
        } else {
            $errorAgregarArchivo = "Falló el registro del archivo";
            $error = mysqli_stmt_error($stmt);
            Log::GuardarEventoString($error, "Guardar archivo");
            $errorAgregarArchivo .= $error;
        }

        mysqli_stmt_close($stmt);
        //  mysqli_close($this->conn);


    // 2 - Actualizar la cantidad del material en la tabla tbl_materiales

    $sql3 = "UPDATE tbl_materiales set Cantidad = Cantidad - $cantidad WHERE ID_Material = '$idHerramienta'";


    $this->conn->query($sql3);

        


    } catch (\Throwable $th) {
        mysqli_close($this->conn);
        $idArchivo = 0;
        Log::GuardarEvento($th, "RegistrarDesechoMaterial");
    }



}
}
