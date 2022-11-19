
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

    $(".nuevoPedido").show();
    $("#pnlnuevoPedido").show("slow");

}


function MostrarFormMaterial() {

    $("#panelContienetblDesecho").hide();

    $(".nuevoPedidoMaterial").show();
    $("#pnlnuevoPedidoMaterial").show("slow");

}

function Atras() {

    $("#panelContienetblDesecho").show("slow");

    $(".nuevoPedidoMaterial").hide();
    $("#pnlnuevoPedidoMaterial").hide();

    $(".pnlnuevoPedido").hide();
    $("#pnlnuevoPedido").hide();
}