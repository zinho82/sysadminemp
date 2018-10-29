$(document).ready(function () {
    $("#facturas_estadoPago").change(function () {
        if ($("#facturas_estadoPago").val() == 82) {
            $("#pago").modal();
        }
    });
//    $("#registropago_tipooperacion").change(function () {
//        alert($("#registropago_tipooperacion").val());
//        if ($("#registropago_tipooperacion").val() == 80) {
//            var target = $(this).attr("href");
//            $("#pago .modal-body").load(target, function () {
//                $("#pago").modal("show");
//            });
//        }
//    });
//          alert($("#registropago_tipooperacion").val());


});

