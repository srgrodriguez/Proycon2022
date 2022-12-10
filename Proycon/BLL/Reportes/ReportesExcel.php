<?php

/*
  Este Archivo se encargara de realizar todos los reportes a excel
 */

require_once '../Classes/PHPExcel.php';
require_once '../../DAL/Interfaces/IProyectos.php';
require_once '../../DAL/Metodos/MProyectos.php';
require_once '../../DAL/Interfaces/IMateriales.php';
require_once '../../DAL/Interfaces/IHerrramientas.php';
require_once '../../DAL/Metodos/MHerramientas.php';
require_once '../../DAL/Metodos/MMaterial.php';
require_once '../../DAL/Conexion.php';
require_once '../../DAL/FuncionesGenerales.php';
require_once '../Autorizacion.php';
require_once '../../DAL/Log.php';

Autorizacion();
//prueba();
//ExportarExcelFinalizarProyecto(12);
if (isset($_POST['txtReporteMaterilesP'])) {
    ExportarExcelMaterialesProyecto($_POST['txtID_ProyectoMateriales']);
}
if (isset($_POST['txtReporteHerramientasP'])) {
    ExportarExcelHerramientasProyecto($_POST['txtID_Proyecto']);
}
if (isset($_POST['txtIDMaterial'])) {
    ExportarTablaDevolucionMaterial($_POST['txtIDMaterial'], $_POST['txtIDProyectotblDevolucion']);
}


if (isset($_POST['txtReporteMaquinaria'])) {
    ExportarExcelMaquinariaProyecto($_POST['txtID_Proyecto']);
}

if (isset($_POST['Codigo'])) {
    if ($_POST['Codigo'] != "") {
        ExportarContenidoTblMateriales($_POST['Codigo']);
    } else {
        ExportarExcelTotalMateriales();
    }
}
if (isset($_GET['txtFinalizarProyecto'])) {
    ExportarExcelFinalizarProyecto($_GET['txtFinalizarProyecto']);
}
if (isset($_POST['txtTotalHerramienta'])) {
    ExportarExcelTotalHerramientas();
}

if (isset($_POST['codigo'])) {
    ExportarHistoriaHerramienta();
}

function ExportarExcelMaterialesProyecto($ID_Proyecto) {
    $objPHPExcel = new PHPExcel();
    $bdProyectos = new MProyectos();
    $opc = $_POST['cboFitrarMateriales'];
    switch ($opc) {
        case "Filtrar por...":$result = $bdProyectos->ListaMaterialesProyecto($ID_Proyecto);
            break;
        case "Nombre":$result = $bdProyectos->FitrosMaterialesProyecto("SELECT m.Codigo, m.Nombre,pm.Cantidad,bp.Fecha,bp.Consecutivo FROM tbl_boletaspedido bp, tbl_prestamomateriales pm,tbl_materiales m where bp.Consecutivo = pm.NBoleta and bp.ID_Proyecto = $ID_Proyecto and pm.ID_Material = m.Codigo ORDER BY m.Nombre ASC");
            break;
        case "Fecha":$result = $bdProyectos->FitrosMaterialesProyecto("SELECT m.Codigo,m.Nombre,pm.Cantidad,bp.Fecha,bp.Consecutivo FROM tbl_boletaspedido bp, tbl_prestamomateriales pm,tbl_materiales m where bp.Consecutivo = pm.NBoleta and bp.ID_Proyecto = $ID_Proyecto and pm.ID_Material = m.Codigo ORDER BY bp.Fecha ASC");
            break;
        case "Pedientes":ExportarPendientesProyecto($ID_Proyecto);
            break;
        case "Ver Totales":ExportarVerTotalesMateriales($ID_Proyecto);
            break;
        default:
            break;
    };
    $objPHPExcel->getProperties()->setCreator("Reporte")
            ->setLastModifiedBy("Reporte")
            ->setTitle("Reporte Materiales")
            ->setSubject("Reporte Materiales")
            ->setDescription("Reporte Materiales.")
            ->setKeywords("office 2010 openxml php")
            ->setCategory("Reporte Materiales");

    $nombreProyecto = $bdProyectos->ObtenerNombreProyecto($ID_Proyecto);
    $fila = mysqli_fetch_array($nombreProyecto, MYSQLI_ASSOC);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', 'Reportes del sistema')
            ->setCellValue('C4', 'Proyecto')
            ->setCellValue('D4', $fila['Nombre'])
            ->setCellValue('C6', 'CODIGO')
            ->setCellValue('D6', 'NOMBRE')
            ->setCellValue('E6', 'CANTIDAD')
            ->setCellValue('F6', 'FECHA')
            ->setCellValue('G6', 'N°BOLETA');




    $i = 7;

    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C$i", $fila['Codigo'])
                ->setCellValue("D$i", $fila['Nombre'])
                ->setCellValue("E$i", $fila['Cantidad'])
                ->setCellValue("F$i", date('d/m/Y', strtotime($fila['Fecha'])))
                ->setCellValue("G$i", $fila['Consecutivo']);
        $i++;
    }
    $rango = "C7:G$i";



    // Fuente de la primera fila en negrita
    //Alinear al centro ,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    $objPHPExcel->getActiveSheet()->getStyle('C2:F4')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    // $objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //  'type' => PHPExcel_Style_Fill::FILL_SOLID,
    //  'startcolor' => array('rgb' => 'ffffff')));
    //$rango="C2:F3";
    // $styleArray = array('font' => array( 'name' => 'Calibri','size' => 18)); 
    // $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('C6:G6')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );
    // Cambiar el nombre de hoja de cálculo
    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C6:G6')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Reporte');
    Headers();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

