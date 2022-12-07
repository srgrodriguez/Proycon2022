
function FiltrosHerramientas() {
    var Filtro = $("#cboFiltroHerramienta").val();
    switch (Filtro) {
        case "0":
            $.ajax({
                type: 'POST',
                url: '../BLL/Herramientas.php?opc=FiltrosHerramientas0',
                success: function (result) {
                    $('#listadoHerramientas').html(result);
                    $('#tbl_total_herramientas').css("display", "inline-table");
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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
            break;
        case "1":
            $.ajax({
                type: 'POST',
                url: '../BLL/Herramientas.php?opc=FiltrosHerramientas1',
                success: function (result) {
                    $('#listadoHerramientas').html(result);
                    $('#tbl_total_herramientas').css("display", "inline-table");
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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
            break;
        case "2":
            $.ajax({
                type: 'POST',
                url: '../BLL/Herramientas.php?opc=FiltrosHerramientas2',
                success: function (result) {
                    $('#listadoHerramientas').html(result);
                    $('#tbl_total_herramientas').css("display", "inline-table");
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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
            break;
        case "3":
            $.ajax({
                type: 'POST',
                url: '../BLL/Herramientas.php?opc=FiltrosHerramientas3',
                success: function (result) {
                    $('#listadoHerramientas').html(result);
                    $('#tbl_total_herramientas').css("display", "inline-table");
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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
            break;
        case "4":
            $.ajax({
                type: 'POST',
                url: '../BLL/Herramientas.php?opc=FiltrosHerramientas4',
                success: function (result) {
                    $('#listadoHerramientas').html(result);
                    $('#tbl_total_herramientas').css("display", "inline-table");
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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
            break;
        case "5":
            $.ajax({
                type: 'POST',
                url: '../BLL/Herramientas.php?opc=FiltrosHerramientas5',
                success: function (result) {
                    $('#tbl_total_herramientas').css("display", "none");
                    $('#tbl_total__tipo_herramientas').css("display", "inline-table");
                    $('#listadoTotalTipoHerramientas').html(result);
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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
            break;
    }
}
function AtrasH() {
    if ($("#BoletaReparacionHerramienta").is(":visible")) {
        $("#reparaciones").show();
        $("#BoletaReparacionHerramienta").hide();
        return;
    }
    if ($("#reparaciones").is(":visible")) {
        $("#MostrarBusquedaHerramienta").show();
        $("#reparaciones").hide();
        return;

    }
    if ($(".MostrarHistorialHerramienta").is(":visible")) {
        $("#reparaciones").show();
        $("#btnreparaciones").show();
        $(".MostrarHistorialHerramienta").hide();
        return;
    }
    if ($(".MostrarTransladoHerramienta").is(":visible")) {
        $("#MostrarBusquedaHerramienta").show();
        $(".MostrarTransladoHerramienta").hide();
        return;
    }

    //slistarTotalHerramientas();
}
function Atras() {
    if ($("#BoletaReparacionHerramienta").is(":visible")) {
        $("#reparaciones").show();
        $("#BoletaReparacionHerramienta").hide();
        return;
    }
    if ($("#reparaciones").is(":visible")) {
        $("#MostrarBusquedaHerramienta").show();
        $("#reparaciones").hide();
        return;

    }
    if ($(".MostrarHistorialHerramienta").is(":visible")) {
        $("#reparaciones").show();
        $("#btnreparaciones").show();
        $(".MostrarHistorialHerramienta").hide();
        return;
    }
    if ($(".MostrarTransladoHerramienta").is(":visible")) {
        $("#MostrarBusquedaHerramienta").show();
        $(".MostrarTransladoHerramienta").hide();
        return;
    }

    //slistarTotalHerramientas();
}
// FILTRO DE LA HERRAMIENTA EN REPARACION

function FiltroInicio2() {
    var inicio = $("#CodHerramientaReparacion").val();
    if (inicio == "") {
        MostrarListaReparaciones();
    }
}


// FILTRO DE LA HERRAMIENTA TRASLADO PARA LIMPIAR TODO

function FiltroInicio() {
    var inicio = $("#txtTrasladoCodigo").val();
    if (inicio == "") {

        $.ajax({
            type: 'POST',
            url: '../BLL/Herramientas.php?opc=listarTranslado',
            success: function (respuesta) {

                if (respuesta == -1) {
                    alert("error");
                } else {
                    $('#listadoTransladoHerramienta').html(respuesta);

                }
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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

// FILTRO DE LA HERRAMIENTA EN EL TRASLADO TIPO

function FiltroTipoHerramientasT() {
    var tipo = $("#cboTipoHerramientaT").val();
    if (tipo != "0") {

        $.ajax({
            data: { "tipo": tipo },
            type: 'POST',
            url: '../BLL/Herramientas.php?opc=FiltroTipoHerramientasT',
            success: function (result) {

                $('#listadoTransladoHerramienta').html(result);
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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

        $.ajax({
            type: 'POST',
            url: '../BLL/Herramientas.php?opc=listarTranslado',
            success: function (respuesta) {

                if (respuesta == -1) {
                    alert("error");
                } else {
                    $('#listadoTransladoHerramienta').html(respuesta);

                }
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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

// FILTRO DE LA HERRAMIENTA EN EL TRASLADO UBICACIÓN

function FiltrosHerramientasU() {
    var ubicacion = $("#cboFiltroHerramientaU").val();
    if (ubicacion != "0") {

        $.ajax({
            data: { "ubicacion": ubicacion },
            type: 'POST',
            url: '../BLL/Herramientas.php?opc=FiltrosHerramientasU',
            success: function (result) {

                $('#listadoTransladoHerramienta').html(result);
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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

        $.ajax({
            type: 'POST',
            url: '../BLL/Herramientas.php?opc=listarTranslado',
            success: function (respuesta) {

                if (respuesta == -1) {
                    alert("error");
                } else {
                    $('#listadoTransladoHerramienta').html(respuesta);

                }
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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
function FiltroReparacionTipoc() {
    $("#txtCodigoVista").val("");
    $("#CodHerramientaReparacion").val("");

}

function FiltroReparacionTipo() {

    var tipo = $("#cbofiltrotipo").val();

    if (tipo == '0') {
        $.ajax({
            type: 'POST',
            url: '../BLL/Herramientas.php?opc=totalReparaciones',
            success: function (respuesta) {

                if (respuesta == -1) {
                    alert("error");
                } else {
                    $('#HerramientasEnReparacion').html(respuesta);
                }
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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

        $.ajax({
            data: { "tipo": tipo },
            type: 'POST',
            url: '../BLL/Herramientas.php?opc=FiltroReparacionTipo',
            success: function (result) {
                $('#HerramientasEnReparacion').html(result);
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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

function soloLetras(evt) {

    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode :
        ((evt.which) ? evt.which : 0));
    if (charCode > 31 && (charCode < 64 || charCode > 90) && (charCode < 97 || charCode > 122)) {
        return false;
    }
    return true;

}



function soloNumeros(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8)
        return true;
    else if (tecla == 0 || tecla == 9)
        return true;
    patron = /[0-9\s]/;// -> solo numeros
    te = String.fromCharCode(tecla);
    return patron.test(te);
}



function LimpiarBoletaFactura() {

    $('#txtNunFactura').val("");
    $('#txtFechaFactura').val("");
    $('#txtDescripcionFactura').val("");
    $('#txtCantidadFactura').val("");
    $("#txtNunFactura").css('border', '1px solid Gainsboro');
    $("#txtFechaFactura").css('border', '1px solid Gainsboro');
    $("#txtDescripcionFactura").css('border', '1px solid Gainsboro');
    $("#txtCantidadFactura").css('border', '1px solid Gainsboro');
}

function FiltroReparacionboleta() {
    var boleta = $("#cbofiltroboleta").val();

    $.ajax({
        data: { "boleta": boleta },
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=FiltroReparacionboleta',
        success: function (result) {

            $('#HerramientasEnReparacion').html(result);
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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


function FiltroReparacionfecha() {
    var fecha = $("#cbofiltrofecha").val();

    $.ajax({
        data: { "fecha": fecha },
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=FiltroReparacionfecha',
        success: function (result) {

            $('#HerramientasEnReparacion').html(result);
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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


// MUESTRA LA LISTA TOTAL DE HERRAMIENTAS

function listarTotalHerramientas() {
    $("#txtCodigo").val("");
    $("#txtCodigoHerra").val("");
    $("#cboFiltroHerramienta").val(0);
    $(".MostrarHistorialHerramienta").hide();
    $("#BoletaReparacionHerramienta").hide();
    $(".MostrarTransladoHerramienta").hide();
    $(".formHerramientas").hide();
    $(".historialreparaciones").hide();
    $("#MostrarBusquedaHerramienta").show();
    if ($("#reparaciones").is(":visible")) {
        $("#reparaciones").hide();
    }

    $.ajax({
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=listar',
        success: function (respuesta) {

            if (respuesta == -1) {
                alert("error");
            } else {
                $('#listadoHerramientas').html(respuesta);
                $('#tbl_total_herramientas').css("display", "inline-table");

            }
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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

function LimpiarColorHerramienta() {
    $("#txtDescripcionH").css('border', '1px solid Gainsboro');
    $("#txtMarcaH").css('border', '1px solid Gainsboro');
    $("#txtProcedenciaH").css('border', '1px solid Gainsboro');
    $("#txtFechaRegistroH").css('border', '1px solid Gainsboro');
    $("#comboHerramientaTipoH").css('border', '1px solid Gainsboro');
    $("#txtCodigoH2").css('border', '1px solid Gainsboro');
    $("#txtPrecioH").css('border', '1px solid Gainsboro');

    $("#txtDescripcionH").val("");
    $("#txtMarcaH").val("");
    $("#txtProcedenciaH").val("");
    $("#txtFechaRegistroH").val("");
    $("#txtCodigoH2").val("");
    $("#comboHerramientaTipoH").val(0);
    $("#txtPrecioH").val("");


}

//Guardar Herramientas

function GuardarHerramienta() {
    var consecutivoHerramienta = $('#txtCodigoH2').val()
    var validaciones = 8;
    var validardescripcion = $('#txtDescripcionH').val()
    var validarmarca = $('#txtMarcaH').val()
    var validarprocedencia = $('#txtProcedenciaH').val()
    var validarfecha = $('#txtFechaRegistroH').val()
    var validartipo = $('#comboHerramientaTipoH').val()
    var validarprecio = $('#txtPrecioH').val()
    var validaMoneda = $('#cboMonedaAgregar').val()

    var datos = {
        "Codigo": $('#txtCodigoH2').val(),
        "Descripcion": $('#txtDescripcionH').val(),
        "Marca": $('#txtMarcaH').val(),
        "Procedencia": $('#txtProcedenciaH').val(),
        "Fecha": $('#txtFechaRegistroH').val(),
        "Tipo": $('#comboHerramientaTipoH').val(),
        "Precio": $('#txtPrecioH').val(),
        "NumFactura": $('#txtNumFacturaH').val(),
        "Moneda" : $('#cboMonedaAgregar').val()
    };
    // Valida el campo de la Descripcion

    if (consecutivoHerramienta == "") {
        $("#txtCodigoH2").css('border', '1px solid red');
        validaciones = validaciones + 1;
    } else {
        $("#txtCodigoH2").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;
    }


    if (validardescripcion == "") {
        $("#txtDescripcionH").css('border', '1px solid red');
        validaciones = validaciones + 1;
    } else {
        $("#txtDescripcionH").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;
    }

    if (validarprecio == "") {
        $("#txtPrecioH").css('border', '1px solid red');
        validaciones = validaciones + 1;
    } else {
        $("#txtPrecioH").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;
    }


    // Valida el campo Marca

    if (validarmarca == "") {
        $("#txtMarcaH").css('border', '1px solid red');
        validaciones = validaciones + 1;
    } else {
        $("#txtMarcaH").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;

    }

    // Valida el campo Procedencia

    if (validarprocedencia == "") {
        $("#txtProcedenciaH").css('border', '1px solid red');
        validaciones = validaciones + 1;
    } else {
        $("#txtProcedenciaH").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;
    }

    // Valida el campo Fecha

    if (validarfecha == "") {
        $("#txtFechaRegistroH").css('border', '1px solid red');
        validaciones = validaciones + 1;

    } else {
        $("#txtFechaRegistroH").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;
    }

    // Valida campo Tipo

    if (validartipo == 0) {
        $("#comboHerramientaTipoH").css('border', '1px solid red');
        validaciones = validaciones + 1;
    } else {
        $("#comboHerramientaTipoH").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;
    }

    // Valida campo Moneda
    if (validaMoneda == 0) {
        $("#cboMonedaAgregar").css('border', '1px solid red');
        validaciones = validaciones + 1;
    } else {
        $("#cboMonedaAgregar").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;
    }


    if (validaciones == 0) {

        $.ajax({
            data: datos,
            type: 'POST',
            url: '../BLL/Herramientas.php?opc=guardar',
            success: function (respuesta) {
                console.log(respuesta);
                if (respuesta != 0) {

                    $("#modalheaderAgregarHerramienta").addClass("mensajeCorrecto");
                    $("#tituloModalAgregarHerramienta").html("<strong>Se Guardo Correctamente la Herramienta </strong>");
                    limpiarFormHerramienta(consecutivoHerramienta);
                    listarTotalHerramientas();
                    setTimeout(function () {
                        $('#ModalAgregarHerramienta').modal('hide');
                    }, 3000);
                } else {
                    $("#modalheaderAgregarHerramienta").addClass("mensajeError");
                    $("#tituloModalAgregarHerramienta").html("<strong>El Codigo ya fue ingresado</strong>");
                }
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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
        $("#modalheaderAgregarHerramienta").addClass("mensajeError");
        $("#tituloModalAgregarHerramienta").html("<strong>Debes llenar todos los campos del Formulario</strong>");
    }

    setTimeout(function () {
        $("#modalheaderAgregarHerramienta").removeClass("mensajeCorrecto");
        $("#modalheaderAgregarHerramienta").removeClass("mensajeError");
        $("#tituloModalAgregarHerramienta").html("Registrar Herramientas");
    }, 3000);


}

// Editar el Tipo de las Herramientas

function LimpiarColorTipoHerramienta() {
    $("#txtnombreTipoHerramienta").val("");
    $("#txtnombreTipoHerramienta").css('border', '1px solid Gainsboro');
    var EditarconsecutivoTipo = $('#txtIDTipoHerramienta2').val()
    $('#txtIDTipoHerramienta').val(EditarconsecutivoTipo);

}

function CambiarTipoHerramienta() {
    var ID_Tipo = $("#txtIDTipoHerramienta").val();
    var DescripcionTipo = $("#txtnombreTipoHerramienta").val();
    var validaciones = 1;

    if (DescripcionTipo == "") {
        $("#txtnombreTipoHerramienta").css('border', '1px solid red');
        validaciones = validaciones + 1;
    } else {
        $("#txtnombreTipoHerramienta").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;
    }

    if (validaciones == 0) {

        $.ajax({
            data: { ID_Tipo: ID_Tipo, DescripcionTipo: DescripcionTipo },
            type: 'POST',
            url: '../BLL/Herramientas.php?opc=cambiarTipo',
            success: function (respuesta) {
                $("#modalTipo").addClass("mensajeCorrecto");
                $("#tituloModalAgregarTipo").html("<strong>Se Edito Correctamente el Tipo de Herramienta </strong>");
                EditarlimpiarFormTipo();
                listarTipoHerramientas();
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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
        $("#modalTipo").addClass("mensajeError");
        $("#tituloModalAgregarTipo").html("<strong>Debes llenar la Descripcion del Tipo Herramienta</strong>");
    }
    setTimeout(function () {
        $("#modalTipo").removeClass("mensajeCorrecto");
        $("#modalTipo").removeClass("mensajeError");
        $("#tituloModalAgregarTipo").html("Agregar Nuevo Tipo De Herramientas");

    }, 3000);
}

// GUARDAR TIPO HERRAMIENTA

function GuardarTipoHerramienta() {
    var consecutivoTipo = $('#txtIDTipoHerramienta').val()
    var validaciones = 1;
    var validardescripcion = $('#txtnombreTipoHerramienta').val()

    var datos = {
        "ID_Tipo": $('#txtIDTipoHerramienta').val(),
        "DescripcionTipo": $('#txtnombreTipoHerramienta').val()
    };


    if (validardescripcion == "") {
        $("#txtnombreTipoHerramienta").css('border', '1px solid red');
        validaciones = validaciones + 1;
    } else {
        $("#txtnombreTipoHerramienta").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;
    }

    if (validaciones == 0) {

        $.ajax({
            data: datos,
            type: 'POST',
            url: '../BLL/Herramientas.php?opc=guardarTipo',
            success: function (respuesta) {
                if (respuesta == 1) {
                    // 
                    $("#modalTipo").addClass("mensajeCorrecto");
                    $("#tituloModalAgregarTipo").html("<strong>Se Guardo Correctamente el Tipo de Herramienta </strong>");
                    limpiarFormTipo(consecutivoTipo);
                    listarTipoHerramientas();
                    setTimeout(function () {
                        $('#ModalAgregarTipoHerramienta').modal('hide');

                    }, 3000);
                }
                else {
                    $('#ModalAgregarTipoHerramienta').modal('hide');
                    $('#infoResponse').html(respuesta);
                }

            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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
        $("#modalTipo").addClass("mensajeError");
        $("#tituloModalAgregarTipo").html("<strong>Debes llenar la Descripcion del Tipo Herramienta</strong>");
    }
    setTimeout(function () {
        $("#modalTipo").removeClass("mensajeCorrecto");
        $("#modalTipo").removeClass("mensajeError");
        $("#tituloModalAgregarTipo").html("Agregar Nuevo Tipo De Herramientas");
    }, 3000);

}


function listarTipoHerramientas() {
    $("#btnEditarTipo").hide();
    $("#btnGuardarTipo").show();
    $.ajax({
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=listarTipo',
        success: function (respuesta) {

            if (respuesta == -1) {
                alert("error")
            } else {

                $('#listadoTipoHerramientas').html(respuesta);
            }
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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

function limpiarFormTipo(consecutivoTipo) {
    var num2 = 1;
    var suma = parseInt(consecutivoTipo) + parseInt(num2);
    $('#txtnombreTipoHerramienta').val(""),
        $('#txtIDTipoHerramienta').val(suma);
    $('#txtIDTipoHerramienta2').val(suma);
}
function EditarlimpiarFormTipo() {
    var EditarconsecutivoTipo = $('#txtIDTipoHerramienta2').val()
    $('#txtnombreTipoHerramienta').val(""),
        $('#txtIDTipoHerramienta').val(EditarconsecutivoTipo);
}

function limpiarFormFactura() {
    $('#txtNunFactura').val(""),
        $('#txtFechaFactura').val(""),
        $('#txtDescripcionFactura').val(""),
        $('#txtCantidadFactura').val("")
}

function limpiarFormHerramienta(consecutivoHerramienta) {
    var codigoHerramienta = consecutivoHerramienta
    $('#txtCodigoH').val(codigoHerramienta),
        $('#txtCodigoH2').val(""),
        $('#txtDescripcionH').val(""),
        $('#txtPrecioH').val(""),
        $('#txtMarcaH').val(""),
        $('#txtProcedenciaH').val(""),
        $('#txtFechaRegistroH').val(""),
        $('#comboHerramientaTipoH').val("0")
}

function MostrarFormReparaciones() {
    $("#mostrarTablaReparaciones").hide();
    $("#MostrarHistorialHerramienta").hide();
    $("#btnreparaciones").hide();
    $("#btnRegresar").show("slow");
    $(".nuevoPedido").show("slow");
    $("#EnviarHerramienta").show();

}

// MUESTRA LA LISTA DE HERRAMIENTAS EN TRASLADO

function MostrarTransladoHerramienta() {
    $("#txtCodigo").val("");
    $("#txtTrasladoCodigo").val("");
    $("#cboFiltroHerramientaU").val(0);
    $("#cboTipoHerramientaT").val(0);
    $(".MostrarHistorialHerramienta").hide();
    $("#BoletaReparacionHerramienta").hide();
    $(".formHerramientas").hide();
    $(".historialreparaciones").hide();
    $("#MostrarBusquedaHerramienta").hide();
    $(".MostrarTransladoHerramienta").show("slow");
    $(".MostrarTransladoHerramienta").css("top", "-50px");
    if ($("#reparaciones").is(":visible")) {
        $("#reparaciones").hide();
    }

    $.ajax({
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=listarTranslado',
        success: function (respuesta) {

            if (respuesta == -1) {
                alert("error");
            } else {
                $('#listadoTransladoHerramienta').html(respuesta);

            }
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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

function LimpiarBusquedaHerramienta() {

    $("#txtCodigoHerramientaBuscar").val("");

    var numFilas = $("#tbl_R_Herramientas tbody tr").length;
    for (i = 0; i < numFilas; i++) {
        $("#tbl_R_Herramientas tbody tr").remove();

    }

}



/////// CARGA EL TOTAL DE REPARACIONES Y HISTORIAL DE TRASLADO DE LAS HERRAMIENTAS

function MostrarHistorial() {
    var codigo = $("#txtCodigoVista").val();
    $("#codigo").val(codigo);
    $("#reparaciones").hide();
    $("#btnreparaciones").hide();
    $(".MostrarHistorialHerramienta").show("slow");
    $("#EnviarHerramienta").hide();
    $(".MostrarTransladoHerramienta").hide();
    $("#BoletaReparacionHerramienta").hide();
    $("#mostrarTablaReparaciones").hide();
    $("#reparaciones").hide();
    if ($("#MostrarBusquedaHerramienta").is(":visible")) {
        $("#MostrarBusquedaHerramienta").hide();
    }

    // ME LLENA LOS CAMPOS DEL TOTAL DE REPARACIONES DE LA HERRAMIENTA
    $.ajax({
        type: "POST",
        url: "../BLL/Herramientas.php?opc=reparacionesTotales&codigo=" + codigo,
        success: function (respuesta) {
            if (respuesta != "") {
                console.log(respuesta);
                $('#tablareparacionestotales').html(respuesta);
            }
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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

    // ME LLENA LOS CAMPOS DEL TOTAL DE TRASLADOS DE LA Herramienta
    $.ajax({
        type: "POST",
        url: "../BLL/Herramientas.php?opc=trasladosTotales&codigo=" + codigo,
        success: function (respuesta) {
            if (respuesta != "") {

                $('#tablatrasladostotales').html(respuesta);
            }
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transacción intente de nuevo " + errorMessage);
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

    // ME LLENA LOS CAMPOS DEL HISTORIAL DE LA Herramienta

    $.ajax({
        type: "POST",
        url: "../BLL/Herramientas.php?opc=InfoHerramienta&codigo=" + codigo,
        success: function (respuesta) {
            console.log("header = " + respuesta);
            var informacion = respuesta;
            var Cadena = informacion.split(";");
            var Codigo = Cadena[0];
            var Marca = Cadena[3];

            var Descripcion = Cadena[1];
            var Fecha = Cadena[4];
            var Procedencia = Cadena[2];

            var precio = Cadena[5];
            var numFactura = Cadena[6];

            $("#NombreHerramienta").html(Codigo);
            $("#FechaAdquisicion").html(Marca);
            $("#HerramientaMarca").html(Descripcion);
            $("#ProcedenciaHerramienta").html(Fecha);
            $("#DescripcionHerramienta").html(Procedencia);

            $("#numFactHerramienta").html(numFactura);
            $("#precioHerramienta").html(precio);

        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transacción intente de nuevo " + errorMessage);
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

    var eliboleta = $("#consecutivoBoletaReparacionSeleccionado").html();
    var r = confirm("¿Esta seguro que desea anular la boleta " + eliboleta + "?");
    if (r) {
        $.ajax({
            type: "POST",
            url: "../BLL/Herramientas.php?opc=eliminarBoletaR&eliboleta=" + eliboleta,
            success: function (respuesta) {
                $("#voletaVista").addClass("mensajeCorrecto");
                $("#tituloBoletaV").html("<strong>Boleta Eliminada Correctamente</strong>")
                MostraBoletasReparaciones();
                setTimeout(function () {
                    $('#ModalVerBoletaReparacion').modal('hide');
                }, 3000);
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert("En este momento no podemos procesar la transaccion intente de nuevo");
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


    setTimeout(function () {
        $("#voletaVista").removeClass("mensajeCorrecto");
        $("#tituloBoletaV").html("Boleta de Reparación de la Herramienta");
    }, 3000);

}

function LimpiarRegistroReparaciones() {
    $("#CodHerramientaReparacion").val("");
    $("#cbofiltrotipo").val(0);
    $("#txtCodigo").val("");

}


function MostrarListaReparaciones() {

    $("#txtCodigo").val("");
    $("#cbofiltrotipo").val("0");
    $("#CodHerramientaReparacion").val("");
    $("#txtCodigoVista").val("");



    $(".MostrarHistorialHerramienta").hide();
    $("#EnviarHerramienta").hide();
    $(".MostrarTransladoHerramienta").hide();
    $("#reparaciones").show("slow");
    $("#btnreparaciones").show("slow");
    $("#BoletaReparacionHerramienta").hide();
    $("#mostrarTablaReparaciones").show("slow");


    if ($("#MostrarBusquedaHerramienta").is(":visible")) {
        $("#MostrarBusquedaHerramienta").hide();
    }

    $.ajax({
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=totalReparaciones',
        success: function (respuesta) {

            if (respuesta == -1) {
                alert("error");
            } else {
                $('#HerramientasEnReparacion').html(respuesta);
            }
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo");
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


function listaEnviadas() {


    var datos = {
        "codigo": $("#txtCodigoHerramientaBuscar").val()
    };

    $.ajax({
        data: datos,
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=listaEnviadas',
        success: function (resultado) {
            if (resultado == null) {
                alert("El valor ya se encuenta en base");
                alert(resultado);
            } else {
                alert("El valor se puede usar");
                alert(resultado);
            }
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo");
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



function BuscarHerramientaNombre() {
    var descripcion = $("#txtCodigoHerramientaBuscar").val();


    $.ajax({
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=descripcion&Descripcion=' + descripcion + '',
        success: function (respuesta) {
            if (respuesta == "") {

                $("#modalBoletaReparacionHerramienta").addClass("mensajeError");
                $("#mensajeBoletaReparacion").html("<strong>Digite una Herramienta Valida</strong>");

            } else {
                $("#ContenidoReparaciones").append(respuesta);
                $("#txtCodigoHerramientaBuscar").val("");
            }

        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo");
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

// JALA VALORES DE LA HERRAMIENTA Y LOS MUESTRA EN LA FACTURA DE REPARACION

function FacturaReparacion(evento) {
    var id_reparacion = $(evento).parents("tr").find("td").eq(0).html();
    var nombre = $(evento).parents("tr").find("td").eq(2).html();
    var Codigo = $(evento).parents("tr").find("td").eq(1).html();
    var numBoleta = $(evento).parents("tr").find("td").eq(6).html();
    $("#NomHerramienta").html(nombre);
    $("#NumReparacion").html(id_reparacion);
    $("#CodHerramienta").html(Codigo);
    $("#NumBoletaF").html(numBoleta);
    $("#ModalRegistrarGastos").modal("show");

}
function EliminarLista(evento) {
    $(evento).parents("tr").remove();
}

// JALA LOS VALORES DE LA HERRAMIENTA Y MUESTRA VENTANA DE TRASLADO DE Herramientas

function TransladoHerramienta(evento) {
    var CodigoT = $(evento).parents("tr").find("td").eq(0).html();
    $.ajax({
        data: { "CodigoT": CodigoT },
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=GuardarTrasladoT',
        success: function (resultado) {
            if (resultado == 1) {
                $(evento).parents("tr").find("td").addClass('trasladoT');
            } else {
                $(evento).parents("tr").find("td").removeClass('trasladoT');
            }
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo");
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

function ListarTrasladoMo() {

    $("#DestinoTrasl").html("");
    $("#cboFiltroTipoHerramientaT").val(0);
    $("#txtCodigo").val("");
    $("#txtTrasladoCodigo").val("");
    $("#cboFiltroHerramientaU").val(0);
    $("#cboTipoHerramientaT").val(0);

    $("#cboFiltroTipoHerramientaT").css('border', '1px solid Gainsboro');
    var Dia = $('#idDia').html();
    var dt = new Date();
    var mes = dt.getMonth() + 1;
    var FechaFinal = dt.getFullYear() + '-' + mes + '-' + dt.getDate();
    $("#FechaFinal").html(FechaFinal);

    $.ajax({
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=ListarTrasladoMo',
        success: function (respuesta) {

            if (respuesta == -1) {
                alert("error")
            } else {
                $("#ModalTranslado").modal("show");
                $('#tablaMostrarTraslado').html(respuesta);
            }
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo");
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

function EliminarTraslado(evento) {


    var CodigoTH = $(evento).parents("tr").find("td").eq(0).html();
    $.ajax({
        data: { "CodigoTH": CodigoTH },
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=EliminarTraslado',
        success: function (resultado) {
            if (resultado = '1') {
                $(evento).parents("tr").remove();
            } else {


            }
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo");
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
// GUARDA LA FACTURA DE LA REPARACION DE LA HERRAMIENTA Y VALIDA SUS CAMPOS

function ElaborarFactura() {

    var validaciones = 4;
    var validarFactura = $('#txtNunFactura').val();
    var validarFecha = $('#txtFechaFactura').val();
    var validarDescripcion = $('#txtDescripcionFactura').val();
    var validarCosto = $('#txtCantidadFactura').val();

    // Valida el campo Factura

    if (validarFactura == "") {
        $("#txtNunFactura").css('border', '1px solid red');
        validaciones = validaciones + 1;
    } else {
        $("#txtNunFactura").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;

    }

    // Valida el campo Fecha

    if (validarFecha == "") {
        $("#txtFechaFactura").css('border', '1px solid red');
        validaciones = validaciones + 1;
    } else {
        $("#txtFechaFactura").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;
    }

    // Valida el campo Descripcion

    if (validarDescripcion == "") {
        $("#txtDescripcionFactura").css('border', '1px solid red');
        validaciones = validaciones + 1;

    } else {
        $("#txtDescripcionFactura").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;
    }

    // Valida campo Costo

    if (validarCosto == "") {
        $("#txtCantidadFactura").css('border', '1px solid red');
        validaciones = validaciones + 1;
    } else {
        $("#txtCantidadFactura").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;


    }

    if (validaciones == 0) {

        var datos = {
            "Codigo": $("#CodHerramienta").html(),
            "ID_Reparacion": $('#NumReparacion').html(),
            "NumFactura": $('#txtNunFactura').val(),
            "FechaEntrada": $('#txtFechaFactura').val(),
            "DescripcionFactura": $('#txtDescripcionFactura').val(),
            "NumBoleta": $('#NumBoletaF').html(),
            "CostoFactura": $('#txtCantidadFactura').val()
        };

        $.ajax({
            data: datos,
            type: 'POST',
            url: '../BLL/Herramientas.php?opc=guardarFactura',
            success: function (resultado) {

                $("#headermodalRegistroGastos").addClass("mensajeCorrecto");
                $("#tituloRegistrarGasto").html("<strong>Factura registrada correctamente</strong>")
                limpiarFormFactura();
                MostrarListaReparaciones();
                setTimeout(function () {
                    $('#ModalRegistrarGastos').modal('hide');
                }, 3000);

            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert("En este momento no podemos procesar la transaccion intente de nuevo");
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
        $("#headermodalRegistroGastos").addClass("mensajeError");
        $("#tituloRegistrarGasto").html("<strong>Debes llenar todos los campos del Formulario</strong>");
    }

    setTimeout(function () {
        $("#headermodalRegistroGastos").removeClass("mensajeCorrecto");
        $("#headermodalRegistroGastos").removeClass("mensajeError");
        $("#tituloRegistrarGasto").html("Factura de la Reparación");
    }, 3000);

}

function guardarNombreDestino() {
    var nuevoD = $('#cboFiltroTipoHerramientaT option:selected').html();
    $("#DestinoTrasl").html(nuevoD);
}

// SE ENCARGAR DE VALIDAR CAMPOS Y ENVIAR LOS DATOS DEL TRASLADO 

function ElaborarTranslado() {
    var cambio = $('#ConsecutivoPedidoHerramientaF').html();
    var validaciones = 1;
    var validarDestino = $('#cboFiltroTipoHerramientaT').val();

    // Valida el campo Destino

    if (validarDestino == "0") {
        $("#cboFiltroTipoHerramientaT").css('border', '1px solid red');
        validaciones = validaciones + 1;
    } else {
        $("#cboFiltroTipoHerramientaT").css('border', '1px solid Gainsboro');
        validaciones = validaciones - 1;

    }
    if (validaciones == 0) {
        var Destino = $('#cboFiltroTipoHerramientaT').val();
        var NumBoleta = $('#ConsecutivoPedidoHerramientaF').html();
        var FechaFinal = $('#FechaFinal').html();

        var datos = {
            "Destino": Destino,
            "NumBoleta": NumBoleta,
            "FechaFinal": FechaFinal
        };
        $.ajax({
            data: datos,
            type: "POST",
            url: "../BLL/Herramientas.php?opc=guardarTranslado",
            success: function (respuesta) {
                if (respuesta != cambio) {
                    $("#ConsecutivoPedidoHerramientaF").html(respuesta);
                    $("#modalTransladoBoleta").addClass("mensajeCorrecto");
                    $("#mensajeTranslado").html("<strong>Traslado Guardado Correctamente</strong>");
                    $.ajax({
                        type: 'POST',
                        url: '../BLL/Herramientas.php?opc=listarTranslado',
                        success: function (respuesta) {

                            if (respuesta == -1) {
                                alert("error");
                            } else {
                                $('#listadoTransladoHerramienta').html(respuesta);
                                $('#cboFiltroTipoHerramientaT').val(0);
                                setTimeout(function () {
                                    $('#ModalTranslado').modal('hide');
                                }, 3000);
                            }
                        }
                    })

                } else {
                    $("#modalTransladoBoleta").addClass("mensajeError");
                    $("#mensajeTranslado").html("<strong>Debes Seleccionar las Herramientas a Trasladar</strong>");

                }
            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert("En este momento no podemos procesar la transaccion intente de nuevo");
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
        $("#modalTransladoBoleta").addClass("mensajeError");
        $("#mensajeTranslado").html("<strong>Debes Seleccionar un Destino</strong>");
    }

    setTimeout(function () {
        $("#modalTransladoBoleta").removeClass("mensajeCorrecto");
        $("#modalTransladoBoleta").removeClass("mensajeError");
        $("#mensajeTranslado").html("Traslado de Herramienta");

    }, 3000);

}

function EditarTipoHerramienta(evento) {

    var id = $(evento).parents("tr").find("td").eq(0).html();
    var tipo = $(evento).parents("tr").find("td").eq(1).html();
    $('#txtIDTipoHerramienta').val(id),
        $('#txtnombreTipoHerramienta').val(tipo);
    $("#btnEditarTipo").show();
    $("#btnGuardarTipo").hide();
}

function AgregarHerramientaBuscadoPNombre(evento) {


    var cod = $(evento).parents("tr").find("td").eq(0).html();
    var descripcion = $(evento).parents("tr").find("td").eq(1).html();
    var marca = $(evento).parents("tr").find("td").eq(2).html();
    var estado = $(evento).parents("tr").find("td").eq(3).html();
    var ubicacion = $(evento).parents("tr").find("td").eq(4).html();

    var nuevaFila = "<tr>" +
        "<td>" + cod + "</td>" +
        "<td>" + descripcion + "</td>" +
        "<td>" + marca + "</td>" +
        "<td>" + estado + "</td>" +
        "<td>" + ubicacion + "</td>" +
        "<td>" +
        "<button title='Quitar Fila' class='btnRemoverFila' type='button'  onclick='Remover(this)'>" +
        "<img title='Eliminar Fila' src='../resources/imagenes/remove.png' alt='' width='20px'/>" +
        "</button>" +
        "</td>" +
        "</tr>";
    $("#ContenidoReparaciones").append(nuevaFila);

}



function Remover(evento) {
    $(evento).parents("tr").remove();
}


//GUARDARRRRRRRRRRRRRR BOLESTAS


function GuardarBoletaReparaciones() {

    var dt = new Date();
    var mes = dt.getMonth() + 1;
    var fecha = dt.getFullYear() + '-' + mes + '-' + dt.getDate();
    var numFilas = $("#tbl_R_Herramientas tbody tr").length;
    var consecutivo = $("#ConsecutivoPedidoHerramienta").html();
    var totalHerramientas = new Array(numFilas);
    var cont = 1;
    var i;

    if (numFilas > 0 && $('#provedorReparacion').val() != "") {
        for (i = 0; i < numFilas; i++) {

            totalHerramientas[i] = document.getElementById("tbl_R_Herramientas").rows[cont].cells[0].innerHTML
            cont++;
        }
        var datos = {
            "consecutivo": consecutivo,
            "fecha": fecha,
            "arreglo": JSON.stringify(totalHerramientas),
            "proveedorReparacion": $('#provedorReparacion').val()

        };

        AjaxRegistroBolestasReparaciones(datos);
    }


}

function AjaxRegistroBolestasReparaciones(datos) {

    $.ajax({
        data: datos,
        type: "POST",
        url: "../BLL/Herramientas.php?opc=registrarReparacion",
        success: function (respuesta) {
            if (respuesta != 0) {

                $("#ConsecutivoPedidoHerramienta").html(" " + respuesta);
                $("#modalBoletaReparacionHerramienta").addClass("mensajeCorrecto");
                $("#mensajeBoletaReparacion").html("<strong>La Boleta se ha generado correctamente</strong>")
                $("#tbl_R_Herramientas tbody tr").remove();
                $("#tablaherramientas tbody tr").remove();
                setTimeout(function () {
                    $('#ModalEnviarReparacion').modal('hide');
                }, 2000);
            }



        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo");
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
        $("#modalBoletaReparacionHerramienta").removeClass("mensajeCorrecto");
        $("#mensajeBoletaReparacion").html("<strong>Salida de Herramienta ha Reparación</strong>")
        MostrarListaReparaciones();

    }, 2000);
}

//      MOSTRA LA LISTA DE BOLETAS DISPONIBLES

function MostraBoletasReparaciones() {
    $.ajax({
        url: "../BLL/Herramientas.php?opc=listarBoletasReparacion",
        success: function (respuesta) {
            $("#contenidoBoletasReparacion").html(respuesta);
            $("#BoletaReparacionHerramienta").show();
            $(".MostrarTransladoHerramienta").hide();
            $(".formHerramientas").hide();
            $(".historialreparaciones").hide();

            if ($("#reparaciones").is(":visible")) {
                $("#reparaciones").hide();
            }

        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo");
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




function VerBoletaReparacion(evento) {

    var fecha = $(evento).parents("tr").find("td").eq(1).html();
    var Usuario = $(evento).parents("tr").find("td").eq(2).html();
    $("#consecutivoBoletaReparacionSeleccionado").html($(evento).parents("tr").find("td").eq(0).html());
    var arregloFecha = fecha.split("-");
    $("#dia").html(arregloFecha[2]);
    $("#mes").html(arregloFecha[1]);
    $("#anno").html(arregloFecha[0]);
    $("#generadaPorBoletaReparacion").html(Usuario);
    $("#ModalVerBoletaReparacion").modal("show");
    $.ajax({
        url: "../BLL/Herramientas.php?opc=VerBoletaReparacion&NumBoleta=" + $(evento).parents("tr").find("td").eq(0).html(),
        success: function (respuesta) {
            $("#contenidoBoletaReparacion").html(respuesta);
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo " + errorMessage);
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

function AgregarGastos() {
    $(".agregarGasto").toggle("slow");
}
function ActualizarTipoHerramienta(event) {
    if (event != null) {
        $("#btnActualizarTipoH").show();
        $("#btnAddTipo").hide();
        var id = $(event).parents("tr").find("td").eq(0).html();
        var tipo = $(event).parents("tr").find("td").eq(1).html();
        $("#txtnombreTipoHerramienta").val(tipo);
    } else {
        $("#btnActualizarTipoH").hide();
        $("#btnAddTipo").show();
    }
}
function RegistrarGasto(evento) {
    if (evento != null) {
        $("#ModalRegistrarGastos").modal();
        var codigo = $(evento).parents("tr").find("td").eq(0).html();
        var tipo = $(evento).parents("tr").find("td").eq(1).html();
        var fecha = $(evento).parents("tr").find("td").eq(2).html();
        $("#Tipoherramienta").html(tipo + ": " + codigo);
        $("#fechaSalida").html(fecha);

    }

}
function MostrarBolestasReparacion() {
    $("#tablaReparaciones").hide();
    $("#BoletasReparacion").show();
}

function Regrasar() {
    $("#reparaciones").show()
    var boletas = $("#BoletasReparacion").is(":visible");
    var tablaReparacion = $("#tablaReparaciones").is(":visible");
    var HistorialHerramiennas = $("#MostrarHistorialHerramienta").is(":visible")
    var buscador = $("#buscarHerrmientas").is(":visible");
    if (boletas) {
        $("#tablaReparaciones").show();

        $("#BoletasReparacion").hide();
        $("#MostrarHistorialHerramienta").hide();


    } else if (HistorialHerramiennas) {
        $("#tablaReparaciones").show("slow");

        $("#BoletasReparacion").hide();
        $("#MostrarHistorialHerramienta").hide();
        // $("#buscarHerrmientas").hide();  
    } else if (tablaReparacion) {
        //$("#buscarHerrmientas").show();   
        $("#tablaReparaciones").hide();
        $("#BoletasReparacion").hide();
        $("#MostrarHistorialHerramienta").hide();
        //$("#buscarHerrmientas").hide();    
    }


}

function Regrasar2() {
    MostrarListaReparaciones();

}

function LimpiarTodo() {

    $("#txtCodigoHerra").val("");
    $("#cboFiltroHerramienta").val(0);
    $("#cbofiltrotipo").val("0");
    $("#CodHerramientaReparacion").val("");
    $("#txtCodigoVista").val("");
    $("#txtTrasladoCodigo").val("");
    $("#cboFiltroHerramientaU").val(0);
    $("#cboTipoHerramientaT").val(0);


}

// busca la herramienta en reparacion apartir del CODIGO DADO

function BuscarHerramientaTablaReparaciones() {

    var codigo = $("#CodHerramientaReparacion").val();
    $.ajax({
        data: { "codigo": codigo },
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=FiltroReparacionCodigo',
        success: function (result) {
            $('#HerramientasEnReparacion').html(result);

        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo");
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

function Exportar_Excel(ID_Tabla) {

    $("#" + ID_Tabla).table2excel({
        filename: "Reporte"
    });

}

function BuscarTHerramienta() {
    var codigo = $("#txtTrasladoCodigo").val();
    if (codigo != "") {
        $.ajax({
            type: "POST",
            url: "../BLL/Herramientas.php?opc=buscarTraslado&codigo=" + codigo,
            success: function (respuesta) {
                if (respuesta != "") {
                    $("#listadoTransladoHerramienta").html(respuesta);
                } else {
                    alert("La herramienta que busca no esta disponible, posiblemente se encuentre en reparación\n\
                Y una herramienta en reparación no se pude trasladar");
                }


            },
            error: function (jqXhr, textStatus, errorMessage) {
                alert("En este momento no podemos procesar la transaccion intente de nuevo");
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
function LimpiarBusquedaTipo() {
    $("#cbofiltrotipo").val("0");
    $("#txtCodigoVista").val("");

}
function LimpiarComboTraslado1() {
    $("#cboTipoHerramientaT").val("0");
    $("#txtTrasladoCodigo").val("");
}
function LimpiarComboTraslado2() {
    $("#cboFiltroHerramientaU").val("0");
    $("#txtTrasladoCodigo").val("");

}
function LimpiarComboTraslado3() {
    $("#cboFiltroHerramientaU").val("0");
    $("#cboTipoHerramientaT").val("0");

}
function LimpiarListadoCombo() {
    $("#cboFiltroHerramienta").val("0");
    $("#txtCodigo").val("");

}
function LimpiarCampoCodigo() {
    $("#txtCodigoHerra").val("");
    $("#txtCodigo").val("");
}
function FiltroInicioL() {

    var inicio = $("#txtCodigoHerra").val();
    if (inicio == "") {
        listarTotalHerramientas();
    }

}

function BuscarHerramientasPorCodigo() {
    var codigo = $("#txtCodigoHerra").val();
    $.ajax({
        type: "POST",
        url: "../BLL/Herramientas.php?opc=buscarherramienCodigo&codigo=" + codigo,
        success: function (respuesta) {
            $("#listadoHerramientas").html(respuesta);
            $('#tbl_total_herramientas').css("display", "inline-table");
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo");
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

//$(BuscarTiempoRealHerramienta());
function BuscarTiempoRealHerramienta(consulta) {


    $(".MostrarHistorialHerramienta").hide();
    $("#EnviarHerramienta").hide();
    $(".MostrarTransladoHerramienta").hide();
    $("#reparaciones").hide();
    $("#btnreparaciones").hide();
    $("#BoletaReparacionHerramienta").hide();
    $("#mostrarTablaReparaciones").hide();
    $.ajax({
        type: "POST",
        url: "../BLL/Herramientas.php?opc=buscarTiempoReal",
        data: { "consulta": consulta },
        beforeSend: function () {
            $("#ResultadoBusqudaHerramienta").html("<div style='margin:auto;width:200px'><img src='../resources/imagenes/loanding.gif'  width='100px'/></div>");
        },
        success: function (respuesta) {
            $("#MostrarBusquedaHerramienta").show();
            $("#listadoHerramientas").html(respuesta);
            $('#tbl_total_herramientas').css("display", "inline-table");
        },
        error: function (jqXhr, textStatus, errorMessage) {
            alert("En este momento no podemos procesar la transaccion intente de nuevo");
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

function Exportar_Pdf(idTablaContent, idtablaFecha, Boleta, idProyecto, tipoBoleta, GeneradaPor) {

    var imgData = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAbgAAAEECAMAAABtHNGPAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAGNQTFRFAD2mb3qAf57St7y/297fk5ugQG68v87p9vf37/P5eIKIEEmsyc3P7e7vipOYgYuQ5ObnpaywMGK3z9ruYIbInKSon7bd3+f0rrS4IFWxj6rY0tXXwMXHr8LjUHrCcJLN////XB/wygAAACF0Uk5T//////////////////////////////////////////8An8HQIQAAEUtJREFUeNrsnemCojAMgJEbAbnFW97/KXdEVNqmF8osHZNfuw5Hm69N0ySA1aEYKRaqAMGhIDgUBIfgUBAcCoJDcCgIDgXBoSA4BPdbEh4dvuS3I6Kz/Z4cgqBCcJ+WZiWSG7mL9Qkp622L4D4onhCc+3OEb31Kym2E4EwEZ1mxHSE4E8EZhg7BjWUfIDgjwVmWjeDMBGf5EYIzEpxVRgjOSHCGkENwhpJDcIaSQ3CQ1AjOTHDWFsGZCS5uEZyR4CwfwU0XeVpnPnCLN5ZLBte5Akm7ecHtEdyMMiO4pU+5JYNLBZULTjI3uBLBmeic/EiF4MwEd0VwZoLbIzgzwS3bViI4Q7PhCM7Q6AmCEwiCMxRcgOBmAneZF9wBwc0ELtpOftxDhXmN4GYC945EF6O9k+8F13VbaToVwS0SnHyJRHDLBNfKwLUIbpHgpFMuQHBTZCfitk4/cAcbwc0iqaB04SM3CBCcmYLgZpHc8bjSnBDcFzsnBwRnJrgawRkJLooRnJHgbIycGAmushCcieAOMkO56JpYw8EFk8Xey/NxFwQ3E7iZM+A2gpsJHNacIDhIIgRnJLhFP6+D4LCS+c+Bw2cHzASHT+sYCu6A4CaKsHQhS+cGFyG42WROcAt/LRSCM7A0b+ngjp5AipnBLf09bOicmDnhEJyRr1xAcNw9XITgjAS3/O8PIDgjDSWCM/E1XgjO2AUOwYHPoRrxKUAEZyY3BMesb4Z8ehPBUS9aMOUTcgjOnGoFBGe6mURwpFey7ToEZxy4/cGszxIvGVyyFnDzwk+Cu5w7w8TqTJaPgCuv58i8rv9lcLH8HXqHoDK064s2laLShUZuKg/dH5a/7JwECA7BITgEh+AQHIJDcAgOwSE4BIfgEByCQ3AIDsEhOASH4BAcgkNwCO435N18HIJbrCA4BIfgPieO8AU1aCrROUFwCA7BITgEh+AQHIJDcAgOwSE4BIfgEByCQ3AIDsEhOASH4BAcgkNw74jbCEoXnNsRwcXni90hOBQEh4LgEBwKgkNBcCgIDsGhIDgUBIfgUBAcCoJDQXAIDgXBoSA4BIeC4FAQHAqCQ3AoCA4FwSE4FASH8tvg0qTwstXaa04p5wjX8TarnyOKJGT+5vxITh/uOC512HF3u8TG2x3JezuwpOCfT64bdtJ79Scx7QyT3U8vV55HtTbhtIC65qnw1rf2030dmjCSo+vyNN3/+VPg3Gb0pEUBoUsy0RG3HzdU+35+IjoeOqOPM66dkfJdwSMf8J8zJxTeaziJHpzFqAXeWLO8R0+4OsqSju0uJU0Cqrrv/mfAhQV1S+aO+YY6wmHBUb/RykypS2T5G+CI01XB0brdaYHbUX/a5DJwP+hY29QVsIangHuoNPM8b5hXJ2q6rV9HrEefLyXBrXIBuDB7XmL41zp/B9xqHeqBC4cJs371slAHF26Ys9euFNyqYSfJXX/eB8Dd27QeVpQcGBHHu8pP4fiITciA2wjA9Sd5x2Gl2RCHh+5Dbr8Xz/+FEIPQPRaU2pXA3bnt8mEFXBNzLn/esx8wT6G4FcPZp4wZqA5BI3dPHjyzkpuu6UE+DZxHTeqEHs/pvZOvX/KMUlzHGh9KmSl1RgHbC48lAFm9vG9RqgOunxGblLoE60EAS+OD+sg43heXdcoD99TjBurhidLeNHAJDYH5xWOUfB+ALgNu/BOpzFtbM7oDzVRwXU7aczm4lDESPTlPDdyROfuupEYE7j5WKDcu7S9EzYxp4DJ2XHjEhV1g0esnYUZ2d/14pSukTEa1R1BDquAo7nJwBTVBHnrNlcBlDLfBZrgicDc+K8rx3/UtbRTdE0s24VxgPCdjHW0kp/VNJI2lBFwHDEcNcKSepOBSwBPu3YSdCrgjZFX70xsRuA64adYPlgRSqSa4BrpGUSQ50ecEHIQF2d2G6B8LjuzXKWE30urgCj1wN0u9ou/mNCe1Na4BjapDXJMH7sQMgc0A3X0P3BpSFTO32J93xDayPyYkjCWpzCNgbzmOkhK4TDC7gZPgFRUSqLdrsO3kiAbAuexEbYYrFWruiQBcvpKxL+Bdh0usEPfuEsaSUmbvxG2S8DPgHNIMSMFJh6cIXL6C3ffNePAA4DZMiCR8TFIXsgBa4Fx4Psl0+VjWqO6OjSWlzOMj4OC44bvgwp5bpr6PC1dqpgkGd+ToaDdmxYBLPcZd7y1287QYp3fAOdLAGW+wZuPfh66NjSWtzGQULdodtcE998SO09yDD0d1cC7oCamCcziuhDMePf1BjzYeHcdjwjuDzo7suVPBedKeuDIdP7o7MpaMMt1xmHoNRrIF4BhJOk1w3TvgIB0RMxEMea1zxuauRwukuxxwI2MJKDNpiBhv+AY4z+3+OzhXBq4Atn7FyE1pFgTuZSwdiEF43L2SBJtwIjhvl0tSSP9/xq3ZxGY4jlEmKtbb0nb2qZ5Au/wNCO5lLB2eI/cDbzCanvYadw/demmnB47nF74HjrfG7dYrsO8Jsa6pOLq/5lWOjaUjatiQYTjqe5UJsHbItwNveZUJx4HjepX3ELQXQtF8Mqk4HRzsKIchaZgbzgYwB7r7MJaOeES5a+i6CtuBZAWHHXfsSRvYBX557GrgeD6px9/HFUAyLgVWweP0yAnYpdPIY+eMtxMbOekIY0mA+3Hii1xhI6Kyj3PY9REwZidGkQ079jK2foYXOUngMc+JnNzTJztmgjKr9XRwO2iTMg5ghsqxyo4wlgQ4j71GCKlIaQPuMUo5wTkVj5ymIdD1lUrIqwA3cidRrDJl0329xkb1RI3cPRGBAwJq90l9GnPIFLIDpPeU7cYMdsCInwyOVQqwUpNXCiFXIIOmIdQqF8wOZMLswGlFZrnuhiikR+5uMjgoH0eOBRe4QQjk46g2EqoCnN98qqkElMJaBTqnUbBhDAdcZEBnbUPd77mMCfJxtF1gN26NNGplSaP/wgx4o5YBp8mPGfSgyXXJgwLkikFmj/a3Gc3uqPENZMBhtw4E53Iy4J5gmaXsApBVPUrLvSypvz/e5J9oty1kak42UM0JPSGZfBxbtOFOBJfStToJpdkTMxp3VM2Jq1VzsptQc+IQNScnYHplMvdEDO4+NB5VXkegOuleWJMl4iovZjARi8x9z10c+7Pye5nU9JoThy7EyUZ9SJN7H1LG3D3rtIY+7DpFcOTZ4VDl5Yod281YBRlgYHayuIAY3MDlXjUJV8Q+jti86io3obC7DV0imz+LiL3NlJBXJ1DK+PLZmlPVO66MXNN1lVJwofc6+3Gho2RHko+GDxi7SVeSfKoEHFOnvGYMb+rJK5lZ74U4iimG9rSCzNBvY1OVr2V9CBt+JbMUHBuM9HJpXGz3WgcLcEH1JOVeMnA/t83ef3YAiMA6/JtkiV5ap+MrZbzsPEuIpX3w3E4LXJeOb7BJFAKa9/XhxNuODEtz8g64/lkcbyV8Wifvj8i4T+vQ+1PgaZ3caW4F3BuP97hKApwEP3gTjp/nGX5KdvfniUR9aG52UnAE+JDP8wZF/6xPc4Kf1mEfuXCGX1Omsa9OvAkOZYGC4BAcCoJDQXAIDgXBoSA4BIeC4FAQHMrfBRds7V4OQSs4Kqa+WPz48u2B+j0WXKMKbvc5c/8evZoSiVp8tVRkf20ngks8ibwOPftSsc9kZw4qp8igna8lofXLlqcxiwPOpv/AOb89PD9dDevzXO8Jtdfcpkg+gj3qTjANnLOSyOtQW6khdavb+rgWjLr2GgOnXILPg4u2pfjz4m0NNaUO3gNnxe0ywFnWNdJuPe9r3m3NtTHnz4KL7Fj8XfiK2xc/eAucdV0KOKustFtfQiYnEq4TfvVBcOc9dQQFI6qFTWnfAecvBpwVV9qtB8gFe915OhVcdGGuTYI7x5IOH94AZ83tnKiDe5LTaD1DTuF2fvQRcBWAJdD0EOvol8FpiAa4BwaN1tMWo9Yzym+A20JXHoGL/CkDz1Bww4qrA84am5vI1zTK08GB3EbgonKSyTAV3H0jpAUuHvX8MsmZngIO5vYCp8qNJmcsuFob3GjK1dPWxgngAksCbmJTZgc3i1f5DCvpgdtLZgEsl3fAtbEEnK09WE0HZ521wVnDglVZEyeqPjhuAwPxhBT02XhwtT64AUGpd9YrrqgN7mCJwUV7vZbE0R8AV+qDu0i0KdtI6IKLYgk4W7cp9RLBBbZAgADsLeIgOuUSg4tcFOtq67kg6YKrJVPnjabMDs51JKLqc7LRvEp6Sgl1AR7l5XUbBGfbF045TXAtJ1lxCIasHxwxKe0zvymXBW4HZBgkcVoo+A+xBnMnFS+KT9xKExzEZb8d9SgWpq1asCktDC4epSAXBo7RgxwcMywDcCuwDyQR4cfSogkuFkdvoKaQMbbW5ydpfH5A/LowcLZikk1wSgDFTBTCz4M3x3MVYHBnSQAN8G6ZUHIN71/F4OxPgEtdiUwHt5WfcmHARSoJH5uzgeLt6GHjVEu4taK9Pp9c8DvgNLxKmZT6pjJmTjmLIpj8UGYNghss3xZ2BxhLSY005rR9pNDrByJzwG0tbXDMcI1YhwGct0ykqoTBWf5ha5ewH1fJMtG1UodazmUWBK49iHZl7Dod6Z/CWrW9YsCbA07ggEsHWqlWYlDDns+CwGmGEfb6kROftV9bxY1EoA3OBietIEnEqSOs4A2BueAmxCptVluR4oJ61gbni3YC0NjgqRDev84NTiNyogluOyU7UMmmgXDvUWoYA2gPSdYqqVlK1laefwXcbInU3hvUzscpa+sAgbuom2S2bZ0EXK23fzUW3LQMuLK2AkhbB3WTrA/O/hJw+jUnt22S8ow7Q9pSzcAGfwCcRuRk7iqvQGdhgbWllveMwba1EnAX1aYcTPYq9/p1lX1/I07kT+oRHDRysPCgOkvA7TUi5eaC069ktuA4caXlg6tlPlsQfS3bx/GeqrH+ELitfjZxCCaXat7JmaNVlTbW8KGxLJBqK4b6DI6cbCekgTkefRwpmSeLn4phVriWU8B1kNyD05Q9bFINBLevukngfHCVuihNuKcTU8WqoyqS5SFsXo5UuKF8NNg4rzK2o+4NcJWlkB5gy1gPspJywPaWkvRAYCmkBypecsjnj78l7uPKw9QC+ofe9pbUPwHK+atOkVwt6k8tXuToTCs8wVuw6/5ywO3ZPE0geUisJMvzQHBXSzbnqr1liVx10VOItTDDTZUmAHUJZyk3v1s6OIWSEpGh5222oZq5y8gTB8u8SL+i5U10Su+AI0PUJUH152O0ka3uUH8c3PTswEzgYPNaDyqvwJI4xt/b7mWrL9eolttWRNaKH69ZqMD3QcTdL4Gb7lXOBY73nEXp+34s2EhI/E7LjqSe/GPa+f5WuFxCxZF0U74PnG4Sj7PBUnEJt7LoSjm9KV8IrtLVlt1NBCcYI4Fw9stDD98IbspjQRPBVRJwim/kgvB8Izg9CxVX08Hx0wnD0XoPyI0fSJ8b3PTIyYzg2niSdZoAjlvtECiH0DiZoa/bx+kuc9fuLXC8lyoEqiE03hD6TnDq6uIWpSiC45EL3m3Kl4JTVVfdvQuOQy7Qbsq1+1Vwy4uc6Cwuh+59cPBbjALF4Cd3qf1Kr/LuoUh9y1j0cll1cOD+gzi6kjZlX3UITjWPdBG+E1kHHPCGRfLo6KrdlG8Gxw/zW5xXxE4Fx+Yc6KMrQVNKhaDMd4H7OYazz9pLH3fVA8egY48OOOj8rUrXvw3c7d0GJTez8klwN/fxIi4LbK+MRd1fK7Wu/8/tQEWmuyuFUwLRKXR8hPtS6XZbP9VQXg6VUmvpRyoPKidFZ9vf8+Oftyc1X03xa1FTqK6f+Yrczg3uf0sg/kLDB6WSjI3ot5ryN8B9oSA4BIeC4FAQHIJDQXAoCA7BoSA4lF+QfwIMAKx5XDBxo052AAAAAElFTkSuQmCC";
    var Npedido = $("#" + Boleta).html();
    var dia = $("#" + idtablaFecha + " tbody tr td").eq(0).html();
    var mes = $("#" + idtablaFecha + " tbody tr td").eq(1).html();
    var anno = $("#" + idtablaFecha + "  tbody tr td").eq(2).html();
    var Proyecto = "";
    if (idProyecto != "") {
        var Proyecto = $("#" + idProyecto).html();
    }
    var TipoBoleta = $("#" + tipoBoleta).html();
    var generadaPor = $("#" + GeneradaPor).html();
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
    var htmlBody = $("#" + idTablaContent).html();
    var margins = { top: 270, bottom: 20, left: 70, width: 100 };
    pdf.fromHTML(htmlBody, margins.left, margins.top, { 'width': margins.width }, function (dispose) {
        pdf.save("Boleta" + '.pdf');
    }, margins);
}


function AbrirModalEliminarHerramienta(codigo) {
    $("#ModalEliminarHerramienta").modal();
    $("#IdCodigo").val(codigo)
}

function EliminarHerramientaElectrica() {
    let motivo = $("#txtMotivoEliminarHerramienta").val();
    let codigo = $("#IdCodigo").val();
    if (StringIsNullOrEmpty(motivo)) {
        MostrarMensajeResultado("Se debe ingresar un motivo", resultado.esValido, "idResultadoEliminarMaquinaria");
    }
    else {
        fetch('../BLL/Herramientas.php?opc=eliminarHerramienta',
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({//Estos son los parametros que recibe el método
                    "codigo": codigo,
                    "motivo": motivo
                })
            })
            .then((response) => {
                if (response.ok) {
                    return response.text();
                }
                else {
                    MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, "idResultadoEliminarMaquinaria");
                    console.log(response);
                }
            })
            .then((data) => {
                if (esJsonValido(data)) {
                    let resultado = JSON.parse(data);
                    $("#txtMotivoEliminarMaquinria").val("");
                    MostrarMensajeResultado(resultado.mensaje, resultado.esValido, "idResultadoEliminarMaquinaria");
                    if (resultado.esValido)
                        listarTotalHerramientas()
                }
                else {
                    MostrarMensajeResultado(data, false, "idResultadoEliminarMaquinaria");
                }
            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, "idResultadoEliminarMaquinaria");
            });
    }
}

function OpenModalEditarHerramienta(codigo) {
    fetch('../BLL/Maquinaria_BL.php?opc=bucarPorCodigoGetJson&codigo=' + codigo,
        {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then((response) => {
            if (response.ok) {
                return response.text();
            }
            else {
                MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, "listadoHerramientas");
                console.log(response);
            }
        })
        .then((data) => {
            if (esJsonValido(data)) {
                let equipo = JSON.parse(data);
                console.log(data)
                $("#txtIdHerramientaEditar").val(equipo.ID_Herramienta)
                $("#txtCodigoEditar").val(equipo.Codigo);
                $("#txtCodigoHerramientaActualEditar").val(equipo.Codigo);
                $("#txtDescripcionEditar").val(equipo.Descripcion);
                $("#txtMarcaEditar").val(equipo.Marca);
                $("#txtPrecioEditar").val(equipo.Precio);
                $("#txtFechaRegistroEditar").val(equipo.FechaIngreso);
                $("#txtProcedenciaEditar").val(equipo.Procedencia);
                document.getElementById("cboTipoHerramientaEditar").value = equipo.ID_Tipo;
                $("#txtNumFacturaEditar").val(equipo.NumFactura)
                // document.getElementById("cboMonedaEditar").value = equipo.MonedaCompra ==null || equipo.MonedaCompra ==""?"0":equipo.MonedaCompra;
                $("#ModalEidtarHerramienta").modal()
            }

        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, "listadoHerramientas");
        });

}

async function EditarHerramienta() {
    const btnEdiatar = $("#btnEditarHerramienta");
    const mensajesResultado = "idResultadoActulizarHerramienta";
    let id = $("#txtIdHerramientaEditar").val()
    let codigo = $("#txtCodigoEditar").val();
    let CodigoActual = $("#txtCodigoHerramientaActualEditar").val();
    let descripcion = $("#txtDescripcionEditar").val();
    let marca = $("#txtMarcaEditar").val();
    let precio = $("#txtPrecioEditar").val();
    let fechaRegistro = $("#txtFechaRegistroEditar").val();
    let procedencia = $("#txtProcedenciaEditar").val();
    let tipo = document.getElementById("cboTipoHerramientaEditar").value;
    let factuara = $("#txtNumFacturaEditar").val();


    if (codigo == "")
        MostrarMensajeResultado("Debe ingresar un código", false, mensajesResultado);
    else if (precio == "" || precio == ".00")
        MostrarMensajeResultado("Debe ingresar el precio del equipo", false, mensajesResultado);
    else if (fechaRegistro == "")
        MostrarMensajeResultado("La fecha de registro no puede ser vacia", false, mensajesResultado);
    else if (tipo == "0")
        MostrarMensajeResultado("Debe seleccionar el tipo de equipo", false, mensajesResultado);
    else {
        btnEdiatar.prop('disabled', true);
        const formData = new FormData();
        formData.append("codigoNuevo", codigo);
        formData.append("codigoActual", CodigoActual);
        formData.append("tipo", tipo);
        formData.append("marca", marca);
        formData.append("descripcion", descripcion);
        formData.append("fechaIngreso", fechaRegistro);
        formData.append("procedencia", procedencia);
        formData.append("precio", precio);
        formData.append("numFactura", factuara);
        formData.append("idHerramienta", id)
        await fetch('../BLL/Herramientas.php?opc=editarHerramienta',
            {
                method: 'POST',
                body: formData
            })
            .then((response) => {
                if (response.ok) {
                    return response.text();
                }
                else {
                    MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, mensajesResultado);
                    console.log(response);
                }
            })
            .then((data) => {
                if (esJsonValido(data)) {

                    let resultado = JSON.parse(data);
                    MostrarMensajeResultado(resultado.mensaje, resultado.esValido, mensajesResultado);
                    if (resultado.esValido) {
                        listarTotalHerramientas();
                        $("#txtIdHerramientaEditar").val("")
                        $("#txtCodigoEditar").val("");
                        $("#txtCodigoHerramientaActualEditar").val("");
                        $("#txtDescripcionEditar").val("");
                        $("#txtMarcaEditar").val("");
                        $("#txtPrecioEditar").val("");
                        $("#txtFechaRegistroEditar").val("");
                        $("#txtProcedenciaEditar").val("");
                        document.getElementById("cboTipoHerramientaEditar").value = "0";
                        $("#txtNumFacturaEditar").val("");
                    }
                }

            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, mensajesResultado);
            });
    }

    btnEdiatar.prop('disabled', false);
}


$(document).on('keyup', '#txtCodigo', function () {
    var valor = $(this).val();
    if (valor != "") {
        BuscarTiempoRealHerramienta(valor);
    }
});

//Enter
$(document).ready(function () {
    $("#txtCodigoHerra").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            BuscarHerramientasPorCodigo();
            e.preventDefault();
        }
    });

    $("#txtCodigoVista").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            MostrarHistorial();
            e.preventDefault();
        }
    });
    $("#CodHerramientaReparacion").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            BuscarHerramientaTablaReparaciones();
            e.preventDefault();
        }
    });
    $("#txtCodigoHerramientaBuscar").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            BuscarHerramientaNombre();
            e.preventDefault();
        }
    });
    $("#txtnombreTipoHerramienta").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            GuardarTipoHerramienta();
            e.preventDefault();
        }
    });

    $("#txtNumFacturaH").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            GuardarHerramienta();
            e.preventDefault();
        }
    });

    $("#txtTrasladoCodigo").keypress(function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 13) {
            BuscarTHerramienta();
            e.preventDefault();
        }
    });


});







