<?php
require_once '../Classes/PHPExcel.php';
require_once '../Autorizacion.php';
require_once 'FuncionesGeneralesReportes.php';
require_once '../../DAL/Interfaces/IDesecho.php';
require_once '../../DAL/Metodos/MDesecho.php';
require_once '../../DAL/Conexion.php';
require_once '../../DAL/FuncionesGenerales.php';


Autorizacion();
if (isset($_GET['opc'])) {
    $opc = $_GET['opc'];
    switch ($opc) {
        case "listarHeramienta":
            GenerarExcelListadoDesechoH();
            break;

            case "listarMaterial":
                GenerarExcelListadoDesechoM();
                break;

            case "listar":
                GenerarExcelListadoDesechoMyH();
                    break;
       
    }
}



function GenerarExcelListadoDesechoH()
{
    try {


        $objPHPExcel = new PHPExcel();
        $bdDesecho = new MDesecho();
        $objPHPExcel->getProperties()->setCreator("Reporte")
                ->setLastModifiedBy("Reporte")
                ->setTitle("Reporte Desecho")
                ->setSubject("Reporte Desecho")
                ->setDescription("Reporte Desecho.")
                ->setKeywords("office 2010 openxml php")
                ->setCategory("Reporte Desecho");
      GenerarEncabezadoReporte($objPHPExcel,"Total de Desecho");
      $listadoColumnas = ["Boleta","Codigo","Cantidad","Descripcion","FechaDesecho","Usuario"];
      GenerarEncabezadoTabla($objPHPExcel, $listadoColumnas);
      $resultado =null;

      $resultado =  $bdDesecho->listarDesechoHerramientasR();
            
       $i = 9;
    
       while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    
           $objPHPExcel->setActiveSheetIndex(0)
                   ->setCellValue("C$i", $fila['Boleta'])
                   ->setCellValue("D$i", $fila['Codigo'])
                   ->setCellValue("E$i", $fila['Cantidad'])
                   ->setCellValue("F$i", $fila['Descripcion'])
                   ->setCellValue("G$i", $fila['FechaDesecho'])
                   ->setCellValue("H$i", $fila['Usuario']);

    
           $i++;
       }
    
       $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
       $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
    
       $rangoTabla = "C8:H$i";
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


function GenerarExcelListadoDesechoM()
{
    try {


        $objPHPExcel = new PHPExcel();
        $bdDesecho = new MDesecho();
        $objPHPExcel->getProperties()->setCreator("Reporte")
                ->setLastModifiedBy("Reporte")
                ->setTitle("Reporte Desecho")
                ->setSubject("Reporte Desecho")
                ->setDescription("Reporte Desecho.")
                ->setKeywords("office 2010 openxml php")
                ->setCategory("Reporte Desecho");
      GenerarEncabezadoReporte($objPHPExcel,"Total de Desecho");
      $listadoColumnas = ["Boleta","Codigo","Cantidad","Descripcion","FechaDesecho","Usuario"];
      GenerarEncabezadoTabla($objPHPExcel, $listadoColumnas);
      $resultado =null;

      $resultado =  $bdDesecho->listarDesechoMaterialesR();
            
       $i = 9;
    
       while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    
           $objPHPExcel->setActiveSheetIndex(0)
                   ->setCellValue("C$i", $fila['Boleta'])
                   ->setCellValue("D$i", $fila['Codigo'])
                   ->setCellValue("E$i", $fila['Cantidad'])
                   ->setCellValue("F$i", $fila['Descripcion'])
                   ->setCellValue("G$i", $fila['FechaDesecho'])
                   ->setCellValue("H$i", $fila['Usuario']);

    
           $i++;
       }
    
       $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
       $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
    
       $rangoTabla = "C8:H$i";
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



function GenerarExcelListadoDesechoMyH()
{
    try {


        $objPHPExcel = new PHPExcel();
        $bdDesecho = new MDesecho();
        $objPHPExcel->getProperties()->setCreator("Reporte")
                ->setLastModifiedBy("Reporte")
                ->setTitle("Reporte Desecho")
                ->setSubject("Reporte Desecho")
                ->setDescription("Reporte Desecho.")
                ->setKeywords("office 2010 openxml php")
                ->setCategory("Reporte Desecho");
      GenerarEncabezadoReporte($objPHPExcel,"Total de Desecho");
      $listadoColumnas = ["Boleta","Codigo","Cantidad","Descripcion","FechaDesecho","Usuario"];
      GenerarEncabezadoTabla($objPHPExcel, $listadoColumnas);
      $resultado =null;

      $resultado =  $bdDesecho->listarDesechoMaterialesMyH();
            
       $i = 9;
    
       while ($fila = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
    
           $objPHPExcel->setActiveSheetIndex(0)
                   ->setCellValue("C$i", $fila['Boleta'])
                   ->setCellValue("D$i", $fila['Codigo'])
                   ->setCellValue("E$i", $fila['Cantidad'])
                   ->setCellValue("F$i", $fila['Descripcion'])
                   ->setCellValue("G$i", $fila['FechaDesecho'])
                   ->setCellValue("H$i", $fila['Usuario']);

    
           $i++;
       }
    
       $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(45);
       $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
       $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
    
       $rangoTabla = "C8:H$i";
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