function ExportarPendientesProyecto($ID_Proyecto) {
    $objPHPExcel = new PHPExcel();
    $bdProyectos = new MProyectos();
    $result = $bdProyectos->FitrosMaterialesProyecto("SELECT tp.ID_Material,m.Nombre,SUM(tp.Cantidad) as Prestamo,(SELECT SUM(Cantidad) FROM tbl_devolucionmateriales WHERE ID_Proyecto = $ID_Proyecto AND Codigo = tp.ID_Material GROUP BY Codigo)as Devolucion from tbl_prestamomateriales tp, tbl_materiales m,tbl_boletaspedido p where tp.ID_Material = m.Codigo and p.ID_Proyecto = $ID_Proyecto and tp.NBoleta = p.Consecutivo AND m.Devolucion = 1 GROUP by tp.ID_Material, m.Nombre ");
    $objPHPExcel->getProperties()->setCreator("Reporte")
            ->setLastModifiedBy("Reporte")
            ->setTitle("Reporte Materiales")
            ->setSubject("Reporte Materiales")
            ->setDescription("Reporte Materiales.")
            ->setKeywords("office 2010 openxml php")
            ->setCategory("Reporte Materiales");
    $nombreProyecto = $bdProyectos->ObtenerNombreProyecto($ID_Proyecto);
    $fila = mysqli_fetch_array($nombreProyecto, MYSQLI_ASSOC);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', 'Resumen Materiales Pendientes por devolver')
            ->setCellValue('C4', 'Proyecto')
            ->setCellValue('D4', $fila['Nombre'])
            ->setCellValue('C6', 'CODIGO')
            ->setCellValue('D6', 'NOMBRE')
            ->setCellValue('E6', 'PRESTAMO')
            ->setCellValue('F6', 'DEVOLUCION')
            ->setCellValue('G6', 'PENDIENTE');




    $i = 7;

    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $pendiente = $fila['Prestamo'] - $fila['Devolucion'];
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C$i", $fila['ID_Material'])
                ->setCellValue("D$i", $fila['Nombre'])
                ->setCellValue("E$i", $fila['Prestamo'])
                ->setCellValue("F$i", $fila['Devolucion'])
                ->setCellValue("G$i", $pendiente);
        $i++;
    }
    $rango = "C7:G$i";



    // Fuente de la primera fila en negrita
    //Alinear al centro ,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    $objPHPExcel->getActiveSheet()->getStyle('C2:F4')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    // $objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //  'type' => PHPExcel_Style_Fill::FILL_SOLID,
    //  'startcolor' => array('rgb' => 'ffffff')));
    //$rango="C2:F3";
    // $styleArray = array('font' => array( 'name' => 'Calibri','size' => 18)); 
    // $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('C6:G6')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );
    // Cambiar el nombre de hoja de cálculo
    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C6:G6')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Reporte');
    Headers();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

function ExportarExcelHerramientasProyecto($ID_Proyecto) {
    $objPHPExcel = new PHPExcel();
    $bdProyectos = new MProyectos();
    $opc = $_POST['cboFiltrarHerramientas'];
    $result = "";
    switch ($opc) {
        case "Filtrar por...":$result = $bdProyectos->ListaHerramientaProyecto($ID_Proyecto);
            break;
        case "Reparacion":$result = $bdProyectos->FiltrosHerramientasProyecto("SELECT tp.Codigo,tt.Descripcion,tp.FechaSalida, th.Estado, tp.NBoleta FROM tbl_prestamoherramientas tp, tbl_herramientaelectrica th, tbl_tipoherramienta tt where tp.ID_Proyecto = $ID_Proyecto and tp.ID_Tipo = tt.ID_Tipo and tp.Codigo =  th.Codigo and th.Estado = 0 ORDER by tp.FechaSalida ASC;");
            break;
        case "Fecha":$result = $bdProyectos->FiltrosHerramientasProyecto("SELECT tp.Codigo,tt.Descripcion,tp.FechaSalida, th.Estado, tp.NBoleta FROM tbl_prestamoherramientas tp, tbl_herramientaelectrica th, tbl_tipoherramienta tt where 
 tp.ID_Proyecto = $ID_Proyecto and tp.ID_Tipo = tt.ID_Tipo and tp.Codigo =  th.Codigo ORDER by tp.FechaSalida ASC;");
            break;
        case "Ver Totales":ExportarVerTotalesHerramienta($ID_Proyecto);
            break;
        default:
            break;
    }
    $objPHPExcel = new PHPExcel();
    // $bdProyectos = new MProyectos();
    $objPHPExcel->getProperties()->setCreator("Reporte")
            ->setLastModifiedBy("Reporte")
            ->setTitle("Reporte Materiales")
            ->setSubject("Reporte Materiales")
            ->setDescription("Reporte Materiales.")
            ->setKeywords("office 2010 openxml php")
            ->setCategory("Reporte Materiales");

    $nombreProyecto = $bdProyectos->ObtenerNombreProyecto($ID_Proyecto);
    $fila = mysqli_fetch_array($nombreProyecto, MYSQLI_ASSOC);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', 'Reportes del sistema')
            ->setCellValue('C4', 'Proyecto')
            ->setCellValue('D4', $fila['Nombre'])
            ->setCellValue('C6', 'CODIGO')
            ->setCellValue('D6', 'TIPO')
            ->setCellValue('E6', 'FECHA')
            ->setCellValue('F6', 'N°BOLETA')
            ->setCellValue('G6', 'ESTADO');



    //$result = $bdProyectos ->ListaHerramientaProyecto($ID_Proyecto);


    $i = 7;

    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $estado = "";
        if ($fila['Estado'] == 1) {
            $estado = "Bueno";
        } else {
            $objPHPExcel->getActiveSheet()->getStyle("C$i:G$i")->getFill()->applyFromArray(array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => '003DA6')));
            $estado = "Reparacion";
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C$i", $fila['Codigo'])
                ->setCellValue("D$i", $fila['Descripcion'])
                ->setCellValue("E$i", date('d/m/Y', strtotime($fila['FechaSalida'])))
                ->setCellValue("F$i", $fila['NBoleta'])
                ->setCellValue("G$i", $estado);
        $i++;
    }

    $rango = "C7:G$i";

