async function MostrarFormUsuario(evento, estado) {

    const RolBodegaProyecto = 6;
    //cargar datos del Form Update
    var id = $(evento).parents("tr").find("td").eq(0).html();
    let usuario = await ObtenerDatosUsuarioGetJson(id);

    $("#usuarioCorrecto").html("Actualizar Datos del Usuario");
    $("#ModalAgregarUsuario").modal('show');
    $('#btnUpdate').show();
    $('#btnInsert').hide();


    //Asignacion de variables a los campos de Texto 
    $("#txtId").val(id);
    $("#txtNombreU").val(usuario.Nombre);
    $("#txtUsuario").val(usuario.Usuario);
    $("#txtContra").val("");

    if (usuario.Rol == 'Administrador') {
        $("#cboRol").val(1);
        $("#cboRol").prop('disabled', true);
        $("#noactivo").prop('disabled', true);

    } else {
        $("#cboRol").val(usuario.ID_Rol);
        $("#cboRol").prop('disabled', false);
        $("#noactivo").prop('disabled', false);
    }

    if (usuario.ID_Rol == RolBodegaProyecto) {
        $("#Proyectos").css("display", "block");
        $("#cboProyecto").val(usuario.ID_Proyecto);
    }
    else {
        $("#Proyectos").css("display", "none");
        $("#cboProyecto").val("0");
    }



    if (estado == '1') {
        $("#activo").attr('checked', true);
        $("#noactivo").attr('checked', false);
    } else if (estado == '0') {
        $("#noactivo").attr('checked', true);
        $("#activo").attr('checked', false);
    }

}

function CambioPassword() {

    var passUsuario = $("#PassCuenta").html().trim();
    var passActual = $("#contraActual").val();
    var nuevoPass = $("#contraNueva").val();
    var confirmarPass = $("#confirmarContra").val();

    $.ajax({
        data: { "viejoPass": passActual, "nuevoPass": confirmarPass, "ID_Usuario": $("#ID_UsuarioCuenta").html() },
        url: "..//BLL/Usuario.php?opc=cambioPass",
        type: "POST",
        success: function (respuesta) {

            console.log(respuesta);

            var myObj = JSON.parse(respuesta)


            if (myObj['codigo'] == 1) {
                $("#modalheaderCambioPass").addClass("mensajeCorrecto");
                $("#TituloCambioPass").html("<strong>" + myObj['mensaje'] + "</strong>");
                $("#PassCuenta").html(nuevoPass);
                $("#contraActual").val("");
                $("#contraNueva").val("");
                $("#confirmarContra").val("");

                setTimeout(function () {
                    $("#modalheaderCambioPass").removeClass("mensajeCorrecto");
                    $("#modalheaderCambioPass").removeClass("mensajeError");
                    $("#TituloCambioPass").html("Cambiar Contraseña");
                    $("#ModalCambioPassword").modal("hide");
                }, 3000);
            } else {
                $("#modalheaderCambioPass").addClass("mensajeError");
                $("#TituloCambioPass").html("<strong>" + myObj['mensaje'] + "</strong>");
                setTimeout(function () {
                    $("#modalheaderCambioPass").removeClass("mensajeCorrecto");
                    $("#modalheaderCambioPass").removeClass("mensajeError");
                    $("#TituloCambioPass").html("Cambiar Contraseña");
                }, 3000);

            }

        }
    });

}
function CambiarNombre() {
    var nuevoNombre = $("#nuevoNombre").val();
    $.ajax({
        data: { "nuevoNombre": nuevoNombre, "ID_Usuario": $("#ID_UsuarioCuenta").html() },
        url: "..//BLL/Usuario.php?opc=cambioNom",
        type: "POST",
        success: function (respuesta) {
            if (respuesta == 1) {
                $("#headerModalCambiarNombre").addClass("mensajeCorrecto");
                $("#TituloCambiarNombre").html("<strong>Nombre Actualizado Correctamente</strong>");
                $("#NombreCuenta").html(nuevoNombre);
                setTimeout(function () {
                    $("#headerModalCambiarNombre").removeClass("mensajeCorrecto");
                    $("#headerModalCambiarNombre").removeClass("mensajeError");
                    $("#TituloCambiarNombre").html("Cambiar de Nombre");
                    $("#ModalCambioNombreCuenta").modal("hide");
                }, 3000);

            } else {
                $("#ModalCambioNombreCuenta").addClass("mensajeError");
                $("#TituloCambiarNombre").html("<strong>Ocurrio un Error</strong>");
                setTimeout(function () {
                    $("#ModalCambioNombreCuenta").removeClass("mensajeCorrecto");
                    $("#ModalCambioNombreCuenta").removeClass("mensajeError");
                    $("#TituloCambiarNombre").html("Cambiar de Nombre");
                    $("#ModalCambioNombreCuenta").modal("hide");
                }, 3000);

            }
        }


    });
}

function abrirModalCambiarNombre() {
    //
    var nombre = $("#NombreCuenta").html().trim();
    $("#nombreActual").val(nombre);
    $("#ModalCambioNombreCuenta").modal('show');

}

function abrirModal() {
    $("#Proyectos").css("display", "none")
    limpiartxt();
    $("#ModalAgregarUsuario").modal('show');
    $("#usuarioCorrecto").html("Agregar Nuevo Usuario");
    $("#cboRol").prop('disabled', false);
    $('#btnUpdate').hide();
    $('#btnInsert').show();

}



