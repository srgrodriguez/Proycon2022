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

  function FormatearMonto(value) {
    var argtemp = "";
    value= value.toString()
    value = value.replace(new RegExp(",", "g"), "");
    for (i = 0; i <= value.length; i++)
      if (value.substr(i, 1) == ".") {
        if ((i) == (value.length - 1))
          value = value + "00";
        if ((i + 1) == (value.length - 1))
          value = value + "0";
        if ((i + 2) != (value.length - 1) || (value[i + 1] == "." || value[i + 2] == ".")) {
          value = "";
          return alert("El Monto debe llevar dos decimales.");
        }
      }
    if (value < 1 && value != "") {
      value = "";
      return alert("El monto no debe ser inferior a una unidad de la moneda utilizada.");
    }
    if (value.substr((value.length - 3), 1) != ".")
      value = value + ".00";
    argtemp = value.substr((value.length - 3), 3);
    ii = 0;
    for (i = (value.length - 4); i >= 0; i--) {
      argtemp = value.substr(i, 1) + argtemp;
      ii++;
      if ((ii == 3) && (i != 0)) {
        argtemp = "," + argtemp;
        ii = 0;
      }
    }
    return argtemp;
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

function  esJsonValido(str) {
  try {
      return (JSON.parse(str) && !!str);
  } catch (e) {
      return false;
  }
}

function ValidarArchivo(event) {
  let constancia = document.getElementById("BNCRMP_cphContenidoPagina_fluCargarCostancia");
  const mensajeArchivoNoSeleccionado = "Nigún archivo seleccionado";
  let lblMesaje = document.getElementById("BNCRMP_cphContenidoPagina_lblMensaje");
  let sizeByte = event.files[0].size;
  let fileName = event.files[0].name;

  let siezeMegaByte = (sizeByte / (1024 * 1024));
  console.log(sizeByte)
  console.log(siezeMegaByte)
  let extencion = fileName.split('.').pop();
  extencion = extencion.toLowerCase();
  let extencionValida = true;
  switch (extencion) {
    case 'jpg':
    case 'pdf': break;
    default:
      extencionValida = false;
  }
  //Si el archivo pesa más de 10 megas o la extencion es invalidad 
  if (siezeMegaByte > 10 || !extencionValida) {
    var clone = event.cloneNode();
    clone.value = '';
    event.parentNode.replaceChild(clone, event);
    lblMesaje.innerHTML = "Formato PDF o imagen JPG, pueden ser firmados digitalmente o escaneados, tamaño máximo 10mb";
    lblMesaje.focus();
    if (constancia.id == event.id) {
      document.getElementById("BNCRMP_cphContenidoPagina_lblAdjuntarConstancia").innerHTML = mensajeArchivoNoSeleccionado;
    }
  }
  else {
    lblMesaje.innerHTML = "";
    if (constancia.id == event.id) {
      document.getElementById("BNCRMP_cphContenidoPagina_lblAdjuntarConstancia").innerHTML = fileName;
    }
  }      
}