//
    // Fuente de la primera fila en negrita
    //Alinear al centro ,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    $objPHPExcel->getActiveSheet()->getStyle('C2:F4')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    // $objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    //   'startcolor' => array('rgb' => 'ffffff')));
    $objPHPExcel->getActiveSheet()->getStyle('C6:G6')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );

    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C6:G6')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Reporte Herramientas');
    Headers();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}









function ExportarExcelMaquinariaProyecto($ID_Proyecto) {
    $objPHPExcel = new PHPExcel();
    $bdProyectos = new MProyectos();
    $opc = $_POST['cboFiltrarMaquinaria'];
    $result = "";
    switch ($opc) {
        case "Filtrar por...":$result = $bdProyectos->ListaMaquinariaProyecto($ID_Proyecto);
            break;
        case "Reparacion":$result = $bdProyectos->FiltrosMaquinariaProyecto("SELECT tp.Codigo,tt.Descripcion,tp.FechaSalida, th.Estado, tp.NBoleta FROM tbl_prestamoherramientas tp, tbl_herramientaelectrica th, tbl_tipoherramienta tt where tp.ID_Proyecto = $ID_Proyecto and tp.ID_Tipo = tt.ID_Tipo and tp.Codigo =  th.Codigo and th.Estado = 0 and tt.TipoEquipo = 'M' ORDER by tp.FechaSalida ASC;");
            break;
        case "Fecha":$result = $bdProyectos->FiltrosMaquinariaProyecto("SELECT tp.Codigo,tt.Descripcion,tp.FechaSalida, th.Estado, tp.NBoleta FROM tbl_prestamoherramientas tp, tbl_herramientaelectrica th, tbl_tipoherramienta tt where 
 tp.ID_Proyecto = $ID_Proyecto and tp.ID_Tipo = tt.ID_Tipo and tp.Codigo =  th.Codigo and tt.TipoEquipo = 'M' ORDER by tp.FechaSalida ASC;");
            break;
        case "Ver Totales":ExportarVerTotalesMaquinaria($ID_Proyecto);
            break;
        default:
            break;
    }
    $objPHPExcel = new PHPExcel();
    // $bdProyectos = new MProyectos();
    $objPHPExcel->getProperties()->setCreator("Reporte")
            ->setLastModifiedBy("Reporte")
            ->setTitle("Reporte Materiales")
            ->setSubject("Reporte Materiales")
            ->setDescription("Reporte Materiales.")
            ->setKeywords("office 2010 openxml php")
            ->setCategory("Reporte Materiales");

    $nombreProyecto = $bdProyectos->ObtenerNombreProyecto($ID_Proyecto);
    $fila = mysqli_fetch_array($nombreProyecto, MYSQLI_ASSOC);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', 'Reportes del sistema')
            ->setCellValue('C4', 'Proyecto')
            ->setCellValue('D4', $fila['Nombre'])
            ->setCellValue('C6', 'CODIGO')
            ->setCellValue('D6', 'TIPO')
            ->setCellValue('E6', 'FECHA')
            ->setCellValue('F6', 'N°BOLETA')
            ->setCellValue('G6', 'ESTADO');



    //$result = $bdProyectos ->ListaHerramientaProyecto($ID_Proyecto);


    $i = 7;

    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $estado = "";
        if ($fila['Estado'] == 1) {
            $estado = "Bueno";
        } else {
            $objPHPExcel->getActiveSheet()->getStyle("C$i:G$i")->getFill()->applyFromArray(array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => '003DA6')));
            $estado = "Reparacion";
        }
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C$i", $fila['Codigo'])
                ->setCellValue("D$i", $fila['Descripcion'])
                ->setCellValue("E$i", date('d/m/Y', strtotime($fila['FechaSalida'])))
                ->setCellValue("F$i", $fila['NBoleta'])
                ->setCellValue("G$i", $estado);
        $i++;
    }

    $rango = "C7:G$i";

//
    // Fuente de la primera fila en negrita
    //Alinear al centro ,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    $objPHPExcel->getActiveSheet()->getStyle('C2:F4')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    // $objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    //   'startcolor' => array('rgb' => 'ffffff')));
    $objPHPExcel->getActiveSheet()->getStyle('C6:G6')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );

    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C6:G6')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Reporte Herramientas');
    Headers();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}











function Headers() {
    /*
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="Reportes.xls"');
      header('Cache-Control: max-age=0');
      // Si usted está sirviendo a IE 9 , a continuación, puede ser necesaria la siguiente
      header('Cache-Control: max-age=1');

      // Si usted está sirviendo a IE a través de SSL , a continuación, puede ser necesaria la siguiente
      header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
      header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
      header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
      header('Pragma: public'); // HTTP/1.0

     */
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="pedido_.xls"');
    header('Pragma: no-cache');
    header('Expires: 0');
}

function ExportarVerTotalesMateriales($ID_Proyecto) {
    $objPHPExcel = new PHPExcel();
    $bdProyectos = new MProyectos();
    $objPHPExcel->getProperties()->setCreator("Reporte")
            ->setLastModifiedBy("Reporte")
            ->setTitle("Reporte Materiales")
            ->setSubject("Reporte Materiales")
            ->setDescription("Reporte Materiales.")
            ->setKeywords("office 2010 openxml php")
            ->setCategory("Reporte Materiales");

    $nombreProyecto = $bdProyectos->ObtenerNombreProyecto($ID_Proyecto);
    $fila = mysqli_fetch_array($nombreProyecto, MYSQLI_ASSOC);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', 'Reporte Total de Materiales enviados a Proyecto')
            ->setCellValue('C4', 'Proyecto')
            ->setCellValue('D4', $fila['Nombre'])
            ->setCellValue('C8', 'CODIGO')
            ->setCellValue('D8', 'NOMBRE')
            ->setCellValue('E8', 'CANTIDAD');
    $result = $bdProyectos->FitrosMaterialesProyecto("SELECT m.Codigo, m.Nombre,SUM(pm.Cantidad) as Suma FROM tbl_boletaspedido bp, tbl_prestamomateriales pm,tbl_materiales m where bp.Consecutivo = pm.NBoleta and bp.ID_Proyecto = $ID_Proyecto and pm.ID_Material = m.Codigo  GROUP BY m.ID_Material ;");
    $i = 9;
    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C$i", $fila['Codigo'])
                ->setCellValue("D$i", $fila['Nombre'])
                ->setCellValue("E$i", $fila['Suma']);
        $i++;
    }
    $rango = "C8:E$i";



    // Fuente de la primera fila en negrita
    //Alinear al centro ,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    $objPHPExcel->getActiveSheet()->getStyle('C2:F6')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    //$objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    //   'startcolor' => array('rgb' => 'ffffff')));
    //$rango="C2:F3";
    // $styleArray = array('font' => array( 'name' => 'Calibri','size' => 18)); 
    // $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('C8:E8')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );
    // Cambiar el nombre de hoja de cálculo
    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C8:E8')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Reporte');
    Headers();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

