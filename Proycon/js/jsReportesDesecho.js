function GenerarReporteDesecho() {

    let filtro= document.getElementById("cboFitrarDesecho").value;
    let url = "";

    if(filtro == "listarMaterial"){
        url = "../BLL/Reportes/ReportesExcelDesecho.php?opc=listarMaterial"
    }else if (filtro == "listarHeramienta"){
        url = "../BLL/Reportes/ReportesExcelDesecho.php?opc=listarHeramienta"
    }else {
        url = "../BLL/Reportes/ReportesExcelDesecho.php?opc=listar"
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
                $a.attr("download", "ReporteDesecho.xls");
                $a[0].click();
                $a.remove();
            }
        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, "idResultadoConsultarFichaTecnica");
        });

}