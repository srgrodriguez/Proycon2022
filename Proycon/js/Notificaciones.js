/*
 * Este script contiene el codigo de las notificaciones en tiempo real para los usuarios
 * de bodega y Proveeduria
 */
$(document).ready(function () {
    setInterval(NotificarUsuarioBodega, 120000);//Se consulta cada 2 minutos
});



function NotificarUsuarioBodega() {
    if (EsProduccion())
        $.ajax({
            url: "..//BLL/Notificaciones.php?opc=notificarBodega",
            type: "POST",
            success: function (respuesta) {
                var json = JSON.parse(respuesta);
                // console.log(json);
                var resp = 0;
                $.each(json, function (i, item) {
                    resp = (item.numPedio * 1) + resp;
                    $("#" + item.ID_Proyecto).css("color", "red");
                    // console.log(item.ID_Proyecto);

                });

                if (resp > 0) {
                    Push.create("Nueva Solicitud de pedidos " + resp, {
                        body: "Tienes un nuevo pedido",
                        icon: "../resources/imagenes/ad.png",
                        timeout: 9000,
                        onClick: function () {
                            window.location = "Proyectos.php";
                            this.close();
                        }
                    });
                }
            }
        });
}

function EsProduccion() {
    var URLdomain = window.location.host
    return URLdomain != "localhost" ? true : false;
}

/*Creo esta funcion atras para el archivo finalizarProyecto*/
function Atras() {
    if ($('#pnlFinalizarProyecto').is(":visible")) {
        window.location.href = "Proyectos.php";
        return;
    }
}
function NotificarUsuarioProveeduaria() {

}