function ExportarVerTotalesHerramienta($ID_Proyecto) {
    $objPHPExcel = new PHPExcel();
    $bdProyectos = new MProyectos();
    $objPHPExcel->getProperties()->setCreator("Reporte")
            ->setLastModifiedBy("Reporte")
            ->setTitle("Reporte Materiales")
            ->setSubject("Reporte Materiales")
            ->setDescription("Reporte Materiales.")
            ->setKeywords("office 2010 openxml php")
            ->setCategory("Reporte Materiales");

    $nombreProyecto = $bdProyectos->ObtenerNombreProyecto($ID_Proyecto);
    $fila = mysqli_fetch_array($nombreProyecto, MYSQLI_ASSOC);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', 'Reportes del sistema')
            ->setCellValue('C4', 'Proyecto')
            ->setCellValue('D4', $fila['Nombre'])
            ->setCellValue('C6', 'TIPO')
            ->setCellValue('D6', 'CANTIDAD');
    $result = $bdProyectos->FiltrosHerramientasProyecto("SELECT tt.Descripcion,COUNT(*) as Cantidad from tbl_prestamoherramientas th, tbl_tipoherramienta tt where th.ID_Proyecto = $ID_Proyecto and th.ID_Tipo = tt.ID_Tipo GROUP by tt.Descripcion;");
    $i = 7;
    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C$i", $fila['Descripcion'])
                ->setCellValue("D$i", $fila['Cantidad']);
        $i++;
    }
    $rango = "C7:D$i";



    // Fuente de la primera fila en negrita
    //Alinear al centro ,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    $objPHPExcel->getActiveSheet()->getStyle('C2:F4')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    // $objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    //   'startcolor' => array('rgb' => 'ffffff')));
    //$rango="C2:F3";
    // $styleArray = array('font' => array( 'name' => 'Calibri','size' => 18)); 
    // $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('C6:D6')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );
    // Cambiar el nombre de hoja de cálculo
    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C6:D6')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Reporte');
    Headers();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}



function ExportarVerTotalesMaquinaria($ID_Proyecto) {
    $objPHPExcel = new PHPExcel();
    $bdProyectos = new MProyectos();
    $objPHPExcel->getProperties()->setCreator("Reporte")
            ->setLastModifiedBy("Reporte")
            ->setTitle("Reporte Maquinaria")
            ->setSubject("Reporte Maquinaria")
            ->setDescription("Reporte Maquinaria.")
            ->setKeywords("office 2010 openxml php")
            ->setCategory("Reporte Maquinaria");

    $nombreProyecto = $bdProyectos->ObtenerNombreProyecto($ID_Proyecto);
    $fila = mysqli_fetch_array($nombreProyecto, MYSQLI_ASSOC);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', 'Reportes del sistema')
            ->setCellValue('C4', 'Proyecto')
            ->setCellValue('D4', $fila['Nombre'])
            ->setCellValue('C6', 'TIPO')
            ->setCellValue('D6', 'CANTIDAD');
    $result = $bdProyectos->FiltrosMaquinariaProyecto("SELECT tt.Descripcion,COUNT(*) as Cantidad from tbl_prestamoherramientas th, tbl_tipoherramienta tt where th.ID_Proyecto = $ID_Proyecto and th.ID_Tipo = tt.ID_Tipo and tt.TipoEquipo = 'M' GROUP by tt.Descripcion;");
    $i = 7;
    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C$i", $fila['Descripcion'])
                ->setCellValue("D$i", $fila['Cantidad']);
        $i++;
    }
    $rango = "C7:D$i";



    // Fuente de la primera fila en negrita
    //Alinear al centro ,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    $objPHPExcel->getActiveSheet()->getStyle('C2:F4')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    // $objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    //   'startcolor' => array('rgb' => 'ffffff')));
    //$rango="C2:F3";
    // $styleArray = array('font' => array( 'name' => 'Calibri','size' => 18)); 
    // $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('C6:D6')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );
    // Cambiar el nombre de hoja de cálculo
    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C6:D6')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Reporte');
    Headers();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

