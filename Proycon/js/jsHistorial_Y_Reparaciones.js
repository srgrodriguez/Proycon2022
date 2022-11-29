function ConsultarHistoria_Y_ReparacionesMaquinaria() {
    ConsultarHistorialMaquinaria();
    ConsultarReparacionesMaquinaria();
    ConsultarMaquinariaPorCodigo();
    $("#equipoEnReparacion").hide()
    $("#historialGastos").show();
}

function ConsultarHistorialMaquinaria() {
    const idMostrarMensajes = "idResultados";
    let codigo = $("#txtCodigoMaquinaria").val()
    if (codigo != "")
        fetch('../BLL/Historia_Y_ReparacionesMaquinaria_BL.php?opc=getHistorial&codigo=' + codigo,
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
                document.getElementById("tblHistorialMaquinaria").innerHTML = data;

            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, idMostrarMensajes);
            })

}

function ConsultarReparacionesMaquinaria() {
    const idMostrarMensajes = "idResultados";
    let codigo = $("#txtCodigoMaquinaria").val()
    if (codigo != "")
        fetch('../BLL/Historia_Y_ReparacionesMaquinaria_BL.php?opc=getReparaciones&codigo=' + codigo,
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
                document.getElementById("tablareparacionestotales").innerHTML = data;

            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, idMostrarMensajes);
            })

}

function ConsultarMaquinariaPorCodigo() {
    const idMostrarMensajes = "idResultados";
    let codigo = $("#txtCodigoMaquinaria").val()
    if (codigo != "")
        fetch('../BLL/Historia_Y_ReparacionesMaquinaria_BL.php?opc=getObtenerInfoMaquinaria&codigo=' + codigo,
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
                if (esJsonValido(data)) {
                    let resultado = JSON.parse(data);
                    let monedaCobro = resultado.CodigoMonedaCobro == "C" ? "¢" : "$";
                    let monedaCompra = resultado.MonedaCompra == "C" ? "¢" : "$";
                    $("#NombreHerramienta").html(resultado.Codigo);
                    $("#FechaAdquisicion").html(resultado.FechaIngreso);
                    $("#HerramientaMarca").html(resultado.Marca);
                    $("#ProcedenciaHerramienta").html(resultado.Procedencia);
                    $("#DescripcionHerramienta").html(resultado.Tipo);
                    $("#numFactHerramienta").html(resultado.NumFactura);
                    $("#precioHerramienta").html(monedaCompra + FormatearMonto(resultado.Precio));
                    $("#txtPrecioAlquiler").html(monedaCobro + FormatearMonto(resultado.PrecioEquipo))
                }

            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, idMostrarMensajes);
            })
}

let listaEquipoReparar = [];
async function ObtenerMaquinariaEnviarReparacion() {
    const idMostrarMensajes = "idResultadoEnviarReparacion";
    let codigo = $("#txtCodigoBuscarEnviarReparacion").val()
    let estaRepacion = await EstaEnReparacion(codigo);
    if (codigo != "") {
        const existe = listaEquipoReparar.find(element => element == codigo);
        if (existe == undefined) {
            if (!estaRepacion) {
                fetch('../BLL/Historia_Y_ReparacionesMaquinaria_BL.php?opc=getObtenerInfoMaquinaria&codigo=' + codigo,
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
                        if (esJsonValido(data)) {
                            let resultado = JSON.parse(data);
                            let generarHTML = "<tr>" +
                                "<td>" + resultado.Codigo + "</td>" +
                                "<td>" + resultado.Tipo + "</td>" +
                                "<td>" + resultado.Marca + "</td>" +
                                "<td>" + resultado.Estado + "</td>" +
                                "<td>" + resultado.Ubicacion + "</td>" +
                                "<td style='text-align: center'>" +
                                "<button onclick='RemoverEquipoBoletaReparacion(this)'>" +
                                "<img src='../resources/imagenes/remove.png' width='15px' alt=''>"
                            "</button>" +
                                "</td>"
                                + "</tr>";
                            listaEquipoReparar.push(resultado.Codigo)
                            $("#ContenidoReparaciones").append(generarHTML);
                            $("#txtCodigoBuscarEnviarReparacion").val("");
                        }
                        else {
                            MostrarMensajeResultado(data, false, idMostrarMensajes);
                        }

                    })
                    .catch((result) => {
                        MostrarMensajeResultado(result, false, idMostrarMensajes);
                    })
            }
            else {
                MostrarMensajeResultado("El equipo ya se encuentra en reparación", false, idMostrarMensajes);
            }
        }
        else {
            MostrarMensajeResultado("El equipo ya agregado en la boleta", false, idMostrarMensajes);
        }
    }
}

