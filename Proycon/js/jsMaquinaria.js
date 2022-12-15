

async function AgregarMaquinaria() {
    btnAgregar = $("#btnAgregarMaquinaria")
    btnAgregar.prop('disabled', true);
    const IdDivRespuesta = "respuestaAgregarMaquinaria";
    let txtCodigoMaquinaria = $("#txtCodigoMaquinaria");
    let txtDescripcionMaquinaria = $("#txtDescripcionMaquinaria");
    let txtMarca = $("#txtMarca");
    let txtPrecio = $("#txtPrecio");
    let txtFechaRegistro = $("#txtFechaRegistro");
    let txtProcedencia = $("#txtProcedencia");
    let comboHerramientaTipo = document.getElementById("comboHerramientaTipo");
    let txtNumFactura = $("#txtNumFactura");
    let txtFile = document.getElementById("txtFileFichaTecnica");
    let moneda = document.getElementById("cboMonedaAgregar").value;
    const formData = new FormData();
    // Agregamos cada archivo a "archivos[]". Los corchetes son importantes
    if (txtFile.files.length > 0)
        formData.append("archivo", txtFile.files[0]);
    formData.append("codigo", txtCodigoMaquinaria.val());
    formData.append("tipo", comboHerramientaTipo.value);
    formData.append("marca", txtMarca.val());
    formData.append("descripcion", txtDescripcionMaquinaria.val());
    formData.append("fechaIngreso", txtFechaRegistro.val());
    formData.append("procedencia", txtProcedencia.val());
    formData.append("precio", txtPrecio.val());
    formData.append("numFactura", txtNumFactura.val());
    formData.append("monedaCompra", moneda)

    if (StringIsNullOrEmpty(txtCodigoMaquinaria.val()) || StringIsNullOrEmpty(txtDescripcionMaquinaria.val()) ||
        comboHerramientaTipo.value == "0" || moneda.value == "0") {
        MostrarMensajeResultado("Debe completar los campos del fromulario", false, IdDivRespuesta)
    }
    else {
        await fetch('../BLL/Maquinaria_BL.php?opc=agregar',
            {
                method: 'POST',
                body: formData
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
                    let resultado = JSON.parse(data);
                    MostrarMensajeResultado(resultado.mensaje, resultado.esValido, IdDivRespuesta);
                    txtCodigoMaquinaria.val("");
                    txtDescripcionMaquinaria.val("");
                    txtFechaRegistro.val("");
                    txtMarca.val("");
                    txtNumFactura.val("");
                    txtPrecio.val("");
                    txtProcedencia.val("");
                    comboHerramientaTipo.value = '0';
                    moneda.value = '0'
                    txtFile.value = null;
                    ListarTotalMaquinaria();
                }
                else {
                    MostrarMensajeResultado(data, false, IdDivRespuesta);
                }

            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, IdDivRespuesta);
            });

    }

    btnAgregar.prop('disabled', false);

}

function OpenModalEditarMaquinaria(codigo, idArchivo) {
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
                $("#txtIdArchivoEditar").val(equipo.ID_Archivo);
                $("#txtCodigoEditarMaquinaria").val(equipo.Codigo);
                $("#txtCodigoActualEditarMaquinaria").val(equipo.Codigo);
                $("#txtDescripcionEditarMaquinaria").val(equipo.Descripcion);
                $("#txtMarcaEditar").val(equipo.Marca);
                $("#txtPrecioEditar").val(equipo.Precio);
                $("#txtFechaRegistroEditar").val(equipo.FechaIngreso);
                // $("#txtProcedenciaEditar").val();
                document.getElementById("comboHerramientaTipoEditar").value = equipo.ID_Tipo;
                $("#txtNumFacturaEditar").val(equipo.NumFactura)
                document.getElementById("txtFileFichaTecnicaEditar");
                document.getElementById("cboMonedaEditar").value = (equipo.CodigoMonedaCobro == null || equipo.CodigoMonedaCobro == "") ? "0" : equipo.CodigoMonedaCobro;
                $("#ModalEditarMaquinaria").modal()
            }

        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, "listadoHerramientas");
        });

}

