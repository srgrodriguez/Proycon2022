<?php

interface IMaquinaria{

public function AgregarMaquinaria(Herramientas $maquinaria);
public function ActualizarMaquinaria(Herramientas $maquinaria);
public function ListarTotalMaquinaria();
public function EliminarMaquinaria($codigo);
public function BuscarMaquinariaEnTiempoReal($strDescripcion);
public function BuscarMaquinariaPorCodigo($Codigo);
}

