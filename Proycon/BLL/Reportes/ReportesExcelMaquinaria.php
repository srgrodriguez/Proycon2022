<?php
require_once '../Classes/PHPExcel.php';
require_once '../Autorizacion.php';
require_once 'FuncionesGeneralesReportes.php';
require_once '../../DAL/Interfaces/IMaquinaria.php';
require_once '../../DAL/Metodos/MMaquinaria.php';
require_once '../../DAL/Conexion.php';
require_once '../../DAL/FuncionesGenerales.php';
require_once '../../DAL/Metodos/MHistoria_Y_ReparacionesMaquinaria.php';
Autorizacion();
if (isset($_GET['opc'])) {
    $opc = $_GET['opc'];
    switch ($opc) {
        case "totalMaquinaria":
            GenerarExcelListadoMaquinaria();
            break;
        case "historialReparaciones":
            GenerarReporteHistorial_Y_ReparacionesMaquinaria();
            break;
         case "maquinariaReparacion":
             GenerarReporteMaquinariaEnReparacion();
             break;
        default:
            break;
    }
}

function GenerarExcelListadoMaquinaria()
{
    try {
        if(isset($_GET['filtro']) && $_GET['filtro'] =="VerTotales")
        {
            die(GenerarReporteTotalPorTipo());
        }

        $objPHPExcel = new PHPExcel();
        $bdMaquinaria = new MMaquinaria();
        $objPHPExcel->getProperties()->setCreator("Reporte")
                ->setLastModifiedBy("Reporte")
                ->setTitle("Reporte Maquinaría")
                ->setSubject("Reporte Maquinaría")
                ->setDescription("Reporte Maquinaría.")
                ->setKeywords("office 2010 openxml php")
                ->setCategory("Reporte Maquinaría");
      GenerarEncabezadoReporte($objPHPExcel,"Total de maquinaria");
      $listadoColumnas = ["Código","Tipo","Precio Alquiler","Fecha Registro","Precio","Disposición","Ubicación","Estado"];
      GenerarEncabezadoTabla($objPHPExcel, $listadoColumnas);
      $resultado =null;
       if(!isset($_GET['codigo']) && !isset($_GET['consulta']) && !isset($_GET['filtro'])){
        $resultado =  $bdMaquinaria->ListarTotalMaquinaria();
       }
       elseif (isset($_GET['consulta']))
       {
        $consulta = $_GET['consulta'];
        $resultado =  $bdMaquinaria->BuscarMaquinariaEnTiempoReal($consulta);
       }
       elseif (isset($_GET['codigo']))
      {
        $codigo = $_GET['codigo'];
        $resultado =  $bdMaquinaria->BuscarMaquinariaPorCodigo($codigo);
      } 
      elseif (isset($_GET['filtro']))
      {
        $filtro = $_GET['filtro'];
        $resultado =  $bdMaquinaria->OrdenarConsusltaMaquinaria($filtro);
      } 
       
       $i = 9;
    
       while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
        $monedaCompra =$fila["MonedaCompra"] == "D" ? "$" : "¢";
        $Monto =  number_format($fila['Precio'], 2, ",", ".");
        $monedaCorbo = $fila['CodigoMonedaCobro'] == "C" ? "¢" : "$";
        $PrecioAlquiler =  number_format($fila['PrecioEquipo'], 2, ",", ".");
        $Fecha = date('d/m/Y', strtotime($fila['FechaIngreso']));
    
           $objPHPExcel->setActiveSheetIndex(0)
                   ->setCellValue("C$i", $fila['Codigo'])
                   ->setCellValue("D$i", $fila['Tipo'])
                   ->setCellValue("E$i", $monedaCorbo . $PrecioAlquiler)
                   ->setCellValue("F$i", $Fecha)
                   ->setCellValue("G$i", $monedaCompra. $Monto)
                   ->setCellValue("H$i",$fila['Disposicion'])
                   ->setCellValue("I$i",$fila['Ubicacion'])
                   ->setCellValue("J$i",$fila['Estado']);
    
           $i++;
       }
    
       $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
       $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
    
       $rangoTabla = "C8:J$i";
       $objPHPExcel->getActiveSheet()->getStyle($rangoTabla)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
       'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));
    
       $objPHPExcel->getActiveSheet()->setTitle('Reporte');
       Headers();
    $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
    ob_start();
    $objWriter->save("php://output");
    $xlsData = ob_get_contents();
    ob_end_clean();
    
    $response =  array(
            'op' => 'ok',
            'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
        );
    
    die(json_encode($response));
    
    } catch (\Throwable $th) {
       $e = $th;
    }
   
}

