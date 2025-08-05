$(document).on("click", "#btn-falta-agendamento", function(e) {
    e.preventDefault();

    var id = $(this).attr('data-id');
    var token = $("input[name=_token]").val();

    if(confirm("Deseja realmente colocar falta para o atendimento: "+ id)) {
        $.post("/admin/agendas/falta", {
            id: id,
            _token: token
        }, function (data) {
            var page = $("#box-grid .pagination li.active span").html();
            loadGRID2(getUri() + "/grid", page);
        }, "json");
    }
});

$(document).on("change", ".cal-imc", function(e) {
    var peso = $("#value_descricao_10").val();
    var altura = $("#value_descricao_11").val();

    if(peso.length && altura.length) {
        var _altura = (altura * altura);
        var imc = peso / _altura;

        $("#info-10").html("<strong>IMC: " + imc.toFixed(2) + "</strong>");
        $("#info-11").html("<strong>IMC: " + imc.toFixed(2) + "</strong>");
    }else{
        $("#info-10").html("");
        $("#info-11").html("");
    }
});

$(document).on("change", ".btn-perguntas-1 input", function(e) {
    $(".has-error").removeClass('has-error');
    $(".has-success").removeClass('has-success');

    var obj = $(this);
    var id = $(this).attr('rel');
    var atendimento = $("#atendimento").val();
    var token = $("input[name=_token]").val();
    var value = ($(".btn-perguntas-1 #value_"+id).length ) ? $(".btn-perguntas-1 #value_"+id+":checked").val() : null;
    var tipo = '1';

    if($("#value_"+id).length){
        var value = getValuesCheckedById($("#value_"+id).attr('id'));
    }

    var value_descricao = ($(".btn-perguntas-1 #value_descricao_"+id).length ) ? $(".btn-perguntas-1 #value_descricao_"+id).val() :  null;

    $.post( "/admin/atendimento/save-recepcao", {tipo: tipo,  value_descricao:value_descricao, value:value,_token:token,atendimento:atendimento,id:id}, function( data ) {
        if(data.status){
            obj.parent().parent().addClass('has-success');
            obj.parent().parent().removeClass('has-error');
           // notySuccess("Registro atualizado com sucesso!");
            updateProcess();
        } else{
            obj.parent().parent().addClass('has-error');
            obj.parent().parent().removeClass('has-success');
            notyFail("Falha ao gravar registro!");
        }
    }, 'json');
});

$(document).on("change", ".btn-perguntas-global input", function(e) {
    $(".has-error").removeClass('has-error');
    $(".has-success").removeClass('has-success');

    var obj = $(this);
    var id = $(this).attr('rel');
    var atendimento = $("#atendimento").val();
    var token = $("input[name=_token]").val();
    var value = ($(".btn-perguntas-1 #value_"+id).length ) ? $(".btn-perguntas-1 #value_"+id+":checked").val() : null;
    var tipo = $(this).attr('key');

    if($("#value_"+id).length){
        var value = getValuesCheckedById($("#value_"+id).attr('id'));
    }

    var value_descricao = ($("div.active .btn-perguntas-global #value_descricao_"+id).length ) ? $("div.active .btn-perguntas-global #value_descricao_"+id).val() :  null;

    $.post( "/admin/atendimento/save-recepcao", {tipo: tipo,  value_descricao:value_descricao, value:value,_token:token,atendimento:atendimento,id:id}, function( data ) {
        if(data.status){
            obj.parent().parent().addClass('has-success');
            obj.parent().parent().removeClass('has-error');
           // notySuccess("Registro atualizado com sucesso!");
            updateProcess();
        } else{
            obj.parent().parent().addClass('has-error');
            obj.parent().parent().removeClass('has-success');
            notyFail("Falha ao gravar registro!");
        }
    }, 'json');
});

