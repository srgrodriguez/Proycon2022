<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Factura
 *
 * @author Steven
 */
class Factura {
 public $Codigo;
 public $ID_Reparacion;
 public $NumFactura;
 public $FechaEntrada;
 public $DescripcionFactura;
 public $CostoFactura;
 public $NumBoleta;

 public function set($data) {
    $class = new Factura();
    foreach ($data AS $key => $value) $class->{$key} = $value;
    return $class;
}
}
