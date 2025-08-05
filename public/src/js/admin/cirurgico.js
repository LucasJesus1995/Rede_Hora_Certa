var atendimento = $("#atendimento").attr('atendimento-id');

function listagemSinaisVitais() {
    $.ajax({
        url: "/admin/atendimento-auxiliar/sinais-vitais/" + atendimento,
        cache: true,
        type: "GET",
        success: function (_html) {
            $("#box-atendimento-sinais-vitais").html(_html);
        }
    });
}


listagemSinaisVitais();