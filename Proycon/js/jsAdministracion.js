/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function listarTotalHerramientas() {
validarMostrar()
 $("#AdminHerramientas").show();
    $.ajax({
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=listar',
       beforeSend: function () {
           $(".AntesResultado").show();
            $(".AntesResultado").html("<div style='margin:auto;width:80%; height: 100px;' ><h3 style='width:40px;display:inline'>Cargando... </h3><img src='../resources/imagenes/loanding.gif'  width='100px'/></div>");
        },
        success: function (respuesta) {

            if (respuesta == -1) {
                alert("error");
            } else {
                 $('#HerramientasEnReparacion').hide()
                 $(".AntesResultado").hide();
                 $('#listadoHerramientas').show()
                $('#listadoHerramientas').html(respuesta);

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
function MostrarListaReparaciones() {
 $("#AdminHerramientas").show();
validarMostrar()
    $.ajax({
        type: 'POST',
        url: '../BLL/Herramientas.php?opc=totalReparaciones',
               beforeSend: function () {
           $(".AntesResultado").show();
            $(".AntesResultado").html("<div style='margin:auto;width:80%;'> <h3 style='width:40px;display:inline'>Cargando... </h3><img src='../resources/imagenes/loanding.gif'  width='100px'/></div>");
        },
        success: function (respuesta) {

            if (respuesta == -1) {
                alert("error");
            } else {
                $(".AntesResultado").hide();
                  $('#listadoHerramientas').hide();
                 $('#HerramientasEnReparacion').show();
                $('#HerramientasEnReparacion').html(respuesta);
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
function validarMostrar(){
    if ($("#AdminHerramientas").is(":visible")) {
        $("#AdminMateriales").hide();
        if ($("#adminReparaciones").is(":visible")) {
        $("#adminReparaciones").hide();
        $("#lHerramientas").show();
        return;
    }
    else if ($("#lHerramientas").is(":visible")) {
        $("#lHerramientas").hide();
        $("#adminReparaciones").show();
                return;
    } 
    }
    if ($("#AdminMateriales").is(":visible")) {
       $("#AdminHerramientas").hide(); 
    }
  
    
}
function BuscarHerramientaCodigo(){
    try {
        $("#btnActualizarH").removeAttr("disabled");      
        $("#btnGuardarH").attr("disabled",true);
        $("#AdminHerramientas").show();
    $.ajax({
        type: 'POST',
        url: '../BLL/Administracion_BL?opc=buscarHCod&codigo='+$("#txtCodigoH2").val(),
       beforeSend: function () {
           $("#tituloModalAgregarHerramienta").html("Procesando...");
        },
        success: function (respuesta) {
             $("#tituloModalAgregarHerramienta").html("Registrar Herramientas");
            if (respuesta == "0") {
                alert("No se encotraron resultados");
            } else {
                $('#frmInsertar').html(respuesta);

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
    } catch (e) {
      alert("Ocurrio un error"+e); 
    }
  
}
function ModificarHerramienta(){}
function GuardarHerramienta() {
    try {
    var consecutivoHerramienta = $('#txtCodigoH2').val()
    var validaciones = 7;
    var validardescripcion = $('#txtDescripcionH').val()
    var validarmarca = $('#txtMarcaH').val()
    var validarprocedencia = $('#txtProcedenciaH').val()
    var validarfecha = $('#txtFechaRegistroH').val()
    var validartipo = $('#comboHerramientaTipoH').val()
    var validarprecio = $('#txtPrecioH').val()
    var datos = {
        "Codigo": $('#txtCodigoH2').val(),
        "Descripcion": $('#txtDescripcionH').val(),
        "Marca": $('#txtMarcaH').val(),
        "Procedencia": $('#txtProcedenciaH').val(),
        "Fecha": $('#txtFechaRegistroH').val(),
        "Tipo": $('#comboHerramientaTipoH').val(),
        "Precio": $('#txtPrecioH').val()
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
                    //listarTotalHerramientas();
                    setTimeout(function () {
                        $('#ModalAgregarHerramienta').modal('hide');
                    }, 3000);
                } else {
                    $("#modalheaderAgregarHerramienta").addClass("mensajeError");
                    $("#tituloModalAgregarHerramienta").html("<strong>El Codigo ya fue ingresado</strong>");
                }
            }


        })
    } else {
        $("#modalheaderAgregarHerramienta").addClass("mensajeError");
        $("#tituloModalAgregarHerramienta").html("<strong>Debes llenar todos los campos del Formulario</strong>");
    }

    setTimeout(function () {
        $("#modalheaderAgregarHerramienta").removeClass("mensajeCorrecto");
        $("#modalheaderAgregarHerramienta").removeClass("mensajeError");
        $("#tituloModalAgregarHerramienta").html("Registrar Herramientas");
    }, 3000);

   
    } catch (e) {
        alert("Ocurrio un error "+e)
    }

  
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
