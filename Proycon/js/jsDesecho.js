
$(document).on('keyup','#txtBuscarHerramienta',function(){
    var valor = $(this).val();
    if(valor.length>0){
		
      BuscaHerramientas(valor);
    }
    else{
        $("#ResultadoBusqudaHerramienta table tr").remove();
    }
});

function BuscaHerramientas(consulta){
    $.ajax({
        type: "POST",
        url: "../BLL/ProyectosBuscar.php",
        data: {"consulta": consulta},
        beforeSend: function () {
            $("#ResultadoBusqudaHerramienta").html("<div style='margin:auto;width:200px'><img src='../resources/imagenes/loanding.gif'  width='100px'/></div>");
        },
        success: function (respuesta) {
            $("#ResultadoBusqudaHerramienta").html(respuesta);
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 0) {

            alert('No nos pudimos Conectar con el sevidor Verifique su conexion a Internet ');

        } else if (jqXHR.status == 404) {

            alert('Error [404] No se encontro el Archivo');

        } else if (jqXHR.status == 500) {

            alert('Error de conexion con el servidor');

        }

    });
}


function FiltrarDesecho() {

    var Filtro = $("#cboFitrarDesecho").val();

    var route = "";

    if(Filtro == "listar"){
        route =  "../BLL/Desecho.php?opc=listar";
    }else if(Filtro == "listarMaterial"){
        route =  "../BLL/Desecho.php?opc=listarMaterial";
    }
    else if(Filtro == "listarHeramienta"){
        route =  "../BLL/Desecho.php?opc=listarHeramienta";
    }
    console.log(Filtro);

    $.ajax({
        type: "GET",
        url: route,
        //data: {"Opc": Fitro},
        beforeSend: function(){
            $("#TablalistadoDesecho").html("Procesando... <center> <img src='../resources/imagenes/loanding.gif' style='margin:auto'  width='20px'/></center>");    
        },
        success: function (respuesta) {
            $("#TablalistadoDesecho").html(respuesta);
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 0) {

            alert('No nos pudimos Conectar con el sevidor Verifique su conexion a Internet ');

        } else if (jqXHR.status == 404) {

            alert('Error [404] No se encontro el Archivo');

        } else if (jqXHR.status == 500) {

            alert('Error de conexion con el servidor');

        }

    });

}



function MostrarFormHerramienta() {
    $("#panelContienetblDesecho").hide();

    
    $("#pnlnuevoPedido").show('slow');
    $(".nuevoPedido").show();
    $("#PedidoHerramientas").show();

    $("#PedidoMateriales").hide();

    


}


function MostrarFormMaterial() {

    $("#panelContienetblDesecho").hide();

    
    $("#pnlnuevoPedido").show('slow');
    $(".nuevoPedido").show();    
    $("#PedidoMateriales").show();

    $("#PedidoHerramientas").hide();



}

function Atras() {

    $("#panelContienetblDesecho").show("slow");

    $(".nuevoPedidoMaterial").hide();
    $("#pnlnuevoPedidoMaterial").hide();

    $(".pnlnuevoPedido").hide();
    $("#pnlnuevoPedido").hide();
}


function BuscarMaterialNombre() {
    var nombre = $("#txtBuscarMaterialP").val();
    if (nombre != null) {
        $.ajax({
            type: 'POST',
            url: '../BLL/Materiales.php?opc=bnombre&Nombre=' + nombre + '',
            beforeSend: function () {
                $("#tbl_body_buscarMaterial").html("<img src='../resources/imagenes/loanding.gif'  width='100px'/>");
            },
            success: function (respuesta) {
                $("#tbl_body_buscarMaterial").html(respuesta);
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 0) {

                alert('No nos pudimos Conectar con el sevidor Verifique su conexion a Internet ');

            } else if (jqXHR.status == 404) {

                alert('Error [404] No se encontro el Archivo');

            } else if (jqXHR.status == 500) {

                alert('Error de conexion con el servidor');

            }

        });
        

    } else {

    }


}