$(document).on("change", ".btn-perguntas-2 input", function(e) {
    $(".has-error").removeClass('has-error');
    $(".has-success").removeClass('has-success');

    var obj = $(this);
    var id = $(this).attr('rel');
    var atendimento = $("#atendimento").val();
    var token = $("input[name=_token]").val();
    var value = ($(".btn-perguntas-2 #value_"+id).length ) ? $(".btn-perguntas-2 #value_"+id+":checked").val() : null;
    var tipo = '2';

    if($("#value_"+id).length){
        var value = getValuesCheckedById($("#value_"+id).attr('id'));
    }

    var value_descricao = ($(".btn-perguntas-2 #value_descricao_"+id).length ) ? $(".btn-perguntas-2 #value_descricao_"+id).val() :  null;

    $.post( "/admin/atendimento/save-recepcao", {tipo: tipo,  value_descricao:value_descricao, value:value,_token:token,atendimento:atendimento,id:id}, function( data ) {
        if(data.status){
            obj.parent().parent().addClass('has-success');
            obj.parent().parent().removeClass('has-error');
           // notySuccess("Registro atualizado com sucesso!");
            updateProcess();
        } else{
            obj.parent().parent().addClass('has-error');
            obj.parent().parent().removeClass('has-success');
            notyFail("Falha ao gravar registro!");
        }
    }, 'json');
});

$(document).on("change", ".btn-perguntas-4 input", function(e) {
    $(".has-error").removeClass('has-error');
    $(".has-success").removeClass('has-success');

    var obj = $(this);
    var id = $(this).attr('rel');
    var atendimento = $("#atendimento").val();
    var token = $("input[name=_token]").val();
    var value = ($(".btn-perguntas-4 #value_"+id).length ) ? $(".btn-perguntas-4 #value_"+id+":checked").val() : null;
    var tipo = '4';

    if($("#value_"+id).length){
        var value = getValuesCheckedById($("#value_"+id).attr('id'));
    }

    var value_descricao = ($(".btn-perguntas-4 #value_descricao_"+id).length ) ? $(".btn-perguntas-4 #value_descricao_"+id).val() :  null;

    $.post( "/admin/atendimento/save-recepcao", {tipo: tipo,  value_descricao:value_descricao, value:value,_token:token,atendimento:atendimento,id:id}, function( data ) {
        if(data.status){
            obj.parent().parent().addClass('has-success');
            obj.parent().parent().removeClass('has-error');
          //  notySuccess("Registro atualizado com sucesso!");
            updateProcess();
        } else{
            obj.parent().parent().addClass('has-error');
            obj.parent().parent().removeClass('has-success');
            notyFail("Falha ao gravar registro!");
        }
    }, 'json');
});

$(document).on("change", "#textarea-evolucao", function(e) {
    $(".has-error").removeClass('has-error');
    $(".has-success").removeClass('has-success');

    var obj = $(this);
    var atendimento = $("#atendimento").val();
    var token = $("input[name=_token]").val();
    var value = $(this).val();

    $.post( "/admin/atendimento/save-evolucao", { value:value,_token:token,atendimento:atendimento}, function( data ) {
        if(data.status){
            obj.parent().parent().addClass('has-success');
            obj.parent().parent().removeClass('has-error');
            notySuccess("Registro atualizado com sucesso!");
            updateProcess();
        } else{
            obj.parent().parent().addClass('has-error');
            obj.parent().parent().removeClass('has-success');
            notyFail("Falha ao gravar registro!");
        }
    }, 'json');
});
$(document).on("change", "#textarea-anotacao", function(e) {
    $(".has-error").removeClass('has-error');
    $(".has-success").removeClass('has-success');

    var obj = $(this);
    var atendimento = $("#atendimento").val();
    var token = $("input[name=_token]").val();
    var value = $(this).val();

    $.post( "/admin/atendimento/save-anotacao", { value:value,_token:token,atendimento:atendimento}, function( data ) {
        if(data.status){
            obj.parent().parent().addClass('has-success');
            obj.parent().parent().removeClass('has-error');
            notySuccess("Registro atualizado com sucesso!");
            updateProcess();
        } else{
            obj.parent().parent().addClass('has-error');
            obj.parent().parent().removeClass('has-success');
            notyFail("Falha ao gravar registro!");
        }
    }, 'json');
});

