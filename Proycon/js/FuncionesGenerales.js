function StringIsNullOrEmpty(value){

    return (!value || value == undefined || value == "" || value.length == 0);
}

function AceptarSoloNumerosMonto(evt) {
    evt = (evt) ? evt : event
    var key = (evt.which) ? evt.which : evt.keyCode;
    if (key > 47 && key < 58 || key == 8 || key == 9 || key == 46 || key == 44) { return true; }
    else { return false; }
  }

  function Formateo_Monto(arg1) {
    var argtemp = "";
    arg1.value = arg1.value.replace(new RegExp(",", "g"), "");
    for (i = 0; i <= arg1.value.length; i++)
      if (arg1.value.substr(i, 1) == ".") {
        if ((i) == (arg1.value.length - 1))
          arg1.value = arg1.value + "00";
        if ((i + 1) == (arg1.value.length - 1))
          arg1.value = arg1.value + "0";
        if ((i + 2) != (arg1.value.length - 1) || (arg1.value[i + 1] == "." || arg1.value[i + 2] == ".")) {
          arg1.value = "";
          return alert("El Monto debe llevar dos decimales.");
        }
      }
    if (arg1.value < 1 && arg1.value != "") {
      arg1.value = "";
      return alert("El monto no debe ser inferior a una unidad de la moneda utilizada.");
    }
    if (arg1.value.substr((arg1.value.length - 3), 1) != ".")
      arg1.value = arg1.value + ".00";
    argtemp = arg1.value.substr((arg1.value.length - 3), 3);
    ii = 0;
    for (i = (arg1.value.length - 4); i >= 0; i--) {
      argtemp = arg1.value.substr(i, 1) + argtemp;
      ii++;
      if ((ii == 3) && (i != 0)) {
        argtemp = "," + argtemp;
        ii = 0;
      }
    }
    arg1.value = argtemp;
  }

  function MostrarMensajeResultado(mensaje, esValido, idSeccionMostrarMensaje) {
    let resultadoHTML = "";
    if (esValido) {
        resultadoHTML = "<div id='mensajError' class='alert alert-success alert-dismissible show' role='alert'>" +
            "<strong></strong> " + mensaje +
            " <button id='btnCloseMensajeError' type='button' class='close' data-dismiss='alert' aria-label='Close'>"
            + " <span aria-hidden='true'>&times;</span>"
            + " </button>"
            + "</div>";
    }
    else {
        resultadoHTML = "<div id='mensajeCorrecto' class='alert alert-danger alert-dismissible show' role='alert'>" +
            "<strong>Error</strong> " + mensaje +
            " <button id='btnCloseMensajeCorrecto' type='button' class='close' data-dismiss='alert' aria-label='Close'>"
            + " <span aria-hidden='true'>&times;</span>"
            + " </button>"
            + "</div>";

    }

    document.getElementById(idSeccionMostrarMensaje).innerHTML = resultadoHTML;

    window.setTimeout(function(){
    //$("#"+idSeccionMostrarMensaje).hide('slow')
    $("#btnCloseMensajeError").click()
    $("#btnCloseMensajeCorrecto").click()
    }, 5000);
}