function RemoverEquipoBoletaReparacion(event) {
    var codigo = $(event).parents("tr").find("td").eq(0).html();
    let posicion = listaEquipoReparar.indexOf(codigo)
    listaEquipoReparar.splice(posicion, 1)
    $(event).parents("tr").remove();
}

function LimpiarBoletaReparacion() {
    listaEquipoReparar = [];
    document.getElementById("ContenidoReparaciones").innerHTML = "";
    $("#provedorReparacion").val("");
}

async function EnviarMaquinariaReparacion() {
    const idMostrarMensajes = "idResultadoEnviarReparacion";
    btn = $("#btnEnviarReparacion");
    btn.prop('disabled', true);
    let proveedor = $("#provedorReparacion").val();
    if (proveedor == "" || listaEquipoReparar.length == 0)
        MostrarMensajeResultado("Debe completar la boleta", false, idMostrarMensajes);
    else {
        let request = new Object();
        request.proveedor = proveedor;
        request.datosReparacion = listaEquipoReparar;
        await fetch('../BLL/Historia_Y_ReparacionesMaquinaria_BL.php?opc=enviarReparacion',
            {
                method: 'POST',
                body: JSON.stringify(request),
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
                if (esJsonValido(data)) {
                    let resultado = JSON.parse(data);
                    MostrarMensajeResultado(resultado.mensaje, resultado.esValido, idMostrarMensajes);
                    LimpiarBoletaReparacion();
                    ConsultarTodaMaquinariaReparacion();
                }

            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, idMostrarMensajes);
            })
    }
    btn.prop('disabled', false);

}

async function EstaEnReparacion(codigo) {
    let estaEnReparacion = false;
    await fetch('../BLL/Historia_Y_ReparacionesMaquinaria_BL.php?opc=estaEnReparacion&codigo=' + codigo,
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
            estaEnReparacion = data;
        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, idMostrarMensajes);
        })

    return estaEnReparacion;
}

function AbrirMondalDatosReparacion(evento) {
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

async function FacturacionReparacionMaquinaria() {
    btn = $("#btnRegistrarDatosFacturarReparacion");
    btn.prop('disabled', true);
    const idMostrarMensajes = "idResultadoFacturarReparacion";
    var request = new Object();
    request.Codigo = $("#CodHerramienta").html();
    request.ID_Reparacion = $("#NumReparacion").html();
    request.NumFactura = $("#txtNunFactura").val();
    request.FechaEntrada = $("#txtFechaFactura").val();
    request.DescripcionFactura = $("#txtDescripcionFactura").val();
    request.CostoFactura = $("#txtCantidadFactura").val();
    request.NumBoleta = $("#NumBoletaF").html();
    if (request.Codigo == "" || request.NumFactura == "" || request.FechaEntrada == "" || request.DescripcionFactura == "" || request.CostoFactura == "")
        MostrarMensajeResultado("Se deben completar todos los campos", false, idMostrarMensajes);
    else {
        await fetch('../BLL/Historia_Y_ReparacionesMaquinaria_BL.php?opc=facturarReparacion',
            {
                method: 'POST',
                body: JSON.stringify(request),
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
                if (esJsonValido(data)) {
                    let resultado = JSON.parse(data);
                    MostrarMensajeResultado(resultado.mensaje, resultado.esValido, idMostrarMensajes);
                    ConsultarTodaMaquinariaReparacion();
                    $("#CodHerramienta").html("");
                    $("#NumReparacion").html("");
                    $("#txtNunFactura").val("");
                    $("#txtFechaFactura").val("");
                    $("#txtDescripcionFactura").val("");
                    $("#txtCantidadFactura").val("");
                    $("#NumBoletaF").html("");
                }
            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, idMostrarMensajes);
            });


    }
    btn.prop('disabled', false);

}

function ConsultarTodaMaquinariaReparacion() {
    fetch('../BLL/Historia_Y_ReparacionesMaquinaria_BL.php?opc=consultarReparacion',
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
            document.getElementById("HerramientasEnReparacion").innerHTML = data;
        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, idMostrarMensajes);
        });

}

