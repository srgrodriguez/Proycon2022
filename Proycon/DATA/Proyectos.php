<?php


class Proyectos {
   
    public  $Nombre;
    public  $Encargado;
    public $FechaCreacion;
    public $FechaCierre;
    public $TipoProyecto;
            

    function  Proyectos() {
       
    }
    
    function getNombre() {
        return $this->Nombre;
    }

    function getEncargado() {
        return $this->Encargado;
    }

    function getFechaCreacion() {
        return $this->FechaCreacion;
    }

    function getFechaCierre() {
        return $this->FechaCierre;
    }

    function getTipoProyecto() {
        return $this->TipoProyecto;
    }

    function setTipoProyecto($TipoProyecto) {
        $this->TipoProyecto = $TipoProyecto;
    }

    function setNombre($Nombre) {
        $this->Nombre = $Nombre;
    }

    function setEncargado($Encargado) {
        $this->Encargado = $Encargado;
    }

    function setFechaCreacion($FechaCreacion) {
        $this->FechaCreacion = $FechaCreacion;
    }

    function setFechaCierre($FechaCierre) {
        $this->FechaCierre = $FechaCierre;
    }


   


}
