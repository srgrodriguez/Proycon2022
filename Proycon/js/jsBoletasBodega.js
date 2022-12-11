

function VerPedido(evento) {
    var TipoPedido = $(evento).parents("tr").find("td").eq(0).html();
    var NBoleta = $(evento).parents("tr").find("td").eq(1).html();
    var fecha = $(evento).parents("tr").find("td").eq(2).html();
    var nombre = $(evento).parents("tr").find("td").eq(3).html();
    var array = fecha.split("-");
    $("#dia").html(array[2]);
    $("#mes").html(array[1]);
    $("#anno").html(array[0]);
    $("#tipoPedidoSeleccionado").html(TipoPedido);
    $("#nomProyectoPedidoSelecionado").html($("#nomProyecto").html());
    $("#generadaPor").html(nombre);
    if (TipoPedido == 1) {
        $("#TipoPedido").html("Materiales");

        $("#btnAnularBoletaMaterial").css("display","inline-block");
        $("#btnAnularBoletaHerramientas").css("display","none");
        $("#btnAnularBoletaMaquinaria").css("display","none");
        
    } else if (TipoPedido == 2) {

        $("#TipoPedido").html("Herramienta");
        $("#btnAnularBoletaMaterial").css("display","none");
        $("#btnAnularBoletaHerramientas").css("display","inline-block");
        $("#btnAnularBoletaMaquinaria").css("display","none");
    }else if (TipoPedido == 3) {
        $("#TipoPedido").html("Maquinaria");
        $("#btnAnularBoletaMaquinaria").css("display","inline-block");
        $("#btnAnularBoletaMaterial").css("display","none");
        $("#btnAnularBoletaHerramientas").css("display","none");
    }

    $.ajax({
        url: "../BLL/Proyectos.php?opc=verpedido&NBoleta=" + NBoleta + "&TipoPedido=" + TipoPedido,
        success: function (respuesta) {
            $("#consecutivoPedidoSeleccionado").html(NBoleta);
            $("#ContenidoPedido_Selecionado").html(respuesta);
            $("#ModalVerPedido").modal("show");
        }
    });
}

function Exportar_Pdf(idTabla) {
    //alert("Entro");
    var imgData = "data:image/png;base64,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";
    var Npedido = $("#consecutivoPedidoSeleccionado").html();
    var dia = $("#tblFecha tbody tr td").eq(0).html();
    var mes = $("#tblFecha tbody tr td").eq(1).html();
    var anno = $("#tblFecha tbody tr td").eq(2).html();
    var Proyecto = $("#nomProyectoPedidoSelecionado").html();
    var TipoBoleta = $("#TipoPedido").html();
    var generadaPor = $("#generadaPor").html();
    var pdf = new jsPDF('p', 'pt', 'letter');
    pdf.addImage(imgData, 'JPEG', 30, 50, 100, 100);
    pdf.setTextColor(255, 0, 0);
    pdf.setFontSize(22);
    pdf.text(400, 110, 'Boleta Nº ' + Npedido);
    pdf.setFontSize(18);
    pdf.setTextColor(0);
    pdf.text(400, 150, 'Fecha: ' + dia + "/" + mes + "/" + anno);
    pdf.setTextColor(0);
    pdf.text(30, 180, Proyecto);
    pdf.text(30, 210, "Boleta de: " + TipoBoleta);
    pdf.text(30, 240, "Generada Por: " + generadaPor);
    pdf.setLineWidth(1);
    pdf.line(20, 260, 600, 260);
    
//pdf.save("Pedido"+'.pdf');


    var htmlBody = $("#"+idTabla).html();
    var margins = {top: 270, bottom: 20, left: 70, width: 100};

    pdf.fromHTML(htmlBody, margins.left, margins.top, {'width': margins.width}, function (dispose) {
        pdf.save("Pedido" + '.pdf');
    }, margins);
    //pdf.create()
}


function ActulizarSeccionPedidos() {
    var ID_Proyecto = 1;

    $.ajax({
        type: "POST",
        url: '../BLL/BoletasBodega_BL.php?opc=listarPedidos&ID_Proyecto=' + ID_Proyecto + '&tipo=' + $("#cboPedidos").val(),
       beforeSend: function () {
               $("#contienePedidos").html("<center>Cargando... <img src='../resources/imagenes/loanding.gif'  width='100px'/></center>");
                },
        success: function (respuesta) {
            $("#contienePedidos").html(respuesta);
            /*  if($("#contienePedidos").height()>500){
             
             $("#contienePedidos").css("overflow","scroll"); 
             }*/
        }

    });
}


function BuscarBoletaPedido(){
    if ($("#txtBoletaPedido").val() != "") {

        if (isNaN($("#txtBoletaPedido").val())) {
            return;
        }

        $.ajax({
                    url: "../BLL/BoletasBodega_BL.php?opc=buscarboleta&numBoleta="+$("#txtBoletaPedido").val()+"&idProyecto=1",
                    success: function (respuesta) {
                            $("#contienePedidos").html(respuesta);
                    }
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 0) {

                        alert('No nos pudimos Conectar con el sevidor Verifique su conexion a Internet ');

                    } else if (jqXHR.status == 404) {

                        alert('Error [404] No se encontro el Archivo');

                    } else if (jqXHR.status == 500) {

                        alert('Error de conexion con el servidor');

                    }

                }); 
         }
         
}