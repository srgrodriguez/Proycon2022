<?php
require_once '../Classes/PHPExcel.php';
require_once '../Autorizacion.php';
require_once 'FuncionesGeneralesReportes.php';
require_once '../../DAL/Interfaces/IMaquinaria.php';
require_once '../../DAL/Metodos/MMaquinaria.php';
require_once '../../DAL/Conexion.php';
require_once '../../DAL/FuncionesGenerales.php';

Autorizacion();
if (isset($_GET['opc'])) {
    $opc = $_GET['opc'];
    switch ($opc) {
        case "totalMaquinaria":
            GenerarExcelListadoMaquinaria();
            break;
        // case "buscarTiempoReal":
        //     GenerarExcelListadoMaquinariaTiempoReal();
        //     break;
        // case "buscarPorCodigo":
        //     GenerarExcelListadoMaquinariaPorCodigo();
        //     break;
        default:
            break;
    }
}

function GenerarExcelListadoMaquinaria()
{
    try {
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
       if(!isset($_GET['codigo']) && !isset($_GET['consulta'])){
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
    
    //    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    //    $objWriter->save('php://output');
    //    exit;
    
    $objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
    ob_start();
    $objWriter->save("php://output");
    $xlsData = ob_get_contents();
    ob_end_clean();
    
    $response =  array(
            'op' => 'ok',
            'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
        );
    //echo base64_encode($xlsData);
    
    die(json_encode($response));
    
    } catch (\Throwable $th) {
       $e = $th;
    }
   
}

function GenerarExcelListadoMaquinariaPorCodigo()
{
    try {
        $codigo = $_GET['codigo'];
        $objPHPExcel = new PHPExcel();
        $bdMaquinaria = new MMaquinaria();
        $objPHPExcel->getProperties()->setCreator("Reporte")
                ->setLastModifiedBy("Reporte")
                ->setTitle("Reporte Maquinaría")
                ->setSubject("Reporte Maquinaría")
                ->setDescription("Reporte Maquinaría.")
                ->setKeywords("office 2010 openxml php")
                ->setCategory("Reporte Maquinaría");
      GenerarEncabezadoReporte($objPHPExcel,"Equipo");
      $listadoColumnas = ["Código","Tipo","Precio Alquiler","Fecha Registro","Precio","Disposición","Ubicación","Estado"];
      GenerarEncabezadoTabla($objPHPExcel, $listadoColumnas);
    
       $resultado =  $bdMaquinaria->BuscarMaquinariaPorCodigo($codigo);
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
    //echo base64_encode($xlsData);
    
    die(json_encode($response));
    
    } catch (\Throwable $th) {
       $e = $th;
    }
   
}

function GenerarExcelListadoMaquinariaTiempoReal()
{
    try {
        $consulta = $_GET['consulta'];
        $objPHPExcel = new PHPExcel();
        $bdMaquinaria = new MMaquinaria();
        $objPHPExcel->getProperties()->setCreator("Reporte")
                ->setLastModifiedBy("Reporte")
                ->setTitle("Reporte Maquinaría")
                ->setSubject("Reporte Maquinaría")
                ->setDescription("Reporte Maquinaría.")
                ->setKeywords("office 2010 openxml php")
                ->setCategory("Reporte Maquinaría");
      GenerarEncabezadoReporte($objPHPExcel,"Equipo");
      $listadoColumnas = ["Código","Tipo","Precio Alquiler","Fecha Registro","Precio","Disposición","Ubicación","Estado"];
      GenerarEncabezadoTabla($objPHPExcel, $listadoColumnas);
    
       $resultado =  $bdMaquinaria->BuscarMaquinariaEnTiempoReal($consulta);
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
    
       $rangoTabla = "C8:E$i";
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
    //echo base64_encode($xlsData);
    
    die(json_encode($response));
    
    } catch (\Throwable $th) {
       $e = $th;
    }
   
}


