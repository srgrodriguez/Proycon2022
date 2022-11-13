<?php

class Herramientas {
    public $codigo;
    public $tipo;
    public $marca;
    public $descripcion;
    public $fechaIngreso;
    public $ubicacion;
    public $procedencia;
    public $precio;
    public $numFactura;
    public $estado;
    public $disposicion;
    
    function Herramientas() {
        
    }
    
    function getCodigo() {
        return $this->codigo;
    }

    function getProcedencia() {
        return $this->procedencia;
    }

    function getPrecio() {
        return $this->precio;
    }

    function setProcedencia($procedencia) {
        $this->procedencia = $procedencia;
    }

    function setPrecio($precio) {
        $this->precio = $precio;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getMarca() {
        return $this->marca;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function getFechaIngreso() {
        return $this->fechaIngreso;
    }

    function getUbicacion() {
        return $this->ubicacion;
    }

    function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setMarca($marca) {
        $this->marca = $marca;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function setFechaIngreso($fechaIngreso) {
        $this->fechaIngreso = $fechaIngreso;
    }

    function setUbicacion($ubicacion) {
        $this->ubicacion = $ubicacion;
    }

    function getNumFactura() {
        return $this->numFactura;
    }

    function setNumFactura($numFactura) {
        $this->numFactura = $numFactura;
    }




    
            
}

class TrasaldoHerramienta {
    public  $Codigo;
    public $Destino;
    public $Ubicacion;
    public $NumBoleta;
    public  $FechaFinal;
    public $ID_Usuario;
    function getCodigo() {
        return $this->Codigo;
    }

    function getDestino() {
        return $this->Destino;
    }

    function getUbicacion() {
        return $this->Ubicacion;
    }

    function getNumBoleta() {
        return $this->NumBoleta;
    }

    function getFechaFinal() {
        return $this->FechaFinal;
    }

    function getID_Usuario() {
        return $this->ID_Usuario;
    }

    function setCodigo($Codigo) {
        $this->Codigo = $Codigo;
    }

    function setDestino($Destino) {
        $this->Destino = $Destino;
    }

    function setUbicacion($Ubicacion) {
        $this->Ubicacion = $Ubicacion;
    }

    function setNumBoleta($NumBoleta) {
        $this->NumBoleta = $NumBoleta;
    }

    function setFechaFinal($FechaFinal) {
        $this->FechaFinal = $FechaFinal;
    }

    function setID_Usuario($ID_Usuario) {
        $this->ID_Usuario = $ID_Usuario;
    }


}