$(document).on("change", ".checked-procedimento-medicina", function(e) {
    var obj = $(this);
    var id = $(this).attr('rel');
    var acao = $('#check_medicina_'+id).is(":checked") ? 1 : 0;
    var quantidade = (acao) ? $("#quantidade-medicina-"+id).val() : 0;

    if(quantidade.length == 0 && acao){
        $("#quantidade-medicina-"+id).val('1');
    }

    if(!acao){
        $("#quantidade-medicina-"+id).val('');
    }

    $("#quantidade-medicina-"+id).change();
});

$(document).on("change", ".quantidade-procedimento-medicina", function(e) {
    $(".has-error").removeClass('has-error');
    $(".has-success").removeClass('has-success');

    var obj = $(this);
    var id = $(this).attr('rel');
    var acao = $('#check_medicina_'+id).is(":checked") ? 1 : 0;
    var quantidade = (acao) ? $("#quantidade-medicina-"+id).val() : 0;
    var procedimento = id;
    var atendimento = $("#atendimento").val();
    var token = $("input[name=_token]").val();

    $.post( "/admin/atendimento/save-procedimento", {quantidade: quantidade,  procedimento:procedimento, atendimento:atendimento,_token:token}, function( data ) {
        if(data.status){
            if(!acao){
                $("#quantidade-medicina-"+id).attr("checked",true);
            }

            obj.parent().parent().addClass('has-success');
            notySuccess("Procedimento cadastrado com sucesso!");
            updateProcess();
        } else{
            obj.parent().parent().addClass('has-error');
            notyFail("Falha ao gravar procedimento!");
        }
    }, 'json');
});

$(document).on("change", ".check-medicamento", function(e) {
    var obj = $(this);
    var id = $(this).attr('rel');
    var acao = $('#check-quantidade-'+id).is(":checked") ? 1 : 0;
    var quantidade = (acao) ? $("#quantidade-medicamento-"+id).val() : 0;

    if(quantidade.length == 0 && acao){
        $("#quantidade-medicamento-"+id).val('1');
    }

    if(!acao){
        $("#quantidade-medicamento-"+id).val('');
    }

    $("#quantidade-medicamento-"+id).change();
});

$(document).on("change", ".quantidade-medicamento", function(e) {
    $(".has-error").removeClass('has-error');
    $(".has-success").removeClass('has-success');

    var obj = $(this);
    var id = $(this).attr('rel');
    //var acao = $('#check-quantidade-'+id).is(":checked") ? 1 : 0;
    var quantidade = $(this).val();
    var medicamento = id;
    var atendimento = $("#atendimento").val();
    var token = $("input[name=_token]").val();

    if(quantidade.length > 0) {
        $("#check-quantidade-"+id).prop("checked",true);
        var acao = 1;
    } else{
        $("#check-quantidade-"+id).prop("checked",false);
        var acao = 0;
    }


    $.post( "/admin/atendimento/save-medicamento", {acao:acao, quantidade: quantidade,  medicamento:medicamento, atendimento:atendimento,_token:token}, function( data ) {
        if(data.status){
            obj.parent().parent().addClass('has-success');
            notySuccess("Procedimento cadastrado com sucesso!");
            updateProcess();
        } else{
            obj.parent().parent().addClass('has-error');
            notyFail("Falha ao gravar procedimento!");
        }
    }, 'json');
});

$(document).on("click", "#btn-save-laudo", function(e) {
    $(".has-error").removeClass('has-error');
    $(".has-success").removeClass('has-success');

    var id = $("#laudo-id").val();
    var cid = $("#cid").val();
    var laudo = $("#laudo").val();
    var laudo_descricao = $("#laudo-description").val();
    var resultado_laudo = $("#tab-medicina-3 input[name=resultado_laudo]:checked").val();
    var biopsia = $("#resultado-laudo-biopsia-input").val();

    var obj = $(this);
    var acao = 1;
    var atendimento = $("#atendimento").val();
    var token = $("input[name=_token]").val();

    if(resultado_laudo == 3 && biopsia.length < 1){
        notyFail("Descreva a suspeita da biopsia");
    } else if(resultado_laudo == undefined) {
        notyFail("Informe o tipo de resultado do laudo!");
    } else {

        $.post("/admin/atendimento/save-laudo", {
            acao: acao,
            id: id,
            cid: cid,
            laudo: laudo,
            laudo_descricao: laudo_descricao,
            biopsia: biopsia,
            resultado: resultado_laudo,
            atendimento: atendimento,
            _token: token
        }, function (data) {
            if (data.status) {
                notySuccess("Laudo cadastrado com sucesso!");
                updateProcess();
                loadingLaudoAtendimento();

                $("input.resultado-laudo-biopsia").prop('checked', false);
                $("input.resultado-laudo-biopsia").change();

                $("select#laudo").val("");
                $('select#laudo').trigger("chosen:updated");

                $("textarea#laudo-description").val("");

                $("select#cid").val("");
                $('select#cid').trigger("chosen:updated");

            } else {
                notyFail("Falha ao gravar laudo!");
                loadingLaudoAtendimento();
            }
        }, 'json');
    }
});

