<?php
require_once '../../DAL/Constantes.php';

function Headers() {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="pedido_.xls"');
    header('Pragma: no-cache');
    header('Expires: 0');
}

function GenerarEncabezadoReporte(PHPExcel $objPHPExcel,$tituloReporte)
{
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', $tituloReporte);
            $objPHPExcel->getActiveSheet()->getStyle('C2:F4')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));

            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setName('imgNotice');
            $objDrawing->setDescription('Noticia');
            $img = Constantes::ImagenReportes;
            $objDrawing->setPath($img);
            $objDrawing->setOffsetX(28);    // setOffsetX works properly
            $objDrawing->setOffsetY(200);  //setOffsetY has no effect
            $objDrawing->setCoordinates('A1');
            $objDrawing->setHeight(100); // logo height
            $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));

}

function GenerarEncabezadoTabla(PHPExcel $objPHPExcel,array $listadoColumnas,$numFilaInicioTabla= 8)
{
  
    $i= $numFilaInicioTabla;
    $contadorObtenerPosicionLetra=2;
    $ultimaLetra= "";
    foreach ($listadoColumnas as &$value) {
        $letra = GenerarArrayLetras($contadorObtenerPosicionLetra);
        $posicion = $letra.$i;
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue("$posicion", $value);
        $ultimaLetra= $letra;
        $contadorObtenerPosicionLetra++;
    }

    $rangoEncabezado = "C$numFilaInicioTabla:$ultimaLetra".$i;
    $objPHPExcel->getActiveSheet()->getStyle($rangoEncabezado)->applyFromArray(
        array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
            'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));
    
    $objPHPExcel->getActiveSheet()->getStyle($rangoEncabezado)->getFill()->applyFromArray(array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => '003DA6')));  
    
}

function GenerarArrayLetras(int $posicionLetra):string{
    $array =  array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
    return  $array[$posicionLetra];
}