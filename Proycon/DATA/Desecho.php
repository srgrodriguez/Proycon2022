<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Factura
 *
 * @author Andrey
 */
class Desecho {
 public $Id;
 public $ID_Herramienta;
 public $Codigo;
 public $Motivo;
 public $FechaDesecho;
 public $ID_Usuario;
 public $TipoDesecho;
 public $Cantidad;
 public $Descripcion;


 function Desecho() {
        
}

function getId() {
    return $this->idHerramienta;
}
function getID_Herramienta() {
    return $this->ID_Herramienta;
}


function getCodigo() {
    return $this->Codigo;
}

function getMotivo() {
    return $this->Motivo;
}

function getFechaDesecho() {
    return $this->FechaDesecho;
}

function getID_Usuario() {
    return $this->ID_Usuario;
}

function getTipoDesecho() {
    return $this->TipoDesecho;
}

function getDescripcion() {
    return $this->Descripcion;
}

function setDescripcion($Descripcion) {
    $this->Descripcion;
}

function setID($Id) {
    $this->Id = $Id;
}


function cantidadID($Id) {
    $this->Id = $Id;
}

function setcantidad($Cantidad) {
    $this->Cantidad = $Cantidad;
}



function setID_Herramienta($ID_Herramienta) {
    $this->ID_Herramienta = $ID_Herramienta;
}

function setCodigo($Codigo) {
    $this->Codigo = $Codigo;
}

function setMotivo($Motivo) {
    $this->Motivo = $Motivo;
}

function setFechaDesecho($FechaDesecho) {
    $this->FechaDesecho = $FechaDesecho;
}

function setID_Usuario($ID_Usuario) {
    $this->ID_Usuario = $ID_Usuario;
}


function setTipoDesecho($TipoDesecho) {
    $this->TipoDesecho = $TipoDesecho;
}

}