$(document).on("change", "form.form-check-list input[type=text], form.form-check-list select", function(e) {
    var obj = $(this);
    var campo = $(this).attr('name');
    var value = $(this).val();
    var atendimento = $("input#atendimento").val();
    var agenda = $("input#agenda").val();
    var token = $("input[name=_token]").val();

    $.post( "/admin/atendimento/check-list", {campo: campo, value: value, agenda:agenda, atendimento:atendimento,_token:token}, function( data ) {
        if(data.status){
            obj.parent().addClass('has-success');
            obj.parent().removeClass('has-error');
        } else{
            obj.parent().addClass('has-error');
            obj.parent().removeClass('has-success');
        }
    }, 'json');
});

$(document).on("change", ".resultado-laudo-biopsia", function(e) {
    var resultado_laudo = $(".resultado-laudo-biopsia:checked").val();

    if(resultado_laudo == 3){
        $("#resultado-laudo-biopsia").removeClass('hidden');
    }else{
        $("#resultado-laudo-biopsia").addClass('hidden');
        $("#resultado-laudo-biopsia input").attr('checked', false);
    }

});

$(document).on("change", "form.form-check-list input[type=checkbox]", function(e) {
    var obj = $(this);
    var campo = $(this).attr('name');
    var value = $(this).is(':checked') ? '1' : '0';
    var atendimento = $("input#atendimento").val();
    var agenda = $("input#agenda").val();
    var token = $("input[name=_token]").val();

    $.post( "/admin/atendimento/check-list", {campo: campo, value: value, agenda:agenda, atendimento:atendimento,_token:token}, function( data ) {
        if(data.status){
            obj.parent().addClass('has-success');
            obj.parent().removeClass('has-error');
        } else{
            obj.parent().addClass('has-error');
            obj.parent().removeClass('has-success');
        }
    }, 'json');

});

$(document).on("change", "#laudo", function(e) {
    var laudo = $(this).val();

    $("#laudo-id").val('');

    if(laudo.length > 0) {
        getLaudoDescription(laudo);
    }else{
        $("#laudo-description").val(' ');
    }
});

function getLaudoDescription(laudo){
    $.ajax({
        url: "/admin/atendimento/laudo-descricao/"+laudo,
        success: function(html) {
            $("#laudo-description").val(html);
        }
    });
}

$(document).on("click", "#btn-imprimir-laudo", function(e) {
    var atendimento = $(this).attr('rel');

    window.open("/admin/atendimento/print-laudo/"+atendimento, "print-laudo");
});

$(document).on("click", "#btn-laudo-edit", function(e) {
    e.preventDefault();

    var laudo = $(this).attr('data-id');

    $.ajax({
        url: "/admin/laudo-medico/laudo/"+laudo,
        success: function(data) {
            obj = JSON.parse(data);

            $("select#cid").val(obj.cid);
            $('select#cid').trigger("chosen:updated");

            $("select#laudo").val(obj.laudo);
            $('select#laudo').trigger("chosen:updated");

            $.each($("input.resultado-laudo-biopsia"), function(){
                if($(this).val() == obj.resultado){
                    $(this).prop('checked', true);
                }else{
                    $(this).prop('checked', false);
                }
            });

            $("input.resultado-laudo-biopsia").change();

            if(obj.resultado == 3){
                $("#resultado-laudo-biopsia input").val(obj.biopsia);
            }else{
                $("#resultado-laudo-biopsia input").val("");
            }

            $("textarea#laudo-description").val(obj.descricao);
            $("input#laudo-id").val(obj.id);
        }
    },"json");
});

