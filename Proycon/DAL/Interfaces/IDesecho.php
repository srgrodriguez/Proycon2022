<?php

interface IDesecho {

    public function listarDesecho();

    public function listarDesechoHerramienta();

    public function listarDesechoMateriales();

    public  function AgregarDesecho(Desecho $Desecho);

    public  function ActualizarDesecho(Desecho $Desecho);

    
}
