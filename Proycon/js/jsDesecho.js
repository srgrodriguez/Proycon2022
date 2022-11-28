
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


function ActualizarTablaDesecho() {    

    route =  "../BLL/Desecho.php?opc=listar";

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
                "<td class='codTabla'>" + cod + "</td>" +
                "<td class='cantidadTabla'>" + cantidad + "</td>" +
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
          cantidad: 1,  // Por defecto 1 ya que es una por registro
          tipo: 1 // Correspondiete a Herramientas  
        };
        ArregloTabla.push(fila);
      });

      var arreglo = JSON.stringify(ArregloTabla);



      AjaxAgregarHerramientas(arreglo,fecha,motivo,consecutivo);
    
}



function GuardarBoletaMateriales(){

    var consecutivo = $("#consecutivoPedidoM").html(); 

    var dt = new Date();
    var mes = dt.getMonth() + 1;
    var fecha = dt.getFullYear() + '-' + mes + '-' + dt.getDate(); 
    var motivo =  $("#motivoDesechoM").val();
    

    let ArregloTabla = [];

    document.querySelectorAll('#tbl_P_Materiales tbody tr').forEach(function(e){
        let fila = {
          codigo: e.querySelector('.codTabla').innerText,
          cantidad: e.querySelector('.cantidadTabla').innerText,  // Por defecto 1 ya que es una por registro
          tipo: 0 // Correspondiete a Material  
        };
        ArregloTabla.push(fila);
      });

      var arreglo = JSON.stringify(ArregloTabla);

      AjaxAgregarMateriales(arreglo,fecha,motivo,consecutivo);
    
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
                
                $("#consecutivoPedidoH").html(" " + respuesta);
                $("#consecutivoPedidoM").html(" " + respuesta);

                
                setTimeout(function () {
                    $("#Mensajesucessfull").modal("hide");
                    
                }, 2000);
                
                ActualizarTablaDesecho(); // volver a cargar la lista     

                // reiniciar la tabla de la boleta
                var table = document.getElementById('cuerpoPedidoHerramientas');    
                table.innerHTML = '';

                $("#motivoDesechoG").val("");
                


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



function AjaxAgregarMateriales(datos, fecha,motivo, consecutivo){
    $.ajax({
        data: {
            data : datos,
               fecha : fecha,
               motivo: motivo,
               consecutivo: consecutivo
            },
        type: "POST",
        url: "../BLL/Desecho.php?opc=registrarPedidoMateriales",
        success: function (respuesta) {
            if (respuesta != 0) {

                $("#MensajeSucessfull").html("La Boleta se ha generado correctamente");
                $("#Mensajesucessfull").modal("show");               
                
                $("#consecutivoPedidoH").html(" " + respuesta);
                $("#consecutivoPedidoM").html(" " + respuesta);

                
                setTimeout(function () {
                    $("#Mensajesucessfull").modal("hide");
                    
                }, 2000);
                
                ActualizarTablaDesecho(); // volver a cargar la lista     

                // reiniciar la tabla de la boleta
                var table = document.getElementById('tbl_Materiales');    
                table.innerHTML = '';

                $("#motivoDesechoM").val("");
                


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


function AgregarHerramientaPedido() {
    var codigo = $("#txtCodigoHerramienta").val();
    $.ajax({
        type: "POST",
        url: "../BLL/Desecho.php?opc=buscarherramientapedido&codigo=" + codigo,
        success: function (respuesta) {
            if (respuesta == 0) {
                $("#ModalDefaul").modal("show");
                $("#MensajeErrorMaterial").html("Codigo Incorrecto");
                $("#CantMaterialExistente").html("");
            } else if (respuesta == -1) {
                $("#ModalDefaul").modal("show");
                $("#MensajeErrorMaterial").html("<strong>Error de Conexion con el servidor de Base de datos</strong>");
            } else {
                $("#cuerpoPedidoHerramientas").html($("#cuerpoPedidoHerramientas").html() + respuesta);
            }
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

function AgregarMaterialPedido() {
    var codigo = $("#txtCodigoMaterial").val();
    var cant = $("#txtCantidadMaterial").val();
    if (isNaN(cant) || cant == "") {
        return;
    }
    if (codigo != "") {

        if (cant < 0) {
            $("#txtCantidadMaterial").css("border", "1px solid red");
        } else {

            $("#txtCodigoMaterial").css("border", "2px solid #cccccc");
            $("#txtCantidadMaterial").css("border", "2px solid #cccccc");
            $.ajax({

                type: 'POST',
                url: '../BLL/Desecho.php?opc=buscarM&id=' + codigo + '&cant=' + cant + '',
                success: function (respuesta) {
                   // alert(respuesta);
                    if (respuesta > 0) {
                        $("#ModalDefaul").modal("show");
                        $("#MensajeErrorMaterial").html("Cantidad Menor A la Existente");
                        $("#CantMaterialExistente").html("<strong>Cantidad en Bodega: </strong>" + respuesta)
                    } else if (respuesta == 0) {
                        $("#ModalDefaul").modal("show");
                        $("#MensajeErrorMaterial").html("Codigo Incorrecto");
                        $("#CantMaterialExistente").html("");
                    } else if (respuesta == -1) {
                        $("#ModalDefaul").modal("show");
                        $("#MensajeErrorMaterial").html("<strong>Error de Conexion con el servidor de Base de datos</strong>");
                    } else {
                        $("#ContenidoPedido").html($("#ContenidoPedido").html() + respuesta);
                        $("#txtCodigoMaterial").val("");
                        $("#txtCantidadMaterial").val("");
                    }


                }
            });
        }
    } else {
        $("#txtCodigoMaterial").css("border", "1px solid red");

    }


}



function VerPedido(evento) {
    var boleta = $(evento).parents("tr").find("td").eq(0).html();
    var motivo = $(evento).parents("tr").find("td").eq(1).html();
    var fechaDesecho = $(evento).parents("tr").find("td").eq(2).html();
    var usuarioDesecho = $(evento).parents("tr").find("td").eq(3).html();
    var tipoDesecho = $(evento).parents("tr").find("td").eq(4).html();

    var array = fechaDesecho.split("-");
    $("#dia").html(array[2]);
    $("#mes").html(array[1]);
    $("#anno").html(array[0]);
    $("#tipoPedidoSeleccionado").html(boleta);
    $("#nomProyectoPedidoSelecionado").html($("#nomProyecto").html());
    $("#generadaPor").html(usuarioDesecho);
    $("#TipoPedido").html(tipoDesecho)


    if (TipoPedido == "Material") {
        
        $("#btnAnularBoletaMaterial").css("display","inline-block");
        $("#btnAnularBoletaHerramientas").css("display","none");
    } else {
       
        $("#btnAnularBoletaMaterial").css("display","none");
        $("#btnAnularBoletaHerramientas").css("display","inline-block");
    }

    $.ajax({
        url: "../BLL/Desecho.php?opc=ConsultarDesecho",

        data:{
            boleta: boleta
        },
        type: "POST",
        success: function (respuesta) {
            $("#consecutivoPedidoSeleccionado").html(boleta);
            $("#ContenidoPedido_Selecionado").html(respuesta);
            $("#ModalVerPedido").modal("show");

            

        }
    });
}

function Exportar_Pdf(idTabla) {
    //alert("Entro");
    var imgData = "data:image/png;base64,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";
    var Npedido = $("#consecutivoPedidoSeleccionado").html();
    var dia = $("#tblFecha tbody tr td").eq(0).html();
    var mes = $("#tblFecha tbody tr td").eq(1).html();
    var anno = $("#tblFecha tbody tr td").eq(2).html();
    var Proyecto = $("#nomProyectoPedidoSelecionado").html();
    var TipoBoleta = $("#TipoPedido").html();
    var generadaPor = $("#generadaPor").html();
    var pdf = new jsPDF('p', 'pt', 'letter');
    pdf.addImage(imgData, 'JPEG', 30, 50, 100, 100);
    pdf.setTextColor(255, 0, 0);
    pdf.setFontSize(22);
    pdf.text(400, 110, 'Boleta Nº ' + Npedido);
    pdf.setFontSize(18);
    pdf.setTextColor(0);
    pdf.text(400, 150, 'Fecha: ' + dia + "/" + mes + "/" + anno);
    pdf.setTextColor(0);
    pdf.text(30, 180, Proyecto);
    pdf.text(30, 210, "Boleta de: " + TipoBoleta);
    pdf.text(30, 240, "Generada Por: " + generadaPor);
    pdf.setLineWidth(1);
    pdf.line(20, 260, 600, 260);
    
//pdf.save("Pedido"+'.pdf');


    var htmlBody = $("#"+idTabla).html();
    var margins = {top: 270, bottom: 20, left: 70, width: 100};

    pdf.fromHTML(htmlBody, margins.left, margins.top, {'width': margins.width}, function (dispose) {
        pdf.save("Pedido" + '.pdf');
    }, margins);
    //pdf.create()
}