function ExportarTablaDevolucionMaterial($ID_Material, $ID_Proyecto) {
    $objPHPExcel = new PHPExcel();
    $bdProyectos = new MProyectos();
    $objPHPExcel->getProperties()->setCreator("Reporte")
            ->setLastModifiedBy("Reporte")
            ->setTitle("Reporte")
            ->setSubject("Reporte")
            ->setDescription("Reporte.")
            ->setKeywords("office 2010 openxml php")
            ->setCategory("Reporte");

    $nombreProyecto = $bdProyectos->ObtenerNombreProyecto($ID_Proyecto);

    $fila = mysqli_fetch_array($nombreProyecto, MYSQLI_ASSOC);
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', 'Reportes Devolucion de materiales a Bodega')
            ->setCellValue('C4', 'Proyecto')
            ->setCellValue('D4', $fila['Nombre'])
            ->setCellValue('C5', 'Material')
            ->setCellValue('D5', $_POST['txtNombreMaterialD'])
            ->setCellValue('C8', 'CANTIDAD')
            ->setCellValue('D8', 'FECHA DEVOLUCION')
            ->setCellValue('E8', 'N°BOLETA');


//,,
    $result = $bdProyectos->ListarDevolucionesPorMaterial($ID_Material, $ID_Proyecto);


    $i = 9;
    $totalDevuelto = 0;
    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $totalDevuelto = $totalDevuelto + $fila['Cantidad'];
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C$i", $fila['Cantidad'])
                ->setCellValue("D$i", date('d/m/Y', strtotime($fila['fecha'])))
                ->setCellValue("E$i", $fila['NBoleta']);
        $i++;
    }

    $rango = "C8:E$i";

    $x = $i = $i + 4;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("C$i", "Total Prestado")
            ->setCellValue("D$i", "Devolucion")
            ->setCellValue("E$i", "Pendiente");
    $i = $i + 1;
    $result = $bdProyectos->ObtenerTotalMaterialPrestadoProyecto($ID_Material, $ID_Proyecto);
    $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $cantaidadPrestada = $fila['CantidadIngreso'];
    $pendiente = $cantaidadPrestada - $totalDevuelto;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("C$i", $cantaidadPrestada)
            ->setCellValue("D$i", $totalDevuelto)
            ->setCellValue("E$i", $pendiente);


//
    // Fuente de la primera fila en negrita
    //Alinear al centro ,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    $objPHPExcel->getActiveSheet()->getStyle('C2:F6')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    // $objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //     'type' => PHPExcel_Style_Fill::FILL_SOLID,
    //    'startcolor' => array('rgb' => 'ffffff')));
    $objPHPExcel->getActiveSheet()->getStyle('C8:E8')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );


    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C8:E8')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    //esto es para la segunda tabla la de resumen

    $objPHPExcel->getActiveSheet()->getStyle("C$x:E$x")->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );

    $objPHPExcel->getActiveSheet()->getStyle("C$x:E$x")->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));
    $objPHPExcel->getActiveSheet()->getStyle("C$x:E$i")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Reporte');
    Headers();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

function ExportarExcelTotalMateriales() {
    $objPHPExcel = new PHPExcel();
    $bdMateriales = new MMaterial();

    $objPHPExcel->getProperties()->setCreator("Reporte")
            ->setLastModifiedBy("Reporte")
            ->setTitle("Reporte")
            ->setSubject("Reporte")
            ->setDescription("Reporte.")
            ->setKeywords("office 2010 openxml php")
            ->setCategory("Reporte");

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', 'Reporte Total de Materiales en Bodega')
            ->setCellValue('C6', 'CODIGO')
            ->setCellValue('D6', 'NOMBRE')
            ->setCellValue('E6', 'CANTIDAD');


//codigo,nombre,cantidad

    $result = $bdMateriales->listarTotalMateriales();
    $i = 7;
    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C$i", $fila['codigo'])
                ->setCellValue("D$i", $fila['nombre'])
                ->setCellValue("E$i", $fila['cantidad']);
        $i++;
    }
    $rango = "C7:E$i";



    // Fuente de la primera fila en negrita
    //Alinear al centro ,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    $objPHPExcel->getActiveSheet()->getStyle('C2:F4')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    // $objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //     'type' => PHPExcel_Style_Fill::FILL_SOLID,
    //    'startcolor' => array('rgb' => 'ffffff')));
    //$rango="C2:F3";
    // $styleArray = array('font' => array( 'name' => 'Calibri','size' => 18)); 
    // $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('C6:E6')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );
    // Cambiar el nombre de hoja de cálculo
    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C6:E6')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Reporte');
    Headers();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

function ExportarContenidoTblMateriales($consulta) {
    $objPHPExcel = new PHPExcel();
    $bdMateriales = new MMaterial();

    $objPHPExcel->getProperties()->setCreator("Reporte")
            ->setLastModifiedBy("Reporte")
            ->setTitle("Reporte")
            ->setSubject("Reporte")
            ->setDescription("Reporte.")
            ->setKeywords("office 2010 openxml php")
            ->setCategory("Reporte");

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', 'Reporte Total de Materiales en Bodega')
            ->setCellValue('C6', 'CODIGO')
            ->setCellValue('D6', 'NOMBRE')
            ->setCellValue('E6', 'CANTIDAD');


//codigo,nombre,cantidad

    $result = $bdMateriales->BuscarTiempoRealHerramienta($consulta);
    $i = 7;
    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C$i", $fila['codigo'])
                ->setCellValue("D$i", $fila['nombre'])
                ->setCellValue("E$i", $fila['cantidad']);
        $i++;
    }
    $rango = "C7:E$i";



    // Fuente de la primera fila en negrita
    //Alinear al centro ,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    $objPHPExcel->getActiveSheet()->getStyle('C2:F4')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    // $objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    //    'startcolor' => array('rgb' => 'ffffff')));
    //$rango="C2:F3";
    // $styleArray = array('font' => array( 'name' => 'Calibri','size' => 18)); 
    // $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('C6:E6')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );
    // Cambiar el nombre de hoja de cálculo
    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C6:E6')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Reporte');
    Headers();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

