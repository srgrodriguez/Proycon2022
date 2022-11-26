<?php
require_once '../BLL/Proyectos.php';require_once '../BLL/Autorizacion.php';ValidarIniciodeSession();
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" href="resources\imagenes\favicon.ico"  type="image/x-icon">
        <title>Finalizar Proyecto</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../css/estilos.css"/>
        <link href="../css/responsivecss.css" rel="stylesheet" type="text/css"/>
        <link href="../fonts/icon/style.css" rel="stylesheet" type="text/css"/>
         <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
         <link href="../css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
         <script type="text/javascript" src="../js/jquery.js"></script>
         <script src="../js/jsMenu.js" type="text/javascript"></script>
         <script src="../js/push.min.js" type="text/javascript"></script>
         <script src="../js/Notificaciones.js" type="text/javascript"></script>
    </head>
    <body>
        <header id="header">
            <?php
            require_once 'Menu.php';
            Crearmenu();
            ?>
        </header>

        
        <main id="contenedor">
            <div class="panel panel-info" id="pnlFinalizarProyecto">
 
    <div class="panel-heading"><h3> Finalizar Proyecto <?php echo NombreProyecto($_GET['id']) ?> </h3></div>
    <div class="panel-body">
         <div class="seccionCerrarProyecto">
           <h3>Advertencia: Esta sección de sistema es para finalizar el proyecto tenga en cuenta las siguientes consideraciones antes de cerrar el proyecto</h3>
           <ul class="ulFinalizarProyecto">
                 <li><p>El proyecto será deshabilitado</p></li>
                 <li><p>Todos los pedidos de proveeduría realizados a dicho proyecto serán eliminados</p></li>
                 <li><p>Todas las boletas de salida de MATERIALES Y HERRAMIENTAS para este proyecto serán eliminadas</p></li>
                 <li><p>Todos los materiales prestados al proyecto serán eliminados</p></li>
                 <li><p>Todas las Herramientas prestadas al proyecto serán Eliminadas</p></li>
                 <li><p>Es decir se borrará todo el registro de este proyecto</p></li>

             </ul>
             <h4>Nota: El sistema generara un reporte en Excel con lo que este proyecto tiene pendiente por entregar a bodega el reporte contiene lo siguiente:</h4>
           <ul class="ulFinalizarProyecto">
                <li><p> Un resumen del material prestado, el material devuelto y lo que queda pendiente</p></li>
                <li><p>Un resumen por cada uno de los materiales con las devoluciones correspondientes por material</p></li>
                <li><p>Un resumen de las herramientas que este Proyecto tiene pendiente por devover </p></li>
            </ul>
             <center>
             <form action="../BLL/Reportes/ReportesExcel.php">
                 
               <input class ="btn btn-danger" id="btnNoResponsive" type="submit" value="Si está de acuerdo con lo descrito anteriormente presione aquí" />
              <input class ="btn btn-danger" id="btnResponsive" type="submit" value="Presione aqui..." />
              <div>
                  
              </div>
              <?php
              echo "<input type='hidden' name='txtFinalizarProyecto' value='".$_GET['id']."' />";
              ?>
              
             </form>
             </center>
         </div>        
    </div>


</div>

        </main>>
        
        
    </body>
</html>