async function OnclickEditarMaquinaria() {
    const btnEditar = $("#btnEditarMaquinaria");
    btnEditar.prop('disabled', true);
    const IdDivRespuesta = "respuestaEditarMaquinaria";
    let txtCodigoMaquinaria = $("#txtCodigoEditarMaquinaria");
    let txtCodigoActual = $("#txtCodigoActualEditarMaquinaria");
    let txtDescripcionMaquinaria = $("#txtDescripcionEditarMaquinaria");
    let txtMarca = $("#txtMarcaEditar");
    let txtPrecio = $("#txtPrecioEditar");
    let txtFechaRegistro = $("#txtFechaRegistroEditar");
    let txtProcedencia = $("#txtProcedenciaEditar");
    let comboHerramientaTipo = document.getElementById("comboHerramientaTipoEditar");
    let txtNumFactura = $("#txtNumFacturaEditar");
    let txtIDHerramienta = $("#txtIdHerramientaEditar");
    let txtFile = document.getElementById("txtFileFichaTecnicaEditar");
    let moneda = document.getElementById("cboMonedaEditar").value;
    const formData = new FormData();
    if (txtFile.files.length > 0)
        formData.append("archivo", txtFile.files[0]);

    formData.append("codigoNuevo", txtCodigoMaquinaria.val());
    formData.append("codigoActual", txtCodigoActual.val());
    formData.append("tipo", comboHerramientaTipo.value);
    formData.append("marca", txtMarca.val());
    formData.append("descripcion", txtDescripcionMaquinaria.val());
    formData.append("fechaIngreso", txtFechaRegistro.val());
    formData.append("procedencia", txtProcedencia.val());
    formData.append("precio", txtPrecio.val());
    formData.append("numFactura", txtNumFactura.val());
    formData.append("monedaCompra", moneda)
    formData.append("idHerramienta", txtIDHerramienta.val())
    formData.append("idArchivo", $("#txtIdArchivoEditar").val())
    if (StringIsNullOrEmpty(txtCodigoMaquinaria.val()) || StringIsNullOrEmpty(txtDescripcionMaquinaria.val()) ||
        comboHerramientaTipo.value == "0" || moneda.value == "0") {
        MostrarMensajeResultado("Debe completar los campos del fromulario", false, IdDivRespuesta)
    }
    else {
        await fetch('../BLL/Maquinaria_BL.php?opc=actualizar',
            {
                method: 'POST',
                body: formData
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
                    let resultado = JSON.parse(data);
                    MostrarMensajeResultado(resultado.mensaje, resultado.esValido, IdDivRespuesta);
                    if (resultado.esValido) {
                        ListarTotalMaquinaria();
                        txtCodigoMaquinaria.val("");
                        txtDescripcionMaquinaria.val("");
                        txtFechaRegistro.val("");
                        txtMarca.val("");
                        txtNumFactura.val("");
                        txtPrecio.val("");
                        txtProcedencia.val("");
                        comboHerramientaTipo.value = '0';
                        moneda.value = '0'
                        txtFile.value = null;

                    }
                }
                else {
                    MostrarMensajeResultado(data, false, IdDivRespuesta);
                }

            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, IdDivRespuesta);
            });

    }

    btnEditar.prop('disabled', false);
}

function AbrirModalEliminarMaquinaria(codigo) {
    $('#IdCodigo').val(codigo);
    $('#ModalEliminarMaquinaria').modal('show');
}

async function EliminarMaquinaria() {
    const btnEliminar = $("#btnEliminarMaquinaria")
    btnEliminar.prop("disabled", true)
    let motivo = $("#txtMotivoEliminarMaquinria").val();
    let codigo = $("#IdCodigo").val();
    if (StringIsNullOrEmpty(motivo)) {
        MostrarMensajeResultado("Se debe ingresar un motivo", resultado.esValido, "idResultadoEliminarMaquinaria");
    }
    else {
        fetch('../BLL/Maquinaria_BL.php?opc=eliminar',
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
                        ListarTotalMaquinaria()
                }
                else {
                    MostrarMensajeResultado(data, false, "idResultadoEliminarMaquinaria");
                }
            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, "idResultadoEliminarMaquinaria");
            });
    }

    btnEliminar.prop("disabled", false);
}