function ExportarExcelFinalizarProyecto($ID_Proyecto) {
    $objPHPExcel = new PHPExcel();
    $bdProyecto = new MProyectos();
    $indice = 0;
    $fila = mysqli_fetch_array($bdProyecto->ObtenerNombreProyecto($ID_Proyecto), MYSQLI_ASSOC);
    $nombreProyecto = $fila['Nombre'];
    $objPHPExcel->getProperties()->setCreator("Reporte")
            ->setLastModifiedBy("Reporte")
            ->setTitle("Reporte")
            ->setSubject("Reporte")
            ->setDescription("Reporte.")
            ->setKeywords("office 2010 openxml php")
            ->setCategory("Reporte");

    $objPHPExcel->setActiveSheetIndex($indice)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex($indice)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex($indice)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', 'Reporte Materiales Pendientes por Devolver')
            ->setCellValue('C4', 'PROYECTO')
            ->setCellValue('D4', $nombreProyecto)
            ->setCellValue('C6', 'CODIGO')
            ->setCellValue('D6', 'NOMBRE')
            ->setCellValue('E6', 'PRESTAMO')
            ->setCellValue('F6', 'DEVOLUCION')
            ->setCellValue('G6', 'PENDIENTE');

//codigo,nombre,cantidad

    $result = $bdProyecto->FitrosMaterialesProyecto("SELECT tp.ID_Material,m.Nombre,SUM(tp.Cantidad) as Prestamo,(SELECT SUM(Cantidad) FROM tbl_devolucionmateriales WHERE ID_Proyecto = $ID_Proyecto AND Codigo = tp.ID_Material GROUP BY Codigo)as Devolucion from tbl_prestamomateriales tp, tbl_materiales m,tbl_boletaspedido p where tp.ID_Material = m.Codigo and p.ID_Proyecto = $ID_Proyecto and tp.NBoleta = p.Consecutivo AND m.Devolucion = 1 GROUP by tp.ID_Material, m.Nombre ");
    $i = 7;
    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $pendiente = $fila['Prestamo'] - $fila['Devolucion'];
        $indice++;
        CrearDevoluciones($objPHPExcel, $indice, $fila['ID_Material'], $ID_Proyecto, $nombreProyecto);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C$i", $fila['ID_Material'])
                ->setCellValue("D$i", $fila['Nombre'])
                ->setCellValue("E$i", $fila['Prestamo'])
                ->setCellValue("F$i", $fila['Devolucion'])
                ->setCellValue("G$i", $pendiente);
        $i++;
    }
    CrearReporterHerrmientasFinProyecto($objPHPExcel, $indice + 1, $ID_Proyecto, $nombreProyecto);
    $objPHPExcel->setActiveSheetIndex(0);
    $rango = "C7:G$i";
    $objPHPExcel->getActiveSheet()->getStyle('C2:F4')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    // $objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    //   'startcolor' => array('rgb' => 'ffffff')));

    $objPHPExcel->getActiveSheet()->getStyle('C6:G6')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );
    // Cambiar el nombre de hoja de cálculo
    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C6:G6')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Materiales');

    $bdProyecto->FinalizarProyecto($ID_Proyecto);

    Headers();
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

function CrearDevoluciones($objPHPExcel, $indice, $Codigo, $ID_Proyecto, $NombreProyecto) {
    //$objPHPExcel = new PHPExcel();
    $bdProyectos = new MProyectos();
    $bdMaterieales = new MMaterial();

    $objPHPExcel->createSheet();
    $fila = mysqli_fetch_array($bdMaterieales->VerificarDisponibilidad($Codigo), MYSQLI_ASSOC);
    $nombreMaterial = $fila['Nombre'];
    $objPHPExcel->setActiveSheetIndex($indice)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex($indice)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex($indice)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', 'Reportes Devolucion de materiales a Bodega')
            ->setCellValue('C4', 'Proyecto')
            ->setCellValue('D4', $NombreProyecto)
            ->setCellValue('C5', 'Material')
            ->setCellValue('D5', $nombreMaterial)
            ->setCellValue('C8', 'CANTIDAD')
            ->setCellValue('D8', 'FECHA DEVOLUCION')
            ->setCellValue('E8', 'N°BOLETA');


//,,
    $result = $bdProyectos->ListarDevolucionesPorMaterial($Codigo, $ID_Proyecto);


    $i = 9;
    $totalDevuelto = 0;
    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $totalDevuelto = $totalDevuelto + $fila['Cantidad'];
        $objPHPExcel->setActiveSheetIndex($indice)
                ->setCellValue("C$i", $fila['Cantidad'])
                ->setCellValue("D$i", date('d/m/Y', strtotime($fila['fecha'])))
                ->setCellValue("E$i", $fila['NBoleta']);
        $i++;
    }

    $rango = "C8:E$i";

    $x = $i = $i + 4;
    $objPHPExcel->setActiveSheetIndex($indice)
            ->setCellValue("C$i", "Total Prestado")
            ->setCellValue("D$i", "Devolucion")
            ->setCellValue("E$i", "Pendiente");
    $i = $i + 1;
    $result = $bdProyectos->ObtenerTotalMaterialPrestadoProyecto($Codigo, $ID_Proyecto);
    $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $cantaidadPrestada = $fila['CantidadIngreso'];
    $pendiente = $cantaidadPrestada - $totalDevuelto;
    $objPHPExcel->setActiveSheetIndex($indice)
            ->setCellValue("C$i", $cantaidadPrestada)
            ->setCellValue("D$i", $totalDevuelto)
            ->setCellValue("E$i", $pendiente);


//
    // Fuente de la primera fila en negrita
    //Alinear al centro ,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    $objPHPExcel->getActiveSheet()->getStyle('C2:F6')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    // $objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //     'type' => PHPExcel_Style_Fill::FILL_SOLID,
    //     'startcolor' => array('rgb' => 'ffffff')));
    $objPHPExcel->getActiveSheet()->getStyle('C8:E8')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );


    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C8:E8')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    //esto es para la segunda tabla la de resumen

    $objPHPExcel->getActiveSheet()->getStyle("C$x:E$x")->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );

    $objPHPExcel->getActiveSheet()->getStyle("C$x:E$x")->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));
    $objPHPExcel->getActiveSheet()->getStyle("C$x:E$i")->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex($indice));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle($Codigo);
}