function GenerarReporteHistorial_Y_ReparacionesMaquinaria()
{
    $codigo =$_GET["codigo"];
    try {
      
        $objPHPExcel = new PHPExcel();
        $bdMaquinaria = new MMaquinaria();
        $resultadoInfoMaquinaria =   $bdMaquinaria->BuscarMaquinariaPorCodigo($codigo);
        $bdHistorial = new MHistoria_Y_ReparacionesMaquinaria();
        $historial = $bdHistorial->ConsultarHistorialMaquinaria($codigo);
        $bdReparaciones = new MHistoria_Y_ReparacionesMaquinaria();
        $reparaciones = $bdReparaciones->ConsultarReparacionesMaquinaria($codigo);

        $objPHPExcel->getProperties()->setCreator("Reporte")
        ->setLastModifiedBy("Reporte")
        ->setTitle("Reporte historial y reparaciones")
        ->setSubject("Reportehistorial y reparaciones")
        ->setDescription("Reporte historial y reparaciones.")
        ->setKeywords("office 2010 openxml php")
        ->setCategory("Reporte historial y reparaciones");
        GenerarEncabezadoReporte($objPHPExcel,"Reporte historial y reparaciones maquinaria");
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("C5", "Código")
        ->setCellValue("C6", "Marca")
        ->setCellValue("C7", "Tipo")
        ->setCellValue("C8", "Fecha Compra")
        ->setCellValue("C9", "Procedencia")
        ->setCellValue("C10", "Valor compra")
        ->setCellValue("C11", "Valor alquiler");

        $infoEquipo = mysqli_fetch_array($resultadoInfoMaquinaria, MYSQLI_ASSOC);
        $monedaCompra =$infoEquipo["MonedaCompra"] == "D" ? "$" : "¢";
        $monedaCobro = $infoEquipo['CodigoMonedaCobro'] == "C" ? "¢" : "$";
        $PrecioAlquiler =  number_format($infoEquipo['PrecioEquipo'], 2, ",", ".");
        $PrecioCompra =  number_format($infoEquipo['Precio'], 2, ",", ".");
        $Fecha = date('d/m/Y', strtotime($infoEquipo['FechaIngreso']));
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("D5", $infoEquipo["Codigo"])
        ->setCellValue("D6", $infoEquipo["Marca"])
        ->setCellValue("D7", $infoEquipo["Tipo"])
        ->setCellValue("D8", $Fecha)
        ->setCellValue("D9", $infoEquipo["Procedencia"])
        ->setCellValue("D10", $monedaCompra.$PrecioCompra)
        ->setCellValue("D11", $monedaCobro.$PrecioAlquiler);
 
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("C14", "Reparaciones Maquinaria");
        $listadoColumnas = ["FECHA","NUMERO FACTURA","DESCRIPCIÓN","COSTO"];
         GenerarEncabezadoTabla($objPHPExcel, $listadoColumnas,15);

         $i = 16;
         $filaInicioTablaReparaciones = 16;
         $costoTotalReparacion = 0;
         while ($fila = mysqli_fetch_array($reparaciones, MYSQLI_ASSOC)) {
          $MontoReparacion =  number_format($fila['MontoReparacion'], 2, ",", ".");
          $Fecha = date('d/m/Y', strtotime($fila['FechaEntrada']));
          $costoTotalReparacion = $costoTotalReparacion+$fila['MontoReparacion'];
             $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue("C$i", $Fecha)
                     ->setCellValue("D$i", $fila['ID_FacturaReparacion'])
                     ->setCellValue("E$i",$fila['Descripcion'])
                     ->setCellValue("F$i",  $MontoReparacion);
      
             $i++;
         }

         $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue("E$i","Total")
         ->setCellValue("F$i",  $costoTotalReparacion);

         $rangoTablaReparaciones = "C$filaInicioTablaReparaciones:F$i";
         $objPHPExcel->getActiveSheet()->getStyle($rangoTablaReparaciones)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
         'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));
      

         $i=$i+2;
         $objPHPExcel->setActiveSheetIndex(0)
         ->setCellValue("C$i","Historial de traslados");
         $i++;
         $listadoColumnas = ["Fecha","NºBoleta","Ubicación","Destino"];
         GenerarEncabezadoTabla($objPHPExcel, $listadoColumnas,$i);
         $i++;
          $filaInicioTablaHistorial = $i;
         while ($fila = mysqli_fetch_array($historial, MYSQLI_ASSOC)) {
            $Fecha = date('d/m/Y', strtotime($fila['Fecha']));
            $destino = $fila['Destino'] == "" ? "En reparación" : $fila['Destino'];
            $ubicacion =  $fila['Ubicacion'] == "" ? "En reparación" : $fila['Ubicacion'];
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C$i", $Fecha)
                ->setCellValue("D$i", $fila['NumBoleta'])
                ->setCellValue("E$i",$ubicacion)
                ->setCellValue("F$i",$destino);
            
        
               $i++;
           }

           $rangoTablaHistorial = "C$filaInicioTablaHistorial:F$i";
           $objPHPExcel->getActiveSheet()->getStyle($rangoTablaHistorial)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
           'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));
        

         $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
         $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
         $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(40);
         $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
         $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
         $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
      
         $objPHPExcel->getActiveSheet()->setTitle('Reporte');
         Headers();
      $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
      ob_start();
      $objWriter->save("php://output");
      $xlsData = ob_get_contents();
      ob_end_clean();
      
      $response =  array(
              'op' => 'ok',
              'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
          );
      
      die(json_encode($response));


 
    } catch (\Throwable $th) {
        //throw $th;
    }
}

