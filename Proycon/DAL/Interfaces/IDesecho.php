<?php

interface IDesecho {

    public function listarDesecho();

    public function listarDesechoHerramienta();

    public function listarDesechoMateriales();

    public  function ActualizarDesecho(Desecho $Desecho);

    public  function ConsultarDesecho($id);

    public function ObternerCosecutivoPedido();
}
