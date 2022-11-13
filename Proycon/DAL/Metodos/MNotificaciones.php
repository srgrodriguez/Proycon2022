<?php
/**
 * Description of MNotificaciones
 *
 * @author Steven
 */
class MNotificaciones implements INotificaciones {
    
    var $conn;
    public function __construct() {
        $conexion = new Conexion();
        $this->conn = $conexion->CrearConexion();
    }

   
    public function NotificarUsuarioBodega() {
        $sql ="SELECT COUNT(UsuarioBodega) as numPeidos,ID_Proyecto,NBoleta FROM tbl_notificaciones GROUP by ID_Proyecto,NBoleta;";
        $result =  $this-> conn->query($sql);
        $this-> conn->close();  
        return $result;
        
    }

}
