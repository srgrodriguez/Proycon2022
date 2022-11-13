<?php


class MMaterial implements IMateriales {
    
    var $conn;
    
    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->CrearConexion();
    }

    
    
   
    public function BuscarMaterial($idMaterial,$cant) {
         $conexion = new Conexion();
        $conn=$conexion->CrearConexion();
        if ($conn->connect_errno) {
            return -1;
        }
        else{
            $sqlselect ="select Codigo,Nombre,Cantidad,Disponibilidad from tbl_materiales where Codigo = '".$idMaterial."'";
            $result=$conn->query($sqlselect); 
            $conn->close();
            return $result;
        }
    }

    public function BuscarMaterialNombre($nombre) {
        $conexion = new Conexion();
        $conn=$conexion->CrearConexion();
        if ($conn->connect_errno) {
            return -1;
        }
 else { 
            $sql ="SELECT * FROM tbl_materiales WHERE Nombre LIKE '%".$nombre."%' ";
            $result=$conn->query($sql); 
            $conn->close();
            return $result;
      }
       
    }

    public function AgregarMateriales(Materiales $Materiales) {
        
       
        $Codigo = $Materiales->getCodigo();
        $Nombre = $Materiales->getNombre();
        $Cantidad = $Materiales->getCantidad();
        $Disponibilidad = $Materiales->getDisponibilidad();
        $Devolucion = $Materiales->getDevolucion();
        
        $sql ="SELECT Codigo FROM tbl_materiales WHERE Codigo ='$Codigo'";
        $result =  $this-> conn->query($sql);
        
        
        if(mysqli_num_rows($result)>0){    // si el resultado es 1 quiere decir que si existe, por lo tanto no entra a insertar el registro 
            $this-> conn->close(); 
            return 0;
             
        }else{            
           
            $sqlInsert ="INSERT INTO tbl_materiales (Codigo, Nombre, Cantidad, Disponibilidad, Devolucion) VALUES (?, ?, ?, ?, ?)";

                if ($stmt = $this->conn->prepare($sqlInsert)) {

                     $Materiales->Codigo = LimpiarCadenaCaracter($this->conn, $Materiales->Codigo);
                     $Materiales->Nombre = LimpiarCadenaCaracter($this->conn, $Materiales->Nombre);
                     $Materiales->Cantidad = LimpiarCadenaCaracter($this->conn, $Materiales->Cantidad);
                     $Materiales->Disponibilidad = LimpiarCadenaCaracter($this->conn, $Materiales->Disponibilidad);
                     $Materiales->Devolucion = LimpiarCadenaCaracter($this->conn, $Materiales->Devolucion);           
                     $stmt->bind_param("ssiii", $Materiales->Codigo, $Materiales->Nombre, $Materiales->Cantidad, $Materiales->Disponibilidad, $Materiales->Devolucion);
                     $OK = $stmt->execute();

                } else {
                        echo "Error de sintaxis en consulta SQL ";
                }           

        $stmt->close();
        $this->conn->close();
        return $OK ? 1 : 0;
       
           
        }
        
        
    }

    public function VerificarDisponibilidad($codigo) {
        
        $sql = "SELECT * FROM tbl_materiales WHERE Codigo ='$codigo'";
        $result =  $this-> conn->query($sql);
        $this-> conn->close();
        
        if(mysqli_num_rows($result)>0){     
            return $result;
        }else{
            return 0;
        }
       
    }

    public function UpdateMateriales(\Materiales $Materiales) {
        $idHerramienta = $Materiales->getIdHerramienta();
        $Codigo = $Materiales->getCodigo();
        $Nombre = $Materiales->getNombre();
        $Cantidad = $Materiales->getCantidad();
        $Devolucion = $Materiales->getDevolucion();
        $stock=$Materiales->getStock();
        
        if ($this->conn->connect_errno) {
            return -1;
        }        
    
        if ($stock == 0) {
             $sql = "UPDATE tbl_materiales SET Codigo = ?, Nombre = ?, Cantidad = Cantidad + ?, Devolucion = ? WHERE ID_Material = ?";
        
             if ($stmt = $this->conn->prepare($sql)) {
                $Materiales->Codigo = LimpiarCadenaCaracter($this->conn, $Materiales->Codigo);
                $Materiales->Nombre = LimpiarCadenaCaracter($this->conn, $Materiales->Nombre);
                $Materiales->Cantidad = LimpiarCadenaCaracter($this->conn, $Materiales->Cantidad);
                $Materiales->Devolucion = LimpiarCadenaCaracter($this->conn, $Materiales->Devolucion);                
                $Materiales->idHerramienta = LimpiarCadenaCaracter($this->conn, $Materiales->idHerramienta);                
                $stmt->bind_param("ssiii", $Materiales->Codigo, $Materiales->Nombre, $Materiales->Cantidad, $Materiales->Devolucion, $Materiales->idHerramienta);
                $OK = $stmt->execute();
            } else {
                echo "Error de sintaxis en consulta SQL ";
            }             
             
        }else{
            $sql = "UPDATE tbl_materiales SET Codigo = ?, Nombre = ?, Devolucion = ?,Cantidad= ? WHERE ID_Material = ?";
             
            if ($stmt = $this->conn->prepare($sql)) {

            $Materiales->Codigo = LimpiarCadenaCaracter($this->conn, $Materiales->codigo);
            $Materiales->Nombre = LimpiarCadenaCaracter($this->conn, $Materiales->Nombre);
            $Materiales->Devolucion = LimpiarCadenaCaracter($this->conn, $Materiales->Devolucion);
            $Materiales->Cantidad = LimpiarCadenaCaracter($this->conn, $Materiales->Cantidad);                
            $Materiales->ID_Material = LimpiarCadenaCaracter($this->conn, $Materiales->ID_Material);                
            $stmt->bind_param("ssiii", $Materiales->Codigo, $Materiales->Nombre, $Materiales->Devolucion, $Materiales->Cantidad, $Materiales->ID_Material);         
            $OK = $stmt->execute();    
            
            
            } else {
                echo "Error de sintaxis en consulta SQL ";
            } 
        }       
            $stmt->close();
            $this->conn->close();
            return $OK ? 1 : 0;        
    }

    public function listarTotalMateriales() {
        $sql ="SELECT codigo,nombre,cantidad FROM tbl_materiales ORDER by nombre ASC ";
        $result =  $this-> conn->query($sql);
        $this-> conn->close();
        return $result;
        
    }
    
    public function BuscarTiempoRealHerramienta($consulta){
        
        $consulta = LimpiarCadenaCaracter($this->conn, $consulta);        
        $sql = "SELECT codigo,nombre,cantidad FROM tbl_materiales where Nombre LIKE ?";

        if ($stmt = $this->conn->prepare($sql)) {
            $like = "%" . $consulta . "%";
            /* ligar parámetros para marcadores */
            $stmt->bind_param("s", $like);
            /* ejecutar la consulta */
            $stmt->execute();
            /* ligar variables de resultado */
        } else {
            echo "Error de sintaxis en consulta SQL ";
        }
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
        
        
        
    }

    public function BuscarMaterialCodigo($Codigo) {        
        
        if ($this->conn->connect_errno) {
            return -1;
        } else {
            $descripcion = LimpiarCadenaCaracter($this->conn, $Codigo);
            $sql = "SELECT codigo,nombre,cantidad FROM tbl_materiales where Codigo = ?";
            if ($stmt = $this->conn->prepare($sql)) {
                $stmt->bind_param("s", $Codigo);
                $stmt->execute();
            } else {
                echo "Error de sintaxis en consulta SQL ";
            }
            $resultado = $stmt->get_result();
            $stmt->close();
            $this->conn->close();
            return $resultado;
        }
        

    }

}
