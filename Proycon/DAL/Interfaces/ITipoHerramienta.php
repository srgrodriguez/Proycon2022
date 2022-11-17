<?php

interface ITipoHerramienta{

public function AgregarTipoHerramienta(TipoHerramienta $tipoHerramienta);
public function ActualizarTipoHerramienta(TipoHerramienta $tipoHerramienta);
public function ListarTipoHerramientas($tipo);

}

