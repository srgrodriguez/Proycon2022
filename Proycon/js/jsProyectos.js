
$(document).on('keyup','#txtBuscarHerramienta',function(){
    var valor = $(this).val();
    if(valor.length>0){
		
      BuscaHerramientas(valor);
    }
    else{
        $("#ResultadoBusqudaHerramienta table tr").remove();
    }
});
$(BuscaHerramientas());
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
function Atras(){
    if($("#materiales").is(":visible")){
        $("#pnlListarProyectos").show();
        $(".informacionProyecto").hide();
         return;
    }
   if ($("#pedidoProveduria").is(":visible")) {
        $("#pnlnuevoPedido").hide();
        $("#pedidoProveduria").hide();
        $("#mhProyectos").show();
            $("#PedidoHerramientas").hide();
            $("#materiales").show();;
            $("#herramientas").show();
        
    }
    if ($("#pnlnuevoPedido").is(":visible")) {
        $("#pnlnuevoPedido").hide();
       
        $("#contienePedidos").show();
        $("#pdotituloBtnA").show();
        $("#pnlListaPedidos").show();
        return;
        
    }
    if ($("#pnlListaPedidos").is(":visible")) {
        $("#pnlListaPedidos").hide();
        $("#mhProyectos").show();
        $(".nombreProyecto").show();
        // $(".nombreProyecto").show();
        $("#materiales").show();
        $("#herramientas").show();
         return;
        
    }
     if($("#pnlColaPedidos").is(":visible")){
         window.location.href="Proyectos.php";
         return;
     }
     if ($("#pnlBoletasPedidos").is(":visible")) {
        $("#pnlColaPedidos").show();
        $("#pnlBoletasPedidos").hide();
        return;
    }
    
    
}









function ExpandirMateriales(opcion) {

    var anchoMateriales = $(".materiales").width();
    var anchoHerramientas = $(".herramientas").width();


    if ((anchoMateriales > 700) || (anchoHerramientas > 700)) {
        if (opcion == 1) {
            $(".materiales").css("width", "49%");
            $("#herramientas").show();
        } else {
            $(".materiales").css("width", "49%");
            $("#materiales").show();
        }
    } else {
        if (opcion == 1) {
            $(".materiales").css("width", "100%");
            $("#herramientas").hide();
            $(".materiales").css("margin-bottom", "3%");
        } else {
            $("#materiales").hide();
            $(".materiales").css("width", "100%");
            $(".materiales").css("margin-bottom", "3%");
        }
    }
}
function agregarProyecto() {
$("#txtNombreProyecto").val("");
$("#txtFechaCreacionProyecto").val("");
$("#txtEncargadoProyecto").val("");
$("#btnInsertarProyecto").show("");
$("#btnEditarProyecto").hide("");


}
function listarProyectos() {
    if ($("#pedidos").is(":visible")) {
        $("#materiales").hide();
        $("#herramientas").hide();
        $("#pedidoProveduria").hide();
        $("#pedidos").addClass("pedidos");
        $("#pedidos").removeClass("materiales");
        $("#pedidos").hide();
        $("#pdotituloBtnA").hide();
        $("#divCboBtnRegresar").hide();
        $("#contienePedidos").hide();
        $(".nuevoPedido").hide();
        $("#PedidoMateriales").hide();
    }
    $(".mostrarProyectos").show("slow");
    $(".informacionProyecto").hide();
}


function HerramientasMateriales(ID_Proyecto) {
    $("#idProecto").html(ID_Proyecto);
    $("#nomProyecto").html("Proyecto: " + $("#" + ID_Proyecto + "").html());
    $("#mhProyectos").show();
    ActualizarMaterialesHerramientaProyecto(ID_Proyecto);
    $(".informacionProyecto").show(100);
    $("#pnlListarProyectos").hide();
    $("#materiales").show();
    $("#herramientas").show();
    $(".menuBotones").css("float", "right");
    $(".menuBotones").css("margin-right", "4%");
    $("#pedidoProveduria").hide();


}
function cargarNombre(proyecto) {
    if (proyecto == 0) {

        $("#nomProyecto").text("Proyecto: Distrito 4");
    } else if (proyecto == 1) {
        $("#nomProyecto").text("Proyecto: Ribera Laureles");
    } else {
        $("#nomProyecto").text("Proyecto: Plataforma de Parqueos");
    }
}

