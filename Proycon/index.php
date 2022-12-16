<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
    $_SESSION['Usuario']=null;
	$_SESSION['Nombre']=null;
	$_SESSION['ID_Usuario']=null;
	$_SESSION['ID_ROL']=null;
?>
<html>
    <head>
        <link rel="icon" href="resources\imagenes\favicon.ico"  type="image/x-icon">
        <title> Software Inventarios Costructora Proycon.SA</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/estilos.css"/>
        <link href="css/responsivecss.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="js/jquery.js"></script>
        <link href="css/bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <script src="../css/bootstrap-4.0.0-alpha.6-dist/js/bootstrap.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="js/jsLogin.js"></script>
   </head>
	
    <body>
	 <header id="headerLogin">
            <div id="logo">
                <img src="resources/imagenes/slide.png" alt="" width="120px" />
            </div>
            <div id="tituloNoresponsive"><h2>Software Inventarios Costructora Proycon.SA</h2></div>
             <div id="tituloResponsive"><h2>Software Inventarios</h2></div>
         </header>
        
        
        <div class="alert alert-danger" role="danger" style="display: none" id="alertaLogin">
           
         </div>
	
	 <main id="contenedor" >
        <div class="container">    
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-info" >
                    <div class="panel-heading" style="background-color: #003DA6; color: #cbcfb1">
					
                        <div class="panel-title">Login</div>
                    </div>     

                    <div style="padding-top:30px" class="panel-body" >

                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                            
                        <form id="loginform" class="form-horizontal" role="form">
                                    
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="txt_usuario" type="text" class="form-control" name="Usuario" value="" placeholder="Ingrese el correo Electronico">                                        
                            </div>
                                
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="txt_password" type="password" class="form-control" name="password" placeholder="Contraseña">
                            </div>
                                        <input type="button" value="Entrar" id="btnLogin" name="btnLogin" class="btn btn-success" />
                       </form> 
                              
                    </div> </div>
				</div>
   </div>
   
   
   </main>
    </body>
</html>
