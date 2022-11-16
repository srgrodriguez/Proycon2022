<?php
Autorizacion();
if (isset($_REQUEST["idProyecto"])) {
    if ($_REQUEST["idProyecto"] == 0) {
        materiales("Distrito 4");
    }
 else {
         materiales("Ribera Laureles");
    }
}

if (isset($_REQUEST["mostrarP"])) {
    proyectos();
}

function Crearmenu() {
     //session_start();
    $nombre =$_SESSION['Nombre'];
     if ($_SESSION['ID_ROL']== 1) {//administrador
     $id = "<span id='IdRolUsuario' style='display:none'>".$_SESSION['ID_ROL']."</span>";
    $menu = '<div id="menu"> <img src="../resources/imagenes/menu.png" id="imgMenu" onclick="Menu()" width="50px"/>' .$id.
            '<div class="imgmenu"><samp class="icon-list2"></samp></div>' .
            '<nav>' .
            '<ul class="ul">' .
            '<li class="li"> <a href=Inicio.php>' .
            '<span class="icon-home"></span> Inicio </a>' .
            '</li>' .
            '<li class="mostrarsubmenu">' .
            '<a href="Usuarios.php"><span class="icon-user"></span> Usuarios </a>' .
            '<span class=".icon-expand_less" id="flechasubmenu"><a href="#"> ></a> </span>' .
            '</li>' .
            '<li> <a href="MiCuenta.php"><span class="icon-man"></span>'.$nombre.'</a></li>' .
            '<li > <a href="Crerrarsession.php" class="salir" ><span class="icon-cross"></span> Salir </a></li>' .
            '</ul>' .
            '</nav>' .
            '</div>';  
     }
     else if ($_SESSION['ID_ROL']== 2) {//proveeduria
          $id = "<span id='IdRolUsuario' style='display:none'>".$_SESSION['ID_ROL']."</span>";
        $menu = '<div id="menu"><img src="../resources/imagenes/menu.png" id="imgMenu" onclick="Menu()" width="50px"/>'.
                 '<button id="btnAtras" type="button" class="btn btn-default" onclick="Regresar()">
                        <img src="../resources/imagenes/regresar.png" alt="" width="20px;"/>
                    </button> '.$id.
            '<div class="imgmenu"><samp class="icon-list2"></samp></div>' .
            '<nav>' .
            '<ul class="ul">' .
            '<li class="li"> <a href=Inicio.php>' .
            '<span class="icon-home"></span> Inicio </a>' .
            '</li>' .
            '<li> <a href="Herramientas.php"><span class="icon-tools"></span> Herramientas </a>' .
            '</li>' .
            '<li> <a href="Materiales.php"><span class="icon-spinner4"></span>  Materiales </a></li>' .
            '<li> <a href="Pedidos.php"><span class="icon-file-text"></span> Pedidos </a></li>' .
            '<li> <a href="MiCuenta.php"><span class="icon-man"></span>'.$nombre.'</a></li>' .
            '<li > <a href="Crerrarsession.php" class="salir" ><span class="icon-cross"></span> Salir </a></li>' .
            '</ul>' .
            '</nav>'.
            '</div>';
 
     }
     else if ($_SESSION['ID_ROL']== 3) {//director de proyectos
          $id = "<span id='IdRolUsuario' style='display:none'>".$_SESSION['ID_ROL']."</span>";
             $menu = '<div id="menu"> <img src="../resources/imagenes/menu.png" id="imgMenu" onclick="Menu()" width="50px"/>' .$id.
            '<div class="imgmenu"><samp class="icon-list2"></samp></div>' .
            '<nav>' .
            '<ul class="ul">' .
            '<li class="li"> <a href=Inicio.php>' .
            '<span class="icon-home"></span> Inicio </a>' .
            '</li>' .
            '<li> <a href="Proyectos.php"><span class="icon-pencil2"></span> Proyectos </a>' .
            '</li>' .
            '<li> <a href="Herramientas.php"><span class="icon-tools"></span> Herramientas </a>' .
            '</li>' .
            '<li> <a href="Materiales.php"><span class="icon-spinner4"></span>  Materiales </a></li>' .
            '<li> <a href="MiCuenta.php"><span class="icon-man"></span>'.$nombre.'</a></li>' .
            '<li > <a href="Crerrarsession.php" class="salir" ><span class="icon-cross"></span> Salir </a></li>' .
            '</ul>' .
            '</nav>' .
            '</div>';
         
     }
     else if ($_SESSION['ID_ROL']== 4 || $_SESSION['ID_ROL']==5) {//Bodega
            $id = "<span id='IdRolUsuario' style='display:none'>".$_SESSION['ID_ROL']."</span>";
             $menu = '<div id="menu"> <img src="../resources/imagenes/menu.png" id="imgMenu" onclick="Menu()" width="50px"/>'
                     . '<button id="btnAtras" type="button" class="btn btn-default" onclick="Atras()">
                        <img src="../resources/imagenes/regresar.png" alt="" width="20px;"/>
                    </button> ' . $id.
            '<div class="imgmenu"><samp class="icon-list2"></samp></div>' .
            '<nav>' .
            '<ul class="ul">' .
            '<li class="li"> <a href=Inicio.php>' .
            '<span class="icon-home"></span> Inicio </a>' .
            '</li>' .
            '<li class="mostrarsubmenu"> <a href="Proyectos.php"><span class="icon-pencil2"></span> Proyectos </a>' .
            '</li>' .
            '<li> <a href="Herramientas.php"><span class="icon-tools"></span> Herramientas </a>' .
            '</li>' .
            '<li> <a href="Materiales.php"><span class="icon-spinner4"></span>  Materiales </a></li>' .
            '<li> <a href="MiCuenta.php"><span class="icon-man"></span>'.$nombre.'</a></li>' .
            '<li > <a href="Crerrarsession.php" class="salir" ><span class="icon-cross"></span> Salir </a></li>' .
            '</ul>' .
            '</nav>' .
            '</div>';
     }    
    echo $menu;
}

