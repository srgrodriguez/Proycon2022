<?php

class MDesecho implements IDesecho {

    var $conn;

    public function __construct() {

        $conexion = new Conexion();
        $this->conn = $conexion->CrearConexion();
    }


    public function listarDesecho(){
              
        $sql = "SELECT id, ID_Herramienta, Codigo, Motivo, FechaDesecho, u.Usuario, TipoDesecho, Cantidad FROM tbl_bitacoradesecho a, tbl_usuario u WHERE a.ID_usuario = u.ID_Usuario";
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
        $sql = "SELECT id, ID_Herramienta, Codigo, Motivo, FechaDesecho, u.Usuario, TipoDesecho, Cantidad FROM tbl_bitacoradesecho a, tbl_usuario u WHERE a.ID_usuario = u.ID_Usuario AND TipoDesecho = 0;";
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
        $sql = "SELECT id, ID_Herramienta, Codigo, Motivo, FechaDesecho, u.Usuario, TipoDesecho, Cantidad FROM tbl_bitacoradesecho a, tbl_usuario u WHERE a.ID_usuario = u.ID_Usuario AND TipoDesecho = 1;";
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

        $sql = "INSERT INTO tbl_bitacoradesecho(ID_Herramienta, Codigo, Motivo, FechaDesecho, ID_Usuario, TipoDesecho,Cantidad) VALUES (?,?,?,?,?,?,?)";
       
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

        $sql = "SELECT id, ID_Herramienta, Codigo, Motivo, FechaDesecho, ID_Usuario, TipoDesecho,Cantidad FROM tbl_bitacoradesecho WHERE = ?";


            $filtradoCadena = LimpiarCadenaCaracter($this->conn, $id);

            if ($stmt = $this->conn->prepare($sql)) {
                $stmt->bind_param("i", $filtradoCadena);

                $stmt->execute();  // Ejecutar la consulta en la BD
                $resultado = $stmt->get_result();           

                $stmt->close();
                $this->conn->close();

                return $resultado; // retonarnar el usuario correcto
            } else {
                echo "Error de conexion";
            }
    }


    public function ObternerCosecutivoPedido() {
        $sql = " SELECT id FROM tbl_bitacoradesecho ORDER BY id DESC LIMIT 1;";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }


}