function CrearReporterHerrmientasFinProyecto($objPHPExcel, $indice, $ID_Proyecto, $NombreProyecto) {
    $bdProyectos = new MProyectos();
    $objPHPExcel->createSheet();
    $objPHPExcel->setActiveSheetIndex($indice)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex($indice)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex($indice)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', 'Reporte Herramientas Fin Proyecto')
            ->setCellValue('C4', 'Proyecto')
            ->setCellValue('D4', $NombreProyecto)
            ->setCellValue('C6', 'CODIGO')
            ->setCellValue('D6', 'TIPO')
            ->setCellValue('E6', 'FECHA')
            ->setCellValue('F6', 'N°BOLETA')
            ->setCellValue('G6', 'ESTADO');



    $result = $bdProyectos->ListaHerramientaProyecto($ID_Proyecto);


    $i = 7;

    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $estado = "";
        if ($fila['Estado'] == 1) {
            $estado = "Bueno";
        } else {
            $estado = "Reparacion";
        }

        $objPHPExcel->setActiveSheetIndex($indice)
                ->setCellValue("C$i", $fila['Codigo'])
                ->setCellValue("D$i", $fila['Descripcion'])
                ->setCellValue("E$i", date('d/m/Y', strtotime($fila['FechaSalida'])))
                ->setCellValue("F$i", $fila['NBoleta'])
                ->setCellValue("G$i", $estado);
        $i++;
    }
    $rango = "C7:G$i";

//
    // Fuente de la primera fila en negrita
    //Alinear al centro ,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    $objPHPExcel->getActiveSheet()->getStyle('C2:F4')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    // $objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    //    'startcolor' => array('rgb' => 'ffffff')));
    $objPHPExcel->getActiveSheet()->getStyle('C6:G6')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );

    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C6:G6')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex($indice));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Reporte Herramientas');
}

function ExportarExcelTotalHerramientas() {
    $objPHPExcel = new PHPExcel();
    $bdHerramientas = new MHerramientas();
    if ($_POST['txtNombreTipoH'] != "") {
        $result = $bdHerramientas->BuscarTiempoRealHerramienta($_POST['txtNombreTipoH']);
        $x = 0;
    } else {
        $opc = $_POST['cboFiltroHerramienta'];
        $x = 100;
        switch ($opc) {
            case "0":$result = $bdHerramientas->listarTotalHerramientas();
                $x = 0;
                break;
            case "1":$result = $bdHerramientas->FiltrosHerramientas1();
                $x = 1;
                break;
            case "2":$result = $bdHerramientas->FiltrosHerramientas2();
                $x = 1;
                break;
            case "3":$result = $bdHerramientas->FiltrosHerramientas3();
                $x = 1;
                break;
            case "4":$result = $bdHerramientas->FiltrosHerramientas4();
                $x = 1;
                break;
            case "5":$result = $bdHerramientas->FiltrarTipoTotalHerramienta();
                $x = 2;
                break;
            default:
                break;
        };
    }
    $objPHPExcel->getProperties()->setCreator("Reporte")
            ->setLastModifiedBy("Reporte")
            ->setTitle("Reporte")
            ->setSubject("Reporte")
            ->setDescription("Reporte.")
            ->setKeywords("office 2010 openxml php")
            ->setCategory("Reporte");

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:F3');



//codigo,nombre,cantidad


    $i = 7;
    if ($x == 0) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('C2', 'Proycon S.A')
                ->setCellValue('C3', 'Reporte Total de Herramietas')
                ->setCellValue('C6', 'CODIGO')
                ->setCellValue('D6', 'TIPO')
                ->setCellValue('E6', 'FECHA REGISTRO')
                ->setCellValue('F6', 'DISPOSICION')
                ->setCellValue('G6', 'UBICACION')
                ->setCellValue('H6', 'ESTADO');
        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("C$i", $fila['Codigo'])
                    ->setCellValue("D$i", $fila['Tipo'])
                    ->setCellValue("E$i", date('d/m/Y', strtotime($fila['FechaIngreso'])))
                    ->setCellValue("F$i", $fila['Disposicion'])
                    ->setCellValue("G$i", $fila['Nombre'])
                    ->setCellValue("H$i", $fila['Estado']);
            $i++;
        }
        
    } else if ($x == 2) {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('C2', 'Proycon S.A')
                ->setCellValue('C3', 'Reporte Total de Herramietas')
                ->setCellValue('C6', 'TIPO')
                ->setCellValue('D6', 'CANTIDAD');
        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("C$i", $fila['Descripcion'])
                    ->setCellValue("D$i", $fila['Cantidad']);
            $i++;
        }
    } else {
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('C2', 'Proycon S.A')
                ->setCellValue('C3', 'Reporte Total de Herramietas')
                ->setCellValue('C6', 'CODIGO')
                ->setCellValue('D6', 'TIPO')
                ->setCellValue('E6', 'FECHA REGISTRO')
                ->setCellValue('F6', 'DISPOSICION')
                ->setCellValue('G6', 'UBICACION')
                ->setCellValue('H6', 'ESTADO');

        while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("C$i", $fila['Codigo'])
                    ->setCellValue("D$i", $fila['Descripcion'])
                    ->setCellValue("E$i", date('d/m/Y', strtotime($fila['FechaIngreso'])))
                    ->setCellValue("F$i", $fila['Disposicion'])
                    ->setCellValue("G$i", $fila['Nombre'])
                    ->setCellValue("H$i", $fila['Estado']);
            $i++;
        }
    }

    $rango = "C7:H$i";



    // Fuente de la primera fila en negrita
    //Alinear al centro ,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    $objPHPExcel->getActiveSheet()->getStyle('C2:H4')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    // $objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //'type' => PHPExcel_Style_Fill::FILL_SOLID,
    // 'startcolor' => array('rgb' => 'ffffff')));
    //$rango="C2:F3";
    // $styleArray = array('font' => array( 'name' => 'Calibri','size' => 18)); 
    // $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('C6:H6')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );
    // Cambiar el nombre de hoja de cálculo
    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C6:H6')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Reporte');
    Headers();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