function proyectos() {
    $titulo ='<h3>Lista de Proyectos</h3>';
    $proyecto = '';
    $arreglo = array();
    $arreglo[0] = 'Distrito';
    $arreglo[1] = 'Ribera Laureles';
    $arreglo[2] = 'Plataforma de Parqueos';
    for ($i = 0; $i < 3; $i++) {
        $onclick = "onclick='herramientas($i)'";
        $proyecto = $proyecto . '<section class="proyecto"> ' .
                                '<h3><a href="javascript:void(0);"' . $onclick . '>' . $arreglo[$i] . ' </a> </h3>' .
                                '</section>';
    }
    echo $titulo.$proyecto;
}
function proyectosProveeduria() {
    $titulo ='<h3>Lista de Proyectos</h3>';
    $proyecto = '';
    $arreglo = array();
    $arreglo[0] = 'Distrito';
    $arreglo[1] = 'Ribera Laureles';
    $arreglo[2] = 'Plataforma de Parqueos';
    for ($i = 0; $i < 3; $i++) {
        $onclick = "onclick='PedidosProyecto(this)'";
        $proyecto = $proyecto . '<section class="proyecto"> ' .
                '<h3><a href="javascript:void(0);"' . $onclick . '>' . $arreglo[$i] . ' </a> </h3>' .
                '</section>';
    }
    echo $titulo.$proyecto;
}

