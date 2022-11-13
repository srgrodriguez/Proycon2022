<?php

/**
 * Description of PedidoProveeduria
 *
 * @author Steven Rodriguez Garro
 */
class HeaderPedido {
   public $ID_Proyecto;
   public $ID_Usuairo;
   public $Fecha;
   public $Consecutivo;
   public $Comentarios;
           
   function HeaderPedido(){
       
   }
   function getID_Proyecto() {
       return $this->ID_Proyecto;
   }
   function getComentarios() {
       return $this->Comentarios;
   }

   function setComentarios($Comentarios) {
       $this->Comentarios = $Comentarios;
   }

      function getID_Usuairo() {
       return $this->ID_Usuairo;
   }

   function getFecha() {
       return $this->Fecha;
   }

   function getConsecutivo() {
       return $this->Consecutivo;
   }

   function setID_Proyecto($ID_Proyecto) {
       $this->ID_Proyecto = $ID_Proyecto;
   }

   function setID_Usuairo($ID_Usuairo) {
       $this->ID_Usuairo = $ID_Usuairo;
   }

   function setFecha($Fecha) {
       $this->Fecha = $Fecha;
   }

   function setConsecutivo($Consecutivo) {
       $this->Consecutivo = $Consecutivo;
   }


 
}
class CuerpoPedido {
   public $Consecutivo;
   public $Codigo;
   public $Cantidad;
   public $Tipo;
   function CuerpoPedido(){
       
   }
   function getTipo() {
       return $this->Tipo;
   }

   function setTipo($Tipo) {
       $this->Tipo = $Tipo;
   }

   
   function getConsecutivo() {
       return $this->Consecutivo;
   }

   function getCodigoHerramienta() {
       return $this->CodigoHerramienta;
   }

   function getCodigoMaterial() {
       return $this->CodigoMaterial;
   }

   function getCantidad() {
       return $this->Cantidad;
   }

   function setConsecutivo($Consecutivo) {
       $this->Consecutivo = $Consecutivo;
   }

   function setCodigoHerramienta($CodigoHerramienta) {
       $this->CodigoHerramienta = $CodigoHerramienta;
   }

   function setCodigoMaterial($CodigoMaterial) {
       $this->CodigoMaterial = $CodigoMaterial;
   }

   function setCantidad($Cantidad) {
       $this->Cantidad = $Cantidad;
   }


    
}