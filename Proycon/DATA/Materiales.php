<?php


class Materiales {
    public $idHerramienta;
    public $Codigo;
    public $Nombre;
    public $Cantidad;
    public $Disponibilidad;
    public $Devolucion;
    public $Stock;
    
    function Materiales() {
        
    }
    
    function getIdHerramienta() {
        return $this->idHerramienta;
    }
    function getStock() {
        return $this->Stock;
    }

    function setStock($Stock) {
        $this->Stock = $Stock;
    }

        function setIdHerramienta($idHerramienta) {
        $this->idHerramienta = $idHerramienta;
    }
        
    function getCodigo() {
        return $this->Codigo;
    }

    function getNombre() {
        return $this->Nombre;
    }

    function getCantidad() {
        return $this->Cantidad;
    }

    function getDisponibilidad() {
        return $this->Disponibilidad;
    }

    function setCodigo($Codigo) {
        $this->Codigo = $Codigo;
    }

    function setNombre($Nombre) {
        $this->Nombre = $Nombre;
    }

    function setCantidad($Cantidad) {
        $this->Cantidad = $Cantidad;
    }

    function setDisponibilidad($Disponibilidad) {
        $this->Disponibilidad = $Disponibilidad;
    }

    function getDevolucion() {
        return $this->Devolucion;
    }

    function setDevolucion($Devolucion) {
        $this->Devolucion = $Devolucion;
    }



    
}
class DevolucionMateriales{

    public $Codigo;
    public $Cantidad;
    public $fecha;
    public $NBoleta;
    public $ID_Proyecto;
    
    
    function getCodigo() {
        return $this->Codigo;
    }

    function getCantidad() {
        return $this->Cantidad;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getNBoleta() {
        return $this->NBoleta;
    }

    function getID_Proyecto() {
        return $this->ID_Proyecto;
    }

    function setCodigo($Codigo) {
        $this->Codigo = $Codigo;
    }

    function setCantidad($Cantidad) {
        $this->Cantidad = $Cantidad;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setNBoleta($NBoleta) {
        $this->NBoleta = $NBoleta;
    }

    function setID_Proyecto($ID_Proyecto) {
        $this->ID_Proyecto = $ID_Proyecto;
    }


}