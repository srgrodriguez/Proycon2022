<?php


interface IPedidos {
  public function ContarHerramientaDisponible($consulta); 
  public function ObternerCosecutivoPedido(); 
  public function ListarProyectos();
  public function ListarPedidosProyecto($ID_Proyecto);
  public function GenerarPedido(HeaderPedido $headerpedido);
  public function ContenidoPedido(CuerpoPedido $contenido);
  public function BuscarCorreoElectronico($busqueda);
  public function AdjuntarCorreoSiempre($ID_Usario);
  public function ObtenerCorreosAdjuntadosSiempre();
  public function DesAdjuntarCorreo($ID_Usario);
  public function VerPedido($ID_Boleta); 
  public function AnularBoletaPedido($ID_Boleta);
  public function BuscarPedido($ID_Boleta);
  public function VerificarProcesoDeBoleta($ID_Boleta);
}
