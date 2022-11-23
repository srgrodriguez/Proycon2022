
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
    var descripcion = $("#txtBuscarHerramienta").val();


    $.ajax({
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=descripcionHerramienta&Descripcion=' + descripcion + '',
        beforeSend: function () {
            $("#tbl_body_buscarHerramienta").html("<img src='../resources/imagenes/loanding.gif'  width='100px'/>");
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