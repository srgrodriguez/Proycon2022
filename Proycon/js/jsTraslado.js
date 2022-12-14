let listaEquipoTrasladar = [];

function ConsultarEquipoTrasladar(tipoFiltro, tipoEquipo = "M") {
    let codigo = "";
    let idTipo = "";
    let ubicacion = "";
    if (tipoFiltro == "codigo")
        codigo = $("#txtTrasladoCodigo").val()
    else if (tipoFiltro == "tipo")
        idTipo = document.getElementById("cboTipoMaquinariaTraslado").value;
    else if (tipoFiltro == "ubicacion")
        ubicacion = document.getElementById("cboUbicacionTraslado").value;

    fetch('../BLL/TrasladarEquipo_BL.php?opc=consultarEquipo',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({//Estos son los parametros que recibe el método
                "codigo": codigo,
                "idTipo": idTipo,
                "ubicacion": ubicacion,
                "tipoEquipo": tipoEquipo
            })
        })
        .then((response) => {
            if (response.ok) {
                return response.text();
            }
            else {
                MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, "listadoTranslado");
                console.log(response);
            }
        })
        .then((data) => {

            document.getElementById("listadoTranslado").innerHTML = data;
        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, "listadoTranslado");
        });
}

function SeleccionarEquipoTrasladar(event) {
    let equipo = new Object()
    var Codigo = $(event).parents("tr").find("td").eq(0).html();
    var Tipo = $(event).parents("tr").find("td").eq(1).html();
    var Ubicacion = $(event).parents("tr").find("td").eq(2).html();
    equipo.codigo = Codigo;
    equipo.tipo = Tipo;
    equipo.ubicacion = Ubicacion;
    if ($(event).is(":checked")) {
        if (listaEquipoTrasladar.length > 0) {
            let existeElemento = listaEquipoTrasladar.find(x => x.codigo == Codigo);
            if (existeElemento) {
                alert("El equipo ya fue seleccionado")
            }
            else {
                $(event).parents("tr").find("td").addClass('trasladoT');
                listaEquipoTrasladar.push(equipo);
            }
        }
        else {
            $(event).parents("tr").find("td").addClass('trasladoT');
            listaEquipoTrasladar.push(equipo);
        }

    }
    else {
        $(event).parents("tr").find("td").removeClass('trasladoT');

        let posicion = listaEquipoTrasladar.indexOf(equipo)
        listaEquipoTrasladar.splice(posicion, 1)
    }
}

function MostrarBoletaTraslado() {
    let generarHtml = '';
    let btnEliminar = "<button onclick=RemoverEquipoBoleta(this)>" +
        "<img src='../resources/imagenes/remove.png' width='15px' alt=''/>" +
        "</button>";
    for (let index = 0; index < listaEquipoTrasladar.length; index++) {
        const equipo = listaEquipoTrasladar[index];
        generarHtml += "<tr>" +
            "<td>" + equipo.codigo + "</td>" +
            "<td>" + equipo.tipo + "</td>" +
            "<td>" + equipo.ubicacion + "</td>" +
            "<td>" + btnEliminar + "</td>" +
            "</tr>";
    }
    document.getElementById("tablaMostrarTraslado").innerHTML = generarHtml;
    $("#ModalTranslado").modal();
}

function RemoverEquipoBoleta(event) {

    let equipo = new Object()
    var Codigo = $(event).parents("tr").find("td").eq(0).html();
    var Tipo = $(event).parents("tr").find("td").eq(1).html();
    var Ubicacion = $(event).parents("tr").find("td").eq(2).html();
    equipo.codigo = Codigo;
    equipo.tipo = Tipo;
    equipo.ubicacion = Ubicacion;
    let posicion = listaEquipoTrasladar.indexOf(equipo)
    listaEquipoTrasladar.splice(posicion, 1)
    $(event).parents("tr").remove();
}

async function TrasladarEquipo() {
    const btnGuardar = $("#btnGuardarTraslado");
    btnGuardar.prop('disabled', true);
    const idMostrarMensajes = "mensajesResultadoTraslado";
    let destino = document.getElementById("cboProyectoDestino").value.trim()
    if (destino == "0")
        MostrarMensajeResultado("Debe seleccionar un destino", false, idMostrarMensajes)
    else if (listaEquipoTrasladar.length == 0)
        MostrarMensajeResultado("No ha seleccionado equipo para trasladar", false, idMostrarMensajes)
    else {
        let cboProyectoDestino = document.getElementById("cboProyectoDestino");
        let nombreProyectoDestino = cboProyectoDestino.options[cboProyectoDestino.selectedIndex].text;
        listaEquipoTrasladarActualizada = [];
        let equiposConUbicacionYDestinoIgual = "";
        for (let index = 0; index < listaEquipoTrasladar.length; index++) {
            const equipo = listaEquipoTrasladar[index];
            equipo.idUbicacionDestino = destino;
            if (equipo.ubicacion == nombreProyectoDestino) {
                equiposConUbicacionYDestinoIgual += ", " + equipo.codigo;
            }
            else
                listaEquipoTrasladarActualizada.push(equipo);
        }



        if (equiposConUbicacionYDestinoIgual == "") {
            listaEquipoTrasladar = [];
            await fetch('../BLL/TrasladarEquipo_BL.php?opc=trasladar',
                {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(listaEquipoTrasladarActualizada)
                })
                .then((response) => {
                    if (response.ok) {
                        return response.text();
                    }
                    else {
                        MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, idMostrarMensajes);
                        console.log(response);
                    }
                })
                .then((data) => {
                    if (esJsonValido(data)) {
                        let resultado = JSON.parse(data);
                        MostrarMensajeResultado(resultado.mensaje, resultado.esValido, idMostrarMensajes);
                        document.getElementById("listadoTranslado").innerHTML = "";
                        document.getElementById("tablaMostrarTraslado").innerHTML = "";
                        ObtenerConsecutivoBoleta();
                    }
                    else
                        MostrarMensajeResultado(data, false, idMostrarMensajes);
                })
                .catch((result) => {
                    MostrarMensajeResultado(result, false, idMostrarMensajes);
                })
        }
        else {
            MostrarMensajeResultado("Los siguientes equipos se estan traladando al mismo proyecto donde esta ubicados "
                + equiposConUbicacionYDestinoIgual,
                false, idMostrarMensajes);
        }
    }
    btnGuardar.prop('disabled', false);
}


async function ObtenerConsecutivoBoleta() {
    const idMostrarMensajes = "mensajesResultadoTraslado";
    await fetch('../BLL/TrasladarEquipo_BL.php?opc=consecutivoBoleta',
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
                MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, idMostrarMensajes);
                console.log(response);
            }
        })
        .then((data) => {
            document.getElementById("ConsecutivoPedidoHerramientaF").innerHTML = data;
        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, idMostrarMensajes);
        })
}
