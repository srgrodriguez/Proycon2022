var stock;
function IngresoDeMateriales() {

    var valor = $('input:radio[name=Estado]:checked').val();
    if (valor == 1) {
        // alert("entro if");
        //$("#txtCodigo")
        $("#txtCodigo").removeAttr("disabled").focus();
        $("#btnBuscarMaterialCodigo").removeAttr("disabled");
        // $("#btnBuscarMaterialCodigo").css("border","none");
        $("#btnBuscarMaterialCodigo").css("border", "1px solid #cccccc");
        $("#txtCantidadStock").css("border", "1px solid red");
        $("#txtNombreMaterial").removeAttr("disabled");
        $("#txtCantidadIngresa").removeAttr("disabled");
        $("#cboMaterial").removeAttr("disabled");
        $("#txtCantidadStock").attr("disabled", true);

        $("#btnAdd").attr("disabled", true);
        $("#btnUpd").removeAttr("disabled");
    } else {
        /*$("#txtCantidadIngresa").attr("disabled",true);
         $("#txtCantidadStock").css("border","none");*/
        $("#txtCantidadStock").css("border", "1px solid red");
        $("#btnBuscarMaterialCodigo").attr("disabled", true);
        $("#btnBuscarMaterialCodigo").css("border", "1px solid red");
        $("#txtCodigo").removeAttr("disabled").focus();
        $("#txtNombreMaterial").removeAttr("disabled");
        $("#txtCantidadIngresa").removeAttr("disabled");
        $("#txtDisponibilidad").removeAttr("disabled");
        $("#btnAdd").removeAttr("disabled");
        $("#btnUpd").attr("disabled", true);
    }

}