function ListarTotalMaquinaria() {
    fetch('../BLL/Maquinaria_BL.php?opc=listar',
        {
            method: 'POST',
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
            // let resultado = JSON.parse(data);
            document.getElementById("listadoHerramientas").innerHTML = data

        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, "listadoHerramientas");
        });
}

function BuscarMaquinariaPorCodigoEnter(event) {
    if (event.keyCode == 13)
        BuscarMaquinariaPorCodigoGetHtml();
}

function BuscarMaquinariaPorCodigoGetHtml() {
    $("#txtBuscaraquinariaTiempoReal").val("");
    document.getElementById("cboFiltroHerramienta").value = "0";
    let codigo = $("#txtCodigoMaquinariaBuscar").val();
    if (!StringIsNullOrEmpty(codigo))
        fetch('../BLL/Maquinaria_BL.php?opc=bucarPorCodigoGetHtml',
            {
                method: 'POST',
                body: JSON.stringify({
                    "codigo": codigo
                }),
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
                // let resultado = JSON.parse(data);
                document.getElementById("listadoHerramientas").innerHTML = data

            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, "listadoHerramientas");
            });
}

function BuscarMaquinariaPorCodigoGetJson(codigo) {
    let resultado;
    if (!StringIsNullOrEmpty(codigo))
        fetch('../BLL/Maquinaria_BL.php?opc=bucarPorCodigoGetJson&codigo=' + codigo,
            {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then((response) => {
                if (response.ok) {
                    return response.json();
                }
                else {
                    MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, "listadoHerramientas");
                    console.log(response);
                }
            })
            .then((data) => {
                resultado = data;
            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, "listadoHerramientas");
            });

    return resultado;
}

function BuscarMaquinariaEnTiempoReal() {
    document.getElementById("cboFiltroHerramienta").value = "0";
    $("#txtCodigoMaquinariaBuscar").val("");
    let valorConsulta = $("#txtBuscaraquinariaTiempoReal").val();
    if (!StringIsNullOrEmpty(valorConsulta)) {
        fetch('../BLL/Maquinaria_BL.php?opc=buscarTiempoReal',
            {
                method: 'POST',
                body: JSON.stringify({
                    "consulta": valorConsulta
                }),
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
                document.getElementById("listadoHerramientas").innerHTML = data

            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, "listadoHerramientas");
            });
    }

}

function VerFichaTecnica(codigo, idArchivo) {
    fetch('../BLL/Maquinaria_BL.php?opc=fichaTecnica&Id=' + idArchivo,
        {
            method: 'GET'
        })
        .then((response) => {
            if (response.ok) {
                return response.text();
            }
            else {
                MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, "idResultadoConsultarFichaTecnica");
                console.log(response);
            }
        })
        .then((data) => {

            $('#idVerPDF').attr('src', 'data:application/pdf;base64,' + data);
            $("#ModalFichaTecnica").modal()
        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, "idResultadoConsultarFichaTecnica");
        });
}

function MostrarTraslado() {
    $("#idInventarioMaquinaria").hide();
    $("#idTrasladarMaquinaria").show();
}

function OrdenarConsusltaMaquinaria() {
    $("#txtCodigoMaquinariaBuscar").val("");
    $("#txtBuscaraquinariaTiempoReal").val("");
    let opc = document.getElementById("cboFiltroHerramienta").value;
    if (opc != "0") {
        fetch('../BLL/Maquinaria_BL.php?opc=filtrarMaquinaria&filtro=' + opc,
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
                if (opc == "VerTotales") {
                    $("#tbl_total__tipo_herramientas").css("display", "inline-table")
                    $("#tbl_total_maquinaria").css("display", "none")
                    document.getElementById("listadoTotalTipoHerramientas").innerHTML = data
                }
                else {
                    $("#tbl_total__tipo_herramientas").css("display", "none")
                    $("#tbl_total_maquinaria").css("display", "inline-table")
                    document.getElementById("listadoHerramientas").innerHTML = data

                }

            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, "listadoHerramientas");
            });
    }
}