function BuscarHerramientaNombre() {
    var descripcion = $("#txtCodigoHerramientaBuscar").val();


    $.ajax({
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=descripcion&Descripcion=' + descripcion + '',
        beforeSend: function () {
            $("#tbl_body_buscarHerramienta").html("<img src='../resources/imagenes/loanding.gif'  width='100px'/>");
        },
        success: function (respuesta) {
            $("#tbl_body_buscarHerramienta").html(respuesta);
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        if (jqXHR.status === 0) {

            alert('No nos pudimos Conectar con el sevidor Verifique su conexion a Internet ');

        } else if (jqXHR.status == 404) {

            alert('Error [404] No se encontro el Archivo');

        } else if (jqXHR.status == 500) {

            alert('Error de conexion con el servidor');

        }

    });


    setTimeout(function () {
        $("#modalBoletaReparacionHerramienta").removeClass("mensajeError");
        $("#mensajeBoletaReparacion").html("Salida De Herramienta Ha Reparación");

    }, 3000);
}


function AgregarMaterialBuscadoPNombre(evento) {
    var cod = $(evento).parents("tr").find("td").eq(0).html();
    var nombre = $(evento).parents("tr").find("td").eq(1).html();
    var stock = $(evento).parents("tr").find("td").eq(2).html();
    var cantidad = 1 * $(evento).parents("tr").find("td").eq(3).children("input").val();
    alert
    $(".txtcantiSolicitadaMaterial").val("");
    if ((stock < cantidad) || (cantidad < 0) || (stock == 0) || (cantidad == "") ) {
        $(evento).parents("tr").find("td").eq(3).children("input").css("border", "1px solid red");
    } else {
        $(evento).parents("tr").find("td").eq(3).children("input").css("border", "1px solid #cccccc");
        var nuevaFila = "<tr>" +
                "<td>" + cod + "</td>" +
                "<td>" + cantidad + "</td>" +
                "<td>" + nombre + "</td>" +
                "<td style='width: 25px;'>" +
                "<button title='Quitar Fila' class='btnRemoverFila' type='button'  onclick='Remover(this)'>" +
                "<img title='Eliminar Fila' src='../resources/imagenes/remove.png' alt='' width='20px'/>" +
                "</button>" +
                "</td>" +
                "</tr>";
        $("#tbl_P_Materiales").append(nuevaFila);
    }

}

function Remover(evento) {
    $(evento).parents("tr").remove();
}


function AgregarHerramientaBuscadoPNombre(evento) {
    var tipo = $(evento).parents("tr").find("td").eq(0).html();
    var cod = $(evento).parents("tr").find("td").eq(1).html();
    var Tipo = $(evento).parents("tr").find("td").eq(2).html();
    var Marca = $(evento).parents("tr").find("td").eq(3).html();

    $(evento).parents("tr").find("td").eq(3).children("input").css("border", "1px solid #cccccc");
    var nuevaFila = "<tr>" +
            "<td hidden='true' >" + tipo + "</td>" +
            "<td id='codTabla'>" + cod + "</td>" +
            "<td id='tipoTabla'>" + Tipo + "</td>" +
            "<td id='marcaTabla'>" + Marca + "</td>" +
            "<td style='width: 25px;'>" +
            "<button title='Quitar Fila' class='btnRemoverFila' type='button'  onclick='Remover(this)'>" +
            "<img title='Eliminar Fila' src='../resources/imagenes/remove.png' alt='' width='20px'/>" +
            "</button>" +
            "</td>" +
            "</tr>";

    $("#tbl_P_Herramientas").append(nuevaFila);


}


function GuardarBoletaMateriales(opc) {
    var dt = new Date();
    var mes = dt.getMonth() + 1;
    var fecha = dt.getFullYear() + '-' + mes + '-' + dt.getDate(); 
    if ($("#cboPedidos2").val() == 1) {
        var numFilas = $("#tbl_P_Materiales tbody tr").length;
       // alert(numFilas);
        var consecutivo = $("#consecutivoPedidoM").html();
        /*Estas variables son alimentadas con dos elementos ocultos que se encuentral
         * al final de la pagina*/
        var ID_Proyecto = $("#idProecto").html();
       // var ID_Usuario = $("#idUsuario").html();
        var matriz = new Array(numFilas);
        var cont = 1;
        var i;
        var j;
        if (numFilas > 0) {
            for (i = 0; i < numFilas; i++) {
                matriz[i] = new Array(numFilas);
                for (j = 0; j < 2; j++) {
                    matriz[i][j] = document.getElementById("tbl_P_Materiales").rows[cont].cells[j].innerHTML;
                }
                cont++;
            }
  
           
            var datos = {
                "TipoPedido": 0, //0 - Para materiales
                "ID_Proyecto": ID_Proyecto,
                "consecutivo": consecutivo,
                "fecha": fecha,
                "arreglo": arreglo
            };

            var arreglo = JSON.stringify(datos);


                AjaxAgregarHerramientas(arreglo);
                if (opc == 1) {
                      var table = document.getElementById("tbl_P_Materiales");
               // var  numFilas =  $("#tbl_P_Materiales tbody tr").length;
            for (var i = 0; i <= numFilas; i++) {
               var firstRow = table.rows[i];
               firstRow.deleteCell (3);}
           ExportarPdfBoletaMateriales();
            }
          
           $("#tbl_P_Materiales tbody tr").remove();

  
            

        }
    }
}

function GuardarBoletaHerramientas(){

    var consecutivo = $("#consecutivoPedidoH").html(); 

    var dt = new Date();
    var mes = dt.getMonth() + 1;
    var fecha = dt.getFullYear() + '-' + mes + '-' + dt.getDate(); 
    var motivo =  $("#motivoDesechoG").val();
    

    let ArregloTabla = [];

    document.querySelectorAll('#tbl_P_Herramientas tbody tr').forEach(function(e){
        let fila = {
          codigo: e.querySelector('#codTabla').innerText,
          cantidad: 1  // Por defecto 1 ya que es una por registro
        };
        ArregloTabla.push(fila);
      });

      var arreglo = JSON.stringify(ArregloTabla);



      AjaxAgregarHerramientas(arreglo,fecha,motivo,consecutivo);
    
}


function AjaxAgregarHerramientas(datos, fecha,motivo, consecutivo){
    $.ajax({
        data: {
            data : datos,
               fecha : fecha,
               motivo: motivo,
               consecutivo: consecutivo
            },
        type: "POST",
        url: "../BLL/Desecho.php?opc=registrarPedido",
        success: function (respuesta) {
            if (respuesta != 0) {

                $("#MensajeSucessfull").html("La Boleta se ha generado correctamente");
                $("#Mensajesucessfull").modal("show");               
          
                setTimeout(function () {
                    $("#Mensajesucessfull").modal("hide");
                }, 2000);
                return 1;
            } else {
                 $("#tbl_P_Materiales tbody tr").html(respuesta);
               $("#Mensajesucessfull").removeClass("alert-success");
                $("#Mensajesucessfull").addClass("alert-danger");
                $("#MensajeSucessfull").html("Ocurrio un ERROR al registrar la Boleta");
                $("#Mensajesucessfull").modal("show");
                setTimeout(function () {
                    $("#Mensajesucessfull").modal("hide");

                }, 2000);
            }

        }
    });
}









