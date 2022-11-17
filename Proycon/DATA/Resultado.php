<?php

class Resultado {
    public $codigo;
    public bool $esValido;
    public $mensaje;

    
    function Herramientas() {
        
    }
    
    function getCodigo() {
        return $this->codigo;
    }

    function getMensaje() {
        return $this->mensaje;
    }
    
    function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    function setMensaje($mensaje) {
        $this->mensaje = $mensaje;
    }  

            
}
