<?php

interface IMateriales {
    public  function BuscarMaterial($idMaterial,$cant);
    public  function BuscarMaterialNombre($nombre); 
    public  function AgregarMateriales(Materiales $Materiales);
    public  function VerificarDisponibilidad($codigo);
    public  function UpdateMateriales(Materiales $Materiales);
    public  function listarTotalMateriales();
    public function BuscarMaterialCodigo($Codigo);
    
   
}
