function AgregarMaquinaria() {

    let txtCodigoMaquinaria = $("#txtCodigoMaquinaria");
    let txtDescripcionMaquinaria = $("#txtDescripcionMaquinaria");
    let txtMarca = $("#txtMarca");
    let txtPrecio = $("#txtPrecio");
    let txtFechaRegistro = $("#txtFechaRegistro");
    let txtProcedencia = $("#txtProcedencia");
    let comboHerramientaTipo = document.getElementById("comboHerramientaTipo");
    let txtNumFactura = $("#txtNumFactura");

    if (StringIsNullOrEmpty(txtCodigoMaquinaria.val()) || StringIsNullOrEmpty(txtDescripcionMaquinaria.val()) ||
        comboHerramientaTipo.value == "0") {
        MostrarMensajeResultado("Debe completar los campos del fromulario", false, "respuestaAgregarMaquinaria")
    }
    else {
        fetch('../BLL/Maquinaria_BL.php?opc=agregar',
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({//Estos son los parametros que recibe el método
                    "codigo": txtCodigoMaquinaria.val(),
                    "tipo": comboHerramientaTipo.value,
                    "marca": txtMarca.val(),
                    "descripcion": txtDescripcionMaquinaria.val(),
                    "fechaIngreso": txtFechaRegistro.val(),
                    "procedencia": txtProcedencia.val(),
                    "precio": txtPrecio.val(),
                    "numFactura": txtNumFactura.val()
                })
            })
            .then((response) => {
                if (response.ok) {
                    return response.text();
                }
                else {
                    MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, "respuestaAgregarMaquinaria");
                    console.log(response);
                }
            })
            .then((data) => {
                let resultado = JSON.parse(data);
                console.log(resultado);
                MostrarMensajeResultado(resultado.mensaje, resultado.esValido, "respuestaAgregarMaquinaria");
                txtCodigoMaquinaria.val("");
                txtDescripcionMaquinaria.val("");
                txtFechaRegistro.val("");
                txtMarca.val("");
                txtNumFactura.val("");
                txtPrecio.val("");
                txtProcedencia.val("");
                comboHerramientaTipo.value = '0';
                ListarTotalMaquinaria();

            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, "respuestaAgregarMaquinaria");
            });

    }

}

function AbrirModalEliminarMaquinaria(codigo) {
    $('#IdCodigo').val(codigo);
    $('#ModalEliminarMaquinaria').modal('show');
}

function EliminarMaquinaria() {
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
                let resultado = JSON.parse(data);
                $("#txtMotivoEliminarMaquinria").val("");
                MostrarMensajeResultado(resultado.mensaje, resultado.esValido, "idResultadoEliminarMaquinaria");
                if (resultado.esValido)
                    ListarTotalMaquinaria()
            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, "idResultadoEliminarMaquinaria");
            });
    }
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