function listarTotalMateriales() {
    $(".formHerramientas").hide();
    $(".historialreparaciones").hide();
    $("#materalesPorProyecto").hide();
    $("#infoMaterialProyecto").hide();
    $("#materalesPorProyecto").hide();
    /*
     $("#BusquedaHerramientas").show("slow");
     $("#BusquedaHerramientas").css("top","-50px");
     /*/

    //Tengo que poner el buscador en blanco para que no me genere problemas a la hora de Exportar a Excel
    $("#txtBuscar").val("");

    $.ajax({
        url: '../BLL/Materiales.php?opc=listar',
        beforeSend: function () {
            $('#listadoMateriales').html("<div style='margin:auto;width:200px'><img src='../resources/imagenes/loanding.gif'  width='100px'/></div>");
        },
        success: function (response) {
            if (response == -1) {
                alert("error");
            } else {
                $('#listadoMateriales').html(response);
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

/*Muestra el formulario para agregar un material es igual al de herramientas
 * se uso el mismo formulario*/
function MostrarFormAgregarHerramientas() {

    $("#ModalAgregarMaterial").modal('show');


}
function MostrarSeccionMaterialesProy() {
    $("#BusquedaHerramientas").hide();
    $(".formHerramientas").hide();
    $("#materalesPorProyecto").show();

}
function BuscarMateriallesProyecto() {
    $("#infoMaterialProyecto").show();
    var x = "Seleccione el Proyecto";
    $("#cboMaterialesProyecto").val(x);

}

/* Nuevas Funciones  */
function ajaxAgregarMateriales() {

    if ($('#txtCodigo').val().length < 10) {


        var status = 0;
        if ($("#chkDevolusion").is(":checked")) {
            status = 1;
        }

        var parametros = {
            "codigo": $('#txtCodigo').val(),
            "nombre": $('#txtNombreMaterial').val(),
            "cantidad": $('#txtCantidadIngresa').val(),
            "disponibilidad": 1,
            "devolucion": status
        };

        $.ajax({

            type: 'POST',
            url: '../BLL/Materiales.php?opc=agregar',
            data: parametros,

            success: function (response) {

                if (response == 1) {
                    $('#modalheaderAgregarMaterial').addClass("mensajeCorrecto");
                    $('#textoHeaderAgregarMaterial').html("<strong>Datos Agregados </strong>");
                    setTimeout(function () {
                        $('#modalheaderAgregarMaterial').removeClass("mensajeCorrecto");
                        $('#textoHeaderAgregarMaterial').html("Agregar Materiales ");

                    }, 3000);


                    //Limpiar txt
                    $('#txtIdHerramienta').val("");
                    $('#txtCodigo').val("");
                    $('#txtNombreMaterial').val("");
                    $('#txtCantidadStock').val("");
                    $('#txtCantidadIngresa').val("");
                    $('#txtDisponibilidad').val("");

                } else {
                    if (isNaN(response)) {
                        $('#infoResponse').html(response);
                    } else
                    {
                        $('#modalheaderAgregarMaterial').addClass("mensajeError");
                        $('#textoHeaderAgregarMaterial').html("<strong>El registro ya existe</strong>");
                        setTimeout(function () {
                            $('#modalheaderAgregarMaterial').removeClass("mensajeError");
                            $('#textoHeaderAgregarMaterial').html("Agregar Materiales ");

                        }, 3000);
                    }
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
        alert("El codigo no puede contener mas de 10 Caracteres");
    }
}

function VerificarHerramientaExistente() {

    var parametros = {
        "codigo": $('#txtCodigo').val()
    };

    $.ajax({

        type: 'POST',
        url: '../BLL/Materiales.php?opc=verificar',
        data: parametros,

        success: function (response) {

            if (response == 0) {
                alert('Codigo no existe');
            } else {
                var json = JSON.parse(response);
                $('#txtIdHerramienta').val(json['ID_Material']);
                $('#txtCantidadStock').val(json['Cantidad']);
                $('#txtCantidadStock2').val(json['Cantidad']);


                stock = json['Cantidad'];
                $('#txtDisponibilidad').val(json['Disponibilidad']);
                $('#txtNombreMaterial').val(json['Nombre']);

                // Validacion de radioButton
                var devolucion = json['Devolucion'];
                //  console.log("devol   "+ $devolucion)
                if (devolucion == 1) {

                    $("#chkDevolusion").attr('checked', true);
                    // console.log("ENTREEE");
                } else {

                    $("#chkDevolusion").attr('checked', false);
                }
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

function UpdateMateriales() {

    var status = 0;
    if ($("#chkDevolusion").is(':checked')) {
        status = 1;
    }
    var cantidad = 0;
    if ($('#txtCantidadIngresa').val() != "") {

        cantidad = $("#txtCantidadIngresa").val();
    } else {
        cantidad = 0;
    }

    var stock1;
    if ($("#txtCantidadStock2").is(":visible")) {
        stock1 = $("#txtCantidadStock2").val();
    } else {
        stock1 = $("#txtCantidadStock").val();
    }
    if (stock1 == stock) {
        stock1 = 0;
    }
    var parametros = {
        "idHerramienta": $('#txtIdHerramienta').val(),
        "codigo": $('#txtCodigo').val(),
        "nombre": $('#txtNombreMaterial').val() == "" ? 0 : $('#txtNombreMaterial').val(),
        "cantidad": cantidad,
        "disponibilidad": $('#txtDisponibilidad').val(),
        "devolucion": status,
        "stock": stock1
    };
    $.ajax({

        type: 'POST',
        url: '../BLL/Materiales.php?opc=update',
        data: parametros,

        success: function (response) {

            if (response == 1) {
                $('#modalheaderAgregarMaterial').addClass("mensajeCorrecto");
                $('#modalheaderAgregarMaterial').html("<strong>Datos Modificados </strong>");
                setTimeout(function () {
                    $('#modalheaderAgregarMaterial').removeClass("mensajeCorrecto");
                    $('#textoHeaderAgregarMaterial').html("Agregar Materiales ");

                }, 3000);
            } else {
                if (isNaN(response)) {
                        $('#infoResponse').html(response);
                    } 
                $('#modalheaderAgregarMaterial').addClass("mensajeError");
                $('#textoHeaderAgregarMaterial').html("<strong>Error al Actualizar</strong>");
                setTimeout(function () {
                    $('#modalheaderAgregarMaterial').removeClass("mensajeError");
                    $('#textoHeaderAgregarMaterial').html("Agregar Materiales ");
                }, 3000);
            }

            //Limpiar txt
            $('#txtIdHerramienta').val("");
            $('#txtCodigo').val("");
            $('#txtNombreMaterial').val("");
            $('#txtCantidadStock').val("");
            $('#txtCantidadIngresa').val("");
            $('#txtDisponibilidad').val("");
            $("#txtCantidadStock2").val("");
            $("#chkDevolusion").attr('checked', false);
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




$(document).on('keyup', '#txtBuscar', function () {
    var valor = $(this).val();

    if (valor != "") {
        $(BuscarTiempoRealHerramienta(valor));
    }

});


function  BuscarTiempoRealHerramienta(consulta) {
    $.ajax({
        type: "POST",
        url: "../BLL/Materiales.php?opc=buscarTiempoReal",
        data: {"consulta": consulta},
        beforeSend: function () {
            $("#ResultadoBusqudaHerramienta").html("<div style='margin:auto;width:200px'><img src='../resources/imagenes/loanding.gif'  width='100px'/></div>");
        },
        success: function (respuesta) {
            $("#listadoMateriales").html(respuesta);
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
function BuscarMaterialCodigo() {

    if ($("#txtCodigoMaterial").val() != "" && $("#txtCodigoMaterial").val().length < 11) {


        $.ajax({
            url: "../BLL/Materiales.php?opc=buscarcodigo&codigo=" + $("#txtCodigoMaterial").val(),
            beforeSend: function () {
                $("#listadoMateriales").html("<div style='margin:auto;width:100px'><img src='../resources/imagenes/loanding.gif'  width='60px'/></div>");
            },
            success: function (respuesta) {

                $("#listadoMateriales").html(respuesta);
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

$(document).ready(function () {
    $("#txtCodigoMaterial").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            BuscarMaterialCodigo();
            e.preventDefault();
        }
    });



});

