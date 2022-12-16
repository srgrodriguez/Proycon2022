<?php

class MboletasBodega {
    
    var $conn;

    public function __construct()
    {

        $conexion = new Conexion();
        $this->conn = $conexion->CrearConexion();
    }

    public function ListarBoletas() {

        $sql = "SELECT tb.Consecutivo, tb.ID_Proyecto, tb.TipoPedido, tu.Usuario, tb.Fecha, tp.Nombre FROM tbl_boletaspedido tb, tbl_usuario tu, tbl_proyectos tp WHERE tb.ID_Usuario = tu.ID_Usuario AND tb.ID_Proyecto = tp.ID_Proyecto AND tb.ID_Proyecto =1 ORDER BY Consecutivo DESC"; 
        $resultado =  $this->conn->query($sql);
       
        $this->conn->close();
        return $resultado;
    }



    public function buscarPedido($NumPedido) {

        $sql = "SELECT tb.Consecutivo, tb.ID_Proyecto, tb.TipoPedido, tu.Usuario, tb.Fecha, tp.Nombre FROM tbl_boletaspedido tb, tbl_usuario tu, tbl_proyectos tp WHERE tb.ID_Usuario = tb.ID_Usuario AND tb.ID_Proyecto = tp.ID_Proyecto AND tb.ID_Proyecto =1 tb.Consecutivo = ?"; 

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $NumPedido);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }


    public function MostrarPedidos($TipoPedido, $ID_Proyecto) {
        $TipoPedido = LimpiarCadenaCaracter($this->conn, $TipoPedido);
        $ID_Proyecto = LimpiarCadenaCaracter($this->conn, $ID_Proyecto);
        $sql = "SELECT Consecutivo,TipoPedido,Nombre,Fecha, tu.Usuario FROM tbl_boletaspedido tb, tbl_usuario tu WHERE tb.ID_Proyecto = ? AND TipoPedido= ? and tb.ID_Usuario = tu.ID_Usuario  AND tb.ID_Proyecto =1   ORDER BY CONSECUTIVO DESC;";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $ID_Proyecto, $TipoPedido);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

    public function BuscarBoletaPedido($numBoleta, $idProyecto) {
        $numBoleta = LimpiarCadenaCaracter($this->conn, $numBoleta);
        $idProyecto = LimpiarCadenaCaracter($this->conn, $idProyecto);
        $sql = "SELECT Consecutivo,TipoPedido,Nombre,Fecha,tu.Usuario FROM tbl_boletaspedido tb, tbl_usuario tu WHERE tb.ID_Usuario = tu.ID_Usuario and tb.Consecutivo = ? and tb.ID_Proyecto =  ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $numBoleta, $idProyecto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();
        $this->conn->close();
        return $resultado;
    }

}
