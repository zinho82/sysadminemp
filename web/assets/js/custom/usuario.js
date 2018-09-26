$(document).ready(function () {
    $("#registro").click(function () {
        $("#registroDialogo").dialog({
            draggable: false,
            modal: true,
            show: {effect: "blind", duration: 1800}
        });
    });
    $("#GuardaReg").click(function () {
        var form = $("#FormRegistro").serialize();
        $.ajax({
            type: "post",
            datatype: "json",
            data: form,
            url: "guardaregistro.php",
            success: function (form) {
                location.reload();
            },
            error: function () {
                alert(" Error No se puede Guardar el Registro");
            }
        });
    });
});