function ConsultarMaquinariaReparacionPorCodigo() {
    let codigo = $("#CodHerramientaReparacion").val();
    if (codigo != "")
        fetch('../BLL/Historia_Y_ReparacionesMaquinaria_BL.php?opc=consultarReparacionPorCodigo&codigo=' + codigo,
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
                document.getElementById("HerramientasEnReparacion").innerHTML = data;
            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, idMostrarMensajes);
            });

}

function ConsultarMaquinariaReparacionPorTipo() {
    let idTipo = document.getElementById("cbofiltrotipo").value;
    if (idTipo != "0")
        fetch('../BLL/Historia_Y_ReparacionesMaquinaria_BL.php?opc=filtrarPorTipoMaquinaria&idTipo=' + idTipo,
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
                document.getElementById("HerramientasEnReparacion").innerHTML = data;
            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, idMostrarMensajes);
            });
    else {
        ConsultarTodaMaquinariaReparacion();
    }

}

function ConsultarTodasLasBoletasReparacionMaquinaria() {

    fetch('../BLL/Historia_Y_ReparacionesMaquinaria_BL.php?opc=consultarBoletasReparacion',
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
            document.getElementById("mostrarBoletasReparacion").innerHTML = data;
        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, idMostrarMensajes);
        });
}

function ConsultarTodasLasBoletasReparacionMaquinariaPorNumBoleta() {
    let numBoleta = document.getElementById("txtNumBoletaBuscar").value;
    if (numBoleta != "")
        fetch('../BLL/Historia_Y_ReparacionesMaquinaria_BL.php?opc=consultarBoletasReparacionPorNumBoleta&numBoleta=' + numBoleta,
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
                document.getElementById("mostrarBoletasReparacion").innerHTML = data;
            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, idMostrarMensajes);
            });

}

function VerBoletaReparacion(evento) {
    const idMostrarMensajes = "mensajesResultadoBoletaReparacion";
    let numBoleta = $(evento).parents("tr").find("td").eq(0).html();
    let fecha = $(evento).parents("tr").find("td").eq(1).html();
    let Usuario = $(evento).parents("tr").find("td").eq(2).html();
    $("#consecutivoBoletaReparacionSeleccionado").html(numBoleta);
    let arregloFecha = fecha.split("/");
    $("#dia").html(arregloFecha[2]);
    $("#mes").html(arregloFecha[1]);
    $("#anno").html(arregloFecha[0]);
    $("#generadaPorBoletaReparacion").html(Usuario);
    $("#ModalVerBoletaReparacion").modal("show");
    if (numBoleta != "")
        fetch('../BLL/Historia_Y_ReparacionesMaquinaria_BL.php?opc=VerBoletaReparacion&numBoleta=' + numBoleta,
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
                document.getElementById("contenidoBoletaReparacion").innerHTML = data;
            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, idMostrarMensajes);
            });

}

function AnularBoletaReparacion() {
    const idMostrarMensajes = "mensajesResultadoBoletaReparacion";
    let numBoleta = $("#consecutivoBoletaReparacionSeleccionado").html();

    if (numBoleta != "")
        fetch('../BLL/Historia_Y_ReparacionesMaquinaria_BL.php?opc=AnularBoletaReparacion&numBoleta=' + numBoleta,
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
                if (esJsonValido(data)) {
                    let resultado = JSON.parse(data);
                    MostrarMensajeResultado(resultado.mensaje, resultado.esValido, idMostrarMensajes);
                }
                else {
                    MostrarMensajeResultado(data, false, idMostrarMensajes);
                }
            })
            .catch((result) => {
                MostrarMensajeResultado(result, false, idMostrarMensajes);
            });

}

function MostraBoletasReparaciones() {
    $("#equipoEnReparacion").hide()
    $("#historialGastos").hide();
    $("#boletasReparacionMaquinaria").show();

}

function Atras() {
    $("#BoletaReparacionHerramienta").is(":visible")

    let reparacion = $("#equipoEnReparacion");
    let gastos = $("#historialGastos");
    let boletas = $("#boletasReparacionMaquinaria");
    if (boletas.is(":visible")) {
        reparacion.show();
        gastos.hide();
        boletas.hide();
    }
    else if (gastos.is(":visible"))
      { 
        reparacion.show();
        boletas.hide();
        gastos.hide();
      }
    else if (reparacion.is(":visible"))
        window.location.href = 'Maquinaria.php'
    else {
        window.location.href = 'Maquinaria.php'
    }

}