function materiales($proyecto) {
     $combobox = '<div class="form-group">
           <label class="col-md-4 control-label" for="Empresa">Ordenar por</label>  
           
            <select class ="form-control" name="opciones" placeholder="Filtrar por...">
            <option>Filtrar por...</option>
            <option>Nombre</option>
            <option>Fecha</option>
            <option>Ver Totales</option>
        </select>
        </div>';
      $btnImprimir = '<button class="btnImprimir" type="submit" value="Imprimir" >Exportar <img src="../resources/imagenes/Excel.png" width="20px" alt=""/></button>';
    $datos= '<div class="contenidoProyecto">';
    $datos = $datos.'<button class=" btn btn-default btnExpandir" type="submit" value="" onclick="ExpandirMateriales(1)" >Expandir  <img src="../resources/imagenes/Expandir.png" width="20px" alt=""/></button>'.'<div class="titulomaterialesherramienta"><h4>Materiales</h4> </div>'. $btnImprimir . $combobox;;
    $datos = $datos . " <table class='tablasG'><thead> <tr> <th>Nombre</th> <th class='centrar'> Cantidad </th><th>Pendiente</th>  <th class='centrar'> Fecha </th><th class='centrar'> N°BOLETA</th><th></th> </tr></thead>";


    $datos = $datos . "<tr> <td class=''>Cajon metalico para herramienta</td><td class='centrar'>1</td><td>1</td><td class='centrar'>20/9/2017</td><td class='centrar'>111</td><td></td></tr>";
    $datos = $datos . "<tr> <td class=''><a href ='#' data-toggle='modal' data-target='#ModalDevolucion' >RT</a></td><td class='centrar'>25</td><td>4</td><td class='centrar'>20/9/2017</td><td class='centrar'>111</td><td class='centrar'><button data-toggle='modal' data-target='#ModalDevolucion'><img src='../resources/imagenes/add_icon.png' width='18px'/></button></td></tr>";
    $datos = $datos . "<tr> <td class=''>Laminas de zinc de 2,44m</td><td class='centrar'>100</td><td>100</td><td class='centrar'>20/9/2017</td><td class='centrar'>456</td><td class ='centrar'><button data-toggle='modal' data-target='#ModalDevolucion'><img src='../resources/imagenes/add_icon.png' width='18px'/></button></td></tr>";
    $datos = $datos . "<tr> <td class=''>Repemax</td><td class='centrar'>100</td><td>0</td><td class='centrar'>20/9/2017</td><td class='centrar'>456</td><td></td></tr>";



    $datos = $datos . "</table>"."</div>";
     
    echo $datos;
}

function herramientas($proyecto) {
     $btnImprimir = '<button class="btnImprimir" type="submit" value="Imprimir" >Exportar <img src="../resources/imagenes/Excel.png" width="20px" alt=""/></button>';
            $combobox = '<div class="form-group">
           <label class="col-md-4 control-label" for="Empresa">Ordenar por</label>  
            <select class ="form-control" name="opciones" placeholder="Filtrar por...">
            <option>Filtrar por...</option>
            <option>Tipo</option>
            <option>Fecha</option>
            <option>Estado</option>
            <option>Ver Totales</option>
        </select>
            </div>';
    $datos = '<button class=" btn btn-default btnExpandir" type="submit" value="" onclick="ExpandirMateriales(0)" >Expandir  <img src="../resources/imagenes/Expandir.png" width="20px" alt=""/></button>'.' <div class="titulomaterialesherramienta"><h4>Herramientas</h4></div> ' .
            $btnImprimir.$combobox;
    $datos =$datos. " <table class='tablasG'><thead> <tr> <th>Tipo</th> <th> Codigo </th> <th> Fecha </th><th> Estado </th> </tr></thead>";
    $datos = $datos . "<tr> <td>Taladro</td><td>1251</td><td>20/9/2017</td><td>Bueno</td</tr>";
    $datos = $datos . "<tr> <td>Taladro</td><td>1250</td><td>20/9/2017</td><td>Reparacion</td</tr>";
    $datos = $datos . "<tr> <td>Rotamartillo</td><td>1253</td><td>20/9/2017</td><td>Bueno</td</tr>";

    $datos = $datos . "</table>";

    echo $datos;

    
}
