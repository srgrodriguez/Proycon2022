<?php
//require_once '../Log.php';
class MUsuarios implements IUsuarios {

    var $conn;

    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->CrearConexion();
    }

    public function ObtenerDatosUsuario($ID_Usuario) {
        $sql = "SELECT u.ID_Usuario,r.Nombre as Rol,u.Nombre,u.Usuario,u.Pass from tbl_usuario u , tbl_rol r
                WHERE u.ID_Usuario = $ID_Usuario and u.ID_Rol=r.ID_Rol; ";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function CambiarPassword($ID_Usuario, $ContraNueva) {        
        
        if ($this->conn->connect_error) {
            return -1;
        }  
        $pass_cifrado = password_hash($ContraNueva, PASSWORD_DEFAULT, array("cost" => 12));
        $sqldata = "UPDATE tbl_usuario set Pass = '$pass_cifrado' where ID_Usuario = $ID_Usuario";     

        $result = $this->conn->query($sqldata);
        $this->conn->close();
        return $result;
    }

    public function RegistrarUsuario(Usuarios $usuarios) {

        
        if ($this->conn->connect_error) {
            return -1;
        }        
        $data = $usuarios->Password;
        $pass_cifrado = password_hash($data, PASSWORD_DEFAULT, array("cost" => 12));
        $sql = "insert into tbl_usuario(Nombre,ID_Rol,Usuario,Pass,Estado) values (?,?,?,?,?)";
       
         if ($stmt = $this->conn->prepare($sql)) {
             
             $usuarios->Nombre = LimpiarCadenaCaracter($this->conn, $usuarios->Nombre);
             $usuarios->ID_Rol = LimpiarCadenaCaracter($this->conn, $usuarios->ID_Rol);
             $usuarios->Usuario = LimpiarCadenaCaracter($this->conn, $usuarios->Usuario);
             $usuarios->Password = $pass_cifrado;
             $usuarios->Estado = LimpiarCadenaCaracter($this->conn, $usuarios->Estado);           
             $stmt->bind_param("sissi", $usuarios->Nombre, $usuarios->ID_Rol, $usuarios->Usuario, $usuarios->Password, $usuarios->Estado);
             $OK = $stmt->execute();
             
        } else {
                echo "Error de sintaxis en consulta SQL ";
        }
            $stmt->close();
            $this->conn->close();
            return $OK ? 1 : 0;
    }

    public function DesactivarUsuario($estado, $idUsuario) {
        $sql = "update tbl_usuario set Estado = '$estado' where ID_Usuario = '$idUsuario'";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function ListarUsuarios() {
        $sql = "select u.ID_Usuario,u.Nombre, u.Usuario, u.Pass, u.Estado, a.Nombre as rol from tbl_usuario u , tbl_rol a WHERE u.ID_Rol = a.ID_Rol ";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    // Reivsar metodo
    public function ModificarUsuario(Usuarios $usuarios) {
        if ($usuarios->Password != "") {
            //$sql = "update tbl_usuario set Nombre = '$usuarios->Nombre',Usuario = '$usuarios->Usuario',Pass = '$usuarios->Password',ID_Rol ='$usuarios->ID_Rol',Estado ='$usuarios->Estado' where ID_Usuario = '$usuarios->ID_Usuarios'";
            $sql = "update tbl_usuario set Nombre = ?,Usuario = ?,Pass = ? ,ID_Rol = ?,Estado = ? where ID_Usuario = ?";

            //Cifrar el password
            $data = $usuarios->Password;
            $pass_cifrado = password_hash($data, PASSWORD_DEFAULT, array("cost" => 12));

            if ($stmt = $this->conn->prepare($sql)) {
                
                $usuarios->Nombre = LimpiarCadenaCaracter($this->conn, $usuarios->Nombre);
                $usuarios->Usuario = LimpiarCadenaCaracter($this->conn, $usuarios->Usuario);
                $usuarios->Password = $pass_cifrado;
                $usuarios->ID_Rol = LimpiarCadenaCaracter($this->conn, $usuarios->ID_Rol);
                $usuarios->Estado = LimpiarCadenaCaracter($this->conn, $usuarios->Estado);
                $usuarios->ID_Usuario = LimpiarCadenaCaracter($this->conn, $usuarios->ID_Usuarios);
                $stmt->bind_param("sssiii", $usuarios->Nombre, $usuarios->Usuario,$usuarios->Password,$usuarios->ID_Rol,$usuarios->Estado,$usuarios->ID_Usuario);
                   
            $stmt->execute();
            } else {
                echo "Error de sintaxis en consulta SQL ";
            }   
            
        } else {
          //  $sql = "update tbl_usuario set Nombre = '$usuarios->Nombre',Usuario = '$usuarios->Usuario',ID_Rol ='$usuarios->ID_Rol',Estado ='$usuarios->Estado' where ID_Usuario = '$usuarios->ID_Usuarios'";
            $sql = "update tbl_usuario set Nombre = ?, Usuario = ?, ID_Rol = ?, Estado = ? where ID_Usuario = ?";

            if ($stmt = $this->conn->prepare($sql)) {
                 $usuarios->Nombre = LimpiarCadenaCaracter($this->conn, $usuarios->Nombre);
                $usuarios->Usuario = LimpiarCadenaCaracter($this->conn, $usuarios->Usuario);
                $usuarios->ID_Rol = LimpiarCadenaCaracter($this->conn, $usuarios->ID_Rol);
                $usuarios->Estado = LimpiarCadenaCaracter($this->conn, $usuarios->Estado);
                $usuarios->ID_Usuario = LimpiarCadenaCaracter($this->conn, $usuarios->ID_Usuarios);
                $stmt->bind_param("ssiii", $usuarios->Nombre, $usuarios->Usuario,$usuarios->ID_Rol,$usuarios->Estado,$usuarios->ID_Usuario);
                   
            $stmt->execute();
                
            } else {
                echo "Error de sintaxis en consulta SQL ";
            }
        
        } 

        $result = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $result;
    }

    public function ComboBox() {
        $sql = "SELECT * FROM tbl_rol";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function ObtieneInfoUsuario($Usuario) {

        try {

            if ($this->conn->connect_error) {
                return -1;
            }

            $sql = "Select Nombre,ID_Usuario,Usuario,Pass,ID_ROL from tbl_usuario where Usuario =?";


            $filtradoCadena = LimpiarCadenaCaracter($this->conn, $Usuario);

            if ($stmt = $this->conn->prepare($sql)) {
                $stmt->bind_param("s", $filtradoCadena);

                $stmt->execute();  // Ejecutar la consulta en la BD
                $resultado = $stmt->get_result(); // retornar la consulta, si existe usuario               

                $stmt->close();
                $this->conn->close();

                return $resultado; // retonarnar el usuario correcto
            } else {
                echo "Error de conexion";
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function ValidarLogin($Usuario) {
        try {

            if ($this->conn->connect_error) {
                return -1;
            }

           $filtradoCadena = LimpiarCadenaCaracter($this->conn, $Usuario);
             
            $sql = "Select ID_Usuario,Pass from tbl_usuario where Usuario =? and Estado = 1";

            if ($stmt = $this->conn->prepare($sql)) {
                $stmt->bind_param("s", $filtradoCadena);

                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                $this->conn->close();
                return $result;
            }         

        } catch (Exception $e) {
            Log::GuardarEvento('ValidarLogin',$e);
            //echo $e->getTraceAsString();
        }
    }   

    public function CambiarNombre($ID_Usuario, $NuevoNombre) {
        $sql = "UPDATE tbl_usuario set Nombre = '" . $NuevoNombre . "' where ID_Usuario = $ID_Usuario;";
        $result = $this->conn->query($sql);
        $this->conn->close();
        return $result;
    }

    public function ValidarUsuarioRegistro($Usuario) {
        $sql = "Select ID_Usuario from tbl_usuario where Usuario ='$Usuario'";
        $result = $this->conn->query($sql);
        return $result;
    }

}