function ExportarHistoriaHerramienta() {
    $objPHPExcel = new PHPExcel();
    $bdHerramientas = new MHerramientas();
    $objPHPExcel->getProperties()->setCreator("Reporte")
            ->setLastModifiedBy("Reporte")
            ->setTitle("Reporte")
            ->setSubject("Reporte")
            ->setDescription("Reporte.")
            ->setKeywords("office 2010 openxml php")
            ->setCategory("Reporte");

    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C2:F2');
    $objPHPExcel->setActiveSheetIndex(0)->mergeCells('C3:F3');
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('C2', 'Proycon S.A')
            ->setCellValue('C3', 'Reporte Historial de Herramieta')
            ->setCellValue('C5', 'CODIGO')
            ->setCellValue('C6', 'MARCA')
            ->setCellValue('C7', 'DESCRIPCION')
            ->setCellValue('C8', 'FECHA ADQUIRIDA')
            ->setCellValue('C9', 'PROCEDENCIA')
            ->setCellValue('C10', 'VARLOR DE COMPRA')
            ->setCellValue('C11', 'NUM FACTURA')
            ->setCellValue('C14', 'REPARACIONES')
            ->setCellValue('C15', 'FECHA')
            ->setCellValue('D15', 'NUMERO FACTURA')
            ->setCellValue('E15', 'DESCRIPCION')
            ->setCellValue('F15', 'COSTO');
//codigo,nombre,cantidad


    $i = 16;
    $result = $bdHerramientas->InfoHerramienta($_POST['codigo']);
    $fila = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $valorHerramienta = "¢" . number_format($fila['Precio'], 2, ",", ".");
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('D5', $_POST['codigo'])
            ->setCellValue('D6', $fila['Marca'])
            ->setCellValue('D7', $fila['Descripcion'])
            ->setCellValue('D8', $fila['FechaIngreso'])
            ->setCellValue('D9', $fila['Procedencia'])
            ->setCellValue('D10', $valorHerramienta)
            ->setCellValue('D11', $fila['NumFactura']);


    $monto = 0;
    $bdHerramientas = new MHerramientas();
    $result = $bdHerramientas->reparacionesTotales($_POST['codigo']);
    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

        $Monto = "¢" . number_format($fila['MontoReparacion'], 2, ",", ".");

        $monto = $monto + $fila['MontoReparacion'];
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C$i", $fila['FechaEntrada'])
                ->setCellValue("D$i", $fila['ID_FacturaReparacion'])
                ->setCellValue("E$i", $fila['Descripcion'])
                ->setCellValue("F$i", $Monto);
        $i++;
    }
    $x = $i + 1;

    $valorTotal = "¢" . number_format($monto, 2, ",", ".");
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("E$x", 'Total')
            ->setCellValue("F$x", $valorTotal);

    $rango = "C15:F$i";
    $i = $i + 2;
    $y = $i;
    // Fuente de la primera fila en negrita
    //Alinear al centro ,'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    $objPHPExcel->getActiveSheet()->getStyle('C2:H4')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 18)));
    $objPHPExcel->getActiveSheet()->getStyle('C5:D11')->applyFromArray(array('font' => array('bold' => true, 'name' => 'Calibri')));
    // $objPHPExcel->getActiveSheet()->getStyle('A1:Z200')->getFill()->applyFromArray(array(
    //   'type' => PHPExcel_Style_Fill::FILL_SOLID,
    //   'startcolor' => array('rgb' => 'ffffff')));
    //$rango="C2:F3";
    // $styleArray = array('font' => array( 'name' => 'Calibri','size' => 18)); 
    // $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray($styleArray);
    $objPHPExcel->getActiveSheet()->getStyle('C15:F15')->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );
    // Cambiar el nombre de hoja de cálculo
    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));

    $objPHPExcel->getActiveSheet()->getStyle('C15:F15')->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));

    /* TABLA DE TRASLADOS */
    $i++;
    $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("C$y", 'Control de Traslados de Herramienta')
            ->setCellValue("C$i", 'FECHA')
            ->setCellValue("D$i", 'NºBoleta')
            ->setCellValue("E$i", 'UBICACION')
            ->setCellValue("F$i", 'DESTINO');

    $objPHPExcel->getActiveSheet()->getStyle("C$i:F$i")->applyFromArray(
            array('font' => array('bold' => true, 'name' => 'Calibri', 'size' => 14, 'color' => array('rgb' => 'ffffff')),
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF'))))
    );

    $objPHPExcel->getActiveSheet()->getStyle("C$i:F$i")->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array('rgb' => '003DA6')));
    $x = $i;
    $bdHerramientas = new MHerramientas();
    $result = $bdHerramientas->trasladosTotales($_POST['codigo']);
    $i++;
    while ($fila = mysqli_fetch_array($result, MYSQLI_ASSOC)) {


        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("C$i", $fila['Fecha'])
                ->setCellValue("D$i", $fila['NumBoleta'])
                ->setCellValue("E$i", ($fila['Ubicacion'] == "") ? "En Reparacion" : $fila['Ubicacion'])
                ->setCellValue("F$i", ($fila['Destino'] == "") ? "En Reparacion" : $fila['Destino']);

        $i++;
    }


    $rango = "C$x:F$i";
    $objPHPExcel->getActiveSheet()->getStyle($rango)->applyFromArray(array('alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
        'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('argb' => 'FFF')))));




    /* Establecer tamanos a las celdas */
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
    $objDrawing = new PHPExcel_Worksheet_Drawing();
    $objDrawing->setName('imgNotice');
    $objDrawing->setDescription('Noticia');
    $img = '../../resources/imagenes/proycon.png'; // Provide path to your logo file
    $objDrawing->setPath($img);
    $objDrawing->setOffsetX(28);    // setOffsetX works properly
    $objDrawing->setOffsetY(200);  //setOffsetY has no effect
    $objDrawing->setCoordinates('A1');
    $objDrawing->setHeight(100); // logo height
    $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
    //Nombre de la hoja
    $objPHPExcel->getActiveSheet()->setTitle('Reporte');
    Headers();

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}
