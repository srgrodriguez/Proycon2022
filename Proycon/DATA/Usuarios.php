<?php

class Usuarios {
    public $ID_Usuarios;
    public $ID_Rol;
    public $Nombre;
    public $Usuario;
    public $Password;
    public $Estado;
    
    function Usuarios() {
        
    }

    
    function getID_Usuarios() {
        return $this->ID_Usuarios;
    }

    function getID_Rol() {
        return $this->ID_Rol;
    }

    function getNombre() {
        return $this->Nombre;
    }

    function getUsuario() {
        return $this->Usuario;
    }

    function getPassword() {
        return $this->Password;
    }

    function setID_Usuarios($ID_Usuarios) {
        $this->ID_Usuarios = $ID_Usuarios;
    }

    function setID_Rol($ID_Rol) {
        $this->ID_Rol = $ID_Rol;
    }

    function setNombre($Nombre) {
        $this->Nombre = $Nombre;
    }

    function setUsuario($Usuario) {
        $this->Usuario = $Usuario;
    }

    function setPassword($Password) {
        $this->Password = $Password;
    }

    function getEstado() {
        return $this->Estado;
    }

    function setEstado($Estado) {
        $this->Estado = $Estado;
    }


    
    
    
    
}