function InsertarProyecto() {
    var nombre = $('#txtNombreProyecto').val();
    var encargado = $('#txtEncargadoProyecto').val();
    var fechaInicio = $('#txtFechaCreacionProyecto').val();

    if (nombre != "" && encargado != "" && fechaInicio != "") {
        var datos = {
            "Nombre": nombre,
            "Encargado": encargado,
            "FechaInicio": fechaInicio
        };
        $.ajax({
            data: datos,
            type: 'POST',
            url: '../BLL/Proyectos.php?opc=registrar',

            success: function (respuesta) {
                if (respuesta == -1) {
                    $("#MostrarMensajeSucess").html("<strong>Error de Conexion con base de datos</strong>");
                    $("#headerInsertarP").addClass("mensajeError");
                } else if (respuesta == 1) {
                    $("#headerInsertarP").addClass("mensajeCorrecto");
                    $("#MostrarMensajeSucess").html("<strong>Datos Insertados Correctamente</strong>");
                    ActualizarListaProyectos();
                    LimpiarFormulario();
                    //
                } else {
                    $("#headerInsertarP").addClass("mensajeError");
                    $("#MostrarMensajeSucess").html("<strong>Ocurrio Un Error Al Insertar Los Datos</strong>");

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

    } else {
        $("#headerInsertarP").addClass("mensajeError");
        $('#txtNombreProyecto').focus();
        $("#MostrarMensajeSucess").html("<strong>Debes llenar todos los campos del Formulario</strong>");
    }

    setTimeout(function () {
        $("#headerInsertarP").removeClass("mensajeCorrecto");
        $("#headerInsertarP").removeClass("mensajeError");
        $("#MostrarMensajeSucess").html("Agregar Nuevo Proyecto");
        $("#ModalAgregarProyecto").modal("hide");
    }, 3000);

}
function ActualizarListaProyectos() {
    $.ajax({
        type: 'POST',
        url: '../BLL/Proyectos.php?opc=listar',
        success: function (respuesta) {
            $("#mostrarProyectos").html(respuesta);
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
function ActualizarMaterialesHerramientaProyecto(ID_Proyecto) {
    $.ajax({
        data: {"idProyecto": ID_Proyecto},
        type: 'POST',
        url: '../BLL/Proyectos.php?opc=listMyH',
         beforeSend: function () {
                $("#mhProyectos").html(" <div style='width:200px;margin:auto'> Cargando <img src='../resources/imagenes/loanding.gif' style='margin:auto'  width='100px'/></div>");
            },
        success: function (respuesta) {
            $("#mhProyectos").html(respuesta);
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
function BuscarProyecto() {
    var datos = {
        "Nombre": $('#txtNombreProyecto').val()};

    $.ajax({
        data: datos,
        type: 'POST',
        url: '../BLL/Proyectos.php?opc=buscar',

        success: function (respuesta) {

            if (respuesta == 0) {
                $("#headerInsertarP").addClass("mensajeAdvertencia");
                $('#txtNombreProyecto').focus();
                $("#MostrarMensajeSucess").html("El Proyecto Solicitado <strong>No EXISTE</strong>");
                setTimeout(function () {
                    $("#headerInsertarP").removeClass("mensajeAdvertencia");
                    $("#MostrarMensajeSucess").html("Agregar Nuevo Proyecto");
                }, 3000);

            } else {
               
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

function ModificarProyecto(){
  var datos = {
        "ID":$("#btnEditarProyecto").val(),
        "nombre": $('#txtNombreProyecto').val(),
        "encargado": $('#txtEncargadoProyecto').val(),
        "fecha": $('#txtFechaCreacionProyecto').val()
    
    };

    $.ajax({
        data: datos,
        type: 'POST',
        url: '../BLL/Proyectos.php?opc=modificarProyecto',

        success: function (respuesta) {

            if (respuesta == 1) {
                $("#headerInsertarP").addClass("mensajeCorrecto");
                $('#txtNombreProyecto').focus();
                $("#MostrarMensajeSucess").html("<strong>Datos modificados Correctamente</strong>");
               ActualizarListaProyectos();
                setTimeout(function () {
                    $("#headerInsertarP").removeClass("mensajeCorrecto");
                    $("#MostrarMensajeSucess").html("Agregar Nuevo Proyecto");
                    $("#ModalAgregarProyecto").modal("hide");
                }, 3000);

            } else {
                $("#headerInsertarP").addClass("mensajeError");
                $('#txtNombreProyecto').focus();
                $("#MostrarMensajeSucess").html("<strong>Ocurrio un Error al edirar los datos</strong>");
                setTimeout(function () {
                    $("#headerInsertarP").removeClass("mensajeError");
                    $("#MostrarMensajeSucess").html("Agregar Nuevo Proyecto");
                }, 3000); 
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
function LimpiarFormulario() {
    $('#txtNombreProyecto').val("");
    $('#txtEncargadoProyecto').val("");
    $('#txtFechaCreacionProyecto').val("");
}
function MostrarSolicitud() {
    //alert($("#idProecto").html());
    window.location.href="SolicitudPedidos.php?id="+$("#idProecto").html();
    
   /*
    $("#pnlListaPedidos").hide();
    $("#PedidoHerramientas").hide();
    $("#materiales").hide();
    $("#herramientas").hide();
    $("#pedidoProveduria").show();
    $("#pedidos").addClass("materiales");
    $("#pedidos").removeClass("pedidos");
    $("#pedidos").show("");
    $("#pdotituloBtnA").hide();
    $("#divCboBtnRegresar").show();
    $("#contienePedidos").hide();
    //$("#headPedidos");
    $(".nuevoPedido").show();
    $("#PedidoMateriales").show();
    $('html,body').animate({
        scrollTop: $(".informacionProyecto").offset().top
    }, 800);
   */
}
function MostrarPedidos() {

    if ($("#pedidoProveduria").is(":visible")) {
        $("#pedidoProveduria").hide();
        $(".nuevoPedido").hide();
        $("#PedidoMateriales").hide();
        $("#pedidos").addClass("pedidos");
        $("#pedidos").removeClass("materiales");
        $("#pedidos").show();
        $("#headPedidos").show();
        $("#pdotituloBtnA").show();
        $("#contienePedidos").show();
        $("#divCboBtnRegresar").hide();
    } else {

        ActulizarSeccionPedidos();
        $("#materiales").hide();
        $("#herramientas").hide();
        $(".nombreProyecto").hide();
       // $(".nombreProyecto").hide();
        $("#pedidos").show();
        $("#pnlListaPedidos").show("slow");
        $("#pnlnuevoPedido").hide();
        $("#headPedidos").show();
        $("#contienePedidos").show();
        $("#pdotituloBtnA").show();
    }




}
function btnRegresarPedidos() {
    var estadoPedido = $(".nuevoPedido").is(":visible");
    if (estadoPedido == true)
    {
        $("#contienePedidos").show();
        $("#pdotituloBtnA").show();
        $("#pedidos").removeClass("pedidosClase");
        $("#btnRegresar").css("display", "none");
        $(".nuevoPedido").hide("slow");
        $("#divCboBtnRegresar").hide();
        $("#pedidoProveduria").hide();
    } else {

        //$("#pedidoProveduria").hide();
        $("#pedidos").hide();
        $("#materiales").show();
        $("#herramientas").show();
        $("#mhProyectos").show("slow");

    }

}
function MostrarFormPedidos() {
    var nombreProyecto = $("#nomProyecto").html();
    $("#nomProyectoPedido").html(nombreProyecto);
    $("#proyectoHerramientas").html(nombreProyecto);
    $("#contienePedidos").hide();
    $("#pdotituloBtnA").hide();
    /*  $("#btnRegresar").show("slow");*/
    $(".nuevoPedido").show();
    $("#pnlnuevoPedido").show("slow");
    $("#pnlListaPedidos").hide();
    $("#divCboBtnRegresar").show();

    if ($("#cboPedidos").val() == 1) {
        $("#cboPedidos2").val(1);
        $("#PedidoMateriales").show();
        $("#PedidoHerramientas").hide();
    } else {
         $("#cboPedidos2").val(2);
        $("#PedidoHerramientas").show();
        $("#PedidoMateriales").hide();

    }
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
                url: '../BLL/Proyectos.php?opc=buscarM&id=' + codigo + '&cant=' + cant + '',
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
function AgregarDeProveduPedidoBodega(evento) {
    
    var codigo = $(evento).parents("tr").find("td").eq(0).html();
    var cant = $(evento).parents("tr").find("td").eq(1).html();
            $.ajax({
                type: 'POST',
                url: '../BLL/Proyectos.php?opc=buscarM&id=' + codigo + '&cant=' + cant + '',
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
function AgregarHerramientaPedido() {
    var codigo = $("#txtCodigoHerramienta").val();
    $.ajax({
        type: "POST",
        url: "../BLL/Proyectos.php?opc=buscarherramientapedido&codigo=" + codigo,
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
        ;

    } else {

    }


}


function seleccionarRemover() {
    var visible = $(".btnRemover").is(":visible");
    var c = $(".chek").is(':checked');

    if (c == true) {
        $(".btnRemover").css("display", "block");

    } else {
        if (visible == true) {
            $(".btnRemover").css("display", "none");

        } else {
            $(".btnRemover").css("display", "block");
        }
         

    }
}
function Remover(evento) {
    $(evento).parents("tr").remove();
}
function CambiarPeido() {
    var valor = $("#cboPedidos2 option:selected").html();
    if (valor == "Materiales") {
        $("#PedidoMateriales").show();
        $("#PedidoHerramientas").hide();
    } else {
        $("#PedidoHerramientas").show();
        $("#PedidoMateriales").hide();
    }
}

function MostrarAgregarDevol() {
    $("#tablaInsertarDevol").toggle();
}
function MostrarActualizarDevol(event) {
    $("#tablaInsertarDevol").show();
    $("#btnActualizarDevolucion").show();
    $("#btnAgregarDevolucion").hide();
    var cantidad = $(event).parents("tr").find("td").eq(0).html();
    var fecha = $(event).parents("tr").find("td").eq(1).html();
    var nBoleta = $(event).parents("tr").find("td").eq(2).html();
    $("#txtCantidadDevol").val(cantidad);
    $("#txtFechaDevol").val((fecha));
    $("#txtBoletaDevol").val(nBoleta);
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
function AgregarHerramientaBuscadoPNombre(evento) {
    var tipo = $(evento).parents("tr").find("td").eq(0).html();
    var cod = $(evento).parents("tr").find("td").eq(1).html();
    var Tipo = $(evento).parents("tr").find("td").eq(2).html();
    var Marca = $(evento).parents("tr").find("td").eq(3).html();

    $(evento).parents("tr").find("td").eq(3).children("input").css("border", "1px solid #cccccc");
    var nuevaFila = "<tr>" +
            "<td hidden='true'>" + tipo + "</td>" +
            "<td>" + cod + "</td>" +
            "<td>" + Tipo + "</td>" +
            "<td>" + Marca + "</td>" +
            "<td style='width: 25px;'>" +
            "<button title='Quitar Fila' class='btnRemoverFila' type='button'  onclick='Remover(this)'>" +
            "<img title='Eliminar Fila' src='../resources/imagenes/remove.png' alt='' width='20px'/>" +
            "</button>" +
            "</td>" +
            "</tr>";

    $("#tbl_P_Herramientas").append(nuevaFila);


}
function GuardarBoletaPedido(opc) {
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
  
            var arreglo = JSON.stringify(matriz);
            var datos = {
                "TipoPedido": $("#cboPedidos2").val(),
                "ID_Proyecto": ID_Proyecto,
                "consecutivo": consecutivo,
                "fecha": fecha,
                "arreglo": arreglo
            };
                AjaxRegistroBolestasPedidos(datos);
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
    } else {
        var numFilas = $("#tbl_P_Herramientas tbody tr").length;
        var consecutivo = $("#consecutivoPedidoH").html();
        var ID_Proyecto = $("#idProecto").html();
        var matriz = new Array(numFilas);
        var cont = 1;
        var i;
        var j;
        if (numFilas > 0) {
            //alert("numFilas " +numFilas);
            for (i = 0; i < numFilas; i++) {
                matriz[i] = new Array(numFilas);
                for (j = 0; j < 2; j++) {
                    matriz[i][j] = document.getElementById("tbl_P_Herramientas").rows[cont].cells[j].innerHTML;
                //console.log( matriz[i][j]);
                }
                cont++;
            }
            var arreglo = JSON.stringify(matriz);
            var datos = {
                "TipoPedido": $("#cboPedidos2").val(),/*Materiales o Herramientas*/
                "ID_Proyecto": ID_Proyecto,
                "consecutivo": consecutivo,
                "fecha": fecha,
                "arreglo": arreglo
            };
            AjaxRegistroBolestasPedidos(datos);
            if (opc == 0) {
               // alert("entro");
           var table = document.getElementById("tbl_P_Herramientas");
            for (var i = 0; i <= numFilas; i++) {
               var firstRow = table.rows[i];
               firstRow.deleteCell (3);}
             ExportarPdfBoletaHerramientas(); 
            }
          
           $("#tbl_P_Herramientas tbody tr").remove();
        }
    }
}
function ElimiarNotificacion(ID_Proyecto){ 
    if ($("#consecutivoPedidoProveeduria").html() != null )  {
        $.ajax({
        url : "../BLL/Proyectos.php?opc=eliminarnotificacion&id="+$("#consecutivoPedidoProveeduria").html()+"&ID_Proyecto="+ID_Proyecto,
         success: function (respuesta) {
             $("#solicitudPedidos").html(respuesta);
         }
    });
    }

}
function GuardarBoletaPedidoHerramienta() {

}
function AjaxRegistroBolestasPedidos(datos) {
    var ID_Proyecto = $("#idProecto").html();
    $.ajax({
        data: datos,
        type: "POST",
        url: "../BLL/Proyectos.php?opc=registrarPedido",
        success: function (respuesta) {
            if (respuesta != 0) {
                //$("#tbl_P_Herramientas tbody tr").remove();
                //$("#tbl_P_Materiales tbody tr").remove();
                $("#consecutivoPedidoM").html(" " + respuesta);
                $("#consecutivoPedidoH").html(" " + respuesta);
                $("#MensajeSucessfull").html("La Boleta se ha generado correctamente");
                $("#Mensajesucessfull").modal("show");
                ActulizarSeccionPedidos();
                $("#mhProyectos").hide();
                ActualizarMaterialesHerramientaProyecto(ID_Proyecto);
                ElimiarNotificacion(ID_Proyecto);
               // Exportar_Pdf("tablaPedidoMateriales");
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
function VerPedido(evento) {
    var TipoPedido = $(evento).parents("tr").find("td").eq(0).html();
    var NBoleta = $(evento).parents("tr").find("td").eq(1).html();
    var fecha = $(evento).parents("tr").find("td").eq(2).html();
    var nombre = $(evento).parents("tr").find("td").eq(3).html();
    var array = fecha.split("-");
    $("#dia").html(array[2]);
    $("#mes").html(array[1]);
    $("#anno").html(array[0]);
    $("#tipoPedidoSeleccionado").html(TipoPedido);
    $("#nomProyectoPedidoSelecionado").html($("#nomProyecto").html());
    $("#generadaPor").html(nombre);
    if (TipoPedido == 1) {
        $("#TipoPedido").html("Materiales");
        $("#btnAnularBoletaMaterial").css("display","inline-block");
        $("#btnAnularBoletaHerramientas").css("display","none");
    } else {
        $("#TipoPedido").html("Herramientas");
                $("#btnAnularBoletaMaterial").css("display","none");
        $("#btnAnularBoletaHerramientas").css("display","inline-block");
    }

    $.ajax({
        url: "../BLL/Proyectos.php?opc=verpedido&NBoleta=" + NBoleta + "&TipoPedido=" + TipoPedido,
        success: function (respuesta) {
            $("#consecutivoPedidoSeleccionado").html(NBoleta);
            $("#ContenidoPedido_Selecionado").html(respuesta);
            $("#ModalVerPedido").modal("show");
        }
    });
}
function EliminarBoletaPedido() {
    var Tipo = $("#tipoPedidoSeleccionado").html();
    var NBoleta = $("#consecutivoPedidoSeleccionado").html();

    $.ajax({
        type: "POST",
        url: '../BLL/Proyectos.php?opc=eliminarpedido&Tipo=' + Tipo + "&NBoleta=" + NBoleta,
        success: function (respuesta) {
            alert(respuesta);
            $("#ContenidoPedido_Selecionado").html(respuesta)
        }
    });

}

function CambiarValorCheck() {

}
ExportarPdfBoletaMateriales();

/*Actualizar Secciones de la pagina*/
function ActulizarSeccionPedidos() {
    var ID_Proyecto = $("#idProecto").html();
    
    if ($("#cboPedidos").val() == 1) {
       $("#tipolista").html("Lista de  pedidos Materiales " + "  <img src='../resources/imagenes/cemento.png' alt='' width='20px'/>"); 
    }
    else{
       $("#tipolista").html("Lista de  pedidos Herramientas "+" <img src='../resources/imagenes/taladro.png' width='20px' alt=''/>");  
    }
    $.ajax({
        type: "POST",
        url: '../BLL/Proyectos.php?opc=listarPedidos&ID_Proyecto=' + ID_Proyecto + '&tipo=' + $("#cboPedidos").val(),
       beforeSend: function () {
               $("#contienePedidos").html("<center>Cargando... <img src='../resources/imagenes/loanding.gif'  width='100px'/></center>");
                },
        success: function (respuesta) {
            $("#contienePedidos").html(respuesta);
            /*  if($("#contienePedidos").height()>500){
             
             $("#contienePedidos").css("overflow","scroll"); 
             }*/
        }

    });
}
/*$(document).on('keyup', '#txtBuscarHerramienta', function () {
    var valor = $(this).val();
    //alert(valor);
      if(valor.length>0){      
       BuscaHerramientas(valor);
    }
    else{
      $("#ResultadoBusqudaHerramienta table").remove();  
    }
});*/



function Exportar_Excel(ID_Tabla) {
    alert("Entoro")
    $.ajax({
        type: "POST",
        url: "../BLL/ReportesExcel.php?opc=exportarmateriales&idProyecto="+$("#idProecto").html(),
        success: function (respuesta) {
           //alert(respuesta)
           // $(".materiales").html(respuesta);
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
   /* $("#" + ID_Tabla).table2excel({
        filename: "Reporte"
    });*/

}
function Descargar(id) {
    tableToExcel(id, 'datos');
}
var tableToExcel = (function () {
    var uri = 'data:application/vnd.ms-excel;base64,'
            , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
            , base64 = function (s) {
                return window.btoa(unescape(encodeURIComponent(s)))
            }
    , format = function (s, c) {
        return s.replace(/{(\w+)}/g, function (m, p) {
            return c[p];
        })
    }
    return function (table, name) {
        if (!table.nodeType)
            table = document.getElementById(table);
        var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML};
        window.location.href = uri + base64(format(template, ctx));
    }
})();

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
function FiltrarMateriales() {
    var ID_Proyecto = $("#idProecto").html();
    var Fitro = $("#cboFitrarMateriales").val();
    $.ajax({
        type: "POST",
        url: "../BLL/Proyectos.php?opc=Fitrar&tipo=material",
        data: {"ID_Proyecto": ID_Proyecto,
            "Filtro": Fitro},
        beforeSend: function(){
            $("#tablaMateriales").html("Procesando... <center> <img src='../resources/imagenes/loanding.gif' style='margin:auto'  width='20px'/></center>");    
        },
        success: function (respuesta) {
            $("#tablaMateriales").html(respuesta);
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

function FiltrarHerramientas() {
    var ID_Proyecto = $("#idProecto").html();
    var Fitro = $("#cboFiltrarHerramientas").val();
    
    $.ajax({
        type: "POST",
        url: "../BLL/Proyectos.php?opc=Fitrar&tipo=herramienta",
        data: {"ID_Proyecto": ID_Proyecto,
            "Filtro": Fitro},
        success: function (respuesta) {
            $("#tablaHerramientas").html(respuesta);
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

function AnularBoletaMaterial() {

    var NBoleta = $("#consecutivoPedidoSeleccionado").html();
    var opcion = confirm("¿Esta apunto de Anular una boleta si la Anula\n\
El material solicitado sera devuelto al inventario General?");
    if (opcion) {
        $.ajax({
            type: "POST",
            url: "../BLL/Proyectos.php?opc=anularBoleta&NBoleta=" + NBoleta,
            success: function (respuesta) {
                $("#mostrarMesajeHeaderModalPedido").addClass("mensajeCorrecto");
                $("#MensajeModalVerPedido").html("<strong>La Boleta se ha eliminado Correctamente</strong>")
                setTimeout(function () {
                    $("#mostrarMesajeHeaderModalPedido").removeClass("mensajeCorrecto");
                    $("#MensajeModalVerPedido").html("");
                    $("#ModalVerPedido").modal("hide");
                    ActulizarSeccionPedidos();
                    ActualizarMaterialesHerramientaProyecto($("#idProecto").html());
                    $("#mhProyectos").hide();
                }, 2000);
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

function AnularBoletaHerramientas(){
      var NBoleta = $("#consecutivoPedidoSeleccionado").html();
    var opcion = confirm("¿Esta apunto de Anular una boleta si la Anula\n\
El material solicitado sera devuelto al inventario General?");
    if (opcion) {
        $.ajax({
            type: "POST",
            url: "../BLL/Proyectos.php?opc=anularboletaherramienta&NBoleta=" + NBoleta,
            success: function (respuesta) {
                $("#mostrarMesajeHeaderModalPedido").addClass("mensajeCorrecto");
                $("#MensajeModalVerPedido").html("<strong>La Boleta se ha eliminado Correctamente</strong>")
                setTimeout(function () {
                    $("#mostrarMesajeHeaderModalPedido").removeClass("mensajeCorrecto");
                    $("#MensajeModalVerPedido").html("");
                    $("#ModalVerPedido").modal("hide");
                    ActulizarSeccionPedidos();
                    ActualizarMaterialesHerramientaProyecto($("#idProecto").html());
                    $("#mhProyectos").hide();
                }, 2000);
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
/*js para la parde solicitud de pedido*/

function procesarPeido(evento){
 
  var NBoleta = $(evento).parents("tr").find("td").eq(0).html();
  var  fecha =  $(evento).parents("tr").find("td").eq(1).html();
  var solicita = $(evento).parents("tr").find("td").eq(2).html();
      var array = fecha.split("-");
    $("#dia").html(array[2]);
    $("#mes").html(array[1]);
    $("#anno").html(array[0]);
    $("#solicita").html(solicita);
    $("#consecutivoPedidoProveeduria").html(NBoleta);
            $.ajax({
            type: "POST",
            url: "../BLL/Proyectos.php?opc=procesarpedido&boleta=" + NBoleta,
            success: function (respuesta) {
                $("#tableCuerpoPedidoProveeduria").html(respuesta);
                $("#pnlColaPedidos").hide();
                $("#pnlBoletasPedidos").show('slow');
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
function AtrasSolicitudPedido(){
     if($("#pnlColaPedidos").is(":visible")){
         window.location.href="Proyectos.php";
     }
     if ($("#pnlBoletasPedidos").is(":visible")) {
         location.reload();
          //window.location.href="SolicitudPedidos.php";
       // $("#pnlColaPedidos").show();
       // $("#pnlBoletasPedidos").hide();
    }
}
function mostrartblDevoluvion(){
    //alert("entro");
    var pendiente = $("#pendienteDevolver").html();
    //alert(pendiente)
    //if (pendiente>0) {
         $("#tblDevoluvion").toggle("show");
    //}
   // else{
       // alert("No puedes realizar mas devoluciones de este material, devido a que no hay pendientes");
   // }
   
}
function DevolucionMaterial(evento){
    
    var codigo = $(evento).parents("tr").find("td").eq(0).html();
    var nombre =  $(evento).parents("tr").find("td").eq(1).html();
     $("#txtIDMaterial").val(codigo);//$("#idProecto").html()
  $("#txtIDProyectotblDevolucion").val($("#idProecto").html());
  $("#txtNombreMaterialD").val(nombre);
    $("#idmaterial").html(codigo);
    ;
       $.ajax({
            url: "../BLL/Proyectos.php?opc=totalmaterialenviado&ID_Material="+codigo+"&idProyecto="+$("#idProecto").html(),
            success: function (respuesta) {
            $("#txttipomaterial").html(nombre+ "<br> <strong style='color:red'> Cantidad prestada:  "+respuesta+"</strong><span id = 'cantidadPrestadaMaterial' style='display:none'>"+respuesta+"</span>")
            }
        });
        ActualizarTablaDevolucion(codigo);
    

    
}

function ActualizarTablaDevolucion(codigo){
            $.ajax({
            type: "POST",
            url: "../BLL/Proyectos.php?opc=listarDevoluciones&ID_Material="+codigo+"&idProyecto="+$("#idProecto").html(),
            success: function (respuesta) {
             $("#mostarContenidoDevoluciones").html(respuesta);
             $("#ModalDevolucionMaterial").modal('show');  
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

function AgregarDevolucion(){
   var  codigo = $("#idmaterial").html();
   var cantidad = $("#txtCantidad").val();
   var fecha =$("#txtFecha").val();
   var boleta = $("#txtBoleta").val();

  //var CantidadPrestanaInici cantidadPrestadaMaterial
   var CantPrestada =  $("#cantidadPrestadaMaterial").html()*1;
 // alert("mult  x 1 "+CantPrestada +"  solo"+ $("#cantidadPrestada").html());
  // alert($("#cantidadPrestada").html());
      if ( $("#pendienteDevolver").html() == 0 ) {
               $("#headermodalDevoluciones").addClass("mensajeError");
                $("#textoheaderDevoluciones").html("<strong>Error este material ya no tiene pendientes por devolver</strong>");
                  setTimeout(function () {
                    $("#headermodalDevoluciones").removeClass("mensajeError");
                    $("#textoheaderDevoluciones").html("Historial de Devoluciones");
                    }, 3000); 
                    return;
    }
    var CantPendiente = $("#pendienteDevolver").html()*1;
    //alert("Cantidad Prestada "+$("#cantidadPrestada").html()+ "Cnatidad "+cantidad +"Cantidad Pendiente "+$("#pendienteDevolver").html())
    if (cantidad > CantPrestada  || cantidad > CantPendiente ) {
                $("#headermodalDevoluciones").addClass("mensajeError");
                $("#textoheaderDevoluciones").html("<strong>Error la cantidad es mayor a la cantidad pendiente</strong>");
                  setTimeout(function () {
                    $("#headermodalDevoluciones").removeClass("mensajeError");
                    $("#textoheaderDevoluciones").html("Historial de Devoluciones");
                    }, 3000);  
                    return;
        
    }
    $("#txtCantidad").val("");
    $("#txtFecha").val("");
    $("#txtBoleta").val("");

   var datos ={"Codigo" : codigo,
                "Cantidad" : cantidad,
                "Fecha" :fecha,
                "Boleta" :boleta,
                "idProyecto":$("#idProecto").html()};
           $.ajax({
            type: "POST",
            data:datos,
            url: "../BLL/Proyectos.php?opc=agregardevolucion",
            beforeSend:function(){
              $("#textoheaderDevoluciones").html("Procesando <img src='../resources/imagenes/loanding.gif' style='margin:auto'  width='20px'/>");  
            },
            success: function (respuesta) {            
                if (respuesta == 1) {
                $("#headermodalDevoluciones").addClass("mensajeCorrecto");
                $("#textoheaderDevoluciones").html("<strong>Datos agregados correctamente</strong>");
                 ActualizarTablaDevolucion(codigo);  
                }
                  setTimeout(function () {
                    $("#headermodalDevoluciones").removeClass("mensajeCorrecto");
                    $("#textoheaderDevoluciones").html("Historial de Devoluciones");
                    }, 3000);   
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
function BuscarBoletaPedido(){
    if ($("#txtBoletaPedido").val() != "") {
        if (isNaN($("#txtBoletaPedido").val())) {
            return;
        }
   $.ajax({
            url: "../BLL/Proyectos.php?opc=buscarboleta&numBoleta="+$("#txtBoletaPedido").val()+"&idProyecto="+$("#idProecto").html(),
            success: function (respuesta) {
                    $("#contienePedidos").html(respuesta);
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
         
}
function BuscarProyectoID(idProyecto){
    
     $.ajax({
            url: "../BLL/Proyectos.php?opc=BuscarProyectoid&ID="+idProyecto,
            success: function (respuesta) {
                
                    $("#frmRegistrarProyecto").html(respuesta);
                    $("#ModalAgregarProyecto").modal("show");
                   $("#btnEditarProyecto").show();
                   $("#btnInsertarProyecto").hide();
                  $("#btnEditarProyecto").val(idProyecto);


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


function FinalizarProyecto(){
     window.location.href="FinalizarProyecto.php?id="+$("#idProecto").html();   
}


/*Eventos con las teclas para maximizar los procesos mas rapidos*/
/*Evento 1 agreagar material al peidodo txtCantidadMaterial */

$(document).ready(function(){
   $('#btnAtras').click(function(){
        alert('Entro')
     if($("#pnlColaPedidos").is(":visible")){
         window.location.href="Proyectos.php";
     }
     if ($("#pnlBoletasPedidos").is(":visible")) {
        $("#pnlColaPedidos").show();
        $("#pnlBoletasPedidos").hide();
    } 
    })
    
    
      $("#txtCantidadMaterial").keypress(function(event) {
       if(event.keyCode == 13) {
        AgregarMaterialPedido();  
       }
    }); 
    $("#txtCodigoHerramienta").keypress(function(event) {
       if(event.keyCode == 13) {
        AgregarHerramientaPedido();      
       }
    });
        $("#txtEncargadoProyecto").keypress(function(event) {
       if(event.keyCode == 13) {
        InsertarProyecto();      
       }
    });
     $("#txtBoleta").keypress(function(event) {
       if(event.keyCode == 13) {
        AgregarDevolucion();      
       }
    });
       $("#txtBoletaPedido").keypress(function(event) {
       if(event.keyCode == 13) {
         BuscarBoletaPedido();  
       }
    }); 
     $("#txtcantiSolicitadaMaterial").keypress(function(event) {
       if(event.keyCode == 13) {
        alert("entro");
         AgregarMaterialBuscadoPNombre(this);    
       }
    });

    
    
    
    
})



/*Reportes pdf*/
//Pedido solicita proveeduria

function ExportarPdfBoletaProveduria(id){
    var imgData = "data:image/png;base64,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";
    var Npedido = $("#consecutivoPedidoProveeduria").html();
    var dia = $("#tblFechaPedidoProveedu tbody tr td").eq(0).html();
    var mes = $("#tblFechaPedidoProveedu tbody tr td").eq(1).html();
    var anno = $("#tblFechaPedidoProveedu tbody tr td").eq(2).html();
    var Proyecto = $("#NombreProyectoBoletaProve").html();
    var TipoBoleta = "Materiales Y Equipo";
    var generadaPor = $("#solicita").html();
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


    var htmlBody = $("#"+"tableCuerpoPedidoProveeduria").html();
   
    var margins = {top: 270, bottom: 20, left: 70, width: 100};


    pdf.fromHTML(htmlBody, margins.left, margins.top, {'width': margins.width}, function (dispose) {
        pdf.save("Pedido" + '.pdf');
    }, margins);
    //pdf.create()
}
function ExportarPdfBoletaMateriales(){
    var imgData = "data:image/png;base64,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";
    var Npedido = $("#consecutivoPedidoM").html();
    var dia = $("#tbl_fechaPedidoM tbody tr td").eq(0).html();
    var mes = $("#tbl_fechaPedidoM tbody tr td").eq(1).html();
    var anno = $("#tbl_fechaPedidoM tbody tr td").eq(2).html();
    var Proyecto = $("#nomProyectoPedidoMateriales").html();
    var TipoBoleta = "Materiales";
    var generadaPor = $("#generaBoletaSalidaMateriales").html();
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
    var htmlBody = $("#"+"tablaPedidoMateriales").html();   
    var margins = {top: 270, bottom: 20, left: 70, width: 100};
    pdf.fromHTML(htmlBody, margins.left, margins.top, {'width': margins.width}, function (dispose) {
        pdf.save("PedidoMateriales" + '.pdf');
    }, margins);
    //pdf.create()  
}
function ExportarPdfBoletaHerramientas(){
    //alert("entro")
    try {
    var imgData = "data:image/png;base64,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";
    var Npedido = $("#consecutivoPedidoH").html();
    var dia = $("#tblFechaBoletaH tbody tr td").eq(0).html();
    var mes = $("#tblFechaBoletaH tbody tr td").eq(1).html();
    var anno = $("#tblFechaBoletaH tbody tr td").eq(2).html();
    var Proyecto = $("#proyectoHerramientas").html();
    var TipoBoleta = "Herramientas";
    var generadaPor = $("#generaBoletaSalidaHerramientas").html();
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


    var htmlBody = $("#"+"tbl_contenidoBoletaHerramientas").html();
   
    var margins = {top: 270, bottom: 20, left: 70, width: 100};
     //  var htmlFecha= $("#"+"tablaPedidoMateriales").html();
     //var marginsFecha= {top: 120, bottom: 1, left: 280, width:1};
     // pdf.fromHTML(htmlFecha, 100, 120,{'width':0},200,10);
        

    pdf.fromHTML(htmlBody, margins.left, margins.top, {'width': margins.width}, function (dispose) {
        pdf.save("PedidoHerramientas" + '.pdf');
    }, margins);
    //pdf.create()    
    } catch (e) {
     alert("Error "+e.toString());   
    }

}

function MostarMoldalLoanding(){
    $("#ModalLoanding").modal("show");
}

function BuscarHerramientasPorCodigoP() {
    
    var idProyecto = $("#txtID_Proyecto").val();
    var idHerramienta = $("#txtCodigoHerraP").val();
    
    
    var datos = {
        "idProyecto" : idProyecto,
        "idHerramienta" : idHerramienta    
    };
    
    $.ajax({
        data: datos,
        type: "POST",
        url: "../BLL/Proyectos.php?opc=buscarHerramientaProyecto",
        success: function (respuesta) {
            $("#tablaHerramientas").html(respuesta);
            console.log(respuesta);
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

function FiltroInicioHerramienta() {
    var idProyecto = $("#txtID_Proyecto").val();
    
    var inicio = $("#txtCodigoHerraP").val();
    if (inicio == "") {
        listarSoloHerramienta(idProyecto);
    }

}

function listarSoloHerramienta(idProyecto){
    
    var idProyecto = $("#txtID_Proyecto").val();
    
     var datos = {
        "idProyecto" : idProyecto  
    };
    
    $.ajax({
        data: datos,
        type: "POST",
        url: "../BLL/Proyectos.php?opc=listarSoloHerramienta",
        success: function (respuesta) {
            $("#tablaHerramientas").html(respuesta);
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







/* $(document).on('keyup', '#txtCantidadMaterial', function () {
    $(this).keypress(function(e){
        if (e.keyCode==13) {
            AgregarMaterialPedido();;
        }
    });
});*/