<?php

interface IProyectos {
  public function RegistrarProyecto(Proyectos $proyecto);
  public function ListarProyectos();
  public function BuscarProyecto($nombre);
  public function BuscarProyectoID($ID);
  public function ModificarProyecto(Proyectos $proyecto,$ID);
  public function CerrarProyecto();
  public function ListaHerramientaProyecto($idProyecto);
  public function ListaMaterialesProyecto($idProyecto);
  public function ListarMaterialesPendientesProyecto($idProyecto);
  public function ObternerCosecutivoPedido();
  public function RegistrarPedido($consecutivo,$idProyecto,$fecha,$idUsuario,$TipoPedido);
  public function RegistrarPedidoMaterial($val);
  public function RegistrarPedidoHerramientas($val);
  public function MostrarPedidos($TipoPedido,$ID_Proyecto);
  public function VerPeidido($NumPedido,$TipoPedido);
  public function EliminarBoletaPedido($NBoleta,$Tipo);
  public function FitrosMaterialesProyecto($sql);
  public function FiltrosHerramientasProyecto($sql);
  public function AnularBoletaMaterial($NBoleta);
  public function AnularBoletaHerramientas($NBoleta);
  public function ColaPedidos($ID_Proyecto);
  public function MostrarPedidoProeveeduria($Boleta);
  public function EliminarNotificacion($Bolea);
  public function DevolucionMaterial(DevolucionMateriales $devolucion);
  public function ListarDevolucionesPorMaterial($ID_Material,$ID_Proyecto);
  public function ObtenerTotalMaterialPrestadoProyecto($ID_Material,$ID_Proyecto);
  public function BuscarBoletaPedido($numBoleta,$idProyecto);
  public function ObtenerNombreProyecto($ID_Proyecto);
  public function NombreProyecto($id);
  public function FinalizarProyecto($ID_Proyecto);
  public function HerramientaPorId($idProyecto, $idHerramienta);
  public function ListarSoloHerramienta($idProyecto);
 

  
  
}
