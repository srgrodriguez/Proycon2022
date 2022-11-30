
function GenerarReporteTotalMaquinaria() {
    let txtBuscarTimpoReal = $("#txtBuscaraquinariaTiempoReal").val();
    let txtBuscarCodigo = $("#txtCodigoMaquinariaBuscar").val();
    let url = "../BLL/Reportes/ReportesExcelMaquinaria.php?opc=totalMaquinaria"
    let opcionBusqueda = "totalMaquinaria";
    if (txtBuscarTimpoReal != "") {
        url = "../BLL/Reportes/ReportesExcelMaquinaria.php?opc=totalMaquinaria&consulta=" + txtBuscarTimpoReal

    } else if (txtBuscarCodigo != "") {
        url = "../BLL/Reportes/ReportesExcelMaquinaria.php?opc=totalMaquinaria&codigo=" + txtBuscarCodigo
    }
    $("#ModalLoanding").modal();
    fetch(url,
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
            $('#btnCloseModalLoading').click();
            if (esJsonValido(data)) {
                let resultado = JSON.parse(data)
                var $a = $("<a>");
                $a.attr("href", resultado.file);
                $("body").append($a);
                $a.attr("download", "ReporteMaquinaria.xls");
                $a[0].click();
                $a.remove();
            }
        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, "idResultadoConsultarFichaTecnica");
        });

}

function GenerarReporteHistorial_Y_ReparacionesMaquinaria() {
    let codigo = $("#NombreHerramienta").html();
    fetch("../BLL/Reportes/ReportesExcelMaquinaria.php?opc=historialReparaciones&codigo=" + codigo,
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
            if (esJsonValido(data)) {
                let resultado = JSON.parse(data)
                var $a = $("<a>");
                $a.attr("href", resultado.file);
                $("body").append($a);
                $a.attr("download", "ReporteMaquinaria.xls");
                $a[0].click();
                $a.remove();
            }
        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, "idResultadoConsultarFichaTecnica");
        });

}

function GenerarReporteMaquinariaEnReparacion() {
    let tipoFiltro = "todo";
    let codigo = $("#CodHerramientaReparacion").val();
    let tipo = document.getElementById("cbofiltrotipo").value;
    if(document.getElementById("cbofiltrotipo").value != "0")
        tipoFiltro = "tipo";
    else if(codigo != "")
        tipoFiltro = "codigo";

    fetch("../BLL/Reportes/ReportesExcelMaquinaria.php?opc=maquinariaReparacion&codigo=" + codigo+"&tipo="+tipo+"&tipoFiltro="+tipoFiltro,
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
            if (esJsonValido(data)) {
                let resultado = JSON.parse(data)
                var $a = $("<a>");
                $a.attr("href", resultado.file);
                $("body").append($a);
                $a.attr("download", "ReporteMaquinaria.xls");
                $a[0].click();
                $a.remove();
            }
        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, "idResultadoConsultarFichaTecnica");
        });

}