function GenerarReporteMaquinariaEnReparacion()
{
    $tipoFiltro =$_GET["tipoFiltro"];
    try {
        
        $codigo ="";
        $tipo="";
        if($tipoFiltro == "codigo"){
            $codigo =$_GET["codigo"];
        }
        else 
        {
            $tipo=$_GET["tipo"];
        }

       $objPHPExcel = new PHPExcel();
        $bdHistorial = new MHistoria_Y_ReparacionesMaquinaria();

        if($tipoFiltro == "todo"){
            $maquinariaReparacion = $bdHistorial->ConsultarTodaMaquinariaReparacion();
        }
        else
            if($codigo != "")
            $maquinariaReparacion = $bdHistorial->ConsultarMaquinariaReparacionPorCodigo($codigo);
            else
            $maquinariaReparacion = $bdHistorial->ConsultarMaquinariaReparacionPorTipo($tipo);


        $objPHPExcel->getProperties()->setCreator("Reporte")
        ->setLastModifiedBy("Reporte")
        ->setTitle("Reporte historial y reparaciones")
        ->setSubject("Reportehistorial y reparaciones")
        ->setDescription("Reporte historial y reparaciones.")
        ->setKeywords("office 2010 openxml php")
        ->setCategory("Reporte historial y reparaciones");
        GenerarEncabezadoReporte($objPHPExcel,"Reporte maquinaria en reparación");

        $listadoColumnas = ["Código","Tipo","Fecha Salida","Dias","Proveedor Reparación","NumBoleta"];
         GenerarEncabezadoTabla($objPHPExcel, $listadoColumnas);

         $i = 9;
         $filaInicioTabla = 9;
         while ($fila = mysqli_fetch_array($maquinariaReparacion, MYSQLI_ASSOC)) {
             $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue("C$i", $fila["Codigo"])
                     ->setCellValue("D$i", $fila['Descripcion'])
                     ->setCellValue("E$i",$fila['Fecha'])
                     ->setCellValue("F$i", $fila['Dias'])
                     ->setCellValue("G$i", $fila['ProveedorReparacion'])
                     ->setCellValue("H$i", $fila['Boleta']);
      
             $i++;
         }


         $rangoTablaReparaciones = "C$filaInicioTabla:H$i";
         $objPHPExcel->getActiveSheet()->getStyle($rangoTablaReparaciones)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
         'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));
      
         $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
         $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
         $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
         $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
         $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
         $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
         $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
      
         $objPHPExcel->getActiveSheet()->setTitle('Reporte');
         Headers();
      $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
      ob_start();
      $objWriter->save("php://output");
      $xlsData = ob_get_contents();
      ob_end_clean();
      
      $response =  array(
              'op' => 'ok',
              'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
          );
      
      die(json_encode($response));

    } catch (\Throwable $th) {
        //throw $th;
    }
}

function GenerarReporteTotalPorTipo()
{
    try {

        $objPHPExcel = new PHPExcel();
        $bdMaquinaria = new MMaquinaria();

       $resultado =  $bdMaquinaria ->OrdenarConsusltaMaquinaria("VerTotales");
        $objPHPExcel->getProperties()->setCreator("Reporte")
        ->setLastModifiedBy("Reporte")
        ->setTitle("Reporte total por tipo de equipo")
        ->setSubject("Reporte total por tipo de equipo")
        ->setDescription("Reporte total por tipo de equipo")
        ->setKeywords("office 2010 openxml php")
        ->setCategory("Reporte total por tipo de equipo");
        GenerarEncabezadoReporte($objPHPExcel,"Reporte total por tipo de equipo");

        $listadoColumnas = ["Descripción","Cantidad"];
         GenerarEncabezadoTabla($objPHPExcel, $listadoColumnas);

         $i = 9;
         $filaInicioTabla = 9;
         while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
             $objPHPExcel->setActiveSheetIndex(0)
                     ->setCellValue("C$i", $fila["Descripcion"])
                     ->setCellValue("D$i", $fila['Cantidad']);
      
             $i++;
         }


         $rangoTablaReparaciones = "C$filaInicioTabla:D$i";
         $objPHPExcel->getActiveSheet()->getStyle($rangoTablaReparaciones)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
         'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));
      
         $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
         $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
         $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
         $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
         $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
         $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
         $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
      
         $objPHPExcel->getActiveSheet()->setTitle('Reporte');
         Headers();
      $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
      ob_start();
      $objWriter->save("php://output");
      $xlsData = ob_get_contents();
      ob_end_clean();
      
      $response =  array(
              'op' => 'ok',
              'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
          );
      
     return json_encode($response);

    } catch (\Throwable $th) {
        //throw $th;
    }
}


