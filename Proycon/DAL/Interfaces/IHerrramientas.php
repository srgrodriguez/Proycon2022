<?php

interface IHerrramientas {

    public function RegistrarHerramientas(Herramientas $Herramientas);

    public function ObtenerConsecutivoTipo();

    public function listarTotalHerramientas();

    public function ObtenerConsecutivoHerramienta();

    public function ObternerCosecutivoReparacion();

    public function BuscarHerramientaNombre($descripcion);

    public function RegistrarTipoHerramienta(Herramientas $Tipo);

    public function listarTipoHerramientas();

     public function RegistrarReparacion($consecutivo, $fecha, $ID_Usuario,$provedorReparacion);

    public function cambiarTipo($ID_Tipo, $DescripcionTipo);

    public function RegistrarReparacionHerramienta($consecutivo, $codigoHerramienta, $fecha);

    public function totalReparaciones();

    public function FacturaReparacion($idReparacion);

    public function FacturacionReparacion(Factura $Facturacion);

    public function listarBoletasReparacion();

    public function VerBoletaReparacion($NumBoleta);

    public function listarTotalHerramientasTranslado();

    public function listaEnviadas($codigo);

    public function eliminarBoletaR($eliboleta);

    public function EliminarTraslado($CodigoTH);

    public function GuardarTrasladoT($CodigoT);

    public function ListarTrasladoMo();

    public function FiltroTrasladoTipo($tipo);

    public function FiltrosHerramientasU($ubicacion);

    public function buscarTraslado($Cod);

    public function reparacionesTotales($codigo);

    public function trasladosTotales($codigo);

    public function InfoHerramienta($codigo);

    public function FiltrosHerramientas0();

    public function FiltrosHerramientas1();

    public function FiltrosHerramientas2();

    public function FiltrosHerramientas3();

    public function FiltrosHerramientas4();

    public function FiltroReparacionfecha($fecha);

    public function FiltroReparacionTipo($tipo);

    public function FiltroReparacionCodigo($codigo);

    public function FiltroReparacionboleta($boleta);

    public function buscarherramienCodigo($Cod);

    public function BuscarTiempoRealHerramienta($consulta);

    public function ObternerCosecutivoPedido();
    public function ActualizarHerramienta(Herramientas $herramienta);

    public function FiltrarTipoTotalHerramienta();
    
}
