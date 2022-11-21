<?php

interface IMaquinaria{

public function AgregarMaquinaria(Herramientas $maquinaria);
public function ActualizarMaquinaria(Herramientas $maquinaria);
public function ListarTotalMaquinaria();
public function EliminarMaquinaria(string $codigo,string $motivo);
public function BuscarMaquinariaEnTiempoReal(string $strDescripcion);
public function BuscarMaquinariaPorCodigo(string $Codigo);
}

