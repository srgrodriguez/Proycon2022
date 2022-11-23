
function listarTipoHerramientas(tipoEquipo) {

    fetch('../BLL/TipoHerramienta_BL.php?opc=listar',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({//Estos son los parametros que recibe el método
                "tipoEquipo": tipoEquipo
            })
        })
        .then((response) => {
            if (response.ok) {
                return response.text();
            }
            else {
                MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, "respuestaTipoHerramienta");
                console.log(response);
            }
        })
        .then((data) => {
            //Si la respuesta es correcta se muestra el resultado
            document.getElementById("listadoTipoHerramientas").innerHTML = data;
        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, "respuestaTipoHerramienta");
        });
}

function EliminarTipoHerramienta(codigo, tipoEquipo) {

    if (confirm("¿Está seguro que desea eliminar este tipo de maquinaria?") == true)
        fetch('../BLL/TipoHerramienta_BL.php?opc=eliminar',
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({//Estos son los parametros que recibe el método
                    "id": codigo
                })
            })
            .then((response) => {
                if (response.ok) {
                    return response.text();
                }
                else {
                    MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, "respuestaTipoHerramienta");
                    console.log(response);
                }
            })
            .then((data) => {
                if (esJsonValido(data)) {
                    let resultado = JSON.parse(data);
                    listarTipoHerramientas(tipoEquipo);

                    MostrarMensajeResultado(resultado.mensaje, resultado.esValido, "respuestaTipoHerramienta");
                }
                else
                    MostrarMensajeResultado(result, false, "respuestaTipoHerramienta");

            })
            .catch((result) => {
                MostrarMensajeResultado(data, false, "respuestaTipoHerramienta");
            });
}

function AgregarNuevoTipo(tipoEquipo) {
    let nombreTipo = $("#txtnombreTipoMaquinaria").val();
    let precio = $("#txtPrecioAlquiler").val();
    let moneda = document.getElementById("cboMoneda").value;
    let formaPago = document.getElementById("cboFormaCobro").value;
    if (StringIsNullOrEmpty(nombreTipo) || StringIsNullOrEmpty(precio) || moneda == "0" || formaPago == "0") {
        MostrarMensajeResultado("Todos los campos son requeridos", false, "respuestaTipoHerramienta")
    }
    else {
        fetch('../BLL/TipoHerramienta_BL.php?opc=agregar',
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({//Estos son los parametros que recibe el método
                    "descripcion": nombreTipo,
                    "precio": precio,
                    "monedaCobro": moneda,
                    "codigoFormadeCobro": formaPago,
                    "tipoEquipo": tipoEquipo
                })
            })
            .then((response) => {
                if (response.ok) {
                    return response.text();
                }
                else {
                    MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, "respuestaTipoHerramienta");
                    console.log(response);
                }
            })
            .then((data) => {
                let resultado = JSON.parse(data);
                MostrarMensajeResultado(resultado.mensaje, resultado.esValido, "respuestaTipoHerramienta");
                $("#txtnombreTipoMaquinaria").val("");
                $("#txtPrecioAlquiler").val("");
                document.getElementById("cboMoneda").value = '0';
                document.getElementById("cboFormaCobro").value = '0';
                listarTipoHerramientas(tipoEquipo);

            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, "respuestaTipoHerramienta");
            });

    }

}

function EditarTipo(id) {
    btnEditar = document.getElementById('btnEditar');
    btnEditar.disabled = false;
    ConsultarTipoHerramientaPorID(id);
}

function ActualizarTipo() {
    btnEditar = document.getElementById('btnEditar');
    let nombreTipo = $("#txtnombreTipoMaquinaria").val();
    let precio = $("#txtPrecioAlquiler").val();
    let id = $("#IdTipoHerramienta").val();
    let tipoEquipo = $("#IdTipoEquipo").val();
    let moneda = document.getElementById("cboMoneda").value;
    let formaPago = document.getElementById("cboFormaCobro").value;
    if (StringIsNullOrEmpty(nombreTipo) || StringIsNullOrEmpty(precio) || moneda == "0" || formaPago == "0") {
        MostrarMensajeResultado("Todos los campos son requeridos", false, "respuestaTipoHerramienta")
    }
    else {
        fetch('../BLL/TipoHerramienta_BL.php?opc=actualizar',
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({//Estos son los parametros que recibe el método
                    "descripcion": nombreTipo,
                    "precio": precio,
                    "monedaCobro": moneda,
                    "codigoFormadeCobro": formaPago,
                    "tipoEquipo": 'M',
                    "id": id
                })
            })
            .then((response) => {
                if (response.ok) {
                    return response.text();
                }
                else {
                    MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, "respuestaTipoHerramienta");
                    console.log(response);
                }
            })
            .then((data) => {
                if (esJsonValido(data)) {
                    let resultado = JSON.parse(data);
                    MostrarMensajeResultado(resultado.mensaje, resultado.esValido, "respuestaTipoHerramienta");
                    $("#txtnombreTipoMaquinaria").val("");
                    $("#txtPrecioAlquiler").val("");
                    $("#IdTipoHerramienta").val("");
                    $("#IdTipoEquipo").val("")
                    document.getElementById("cboMoneda").value = '0';
                    document.getElementById("cboFormaCobro").value = '0';
                    listarTipoHerramientas(tipoEquipo);
                    btnEditar.disabled = true;
                }
                else {
                    MostrarMensajeResultado(data, false, "respuestaTipoHerramienta");
                }

            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, "respuestaTipoHerramienta");
            });

    }


}

function ConsultarTipoHerramientaPorID(id) {
    fetch('../BLL/TipoHerramienta_BL.php?opc=consultar',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({//Estos son los parametros que recibe el método
                "id": id
            })
        })
        .then((response) => {
            if (response.ok) {
                return response.text();
            }
            else {
                MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, "respuestaTipoHerramienta");
                console.log(response);
            }
        })
        .then((data) => {
            if (esJsonValido(data)) {
                let resultado = JSON.parse(data);
                $("#IdTipoHerramienta").val(id)
                $("#txtnombreTipoMaquinaria").val(resultado.Descripcion);
                $("#txtPrecioAlquiler").val(resultado.PrecioEquipo);
                $("#IdTipoEquipo").val(resultado.TipoEquipo);
                document.getElementById("cboMoneda").value = resultado.CodigoMonedaCobro;
                document.getElementById("cboFormaCobro").value = resultado.CodigoFormaCobro;
            }
            else {
                MostrarMensajeResultado(result, false, "respuestaTipoHerramienta");
            }
        })
        .catch((result) => {
            MostrarMensajeResultado(data, false, "respuestaTipoHerramienta");
        });
}