$(document).on("click", ".btn-laudo-imagens", function(e) {
    e.preventDefault();

    var laudo = $(this).attr('data-id');
    var box = $("#box-laudo-upload-imagens");

    $("#atendimento-laudo-id").val(laudo);
    setConfigUploadLaudo();
    box.find(".fs-upload-target").html("Clique ou arraste  para fazer enviar as imagens referente ao laudo &nbsp;<b>"+laudo+"</b>");
    box.removeClass('hidden');
});

$(document).on("click", "#btn-laudo-delete", function(e) {
    e.preventDefault();

    var laudo = $(this).attr('data-id');

    $.ajax({
        url: "/admin/atendimento/delete-laudo/"+laudo,
        type: 'GET',
        success: function() {
            loadingLaudoAtendimento();
        }
    },"json");
});

$(document).on("click", "#btn-finalizar-atendimento", function(e) {
    e.preventDefault();

    var atendimento = $(this).attr('data-atendimento');

    noty({
        text: user['nome'] + ", deseja realmente finalizar o atendimento " + atendimento + " com o médico " + user['medico'],
        animation: {
            open: 'animated bounceInLeft',
            close: 'animated bounceOutLeft',
            easing: 'fade',
            speed: 500
        },
        layout : 'center',
        type : 'primary',
        buttons: [
            {addClass: 'btn btn-danger', text: 'Cancelar', onClick: function($noty) {
                $noty.close();
            }
            },
            {addClass: 'btn btn-primary', text: 'Finalizar', onClick: function($noty) {

                $.post("/admin/atendimento/finalizar-digitador", {atendimento: atendimento, _token: token}, function (data) {
                    if (data.status) {
                        $("#btn-search-agenda").click();
                        notySuccess("Atendimentos finalizado com sucesso!");
                        closeModal();
                    } else {
                        if(data.message == undefined)
                            notyFail("Não foi possivel executar a solicitação");
                        else
                            notyFail(data.message);
                    }
                }, 'json');

                $noty.close();
            }
            }
        ],
        timeout: 100,
        killer: true,
        force: true,
        modal: modal
    });

});

$(document).on("click", "#btn-laudo-print", function(e) {
    e.preventDefault();

    var laudo = $(this).attr('data-id');

    window.open("/admin/atendimento/print-laudo/"+laudo, "print-laudo");
});

function loadingLaudoAtendimento(){
    var token = $("input[name=_token]").val();
    var atendimento = $("#atendimento").val();

    if($("div#grid-laudo").length) {
        $.ajax({
            url: "/admin/atendimento/grid-laudo/" + atendimento,
            cache: true,
            success: function (data) {
                $("#grid-laudo").html(data);
            }
        });
    }
}

function updateProcess(){
    if($("#progress-atendimento").length) {
        var atendimento = $("input#atendimento").val();
        var token = $("input[name=_token]").val();

        $.post("/admin/atendimento/progress", {_token: token, atendimento: atendimento}, function (data) {
            $("#progress-atendimento").html(data);
        });

        atualizaAtendimentoAgenda();
    }
}

function checkAtendimentoStatus(){
    switch ($("#atendimento-status").val()) {
        case "0":
        case "6":
        case "7":
        case "10":
        case "98":
        case "99":
            blockEdit();
            break;
    }
}

function blockEdit(){
    $("#box-geral-atendimento input").attr('disabled', true);
    $("#box-geral-atendimento textarea").attr('disabled', true);
    $("#box-geral-atendimento select").attr('disabled', true);
    // $("#box-geral-atendimento #btn-save-laudo").attr('disabled', true);
    // $("#box-geral-atendimento #btn-laudo-edit").hide();
    // $("#box-geral-atendimento #btn-laudo-delete").hide();
    // $("#box-geral-atendimento #box-atendimento-laudo").hide();


    $("#box-atendimento-laudo input").attr('disabled', false);
    $("#box-atendimento-laudo textarea").attr('disabled', false);
    $("#box-atendimento-laudo select").attr('disabled', false);
}

function uploadLaudocomImagens(atendimento_laudo){


}

updateProcess();
loadingLaudoAtendimento();