//AgregarUsuario()
function AgregarUsuario() {
    var status = '';
    let idProyecto = "";
    if ($('#activo').is(":checked")) {
        status = 1;
    } else if ($('#noactivo').is(":checked")) {
        status = 0;
    }

    if ($("#Proyectos").is(":visible")) {
        idProyecto = document.getElementById("cboProyecto").value;
    }
    //console.log($('#cboRol').val());
    //if (/^\w+([\.-]?\w+)*@\proycon+([\.-]?\w+)*(\.\w{2,4})+$/.test($('#txtUsuario').val())) {
    ///^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test($('#txtUsuario').val())) {


        var parametros = {

            "nombre": $('#txtNombreU').val(),
            "usuario": $('#txtUsuario').val(),
            "pass": $('#txtContra').val(),
            "rol": $('#cboRol').val(),
            "status": status,
            "idProyecto": idProyecto
        };

        if ($('#txtNombreU').val() != "" && $('#txtUsuario').val() != "" &&
            $('#txtContra').val() != "" && idProyecto.trim() != "0") {

            $.ajax({
                type: 'POST',
                url: '../BLL/Usuario.php?opc=registrar',
                data: parametros,
                success: function (response) {
                    if (response == 0) {
                        alert("El nombre de Usuario ya hacido utilizado");
                        $("#txtUsuario").focus();
                    } else {
                        //$('#ModalAgregarUsuario').modal('hide');
                        $('#tablaUsuarios').html(response);
                        $("#modalheaderUsuario").addClass("mensajeCorrecto");
                        $("#usuarioCorrecto").html("<strong>Datos Insertados Correctamente</strong>");
                        LimpiarFormulario();
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
            $("#modalheaderUsuario").addClass("mensajeError");
            if (idProyecto.trim() == "0") {
                $("#usuarioCorrecto").html("<strong>Debe seleccionar un proyecto</strong>");
            }
            else
                $("#usuarioCorrecto").html("<strong>No puede dejar Campos en Blanco</strong>");

        }



        setTimeout(function () {
            $("#modalheaderUsuario").removeClass("mensajeCorrecto");
            $("#modalheaderUsuario").removeClass("mensajeError");
            $("#usuarioCorrecto").html("Agregar Nuevo Usuario");
        }, 3000);
    } else {
        $('#txtUsuario').css("border", "1px solid red");
        $('#txtUsuario').focus();
    }
}


function ActualizarUsuario() {

    if ($('#activo').is(":checked")) {
        var status = 1;
    } else if ($('#noactivo').is(":checked")) {
        var status = 0;
    }
    let idProyecto = "";
    if ($("#Proyectos").is(":visible")) {
        idProyecto = document.getElementById("cboProyecto").value;
    }
    //console.log($('#cboRol').val());

    //let proyecto  = 

    var parametros = {
        "id": $('#txtId').val(),
        "nombre": $('#txtNombreU').val(),
        "usuario": $('#txtUsuario').val(),
        "pass": $('#txtContra').val(),
        "rol": $('#cboRol').val(),
        "status": status,
        "idProyecto": idProyecto
    };
    if ($('#txtNombreU').val() != "" && $('#txtUsuario').val() != "" && idProyecto.trim() != "0") {
        $.ajax({
            type: 'POST',
            url: '../BLL/Usuario.php?opc=update',
            data: parametros,
            success: function (response) {
                $("#modalheaderUsuario").addClass("mensajeCorrecto");
                $("#usuarioCorrecto").html("<strong>Datos Actualizados Correctamente</strong>");
                $('#tablaUsuarios').html(response);
                setTimeout(function () {
                    $('#ModalAgregarUsuario').modal('hide');
                    $("#modalheaderUsuario").removeClass("mensajeCorrecto");
                    $("#usuarioCorrecto").html("Agregar Nuevo Usuario");
                }, 3000);
                LimpiarFormulario();
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
    else {
        $("#modalheaderUsuario").addClass("mensajeError");
        if (idProyecto.trim() == "0") {
            $("#usuarioCorrecto").html("<strong>Debe seleccionar un proyecto</strong>");
        }
        else
            $("#usuarioCorrecto").html("<strong>No puede dejar Campos en Blanco</strong>");
    }

}
function LimpiarFormulario() {

    $("#txtNombreU").val("");
    $("#txtUsuario").val("");
    $("#txtContra").val("");
    $("#cboRol").val(1);
    $("#activo").checked = false;
    $("#noactivo").checked = false;
}

function updateEstado(evento, estado) {


    var id = $(evento).parents("tr").find("td").eq(0).html();
    $(evento).parents("tr").css("background", "red");
    var parametros = {
        "id": id,
        "estado": estado
    };
    $.ajax({
        type: 'POST',
        url: '../BLL/Usuario.php?opc=updEstatus',
        data: parametros,
        success: function (response) {

            $('#tablaUsuarios').html(response);

        }


    });

}

function limpiartxt() {
    $("#txtNombreU").val('');
    $("#txtUsuario").val('');
    $("#txtContra").val('');
}

function ValidarRol() {
    const RolBodegaPoryecto = 6;
    let rol = document.getElementById("cboRol").value;
    let proyectos = $("#Proyectos")
    if (rol == RolBodegaPoryecto) {
        proyectos.css("display", "block");
    }
    else {
        proyectos.css("display", "none");
    }

}

async function ObtenerDatosUsuarioGetJson(ID_Usuario) {
    const IdDivRespuesta = "mensajesResultado";
    let usuario = new Object();
    await fetch('../BLL/Usuario.php?opc=getUser&idUsuario=' + ID_Usuario,
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
                MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, IdDivRespuesta);
                console.log(response);
            }
        })
        .then((data) => {
            if (esJsonValido(data)) {
                usuario = JSON.parse(data);
            }
            else {
                MostrarMensajeResultado(data, false, IdDivRespuesta);
            }

        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, IdDivRespuesta);
        });

    return usuario;
}