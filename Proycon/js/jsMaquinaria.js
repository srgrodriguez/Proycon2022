// $(document).on('keyup', '#txtBuscaraquinariaTiempoReal', function () {
//     var valor = $(this).val();
//     if (valor != "") {
//         BuscarMaquinariaEnTiempoReal(valor);
//     }
// });

function AgregarMaquinaria() {

    let txtCodigoMaquinaria = $("#txtCodigoMaquinaria");
    let txtDescripcionMaquinaria = $("#txtDescripcionMaquinaria");
    let txtMarca = $("#txtMarca");
    let txtPrecio = $("#txtPrecio");
    let txtFechaRegistro = $("#txtFechaRegistro");
    let txtProcedencia = $("#txtProcedencia");
    let comboHerramientaTipo = document.getElementById("comboHerramientaTipo");
    let txtNumFactura = $("#txtNumFactura");
    let txtFile =document.getElementById("txtFileFichaTecnica");
    const formData = new FormData();
    // Agregamos cada archivo a "archivos[]". Los corchetes son importantes
    if(txtFile.files.length>0)
    formData.append("archivo", txtFile.files[0]);
    else 
    formData.append("archivo","");


          formData.append("codigo", txtCodigoMaquinaria.val());
          formData.append("tipo", comboHerramientaTipo.value);
          formData.append("marca", txtMarca.val());
          formData.append("descripcion", txtDescripcionMaquinaria.val());
          formData.append("fechaIngreso", txtFechaRegistro.val());
          formData.append("procedencia", txtProcedencia.val());
          formData.append("precio", txtPrecio.val());
          formData.append("numFactura", txtNumFactura.val());
          formData.append("archivo",txtFile.files[0]);
 
    if (StringIsNullOrEmpty(txtCodigoMaquinaria.val()) || StringIsNullOrEmpty(txtDescripcionMaquinaria.val()) ||
        comboHerramientaTipo.value == "0") {
        MostrarMensajeResultado("Debe completar los campos del fromulario", false, "respuestaAgregarMaquinaria")
    }
    else {
        fetch('../BLL/Maquinaria_BL.php?opc=agregar',
            {
                method: 'POST',
                body: formData
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
                if(esJsonValido(data))
                {
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
                }
                else{
                    MostrarMensajeResultado(data, false, "respuestaAgregarMaquinaria");
                }

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

function BuscarMaquinariaPorCodigoEnter(event)
{
    if(event.keyCode  == 13)
     BuscarMaquinariaPorCodigoGetHtml();
}

function BuscarMaquinariaPorCodigoGetHtml()
{
    let codigo = $("#txtCodigoMaquinariaBuscar").val();
    if(!StringIsNullOrEmpty(codigo))
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

function BuscarMaquinariaPorCodigoGetJson()
{
    let codigo = $("#txtCodigoMaquinariaBuscar").val();
    if(!StringIsNullOrEmpty(codigo))
        fetch('../BLL/Maquinaria_BL.php?opc=bucarPorCodigoGetJson',
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
            document.getElementById("listadoHerramientas").innerHTML = data

        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, "listadoHerramientas");
        });
}

$(BuscarMaquinariaEnTiempoReal())
function BuscarMaquinariaEnTiempoReal()
{
   let valorConsulta =$("#txtBuscaraquinariaTiempoReal").val();
   if(!StringIsNullOrEmpty(valorConsulta))
   {
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

function VerFichaTecnica(codigo,idArchivo)
{

    fetch('../BLL/Maquinaria_BL.php?opc=fichaTecnica&Id='+idArchivo,
        {
            method: 'GET'
        })
        .then((response) => {
            if (response.ok) {
                console.log(response)
                return response.text();
            }
            else {
                MostrarMensajeResultado("Ha ocurrido un error " + response.statusText, false, "listadoHerramientas");
                console.log(response);
            }
        })
        .then((data) => {
        console.log(data)
        const converted = toBinary(data);
         // var iframe =   "<iframe type='application/pdf'></iframe>";
        // $('#idVerPDF').attr('src','data:application/pdf;base64,'+btoa(unescape(encodeURIComponent(data ))));
          $('#idVerPDF').attr('src','data:application/pdf;base64,'+decodeURIComponent(escape(window.atob(data))) );
            //document.getElementById("listadoHerramientas").innerHTML = data

        })
        .catch((result) => {
            MostrarMensajeResultado(result, false, "listadoHerramientas");
        });
}


function toBinary(string) {
    const codeUnits = Uint16Array.from(
      { length: string.length },
      (element, index) => string.charCodeAt(index)
    );
    const charCodes = new Uint8Array(codeUnits.buffer);
  
    let result = "";
    charCodes.forEach((char) => {
      result += String.fromCharCode(char);
    });
    return result;
  }