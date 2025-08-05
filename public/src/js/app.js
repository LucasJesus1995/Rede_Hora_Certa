var html_loading = '<div class="text-center m-b" style="padding: 30px"><i class="fa fa-circle-o-notch fa-spin text-lg text-muted-lt"></i></div>';
var ua = navigator.userAgent.toLowerCase(),
    platform = navigator.platform.toLowerCase();
platformName = ua.match(/ip(?:ad|od|hone)/) ? 'ios' : (ua.match(/(?:webos|android)/) || platform.match(/mac|win|linux/) || ['other'])[0],
    isMobile = /ios|android|webos/.test(platformName);

$(".chosen").chosen({width: '100%', search_contains: true});

$(document).on("change", "select.combo-arena", function (e) {
    var id = $(this).val();

    $select_linha_cuidado = $("select.linha_cuidado");
    $select_linha_cuidado.find('option').remove();
    $('<option>').val('').text('...').appendTo($select_linha_cuidado);

    $select_medico = $("select.profissional");
    $select_medico.find('option').remove();
    $('<option>').val('').text('...').appendTo($select_medico);

    $("select.linha_cuidado").trigger("chosen:updated");

    if (id) {

        var uri = '/admin/linha-cuidado/arena/' + id;
        $.getJSON(uri, function (response) {
            if (response.status) {

                $.each(response.data, function (key, value) {
                    $('<option>').val(key).text(value).appendTo($select_linha_cuidado);
                });

                $("select.linha_cuidado").addClass('chosen');

                $("select.linha_cuidado").val($("#combo-linha_cuidado").val());
                $("select.linha_cuidado").chosen({width: '100%', search_contains: true});
                ;
                $("select.linha_cuidado").trigger("chosen:updated");
            }
        });

    }
});

$(".autocomplete-pacientes").autocomplete({
    delay: 300,
    minLength: 9,
    source: function (request, response) {
        $.ajax({
            url: "/admin/pacientes/search/" + request.term,
            type: "GET",
            dataType: "json",
            success: function (data) {
                response(data);
            }
        });
    }
});

$(document).on("click", "a.btn-new-oferta", function (e) {
    e.preventDefault();

    var uri = $(this).attr('href');

    setModalLarge("Ofertas", html_loading);
    $.ajax({
        url: uri,
        type: "GET",
        success: function (html) {
            setModalBodyLarge(html);
        }
    });
});

$(".autocomplete-atendimentos").autocomplete({
    source: function (request, response) {
        $.ajax({
            url: "/admin/atendimento/search/" + request.term,
            type: "GET",
            dataType: "json",
            success: function (data) {
                response(data);
            }
        });
    }
});


function checkInMedicina(atendimento) {
    $.ajax({
        url: "/admin/atendimento/check-in-medicina/" + atendimento,
        type: "GET",
        dataType: "json",
        success: function () {

        }
    });
};

function setObjectNameValue(obj, name, value) {
    var child = obj.find("[name='" + name + "']");
    if (child.size()) {
        child.val(value);
    }

    $(".chosen").trigger("chosen:updated");
}

function setNameValue(name, value) {
    var child = $("[name='" + name + "']");
    if (child.size()) {
        child.val(value);
        child.change();
    }

    $(".chosen").trigger("chosen:updated");
}

function dataFormatada(d) {
    var data = new Date(d),
        dia = data.getDate(),
        mes = data.getMonth() + 1,
        ano = data.getFullYear();
    return [dia, mes, ano].join('/');
}

function getDadosCEP(cep) {
    var url = "/services/cep/" + cep;

    $.ajax({
        url: url,
        cache: false,
        type: "GET",
        dataType: 'json',
        success: function (data) {
            if (data) {
                setNameValue('estado', data.cidade.estado.id);
                setNameValue('endereco', data.endereco);
                setNameValue('bairro', data.bairro);
                $("[name='estado']").change();
                $("#combo-cidade").val(data.cidade.id);
            }
        }
    });
}

function updateCombos() {

    $(document).on("change", ".cns-service", function (e) {
        e.preventDefault();

        var url = "/services/cns/" + $(this).val();

        $.ajax({
            url: url,
            cache: false,
            type: "GET",
            dataType: 'json',
            success: function (data) {
                if (data) {
                    var nascimento = (data.nascimento) ? new Date(data.nascimento).toLocaleDateString() : null;
                    var raca_cor = (data.raca_cor) ? "0" + data.raca_cor : null;
                    var endereco_tipo = (data.endereco_tipo) ? "0" + data.endereco_tipo : null;

                    var box = $("#box-paciente");
                    setObjectNameValue(box, 'id', data.id);
                    setObjectNameValue(box, 'nome', data.nome);
                    setObjectNameValue(box, 'mae', data.mae);
                    setObjectNameValue(box, 'nascimento', nascimento);
                    setObjectNameValue(box, 'sexo', data.sexo);
                    setObjectNameValue(box, 'cpf', data.cpf);
                    setObjectNameValue(box, 'rg', data.rg);
                    setObjectNameValue(box, 'estado_civil', data.estado_civil);
                    setObjectNameValue(box, 'raca_cor', raca_cor);
                    setObjectNameValue(box, 'celular', data.celular);
                    setObjectNameValue(box, 'telefone_residencial', data.telefone_residencial);
                    setObjectNameValue(box, 'email', data.email);
                    setObjectNameValue(box, 'contato', data.contato);
                    setObjectNameValue(box, 'endereco_tipo', endereco_tipo);
                    setObjectNameValue(box, 'endereco', data.endereco);
                    setObjectNameValue(box, 'numero', data.numero);
                    setObjectNameValue(box, 'cep', data.cep);
                    setObjectNameValue(box, 'estabelecimento', data.estabelecimento);
                    setObjectNameValue(box, 'descricao', data.descricao);

                    getDadosCEP(data.cep);
                }
            }
        });
    });

    $(".cep").on('change', function (e) {
        var cep = $(this).val();
        if (cep.length > 7) {
            getDadosCEP(cep);
        }
    });
}

updateCombos();

$("#id-field-paciente").on('change', function (e) {
    var paciente = $(this).val().split(" - ");

    if (paciente[0].length > 0) {
        $.ajax({
            url: "/admin/pacientes/by-cns/" + paciente[0],
            type: "GET",
            dataType: "json",
            success: function (paciente) {
                if (paciente.estabelecimento != null && paciente.estabelecimento > 0) {
                    $("#id-field-estabelecimento").val(paciente.estabelecimento);
                    $("#id-field-estabelecimento").trigger("chosen:updated");
                }
            }
        });
    }
});

function clearInputs(form) {
    clearErrorInputs();

    form.find("input[type='hidden']").val('');
    form.find("input[type='text']").val('');
    form.find("input[type='email']").val('');
    form.find("input[type='file']").val('');
    form.find("textarea").val('');
    form.find("select").val(0);

    form.find("input[name='_token']").val(token);

    $('.chosen').trigger('chosen:updated');
}

function clearErrorInputs() {
    $("form input").parent().removeClass('has-error');
    $("form textarea").parent().removeClass('has-error');
    $("form select").parent().removeClass('has-error');

    $(".form-input-error").remove();
    $("div.box-error-message").html("");
    $("#error-validation").html("");
}

function displayBoxError(message) {
    if ($("#error-validation").length > 0) {
        $("#error-validation").html("<div class='alert alert-danger'>" + message + "</div>");
    }
}

function printErrorField(xhr, _alert, display_box) {
    clearErrorInputs();
    var _errors = JSON.parse(xhr.responseText);
    var _errors_box = '';

    if (xhr.status == 422) {
        $.each(_errors, function (key, value) {
            $.each(['input', 'textarea', 'select'], function (_key, field) {
                var obj = $("form " + field + "[name='" + key + "']").parent();
                if (obj.length) {
                    obj.addClass('has-error');
                    obj.append("<div class='invalid-feedback form-input-error'>" + value + "</div>");

                    _errors_box += "<p> - " + value + "</p>";
                }
            });
        });

        if (display_box) {
            displayBoxError(_errors_box);
        }

    } else {
        if (_errors.message != undefined) {
            _errors_box = _errors.message;
        } else {
            _errors_box = "Erro não esperando, entre em contato com suporte!";
        }

        notyFail(_errors_box);

        if (display_box) {
            displayBoxError(_errors_box);
        }
    }
}

function printErrorBoxAlert(xhr, _alert) {
    var _errors = JSON.parse(xhr.responseText);

    if (xhr.status == 422) {
        var html_erro = "<div class='alert alert-danger'>";
        jQuery.each(_errors, function (error) {
            if (_alert)
                notyFail(this);

            html_erro += "<li>" + this + "</li>";
        });
        html_erro += "</div>";

        $("#box-grid").html(html_erro);
    } else {
        if (_errors.message != undefined)
            notyFail(_errors.message);
        else
            notyFail("Erro não esperando, entre em contato com suporte!");
    }
}

$(document).on("change", ".importacao-oferta", function (e) {
    e.preventDefault();

    var obj = $(this);
    var lote = $("#importacao-oferta-lote").val();
    var ano = $("#importacao-oferta-ano").val();
    var mes = $("#importacao-oferta-mes").val();
    var arena = $(this).attr('data-arena');
    var linha_cuidado = $(this).attr('data-linha-cuidado');
    var qtd = $(this).val();
    var token = $("input[name=_token]").val();

    $('.border-red').removeClass('border-red');
    $('.border-green').removeClass('border-green');

    $.post("/admin/importacao/oferta-save", {
            lote: lote,
            ano: ano,
            mes: mes,
            arena: arena,
            linha_cuidado: linha_cuidado,
            qtd: qtd,
            _token: token
        },
        function (data) {
            if (data.status) {
                obj.addClass('border-green');
            } else {
                obj.addClass('border-red');
            }
        }, "json")
        .fail(function (xhr, status, error) {
            printErrorBoxAlert(xhr, false);
        });

});

$(document).on("click", ".btn-remove-agendamento", function (e) {
    e.preventDefault();

    var obj = $(this);
    var id = obj.attr('data-id');
    var token = $("input[name=_token]").val();

    noty({
        text: user['nome'] + ", deseja realmente remover as agenda referente a importação " + id + "?" +
            "<br /><br />Só será removido agenda com status 'Aberto'!",
        animation: {
            open: 'animated bounceInLeft',
            close: 'animated bounceOutLeft',
            easing: 'fade',
            speed: 500
        },
        layout: 'center',
        type: 'primary',
        buttons: [
            {
                addClass: 'btn btn-danger', text: 'Cancelar', onClick: function ($noty) {
                    $noty.close();
                }
            },
            {
                addClass: 'btn btn-success', text: 'Remover Agenda', onClick: function ($noty) {
                    $.post("/admin/importacao/agenda-delete-agendamento", {id: id, _token: token}, function (data) {
                        if (data.status) {
                            $("#btn-search-agenda").click();
                            notySuccess("Agenda removida com sucesso!");
                        } else {
                            if (data.message == undefined)
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


$(document).on("click", ".btn-open-atualizacao-massa", function (e) {
    clearInputs($(".campos-atualizacao-em-massa"));

    $(".form-atualizacao-em-massa select[name='aberta']").val('');
    $('.chosen').trigger('chosen:updated');
});
$(document).on("click", ".cancelar-ofertas", function (e) {
    var form = $(this).parents('form');

    clearInputs(form);
});

$(document).on("click", ".submit-atualizacao-massa", function (e) {
    e.preventDefault();

    var form = $("form.form-atualizacao-em-massa");

    var data = {};
    data.pesquisa = $("form.dados-em-massa").serializeObject();
    data.campos = form.serializeObject();
    data._token = data.campos._token;

    $.post(form.attr('action'), data,
        function (response) {
            var response = $.parseJSON(response);

            if (response.status) {
                var data_response = response.data;

                if (data_response.status != undefined) {
                    console.log(data_response.status);
                    $(".oferta-pesquisa select[name=status]").val(data_response.status);
                }

                if (data_response.aberta != undefined) {
                    $(".oferta-pesquisa select[name=aberta]").val(data_response.aberta);
                }

                $('.chosen').trigger('chosen:updated');

                loadingDataGridOfertas();
            } else {
                notyFail(response.message);
            }

        })
        .fail(function (xhr, status, error) {
            printErrorField(xhr, false, true);
        });
});

$(document).on("click", "a.delete-oferta-massa", function (e) {
    e.preventDefault();

    if (confirm(user.nome + ", deseja realmente remover todas as ofertas filtradas?")) {
        var data = {};
        data.pesquisa = $("form.dados-em-massa").serializeObject();
        data._token = data.pesquisa._token;

        $.post("/admin/ofertas/delete-massa", data,
            function (response) {
                var response = $.parseJSON(response);

                if (response.status) {
                    loadingDataGridOfertas();
                } else {
                    notyFail(response.message);
                }

            })
            .fail(function (xhr, status, error) {
                printErrorField(xhr, false, true);
            });
    }
});

$(document).on("click", ".submit-ofertas", function (e) {
    e.preventDefault();
    clearErrorInputs();

    var form = $(this).parents('form');
    var id = $(this).parents('form').find("input[name='id']").val();

    notyInfo("Aguarde, os dados estão sendo gravados!");

    $.post(form.attr('action'), form.serialize(),
        function (response) {
            var response = $.parseJSON(response);

            if (response.status) {
                var data_response = response.data;

                $(".oferta-pesquisa input[name=data-inicial]").val(data_response.data);
                $(".oferta-pesquisa input[name=data-final]").val(data_response.data_final);
                $(".oferta-pesquisa input[name=horario-inicial]").val(data_response.hora_inicial);
                $(".oferta-pesquisa input[name=horario-final]").val(data_response.hora_final);
                $(".oferta-pesquisa select[name=unidade]").val(data_response.arena);
                $(".oferta-pesquisa select[name=status]").val(data_response.status);
                $(".oferta-pesquisa select[name=aberta]").val(data_response.aberta);

                $('.chosen').trigger('chosen:updated');

                loadingDataGridOfertas();

                if (id.length > 0) {
                    clearInputs(form);
                }

            } else {
                notyFail(response.message);
            }

        })
        .fail(function (xhr, status, error) {
            printErrorField(xhr, false, true);
        });
});


$(document).on("click", ".btn-editar-oferta", function (e) {
    e.preventDefault();
    clearErrorInputs();

    var oferta = $(this).attr('data-id');

    $.get("/admin/ofertas/registro/" + oferta,
        function (html) {
            $(".box-oferta-cadastro").html(html);
            $(".btn-tab-cadastro").click();
            loadingMask();
        })
        .fail(function (xhr, status, error) {
            printErrorField(xhr, false, true);
        });
});

$(document).on("click", ".btn-relatorio-ofertas", function (e) {
    e.preventDefault();

    var form = $(this).parents('form');

    $("#box-grid").html(html_loading);

    notyInfo("Seu relatorio esta sendo gerado!");

    clearErrorInputs();
    $.post("/admin/ofertas/relatorio", form.serialize(),
        function (html) {
            $("#box-grid").html(html);
        })
        .fail(function (xhr, status, error) {
            printErrorField(xhr, false, true);
        });
});

$(document).on("click", ".btn-relatorio-ofertas-escala", function (e) {
    e.preventDefault();

    var form = $(this).parents('form');

    $("#box-grid").html(html_loading);

    notyInfo("Seu relatorio esta sendo gerado!");

    clearErrorInputs();
    $.post("/admin/ofertas/relatorio-escala", form.serialize(),
        function (html) {
            $("#box-grid").html(html);
        })
        .fail(function (xhr, status, error) {
            printErrorField(xhr, false, true);
        });
});

$(document).on("click", ".btn-oferta-excluir", function (e) {
    e.preventDefault();

    var obj = $(this);
    var oferta = obj.attr('data-id');

    if (confirm("Deseja realmente remover este registro?")) {
        $.post("/admin/ofertas/remover", {oferta: oferta, _token: token},
            function (response) {
                var response = $.parseJSON(response);

                if (response.status) {
                    notySuccess("Registro removido com successo");
                    obj.parents('tr').remove();
                } else {
                    notyFail(response.message);
                }

            })
            .fail(function (xhr, status, error) {
                printErrorField(xhr, false, true);
            });
    }
});

$(document).on("click", ".btn-oferta-aprovacao", function (e) {
    e.preventDefault();

    var obj = $(this);
    var oferta = obj.attr('data-id');

    setModal("Aprovação", html_loading);
    $.get("/admin/ofertas/aprovacao/" + oferta,
        function (html) {
            setModalBody(html);
        })
        .fail(function (xhr, status, error) {
            printErrorField(xhr, false, true);
            closeModal();
        });
});

$(document).on("click", ".btn-aprovacao-ofertas", function (e) {
    e.preventDefault();

    var form = $(this).parents('form');

    $.post(form.attr('action'), form.serialize(),
        function (response) {
            var response = $.parseJSON(response);

            if (response.status) {
                $("#oferta-" + response.data.id + " .status-oferta strong").html(response.status_descricao);

                if (response.data.data_aprovacao != "") {
                    $("#oferta-" + response.data.id + " .oferta-aprovacao").remove();
                }

                notySuccess("Registro gravado com sucesso!");
                closeModal();
            } else {
                notyFail(response.message);
            }

        }).fail(function (xhr, status, error) {
        printErrorField(xhr, false, true);
    });
});

$(document).on("click", ".btn-oferta-ocorrencia", function (e) {
    e.preventDefault();

    var obj = $(this);
    var oferta = obj.attr('data-id');

    setModal("Ocorrências", html_loading);
    $.get("/admin/ofertas/ocorrencias/" + oferta,
        function (html) {
            setModalBody(html);
        })
        .fail(function (xhr, status, error) {
            printErrorField(xhr, false, true);
            closeModal();
        });
});

$(document).on("click", ".btn-ocorrencias-ofertas", function (e) {
    e.preventDefault();

    var form = $(this).parents('form');

    notyInfo("Aguarde, os dados estão sendo gravados!");

    $.post(form.attr('action'), form.serialize(),
        function (response) {
            var response = $.parseJSON(response);

            if (response.status) {
                $("#oferta-" + response.data.id + " .status-oferta strong").html(response.status_descricao);

                notySuccess("Registro gravado com sucesso!");
                closeModal();
            } else {
                notyFail(response.message);
            }

        }).fail(function (xhr, status, error) {
        printErrorField(xhr, false, true);
    });
});

$(document).on("click", ".btn-oferta-status", function (e) {
    e.preventDefault();

    var obj = $(this);
    var oferta = obj.attr('data-id');

    setModal("Status", html_loading);
    $.get("/admin/ofertas/status/" + oferta,
        function (html) {
            setModalBody(html);
        })
        .fail(function (xhr, status, error) {
            printErrorField(xhr, false, true);
            closeModal();
        });
});

$(document).on("click", ".btn-status-ofertas", function (e) {
    e.preventDefault();

    var form = $(this).parents('form');

    clearErrorInputs();
    $.post(form.attr('action'), form.serialize(),
        function (response) {
            var response = $.parseJSON(response);

            if (response.status) {
                $("#oferta-" + response.data.id + " .status-oferta strong").html(response.status_descricao);

                notySuccess("Registro gravado com sucesso!");
                closeModal();
            } else {
                notyFail(response.message);
            }

        }).fail(function (xhr, status, error) {
        printErrorField(xhr, false, true);
    });
});

function loadingDataGridOfertas() {
    $(".btn-pesquisa-ofertas").click();
    loadingMask();
}

$(document).on("click", ".btn-pesquisa-ofertas", function (e) {
    e.preventDefault();

    var form = $(this).parents('form');

    $.get(form.attr('action'), form.serialize(),
        function (data) {
            $("#box-grid").html(data);
        })
        .fail(function (xhr, status, error) {
            printErrorBoxAlert(xhr, false);
        });

});

$(document).on("click", ".btn-receita-arena", function (e) {
    e.preventDefault();

    var contrato = $("#receita-arena-contrato").val();
    var faturamento = $("#receita-arena-faturamento").val();
    var arena = $(this).attr('data-arena');

    setModalLarge("Relatório", html_loading);
    $.post("/admin/relatorios/receita-arena/detalhes-linha-cuidado", {
            _token: token,
            contrato: contrato,
            faturamento: faturamento,
            arena: arena
        }, function (body) {
            setModalLarge("Relatório", body);
        }
    )
        .fail(function (xhr, status, error) {
            notyFail("Não foi possivel gerar o relatorio!");
        });
});

$(document).on("click", ".btn-receita-arena-linha_cuidado", function (e) {
    e.preventDefault();

    var contrato = $("#receita-arena-contrato").val();
    var faturamento = $("#receita-arena-faturamento").val();
    var arena = $("#receita-arena").val();
    var linha_cuidado = $(this).attr('data-linha-cuidado');

    setModalBodyLarge(html_loading);
    $.post("/admin/relatorios/receita-arena/detalhes-linha-cuidado-procedimentos", {
            _token: token,
            contrato: contrato,
            faturamento: faturamento,
            arena: arena,
            linha_cuidado: linha_cuidado
        }, function (body) {
            setModalLarge("Relatório", body);
        }
    )
        .fail(function (xhr, status, error) {
            notyFail("Não foi possivel gerar o relatorio!");
        });
});

$(document).on("click", ".btn-relatorio-gordura-detalhado", function (e) {
    e.preventDefault();

    var contrato = $("#receita-arena-contrato").val();
    var periodo = $(this).attr('data-periodo');

    setModalBodyLarge(html_loading);
    $.post("/admin/relatorios/gordura-detalhado/relatorio-periodo", {
            _token: token,
            contrato: contrato,
            periodo: periodo
        }, function (body) {
            setModalLarge("Relatório", body);
        }
    )
        .fail(function (xhr, status, error) {
            notyFail("Não foi possivel gerar o relatorio!");
        });
});

$(document).on("click", ".btn-relatorio-gordura-detalhado-procedimento", function (e) {
    e.preventDefault();

    var contrato = $("#receita-arena-contrato").val();
    var periodo = $(this).attr('data-periodo');
    var procedimento = $(this).attr('data-procedimento');

    setModalBodyLarge(html_loading);
    $.post("/admin/relatorios/gordura-detalhado/relatorio-periodo-procedimento", {
            _token: token,
            contrato: contrato,
            periodo: periodo,
            procedimento: procedimento
        }, function (body) {
            setModalLarge("Relatório", body);
        }
    )
        .fail(function (xhr, status, error) {
            notyFail("Não foi possivel gerar o relatorio!");
        });
});

$(document).on("click", "#btn-previsao-faturamento", function (e) {
    e.preventDefault();

    var form = $(this).parents('form');

    $("#box-grid").html(html_loading);
    notyInfo("Aguarde, seu relatorio está sendo gerado!");

    $.post("/admin/relatorio/previsao-faturamento", form.serialize(),
        function (data) {
            notySuccess("Relatorio gerado com sucesso!");
            $("#box-grid").html(data.download);
            openDownload(data.link);
        })
        .fail(function (xhr, status, error) {
            printErrorBoxAlert(xhr, false);
        });

});

$(document).on("click", "#btn-faturamento-linha-cuidado", function (e) {
    e.preventDefault();

    var form = $(this).parents('form');

    $("#box-grid").html(html_loading);
    notyInfo("Aguarde, seu relatorio está sendo gerado!");

    $.post("/admin/relatorio/faturamento-linha-cuidado", form.serialize(),
        function (data) {
            notySuccess("Relatorio gerado com sucesso!");
            $("#box-grid").html(data.download);
            openDownload(data.link);
        })
        .fail(function (xhr, status, error) {
            printErrorBoxAlert(xhr, false);
        });

});


$(document).on("click", "#btn-relatorio-faturamento-sub-grupo", function (e) {
    e.preventDefault();

    var form = $(this).parents('form');

    $("#box-grid").html(html_loading);
    notyInfo("Aguarde, seu relatorio está sendo gerado!");

    $.post("/admin/relatorio/faturamento-sub-grupo", form.serialize(),
        function (data) {
            notySuccess("Relatorio gerado com sucesso!");
            $("#box-grid").html(data.download);
            openDownload(data.link);
        })
        .fail(function (xhr, status, error) {
            printErrorBoxAlert(xhr, false);
        });

});


$(document).on("click", "#btn-configuracoes-procedimentos", function (e) {
    e.preventDefault();

    var form = $(this).parents('form');

    $("#box-grid").html(html_loading);
    notyInfo("Aguarde, seu relatorio está sendo gerado!");

    $.post("/admin/relatorio/configuracoes-procedimentos", form.serialize(),
        function (data) {
            notySuccess("Relatorio gerado com sucesso!");
            $("#box-grid").html(data.download);
            openDownload(data.link);
        })
        .fail(function (xhr, status, error) {
            printErrorBoxAlert(xhr, false);
        });

});

$("#btn-visualizar-agenda").on('click', function (e) {
    e.preventDefault();
    var form = $(this).parents('form');
    $("#box-grid").html(html_loading);

    $.post("/admin/importacao/oferta-grid",
        form.serialize()
        , function (html) {
            $("#box-grid").html(html);
        })
        .fail(function (xhr, status, error) {
            printErrorBoxAlert(xhr, false);
        });
});

var SPMaskBehavior = function (val) {
    return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
}, spOptions = {
    onKeyPress: function (val, e, field, options) {
        field.mask(SPMaskBehavior.apply({}, arguments), options);
    }
};

loadingMask();
loadingDate();

function loadingMask() {
    $(".money").maskMoney({prefix: 'R$ ', allowNegative: true, thousands: ' ', decimal: '.', affixesStay: false});

    $(".numbers").keyup(function () {
        this.value = this.value.replace(/[^0-9\.]/g, '');
    });

    $(".number").keyup(function () {
        this.value = this.value.replace(/[^0-9\.]/g, '');
    });

    $('.cell-phone').mask(SPMaskBehavior, spOptions);

    ///// MASK
    $(".date").mask("99/99/9999", {placeholder: "99/99/9999"});
    $(".time").mask("99:99", {placeholder: "HH:MM"});
    $(".timestamp").mask("99/99/9999 99:99:99", {placeholder: "99/99/9999 99:99:99"});
    $(".cpf").mask("999.999.999-99", {placeholder: "999.999.999-99"});
    $(".phone").mask("(99) 9999-9999", {placeholder: "(99) 9999-9999"});
    $(".cep").mask("99999-999", {placeholder: "99999-999"});
    $(".autorizacao").mask("99.99.9.9.999.999-9");

    loadingDate();

    setTimeout(function () {
        $(".chosen").chosen({width: '100%', search_contains: true});
    }, 1000);

    formAjax();
}

function loadingDate() {
    $(function () {
        $('.date').datepicker({
            format: 'dd/mm/yyyy',
            language: "pt-BR",
            todayBtn: true,
            autoclose: true
        });

    });
}

function getUri() {
    var path = window.location.pathname.split('/');
    return "/" + path[1] + "/" + path[2];
}

function getSearch() {

    $("#btn-search-grid").on('click', function (e) {
        e.preventDefault();
        var q = $("#input-search").val();
        var field = null;
        if ($("#input-field").length) {
            if ($("#input-field").val().length > 0) {
                field = $("#input-field").val();
            } else {
                notyFail("Selecione um campo para filtro!");
                return;
            }
        }

        if (q.length > 1) {
            $("#box-grid").html(html_loading);
            $.ajax({
                url: getUri() + "/grid?q=" + q + "&field=" + field,
                cache: false,
                success: function (data) {
                    $("#box-grid").html(data);
                }
            });
        } else {
            loadingDataGrid();
        }


    });

    $("#btn-search-grid-usuarios").on('click', function (e) {
        e.preventDefault();
        var q = $("#input-search").val();
        var perfil = $("#usuario-perfil").val();

        if (q.length > 1 || perfil.length > 0) {

            $.ajax({
                url: getUri() + "/grid?q=" + q + "&perfil=" + perfil,
                cache: false,
                success: function (data) {
                    $("#box-grid").html(data);
                }
            });
        } else {
            loadingDataGrid();
        }
    });

    $("#btn-search-grid-profissional").on('click', function (e) {
        e.preventDefault();
        var q = $("#input-search").val();
        var perfil = $("#profissional-perfil").val();

        if (q.length > 1 || perfil.length > 0) {

            $.ajax({
                url: getUri() + "/grid?q=" + q + "&perfil=" + perfil,
                cache: false,
                success: function (data) {
                    $("#box-grid").html(data);
                }
            });
        } else {
            loadingDataGrid();
        }
    });

    $("#btn-search-grid-laudo-medico").on('click', function (e) {
        e.preventDefault();
        var q = $("#input-search").val();
        var medico = $("#medico-combo").val();

        if (q.length > 1 || medico.length > 0) {

            $.ajax({
                url: getUri() + "/grid?q=" + q + "&medico=" + medico,
                cache: false,
                success: function (data) {
                    $("#box-grid").html(data);
                }
            });
        } else {
            loadingDataGrid();
        }
    });

    $("#input-search").on('keydown', function (e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            $("#btn-search-grid").click();
        }
    });
}


$(document).on("click", ".display_grid", function (e) {
    e.preventDefault();

    var key = $(this).attr('data-key');
    $("#display-" + key).toggle('slow');
});

$("#btn-print-agenda").on("click", function (e) {
    e.preventDefault();

    var arena = $("#form-atendimento #arena").val();
    var linha_cuidado = $("#form-atendimento #linha_cuidado").val();
    var paciente = $("#form-atendimento #paciente").val();
    var data = $("#form-atendimento #data").val();
    var token = $("input[name=_token]").val();

    if (arena.length <= 0 || data.length <= 0) {
        notyFail("Informe uma arena e uma data para impressão");
    } else {
        window.open("/admin/agendas/print?arena=" + arena + "&linha_cuidado=" + linha_cuidado + "&paciente=" + paciente + "&data=" + data + "&_token=" + token, "print-agenda");
    }
});

$(document).on("click", "#btn-gerar-absenteismo", function () {
    var arena = $("#relatorio-absenteismo #arena").val();
    var tipo = $("#relatorio-absenteismo #tipo").val();
    var periodo = $("#relatorio-absenteismo #periodo").val();
    var token = $("input[name=_token]").val();

    $("#box-grid").html(html_loading);

    $.post("/admin/relatorio/absenteismo", {
        arena: arena,
        tipo: tipo,
        periodo: periodo,
        _token: token
    }, function (data) {
        $("#box-grid").html(data);
    });
});


$(document).on("click", "#btn-gerar-exportacao-pacientes", function () {
    var arena = $("#relatorio-exportacao #arena").val();
    var periodo = $("#relatorio-exportacao #periodo").val();
    var token = $("input[name=_token]").val();

    var html_old = $("#box-grid").html();

    $("#box-grid").html(html_loading);

    $.post("/admin/exportacao/pacientes", {
        arena: arena,
        periodo: periodo,
        _token: token
    }, function (data) {
        if (data.success) {
            $("#box-grid").html(data.html);
        } else {
            notyFail(data.message);
            $("#box-grid").html(html_old);
        }
    }, "json");
});

$(document).on("click", "#btn-gerar-relatorio-linha-cuidado-metrica", function () {
    var lote = $("#relatorio-linha-cuidado-metrica #lote").val();
    var mes = $("#relatorio-linha-cuidado-metrica #mes").val();
    var ano = $("#relatorio-linha-cuidado-metrica #ano").val();
    // var status = $("#relatorio-linha-cuidado-metrica #status").val();
    var token = $("input[name=_token]").val();

    $.post("/admin/relatorio/faturamento-lote-gerencia", {
        lote: lote,
        status: status,
        mes: mes,
        ano: ano,
        _token: token
    }, function (html) {
        $("#box-grid").html(html);
    });

});

$(document).on("click", "#btn-gerar-relatorio-paciente-dia", function () {
    var ano = $("#relatorio-paciente-dia #ano").val();
    var mes = $("#relatorio-paciente-dia #mes").val();
    var arena = $("#relatorio-paciente-dia #arena").val();
    var medico = $("#relatorio-paciente-dia #medico").val();
    var token = $("input[name=_token]").val();

    notyInfo("Aguarde, seu relatorio está sendo gerado!");

    $.post("/admin/relatorio/pacientes-dias", {
        arena: arena,
        medico: medico,
        mes: mes,
        ano: ano,
        _token: token
    }, function (data) {
        if (data.success) {
            notySuccess("Relatório gerado com sucesso!");
            window.open(data.link);
        } else {
            notyFail("Não foi possivel gerar o relatorio");
        }
    });

});

$(document).on("click", "#btn-gerar-relatorio-producao-indicadores", function () {
    var lote = $("#relatorio-producao-indicadores #lote").val();
    var mes = $("#relatorio-producao-indicadores #mes").val();
    var ano = $("#relatorio-producao-indicadores #ano").val();
    var status = $("#relatorio-producao-indicadores #status").val();
    var token = $("input[name=_token]").val();

    $("#box-grid").html(html_loading);

    $.post("/admin/relatorio/indicadores-producao", {
        lote: lote,
        status: status,
        mes: mes,
        ano: ano,
        _token: token
    }, function (html) {
        $("#box-grid").html(html);
    });

});

$(document).on("click", "#btn-relatorio-aderencia-digitador", function () {
    var arena = $("#relatorio-aderencia-digitador #arena").val();
    var linha_cuidado = $("#relatorio-aderencia-digitador #linha_cuidado").val();
    var data_inicial = $("#relatorio-aderencia-digitador #data_inicial").val();
    var data_final = $("#relatorio-aderencia-digitador #data_final").val();
    var token = $("input[name=_token]").val();

    $("#box-grid").html(html_loading);

    $.post("/admin/relatorio/aderencia-digitador", {
        arena: arena,
        linha_cuidado: linha_cuidado,
        data_inicial: data_inicial,
        data_final: data_final,
        _token: token
    }, function (html) {
        $("#box-grid").html(html);
    });
});

$(document).on("click", "#btn-meta-procedimento", function () {
    var id = $(this).attr('rel');
    var arena = $("#relatorio-meta-faturamento #arena").val();
    var ano = $("#relatorio-meta-faturamento #ano").val();
    var mes = $("#relatorio-meta-faturamento #mes").val();
    var token = $("input[name=_token]").val();

    $("#box-grid").html(html_loading);

    $.post("/admin/faturamento-procedimento/meta-procedimento", {
        arena: arena,
        ano: ano,
        mes: mes,
        _token: token
    }, function (data) {
        $("#box-grid").html(data);
    });
});

$(document).on("change", ".checked-atendimento-procedimento", function () {
    var id = $(this).parents('tr').attr('data-id');

    atualizaAtedimentoProcedimento(id);
});

$(document).on("change", ".quantidade-procedimento-medicina-quantidade", function () {
    var id = $(this).parents('tr').attr('data-id');

    atualizaAtedimentoProcedimento(id);
});

function atualizaAtedimentoProcedimento(id) {

    var checked = $("#atendimento-procedimento-" + id).is(':checked') ? 1 : 0;
    var quantidade = $("#atendimento-quantidade-" + id).val();
    var autorizacao = $("#atendimento-autorizacao-" + id).val();
    var agenda = $("#atendimento-agenda").val();
    var atendimento = $("#atendimento-id").val();

    $.post("/admin/atendimento/atendimento-medico-procedimento-save", {
        procedimento: id,
        checked: checked,
        quantidade: quantidade,
        autorizacao: autorizacao,
        atendimento: atendimento,
        _token: $("input[name=_token]").val()
    }, function (data) {
        if (data.success) {
            var _agenda = [];
            _agenda.push(agenda);

            getDadosComplementaresFaturamentoAgendas(_agenda);
        }
    }, "json");
}

function getDadosComplementaresFaturamentoAgendas(agenda) {
    var tables = $("#table-atendimento-medico-faturamento tbody");
console.log('sdfsdf')
    $.ajax({
        type: 'POST',
        url: "/admin/atendimento/atendimento-medico-dados-complementar",
        dataType: "json",
        data: {
            _token: $("input[name=_token]").val(),
            agenda: agenda
        },
        success: function (data) {
            $.each(data, function (i, obj) {
                var line = tables.find("#" + i);

                var medico = obj.medico;
                var procedimentos = obj.procedimentos;

                if (medico && medico.nome != null) {

                    var medico = "<div class='alert alert-info' style='margin-bottom: 2px; padding: 4px;'><strong>" + medico.nome + "</strong><br />" + medico.cns + "</div>";
                    line.find(".box-medico").html(medico);

                    var obj = line.find(".box-codigo-atendimento");
                    obj.removeClass('bg-info');
                    obj.removeClass('bg-danger');
                    obj.addClass('bg-danger');
                }

                var _procedimento = "";
                $.each(procedimentos, function (i, item) {
                    var faturista = (item.faturista) ? "\n\nFATURISTA: " + item.faturista : "";
                    _procedimento += "<acronym title='" + item.nome + faturista + " '><strong >" + item.quantidade + "ss</strong> " + truncar(item.nome, 50) + "</acronym><br />";
                });

                line.find(".box-procedimento").html(_procedimento);
            });

            getDadosComplementaresFaturamentoAgendasArquivos(agenda)
        },
        async: true
    });
}


function getDadosComplementaresFaturamentoAgendasArquivos(agenda) {
    var tables = $("#table-atendimento-medico-faturamento tbody");

    $.ajax({
        type: 'POST',
        url: "/admin/atendimento/atendimento-medico-dados-complementar-anexos",
        dataType: "json",
        data: {
            _token: $("input[name=_token]").val(),
            agenda: agenda
        },
        success: function (data) {
            $.each(data, function (i, obj) {
                let line = tables.find("#" + i);

                let arquivos = obj.arquivos;
                let condutas = obj.condutas;

                let _btn_anexo = line.find('.btn-agenda-anexos-fechamento');
                let _btn_conduta = line.find('.btn-agenda-conduta-fechamento');

                if (arquivos > 0) {
                    _btn_anexo.html("Arquivos (" + arquivos + ")");
                    _btn_anexo.removeClass('btn-default');
                    _btn_anexo.addClass('btn-info');
                } else {
                    _btn_anexo.html("Arquivos");
                    _btn_anexo.removeClass('btn-info');
                    _btn_anexo.addClass('btn-default');
                }

                if (condutas > 0) {
                    _btn_conduta.html("Conduta")
                    _btn_conduta.removeClass('btn-info');
                    _btn_conduta.addClass('btn-info');
                }

            });
        },
        async: true
    });
}

function atualizaDataComplementar() {
    var agenda = [];
    var count = 0;

    $("#table-atendimento-medico-faturamento tbody tr").each(function () {
        agenda.push($(this).attr('id'));
        count++;

        if (count == 100) {
            count = 0;

            getDadosComplementaresFaturamentoAgendas(agenda);
            agenda = [];
        }
    });

    getDadosComplementaresFaturamentoAgendas(agenda);
}

function atualizaDataComplementarAgenda() {
    var agenda = [];
    var count = 0;

    $("#table-agenda-atendimento tbody tr").each(function () {
        agenda.push($(this).attr('id'));
        count++;

        if (count == 5) {
            count = 0;
            getDadosComplementaresAgendas(agenda);
            agenda = [];
        }
    });

    if (agenda.length > 0)
        getDadosComplementaresAgendas(agenda);
}

function getDadosComplementaresAgendas(agenda) {
    var tables = $("#table-agenda-atendimento tbody");

    $.ajax({
        type: 'POST',
        url: "/admin/agendas/dados-complementar",
        dataType: "json",
        data: {
            _token: $("input[name=_token]").val(),
            agenda: agenda
        },
        success: function (data) {
            $.each(data, function (i, obj) {
                var line = tables.find("#" + i);

                line.find(".box-biopsia").html(obj.html_biopsia);
                line.find(".box-atendimento-etapa").html(obj.html_atendimento_status);
                line.find(".box-agenda-status").html(obj.html_agenda_status);
            });

        },
        async: true
    });
}

function getAtendimentoAnexos(atendimento) {
    $.ajax({
        type: 'POST',
        url: "/admin/atendimento/anexos-listagem/" + atendimento,
        data: {
            _token: $("input[name=_token]").val()
        },
        success: function (html) {
            $("#box-atendimento-listagem").html(html);
        }
    });
}

function atualizaAtendimentoAgenda() {
    if ($("#atendimento_agenda").length) {
        var agenda = $("#atendimento_agenda").val();

        var _agenda = [];
        _agenda.push(agenda);

        getDadosComplementaresAgendas(_agenda);
    }
}

$(document).on("change", ".data-procedimento-contrato", function () {
    var obj = $(this).parents('tr');
    var demanda = obj.find(".demanda").val();
    var quantidade = obj.find(".quantidade").val();
    var valor_unitario = obj.find(".valor_unitario").val();
    var contrato = obj.attr("data-contrato");
    var lote = obj.attr("data-lote");
    var procedimento = obj.attr("data-procedimento");
    var token = $("input[name=_token]").val();

    $.post("/admin/contratos/procedimento-contrato", {
        demanda: demanda,
        quantidade: quantidade,
        valor_unitario: valor_unitario,
        contrato: contrato,
        lote: lote,
        procedimento: procedimento,
        _token: token
    }, function (data) {
        if (!data.success)
            notyFail("Não foi possivel atualizar o registro");
    }, "json");
});

$(document).on("change", ".btn-checked-medicamento-linha-cuidado", function () {
    var id = $(this).attr('rel');
    var def = $("#default_" + id).is(':checked') ? 1 : 0;
    var valor = $("#valor_" + id).val();
    var token = $("input[name=_token]").val();

    $.post("/admin/linha-cuidado/medicamentos", {
        id: id,
        default: def,
        valor: valor,
        _token: token
    }, function (data) {
        notySuccess("Registro atualizado com sucesso!");
    }, "json");
});

$(document).on("change", ".btn-checked-exame-linha-cuidado", function () {
    var linha_cuidado = $(this).attr('data-linha-cuidado');
    var exame = $(this).attr('data-exame');
    var def = $(this).is(':checked') ? 1 : 0;
    var token = $("input[name=_token]").val();

    $.post("/admin/exames/linha-cuidado", {
        linha_cuidado: linha_cuidado,
        exame: exame,
        default: def,
        _token: token
    }, function (data) {
        if (data.success)
            notySuccess("Registro atualizado com sucesso!");
        else
            notyFail(data.message);
    }, "json");
});

$(document).on("change", ".btn-checked-programa-linha-cuidado", function () {
    var token = $("input[name=_token]").val();
    var checked = $(this).is(':checked') ? 1 : 0;

    $.post("/admin/programas/linhas-cuidado", {
        linha_cuidado: $(this).attr('id-linha-cuidado'),
        programa: $(this).attr('id-programa'),
        checked: checked,
        _token: token
    }, function (data) {
        notySuccess("Registro atualizado com sucesso!");
    }, "json");
});

$(document).on("change", ".btn-checked-programa-arenas", function () {
    var token = $("input[name=_token]").val();
    var checked = $(this).is(':checked') ? 1 : 0;

    $.post("/admin/programas/arenas", {
        arena: $(this).attr('id-arenas'),
        programa: $(this).attr('id-programa'),
        checked: checked,
        _token: token
    }, function (data) {
        notySuccess("Registro atualizado com sucesso!");
    }, "json");
});

$(document).on("change", ".btn-checked-procedimento-cids", function () {
    var token = $("input[name=_token]").val();
    var checked = $(this).is(':checked') ? 1 : 0;

    $.post("/admin/procedimentos/cids", {
        procedimento: $(this).attr('id-procedimento'),
        cid: $(this).attr('id-cid'),
        checked: checked,
        _token: token
    }, function (data) {
        notySuccess("Registro atualizado com sucesso!");
    }, "json");
});

$(document).on("change", ".btn-checked-linha_cuidado-cids", function () {
    var token = $("input[name=_token]").val();
    var checked = $(this).is(':checked') ? 1 : 0;

    $.post("/admin/linha-cuidado/cids", {
        linha_cuidado: $(this).attr('id-linha-cuidado'),
        cid: $(this).attr('id-cid'),
        checked: checked,
        _token: token
    }, function (data) {
        notySuccess("Registro atualizado com sucesso!");
    }, "json");
});

$(document).on("change", ".btn-checked-guia-cids", function () {
    var token = $("input[name=_token]").val();
    var checked = $(this).is(':checked') ? 1 : 0;

    $.post("/admin/guias/cids", {
        guia: $(this).attr('id-guias'),
        cid: $(this).attr('id-cid'),
        checked: checked,
        _token: token
    }, function (data) {
        notySuccess("Registro atualizado com sucesso!");
    }, "json");
});

$(document).ready(function () {
    $(document).on("keyup", "#input_busca_procedimentos_cids", function (e) {
        filterTable(this, '#table_procedimentos_cids');
    });

    $(document).on("keyup", "#input_busca_linha_cuidado_cids", function (e) {
        filterTable(this, '#table_linha_cuidado_cids');
    });

    $(document).on("keyup", "#input_busca_guia_cids", function (e) {
        filterTable(this, '#table_guia_cids');
    });

    $(document).on("keyup", "#input_busca_programas_arenas", function (e) {
        filterTable(this, '#table_programas_arenas');
    });

    $(document).on("keyup", "#input_busca_programas_linha_cuidado", function (e) {
        filterTable(this, '#table_programas_linha_cuidado');
    });
});

function filterTable(idInput, idTable) {
    filter = new RegExp($(idInput).val(), 'i');
    $(idTable + " tbody tr").filter(function () {
        $(this).each(function () {
            found = false;
            $(this).find('.row_td_search').each(function () {
                content = $(this).html();
                if (content.match(filter)) {
                    found = true
                }
            });
            if (!found) {
                $(this).hide();
            } else {
                $(this).show();

            }
        });
    });
    $(idTable).removeClass('table-striped');
    $(idTable + " tbody tr").removeClass('odd');
    $(idTable + " tbody tr").removeClass('even');
    $(idTable + " tbody tr:visible").each(function (index) {
        $(this).toggleClass("odd", !!(index & 1));
    });
}

$(document).on("change", ".procedimento-meta-input", function () {
    var element = $(this);
    var id = element.attr('data-id');
    var arena = element.attr('data-arena');
    var linha_cuidado = element.attr('data-linha_cuidado');
    var ano = element.attr('data-ano');
    var mes = element.attr('data-mes');
    var procedimento = element.attr('data-procedimento');
    var valor = element.val();
    var token = $("input[name=_token]").val();

    $(".procedimento-meta-input").css('background-color', 'none');

    $.post("/admin/faturamento-procedimento/meta-procedimento-save", {
        id: id,
        arena: arena,
        linha_cuidado: linha_cuidado,
        ano: ano,
        mes: mes,
        procedimento: procedimento,
        valor: valor,
        _token: token
    }, function (data) {
        if (data.success) {
            element.attr('data-id', data.id);
            element.css('background-color', '#DEF9DE');

            (id.length > 0) ? notySuccess("Registro criado com sucesso!") : notySuccess("Registro atualizado com sucesso!");
        } else {
            element.css('background-color', '#F7C3C3');
            notyFail("Não foi possivel salvar o registro!");
        }
    }, "json");
});

$(document).on("change", ".exibicao_relatorio_data", function () {
    if ($(this).val() == 'idade') {
        if ($(this).is(':checked')) {
            $("tr.relatorio-detalhado-idade").css('display', '')
        } else {
            $("tr.relatorio-detalhado-idade").css('display', 'none')
        }
    }

    if ($(this).val() == 'sexo') {
        if ($(this).is(':checked')) {
            $("tr.relatorio-detalhado-sexo").css('display', '')
        } else {
            $("tr.relatorio-detalhado-sexo").css('display', 'none')
        }
    }
});

$(document).on("click", ".btn-action-perfil input", function () {

    var perfil = $("#perfil").val();
    var id = $(this).parents('tr').attr('data-id');

    var view = $("#ln-update-" + id + " input[name=view]").is(':checked') ? 1 : 0;
    var created = $("#ln-update-" + id + " input[name=created]").is(':checked') ? 1 : 0;
    var list = $("#ln-update-" + id + " input[name=list]").is(':checked') ? 1 : 0;
    var remove = $("#ln-update-" + id + " input[name=delete]").is(':checked') ? 1 : 0;
    var token = $("input[name=_token]").val();

    $.post("/admin/perfil/view", {
        perfil: perfil,
        permission: id,
        view: view,
        created: created,
        list: list,
        delete: remove,
        _token: token
    }, function (data) {

    }, "json");
});

$(document).on("click", "#relatorio-procedimento-contrato-xls", function (e) {
    e.preventDefault();

    var lotes = $("#relatorio-linha-cuidado-metrica #lote").val();
    var ano = $("#relatorio-linha-cuidado-metrica #ano").val();
    var mes = $("#relatorio-linha-cuidado-metrica #mes").val();
    // var status = $("#relatorio-linha-cuidado-metrica #status").val();
    var token = $("input[name=_token]").val();

    notyInfo("Aguarde, seu relatorio esta sendo gerado!")

    $.post("/admin/relatorio/faturamento-lote-gerencia-xls", {
        lotes: lotes,
        status: status,
        ano: ano,
        mes: mes,
        _token: token
    }, function (data) {
        if (data.success) {
            window.open(data.link);
        } else {
            notyFail("Não foi possivel gerar o relatorio");
        }

    }, "json");
});

$(document).on("click", ".btn-resultado-biopsia", function (e) {
    e.preventDefault();

    var atendimento = $(this).attr('rel');

    $.ajax({
        url: "/admin/atendimento/laudo-biopsia/" + atendimento,
        cache: false,
        success: function (body) {
            $("#modal-large .modal-body").html(body);
        }
    });

    setModalLarge("Resultado da Biopsia", html_loading);
});

$(document).on("click", ".btn-paciente-prontuario", function (e) {
    e.preventDefault();

    var paciente = $(this).attr('data-id');

    setModalLarge("Prontuário", html_loading);

    $.ajax({
        url: "/admin/pacientes/prontuario/" + paciente,
        cache: false,
        success: function (body) {
            $("#modal-large .modal-body").html(body);
        }
    });
});

$(document).on("click", ".btn-paciente-card-cies", function (e) {
    e.preventDefault();

    var cpf = $(this).attr('data-cpf');
    window.open("/admin/pacientes/print-card-cies/" + cpf, "card-cies");
});

$(document).on("click", ".btn-contrato-procedimentos", function (e) {
    e.preventDefault();

    setModalLarge("Procedimentos", html_loading);

    $.ajax({
        url: $(this).attr('href'),
        cache: false,
        success: function (body) {
            $("#modal-large .modal-body").html(body);
        }
    });
});

$(document).on("click", "#btn-prontuario-paciente-agenda", function (e) {
    e.preventDefault();

    var paciente = $(this).attr('data-id');

    $("#box-prontuario-paciente").html(html_loading);

    $.ajax({
        url: "/admin/pacientes/prontuario-agenda/" + paciente,
        cache: false,
        success: function (body) {
            $("#box-prontuario-paciente").html(body);
        }
    });
});

$(document).on("click", "#btn-anexo-paciente-agenda", function (e) {
    e.preventDefault();

    var paciente = $(this).attr('data-id');

    $("#box-prontuario-paciente").html(html_loading);

    $.ajax({
        url: "/admin/pacientes/anexos/" + paciente,
        cache: false,
        success: function (body) {
            $("#box-prontuario-paciente").html(body);
        }
    });
});

$(document).on("click", ".btn-perfil-view", function (e) {
    e.preventDefault();

    var perfil = $(this).attr('rel');

    $.ajax({
        url: "/admin/perfil/view/" + perfil,
        cache: false,
        success: function (body) {
            $("#modal-large .modal-body").html(body);
        }
    });

    setModalLarge("Permissoes", html_loading);
});


$(document).on("click", ".btn-linha_cuidado-medicamentos", function (e) {
    e.preventDefault();

    var href = $(this).attr('url');
    var title = $(this).attr('title');

    $.ajax({
        url: href,
        cache: false,
        success: function (body) {
            $("#modal-large .modal-body").html(body);
        }
    });

    setModalLarge(title, html_loading);
});

$(document).on("click", ".btn-linha_cuidado-exames", function (e) {
    e.preventDefault();

    var href = $(this).attr('url');
    var title = $(this).attr('title');

    $.ajax({
        url: href,
        cache: false,
        success: function (body) {
            $("#modal-large .modal-body").html(body);
        }
    });

    setModalLarge(title, html_loading);
});

$(document).on("click", ".btn-programas-linha_cuidado", function (e) {
    e.preventDefault();

    var href = $(this).attr('href');
    var title = $(this).attr('title');

    $.ajax({
        url: href,
        cache: false,
        success: function (body) {
            $("#modal-large .modal-body").html(body);
        }
    });

    setModalLarge(title, html_loading);
});

$(document).on("click", ".btn-programas-arenas", function (e) {
    e.preventDefault();

    var href = $(this).attr('href');
    var title = $(this).attr('title');

    $.ajax({
        url: href,
        cache: false,
        success: function (body) {
            $("#modal-large .modal-body").html(body);
        }
    });

    setModalLarge(title, html_loading);
});

$(document).on("click", ".btn-guias-cids", function (e) {
    e.preventDefault();

    var href = $(this).attr('href');
    var title = $(this).attr('title');

    $.ajax({
        url: href,
        cache: false,
        success: function (body) {
            $("#modal-large .modal-body").html(body);
        }
    });

    setModalLarge(title, html_loading);
});

$(document).on("click", ".btn-procedimentos-cids", function (e) {
    e.preventDefault();

    var href = $(this).attr('href');
    var title = $(this).attr('title');

    $.ajax({
        url: href,
        cache: false,
        success: function (body) {
            $("#modal-large .modal-body").html(body);
        }
    });

    setModalLarge(title, html_loading);
});

$(document).on("click", ".btn-linha_cuidado-cids", function (e) {
    e.preventDefault();

    var href = $(this).attr('href');
    var title = $(this).attr('title');

    $.ajax({
        url: href,
        cache: false,
        success: function (body) {
            $("#modal-large .modal-body").html(body);
        }
    });

    setModalLarge(title, html_loading);
});

$("#form-atendimento input").on('keypress ', function (e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        $("#btn-search-agenda").click();
    }
});

$("#btn-search-agenda").on("click", function (e) {
    e.preventDefault();

    var arena = $("#form-atendimento #arena").val();
    var linha_cuidado = $("#form-atendimento #linha_cuidado").val();
    var paciente = $("#form-atendimento #paciente").val();
    var data = $("#form-atendimento #data").val();
    var token = $("input[name=_token]").val();

    $("#box-grid").html(html_loading);

    $.get("/admin/agendas/grid", {
        arena: arena,
        linha_cuidado: linha_cuidado,
        paciente: paciente,
        data: data,
        _token: token
    }, function (data) {
        $("#box-grid").html(data);
    });

});

$("#btn-faturamento-fechamento").on("click", function (e) {
    e.preventDefault();

    var procedimento = $("#form-fechamento #procedimento").val();
    var arena = $("#form-fechamento #arena").val();
    var linha_cuidado = $("#form-fechamento #linha_cuidado").val();
    var data = $("#form-fechamento #data").val();
    var status = $("#form-fechamento #status").val();
    var profissional = $("#form-fechamento #profissional").val();
    var token = $("input[name=_token]").val();

    if (arena.length == 0) {
        notyFail("Informe uma arena para pesquisa");
    } else if (linha_cuidado.length == 0) {
        notyFail("Informe uma especialidade para pesquisa");
    } else if (data.length < 10) {
        notyFail("Data inválida ou não informada!");
    } else {
        $("#box-grid").html(html_loading);

        $.get("/admin/faturamento-procedimento/fechamento-pesquisa", {
            arena: arena,
            procedimento: procedimento,
            profissional: profissional,
            status: status,
            linha_cuidado: linha_cuidado,
            data: data,
            _token: token
        }, function (data) {
            $("#box-grid").html(data);
        });
    }

});

$(document).on("click", ".agenda-checked", function (e) {
    var total = 0;

    $("input.agenda-checked:checked").each(function () {
        total += 1;
    });

    $("#btn-atualizacao-atendimento b").html(total);
});

$(document).on("click", "#btn-atualizacao-atendimento", function (e) {
    e.preventDefault();

    var atendimentos = [];
    $("input.agenda-checked:checked").each(function () {
        atendimentos.push($(this).val());
    });

    var medico = $("#medico").val();
    var quantidade = $("#quantidade").length ? $("#quantidade").val() : 0;
    var procedimento = $("#procedimento").length ? $("#procedimento").val() : null;
    var linha_cuidado = $("#linha_cuidado").length ? $("#linha_cuidado").val() : null;
    var arena = $("#arena").length ? $("#arena").val() : null;

    if (medico.length == 0) {
        notyFail(user['nome'] + ", nenhum médicos está selecionado!");
    } else if (atendimentos.length == 0 && quantidade == 0) {
        notyFail(user['nome'] + ", nenhum atendimento foi selecionado!");
    } else {
        var medico_nome = $('#medico option:selected').html();
        var qtd = (quantidade > 0) ? quantidade : atendimentos.length;

        noty({
            text: user['nome'] + ", você tem certeza que deseja atualizar " + qtd + " atendimento(s) para o médico " + medico_nome,
            animation: {
                open: 'animated bounceInLeft',
                close: 'animated bounceOutLeft',
                easing: 'fade',
                speed: 500
            },
            layout: 'center',
            type: 'primary',
            buttons: [
                {
                    addClass: 'btn btn-danger', text: 'Cancelar', onClick: function ($noty) {
                        $noty.close();
                    }
                },
                {
                    addClass: 'btn btn-primary', text: 'Atualizar', onClick: function ($noty) {
                        notyInfo(user['nome'] + " aguarde, o sistema está processando sua validação");

                        var agendas = [];
                        if (quantidade > 0) {
                            $("#table-atendimento-medico-faturamento tbody tr").each(function () {
                                agendas.push($(this).attr('id'));
                            });
                        }

                        $.post("/admin/atendimento/atendimento-medico-massa", {
                            agendas: agendas,
                            quantidade: quantidade,
                            arena: arena,
                            linha_cuidado: linha_cuidado,
                            procedimento: procedimento,
                            medico: medico,
                            atendimentos: atendimentos,
                            _token: $("input[name=_token]").val()
                        }, function (data) {
                            var obj = $.parseJSON(data);

                            if (obj.message) {
                                notySuccess(obj.message);
                            } else {
                                notySuccess("Atendimentos atualizado com sucesso!");
                            }

                            $("#btn-faturamento-fechamento").click();
                        }).fail(function () {
                            notyFail("Não foi possivel executar a solicitação");
                        }, "json");

                        $noty.close();
                    }
                }
            ],
            timeout: 100,
            killer: true,
            force: true,
            modal: modal
        });
    }

});

$(document).on("click", ".btn-agenda-anexos-fechamento", function (e) {
    e.preventDefault();

    var agenda = $(this).attr('agenda');

    setModal("Atendimento (Anexos)", html_loading);

    $.ajax({
        url: "/admin/atendimento/anexos/" + agenda,
        type: 'GET',
        success: function (html) {
            setModalBody(html);

            var _agenda = [];
            _agenda.push(agenda);

            getDadosComplementaresFaturamentoAgendas(_agenda);
        }
    });
});

$(document).on("click", ".btn-fechamento-anexo-remove", function (e) {
    e.preventDefault();

    let obj = $(this);
    let line = $(this).parents('tr');

    noty({
        text: user['nome'] + ", deseja realmente remover o arquivo?",
        animation: {
            open: 'animated bounceInLeft',
            close: 'animated bounceOutLeft',
            easing: 'fade',
            speed: 500
        },
        layout: 'center',
        type: 'primary',
        buttons: [
            {
                addClass: 'btn btn-danger', text: 'Cancelar', onClick: function ($noty) {
                    $noty.close();
                }
            },
            {
                addClass: 'btn btn-success', text: 'Remover Arquivo', onClick: function ($noty) {
                    let id = obj.attr('data-id');
                    let atendimento = obj.attr('data-atendimento');
                    let agenda = obj.attr('data-agenda');

                    $.post("/admin/atendimento/anexos-delete", {id: id, _token: token}, function (data) {
                        getAtendimentoAnexos(atendimento);

                        var _agenda = [];
                        _agenda.push(agenda);

                        getDadosComplementaresFaturamentoAgendas(_agenda);
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

$(document).on("click", ".btn-agenda-conduta-fechamento", function (e) {
    e.preventDefault();

    var agenda = $(this).attr('agenda');

    setModal("Atendimento (Conduta)", html_loading);

    $.ajax({
        url: "/admin/atendimento/conduta/" + agenda,
        type: 'GET',
        success: function (html) {
            setModalBody(html);

            var _agenda = [];
            _agenda.push(agenda);

            getDadosComplementaresFaturamentoAgendas(_agenda);
        }
    });
});

$(document).on("change", ".checked-atendimento input", function (e) {
    e.preventDefault();
    var obj = $(this);

    if (obj.is(":checked")) {
        $.post("/admin/atendimento/faturamento-valida-atendimento", {
            _token: token,
            agenda: $(this).parents('tr').attr('id')
        }, function (data) {
            if (!data.success) {
                obj.attr("checked", false);
                notyFail(data.message);
            }
        }, "json");
    }
});

$(document).on("click", ".btn-reset-atendimento-faturado", function (e) {
    e.preventDefault();

    var agenda = $(this).parents('tr').attr('id');

    $.post("/admin/atendimento/reset-agenda", {
        agenda: agenda,
        _token: token
    }, function (data) {
        if (data.success) {
            $("tr#" + agenda).find(".box-reset").remove();
            $("tr#" + agenda).find(".box-medico").remove();
            $("tr#" + agenda).find(".box-status").html('Atendimento');

            var obj = $("tr#" + agenda).find(".box-codigo-atendimento");
            obj.removeClass('bg-info');
            obj.removeClass('bg-danger');
            obj.addClass('bg-info');

            $("tr#" + agenda).find(".checked-atendimento").removeClass("display-none");

            var _agenda = [];
            _agenda.push(agenda);

            getDadosComplementaresFaturamentoAgendas(_agenda);

            var btn_procedimento = '<div class="align-right"><br /><button class="btn btn-xs btn-success waves-effect btn-atendimento-medico-procedimento" data-id="' + agenda + '" >PROCEDIMENTOS</button></div>';
            $("tr#" + agenda).find(".box-procedimento-button").html(btn_procedimento);
        } else {
            notyFail(data.message);
        }

    }, "json");

});

$(document).on("keyup", "#box-atualizacao-massa #quantidade", function (e) {
    $("#btn-atualizacao-atendimento b").html($(this).val());
});

$(document).on("click", "#btn-faturamento-ativar", function (e) {
    e.preventDefault();

    var faturamento = $(this).attr('data-faturamento');

    $.post("/admin/faturamento/ativar", {
        faturamento: faturamento,
        _token: $("input[name=_token]").val()
    }, function (data) {
        if (data.success) {
            notySuccess(data.message);
            window.location.reload();
        } else {
            notyFail(data.message);
        }
    }, 'json');
});

$(document).on("click", "#btn-faturamento-fechar", function (e) {
    e.preventDefault();

    var faturamento = $(this).attr('data-faturamento');

    $.post("/admin/faturamento/fechar", {
        faturamento: faturamento,
        _token: $("input[name=_token]").val()
    }, function (data) {
        if (data.success) {
            notySuccess(data.message);
            window.location.reload();
        } else {
            notyFail(data.message);
        }
    }, 'json');
});

$(document).on("click", ".btn-atendimento-medico-procedimento", function (e) {
    e.preventDefault();

    var agenda = $(this).attr('data-id');

    setModalLarge('Procedimentos', html_loading);

    $.post("/admin/atendimento/atendimento-medico-procedimento", {
        agenda: agenda,
        _token: $("input[name=_token]").val()
    }, function (data) {
        var _agenda = [];
        _agenda.push(agenda);

        getDadosComplementaresFaturamentoAgendas(_agenda);

        setModalBodyLarge(data);
    });
});

$(document).on("click", "#btn-gerar-relatorio-bpa", function (e) {
    e.preventDefault();

    var faturamento = $("#relatorio-bpa #faturamento").val();
    var lote = $("#relatorio-bpa #lote").val();
    var token = $("input[name=_token]").val();

    if (lote.length == 0) {
        notyFail('Lote inválido ou não selecionado');
    } else if (faturamento.length == 0) {
        notyFail('Faturamento inválido ou não selecionado');
    } else {
        $("#box-grid").html(html_loading);

        $.post("/admin/relatorio/bpa-file", {
            faturamento: faturamento,
            lote: lote,
            _token: token
        }, function (data) {
            $("#box-grid").html(data);
        });
    }
});

$(document).on("click", "#btn-gerar-relatorio-apac", function (e) {
    e.preventDefault();

    var faturamento = $("#relatorio-apac #faturamento").val();
    var lote = $("#relatorio-apac #lote").val();
    var token = $("input[name=_token]").val();

    if (lote.length == 0) {
        notyFail('Lote inválido ou não selecionado');
    } else if (faturamento.length == 0) {
        notyFail('Faturamento inválido ou não selecionado');
    } else {
        $("#box-grid").html(html_loading);

        $.post("/admin/relatorio/apac-file", {
            faturamento: faturamento,
            lote: lote,
            _token: token
        }, function (data) {
            $("#box-grid").html(data);
        });
    }
});

$(document).on("click", "#btn-gerar-relatorio-tempo", function (e) {
    e.preventDefault();

    var arena = $("#relatorio-tempo #arena").val();
    var linha_cuidado = $("#relatorio-tempo #linha_cuidado").val();
    var data_inicial = $("#relatorio-tempo #data_inicial").val();
    var data_final = $("#relatorio-tempo #data_final").val();
    var medico = $("#relatorio-tempo #medico").val();
    var token = $("input[name=_token]").val();

    $("#box-grid").html(html_loading);

    $.post("/admin/relatorio/relatorio-tempo", {
        arena: arena,
        linha_cuidado: linha_cuidado,
        data_inicial: data_inicial,
        data_final: data_final,
        medico: medico,
        _token: token
    }, function (data) {
        $("#box-grid").html(data);
    });

});

$(document).on("click", "#btn-gerar-relatorio-biopsia", function (e) {
    e.preventDefault();

    var ano = $("#relatorio-biopsia #ano").val();
    var mes = $("#relatorio-biopsia #mes").val();
    var arena = $("#relatorio-biopsia #arena").val();
    var linha_cuidado = $("#relatorio-biopsia #linha_cuidado").val();
    var medico = $("#relatorio-biopsia #medico").val();

    var token = $("input[name=_token]").val();

    if (ano.length == 0) {
        notyFail('Selecione um ANO para pesquisa!');
    } else {
        $("#box-grid").html("<div class='alert alert-info align-center'>AGUARDE! Relatório está sendo processado!</div>");

        $.post("/admin/relatorio/biopsia", {
            ano: ano,
            mes: mes,
            linha_cuidado: linha_cuidado,
            arena: arena,
            medico: medico,
            _token: token
        }, function (data) {
            $("#box-grid").html(data);
            $("#btn-download-arquivo-biopsia").click();
        });
    }
});

$(document).on("click", "#btn-gerar-relatorio-biopsia-resumo", function (e) {
    e.preventDefault();

    var ano = $("#relatorio-biopsia #ano").val();
    var mes = $("#relatorio-biopsia #mes").val();
    var arena = $("#relatorio-biopsia #arena").val();
    var linha_cuidado = $("#relatorio-biopsia #linha_cuidado").val();

    var token = $("input[name=_token]").val();

    if (ano.length == 0) {
        notyFail('Selecione um ANO para pesquisa!');
    } else {
        $("#box-grid").html("<div class='alert alert-info align-center'>AGUARDE! Relatório está sendo processado!</div>");

        $.post("/admin/relatorio/biopsia-resumo", {
            ano: ano,
            mes: mes,
            linha_cuidado: linha_cuidado,
            arena: arena,
            _token: token
        }, function (data) {
            $("#box-grid").html(data);
        });
    }
});

$(document).on("click", ".btn-save-resultado-laudo", function (e) {
    e.preventDefault();

    var laudo = $(this).attr('rel');
    var status_biopsia = $("#laudo-" + laudo + " #status_biopsia").val();
    var resultado_biopsia = $("#laudo-" + laudo + " #descricao").val();

    if (status_biopsia == 0) {
        notyFail('Informe um status para gravação!');
    } else if (resultado_biopsia.length == 0) {
        notyFail('Informe uma descrição para gravação!');
    } else {
        $.post("/admin/atendimento/laudo", {
            laudo: laudo,
            status_biopsia: status_biopsia,
            resultado_biopsia: resultado_biopsia,
            _token: $("input[name=_token]").val()
        }, function (data) {
            notySuccess("Registro gravado com sucesso");
            atualizaAtendimentoAgenda();
        });
    }

});

$(document).on("click", ".btn-delete-cirugia-linha-cuidado-procedimentos", function (e) {
    e.preventDefault();

    var tr = $(this).parent().parent();
    var procedimento = tr.attr('data-procedimento');
    var linha_cuidado = tr.attr('data-linha-cuidado');

    $.post("/admin/cirugia-linha-cuidado/delete-procedimentos", {
        procedimento: procedimento,
        linha_cuidado: linha_cuidado,
        _token: $("input[name=_token]").val()
    }, function (data) {
        tr.find('.quantidade').val("");

        notyInfo("Registro removido com sucesso!");
    });

});

$(document).on("click", "#btn-gerar-relatorio-faturamento", function (e) {
    e.preventDefault();

    var faturamento = $("#relatorio-faturamento #faturamento").val();
    var lote = $("#relatorio-faturamento #lote").val();
    var arena = $("#relatorio-faturamento #arena").val();
    var linha_cuidado = $("#relatorio-faturamento #linha_cuidado").val();
    var medico = $("#relatorio-faturamento #medico").val();
    var token = $("input[name=_token]").val();

    $("#box-grid").html(html_loading);

    $.post("/admin/relatorio/faturamento-grid", {
        faturamento: faturamento,
        lote: lote,
        arena: arena,
        linha_cuidado: linha_cuidado,
        medico: medico,
        _token: token
    }, function (data) {
        $("#box-grid").html(data);
    });

});


$("#bpa-file-download").on("click", function (e) {
    e.preventDefault();

    window.location = "/admin/relatorio/bpa-file-download";
});

$(document).on("click", "#btn-gerar-relatorio-procedimentos", function (e) {
    e.preventDefault();

    var arena = $("#bparelatorio-procedimentos #arena").val();
    var linha_cuidado = $("#bparelatorio-procedimentos #linha_cuidado").val();
    var ano = $("#bparelatorio-procedimentos #ano").val();
    var mes = $("#bparelatorio-procedimentos #mes").val();
    var medico = $("#bparelatorio-procedimentos #medico").val();
    var finalizacao = $("#bparelatorio-procedimentos #finalizacao").val();
    var token = $("input[name=_token]").val();

    if (arena.length == 0) {
        notyFail('Arena inválida!');
    } else {
        $("#box-grid").html(html_loading);

        $.post("/admin/relatorio/grid-procedimentos", {
            arena: arena,
            linha_cuidado: linha_cuidado,
            finalizacao: finalizacao,
            ano: ano,
            mes: mes,
            medico: medico,
            _token: token
        }, function (data) {
            $("#box-grid").html(data);

        });
    }
});

$(".btn-new-entry").on("click", function (e) {
    e.preventDefault();

    window.location.href = getUri() + "/entry";
});

$(document).on("click", "#box-grid .pagination li  a", function (e) {
    e.preventDefault();

    var page = $(this).html();

    var uri = getUri() + "/grid";

    var uri_current = $("#url-pagination").val();
    if (uri_current != undefined && uri_current != "") {
        uri = uri_current;
    }

    loadGRID2(uri, page);
});

$(document).on("click", ".btn-back-listagem", function (e) {
    e.preventDefault();

    window.location.href = getUri() + "/list";
});

$(document).on("click", ".btn-submit", function (e) {
    e.preventDefault();

    $(this).parents('form').submit();
});

$(document).on("click", ".btn-a-post", function (e) {
    e.preventDefault();
    $("#box-body").html("");

    notyInfo("Aguarde, seu relatorio está sendo processado!");
    var uri = $(this).attr('href');
    $.post(uri, {_token: token}, function (data) {
        $("#box-body").html(data);
    }).fail(function (xhr, status, error) {
        notyFail("Não foi possivel processar o relatorio!")
        printErrorBoxAlert(xhr, false);
    });

});

$(document).on("click", "a.btn-grid-delete", function (e) {
    e.preventDefault();

    if (confirm(msg.remove_registro)) {
        var uri_current = $("#url-pagination").val();
        var uri = $(this).attr('href');

        $.getJSON(uri, function (response) {
            if (response.status) {
                loadGRID(uri_current, null);
            }
        });
    }

    return false;
});

$(document).on("change", "select.combo-estado", function (e) {
    var estado = $(this).val();

    if (estado) {
        var uri = '/admin/cidades/by-estado/' + estado;

        $.getJSON(uri, function (response) {
            if (response.status) {
                $select = $(".combo-cidade");
                $select.find('option').remove();

                $('<option>').val('').text('...').appendTo($select);
                $.each(response.data, function (key, value) {
                    $('<option>').val(key).text(value).appendTo($select);
                });

                $(".combo-cidade").val($("#combo-cidade").val());
                $('.chosen').trigger('chosen:updated');
            }
        });
    }
});

$(document).on("change", "select.combo-profissional", function (e) {
    var perfil = $(this).val();

    if (perfil) {
        var uri = '/admin/profissionais/combo/' + perfil;

        $.getJSON(uri, function (response) {
            if (response.status) {
                $select = $("select#profissional");
                $select.find('option').remove();

                $('<option>').val('').text('...').appendTo($select);
                $.each(response.data, function (key, value) {
                    $('<option>').val(key).text(value).appendTo($select);
                });

                $("select#profissional").val($("#combo-profissional").val());
                $('.chosen').trigger('chosen:updated');
            }
        });
    }
});

$(document).on("change", "select.combo-especialidade-profissionais", function (e) {
    var especialidade = $(this).val();

    $select = $("select.profissionais");
    $select.find('option').remove();
    $('<option>').val('').text('...').appendTo($select);
    $('.chosen').trigger('chosen:updated');

    if (especialidade) {
        var uri = '/admin/linha-cuidado/profissionais/' + especialidade;

        $.getJSON(uri, function (response) {
            if (response.status) {
                $.each(response.data, function (key, value) {
                    $('<option>').val(key).text(value).appendTo($select);
                });
                $('.chosen').trigger('chosen:updated');
            }
        });
    }
});


$(document).on("click", "#btn-faturamento-procedimentos-medico", function (e) {
    e.preventDefault();

    var data = $(this).parents('form').serialize();
    $("#box-grid").html(html_loading);
    notyInfo("Aguarde, seu relatorio está sendo gerado!");

    $.post("/admin/relatorio/faturamento-procedimentos-medico", data,
        function (data) {
            $("#box-grid").html(data);
        })
        .fail(function (xhr, status, error) {
            printErrorBoxAlert(xhr, false);
        });

});

$(document).on("click", "#btn-relatorio-procedimentos-agenda-falta-gordura", function (e) {
    e.preventDefault();

    var data = $(this).parents('form').serialize();
    $("#box-grid").html(html_loading);
    notyInfo("Aguarde, seu relatorio está sendo gerado!");

    $.post("/admin/relatorio/agenda-producao", data,
        function (data) {
            $("#box-grid").html(data);
        })
        .fail(function (xhr, status, error) {
            printErrorBoxAlert(xhr, false);
        });

});

$(document).on("click", "#btn-relatorio-ajax", function (e) {
    e.preventDefault();

    var data = $(this).parents('form').serialize();
    $("#box-grid").html(html_loading);
    notyInfo("Aguarde, seu relatorio está sendo gerado!");

    $.post(window.location.pathname, data,
        function (data) {
            $("#box-grid").html(data);
        })
        .fail(function (xhr, status, error) {
            printErrorBoxAlert(xhr, false);
        });

});

$(document).on("change", "select.combo-especialidade-procedimentos", function (e) {
    var especialidade = $(this).val();

    $select_procedimentos = $("select.procedimentos");
    $select_procedimentos.find('option').remove();
    $('<option>').val('').text('...').appendTo($select_procedimentos);
    $('.chosen').trigger('chosen:updated');

    if (especialidade) {
        var uri = '/admin/linha-cuidado/procedimentos/' + especialidade;

        $.getJSON(uri, function (response) {
            if (response.status) {
                $.each(response.data, function (key, value) {
                    $('<option>').val(key).text(value).appendTo($select_procedimentos);
                });
                $('.chosen').trigger('chosen:updated');
            }
        });
    }

});

$(document).on("change", "select.combo-especialidade-procedimentos-principais", function (e) {
    var especialidade = $(this).val();

    $select_procedimentos = $("select.procedimentos");
    $select_procedimentos.find('option').remove();
    $('<option>').val('').text('...').appendTo($select_procedimentos);
    $('.chosen').trigger('chosen:updated');

    if (especialidade) {
        var uri = '/admin/linha-cuidado/procedimentos-principais/' + especialidade;

        $.getJSON(uri, function (response) {
            if (response.status) {
                $.each(response.data, function (key, value) {
                    $('<option>').val(key).text(value).appendTo($select_procedimentos);
                });
                $('.chosen').trigger('chosen:updated');
            }
        });
    }

});


$(document).on("change", "select.combo-arena-equipamentos", function (e) {
    var id = $(this).val();

    $select = $("select#equipamento");
    $select.find('option').remove();
    $('<option>').val('').text('...').appendTo($select);
    $select.trigger("chosen:updated");

    if (id && $select.length) {
        var uri = '/admin/arena-equipamentos/arena/' + id;
        $.getJSON(uri, function (response) {
            if (response.status) {
                $.each(response.data, function (key, value) {
                    $('<option>').val(key).text(value).appendTo($select);
                });

                $select.val($("#combo-equipamento").val());
                $select.trigger("chosen:updated");
            }
        });
    }
});

$(document).on("change", "select.combo-arena-equipamentos", function (e) {
    var id = $(this).val();

    $select = $("select.equipamento");
    $select.find('option').remove();
    $('<option>').val('').text('...').appendTo($select);
    $select.trigger("chosen:updated");

    if (id && $select.length) {
        var uri = '/admin/arena-equipamentos/arena/' + id;
        $.getJSON(uri, function (response) {
            if (response.status) {
                $.each(response.data, function (key, value) {
                    $('<option>').val(key).text(value).appendTo($select);
                });

                $select.trigger("chosen:updated");
            }
        });
    }
});

$(document).on("change", "select.profissional", function (e) {
    $('#combo-profissional').val($(this).val());
});

$(document).on("change", "select.profissionais", function (e) {
    $('#combo-profissional').val($(this).val());
});

$(document).on("change", "select.profissionais", function (e) {
    $('#combo-profissional').val($(this).val());
});

$(document).on("change", "select.linha_cuidado", function (e) {
    $('#combo-linha_cuidado').val($(this).val());
});

$(document).on("change", "select.equipamento", function (e) {
    $('#combo-equipamento').val($(this).val());
});

$(document).on("change", "select.combo-cidade", function (e) {
    $('#combo-cidade').val($(this).val());
});

$(document).on("click", ".btn-gerar-atendimento", function (e) {
    e.preventDefault();

    var url = "/admin/atendimento/view/" + $(this).attr('rel');

    $.ajax({
        url: url,
        cache: false,
        success: function (data) {
            setModalLarge('Agendamento', data);
        }
    });

});


$(document).on("click", "#btn-gerar-relatorio-conduta", function (e) {
    e.preventDefault();

    var url = "/admin/relatorio/conduta-grid";

    $.ajax({
        url: url,
        cache: false,
        type: "POST",
        data: $("#relatorio-producao form").serialize(),
        success: function (data) {
            $("#box-grid").html(data);
        }
    });
});

$(document).on("click", ".btn-relatorio-conduta-medico", function (e) {
    e.preventDefault();

    var id = $(this).attr('id');
    var url = "/admin/relatorio/conduta-data?" + $("form#relatorio-conduta-" + id).serialize();

    window.open(url, "relatorio-producao");
});

$(document).on("click", "#btn-gerar-relatorio-producao", function (e) {
    e.preventDefault();

    var url = "/admin/relatorio/producao-grid";

    $.ajax({
        url: url,
        cache: false,
        type: "POST",
        data: $("#relatorio-producao form").serialize(),
        success: function (data) {
            $("#box-grid").html(data);
        }
    });
});


$(document).on("click", "#btn-listagem-atendimento-paciente", function (e) {
    e.preventDefault();

    var url = "/admin/listagem/atendimento-pacientes";
    $("#box-grid").html(html_loading);

    $.ajax({
        url: url,
        cache: false,
        type: "POST",
        data: $("form#listagem-atendimento-paciente").serialize(),
        success: function (data) {
            $("#box-grid").html(data);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            printErrorBoxAlert(xhr, false);
        }
    });
});

$(document).on("click", "#btn-gerar-relatorio-producao-exportacao", function (e) {
    e.preventDefault();

    var url = "/admin/relatorio/producao-exportacao-grid";

    notyInfo("Aguarde, seu relatorio esta sendo processando!");

    $.ajax({
        url: url,
        cache: false,
        type: "POST",
        data: $("#relatorio-producao form").serialize(),
        success: function (data) {
            notySuccess("Relatorio gerado com sucesso!");
            $("#box-grid").html(data.download);
            openDownload(data.link);
        },
        error: function (xhr, ajaxOptions, thrownError) {
            notyFail(thrownError);
        }
    }, "json");
});

$(document).on("click", ".btn-agenda-cancelar", function (e) {
    e.preventDefault();

    var url = "/admin/agendas/cancelar/" + $(this).attr('data-id');

    $.ajax({
        url: url,
        cache: false,
        type: "GET",
        success: function () {
            $("#btn-search-agenda").click();
        }
    });
});

$(document).on("click", ".btn-relatorio-producao-medico", function (e) {
    e.preventDefault();

    var id = $(this).attr('id');
    var url = "/admin/relatorio/producao-data?" + $("form#relatorio-producao-" + id).serialize();

    window.open(url, "relatorio-producao");
});

$(document).on("click", ".btn-termo-responsabilidade", function (e) {
    e.preventDefault();

    var url = "/admin/impressao/termo-consentimento/" + $(this).attr('rel');

    window.open(url, "print-termo");
});

$(document).on("click", ".btn-check-list", function (e) {
    e.preventDefault();

    var url = "/admin/atendimento/check-list/" + $(this).attr('rel');

    $.ajax({
        url: url,
        cache: false,
        success: function (data) {
            setModalLarge('Check-list', data);
        }
    });

});

$(document).on("keyup", "#input-cirugia-procedimentos", function (e) {
    var index = $(this).parent().index();

    var nth = "#table-cirugia-procedimentos td:nth-child(" + (index + 2).toString() + ")";
    var valor = $(this).val().toUpperCase();

    $("#table-cirugia-procedimentos  tbody tr").show();

    $(nth).each(function () {
        if ($(this).text().toUpperCase().indexOf(valor) < 0) {
            $(this).parent().hide();
        }
    });

});

$(document).on("click", ".btn-cirugia-linha-cuidado-procedimentos", function (e) {
    e.preventDefault();

    var url = $(this).attr('url');
    var title = $(this).attr('title');

    $.ajax({
        url: url,
        cache: false,
        success: function (data) {
            setModalLarge(title, data);
        }
    });

});

$(document).on("click", ".box-line-relatorio", function (e) {
    e.preventDefault();

    var id = $(this).attr('rel');

    setModalLarge('Importação', $('#box-line-' + id).find('td').html());
});

$(document).on("click", ".btn-lote-view", function (e) {
    e.preventDefault();

    var url = "/admin/lotes/arena/" + $(this).attr('rel');

    $.ajax({
        url: url,
        cache: false,
        success: function (data) {
            setModalLarge('Lote > Arenas', data);
        }
    });
});

$(document).on("click", ".btn-lote-linha_cuidado-view", function (e) {
    e.preventDefault();

    var url = "/admin/lotes/linha-cuidado/" + $(this).attr('rel');

    $.ajax({
        url: url,
        cache: false,
        success: function (data) {
            setModalLarge('Lote > Arenas > Especialidade', data);
        }
    });
});

$(document).on("click", ".btn-lote-profissionais-view", function (e) {
    e.preventDefault();

    var url = "/admin/lotes/profissionais/" + $(this).attr('rel');

    $.ajax({
        url: url,
        cache: false,
        success: function (data) {
            setModalLarge('Gerenciamento de CNES (Lote / Profissional)', data);
        }
    });
});

$(document).on("click", ".btn-close-cbos", function (e) {
    e.preventDefault();

    $(".cbo-list").html("");
});

$(document).on("click", ".btn-lote-procedimento-lista-cbos", function (e) {
    e.preventDefault();

    var lote_profissional = $(this).parents('tr').attr('data-id');

    $("#cbo-list-" + lote_profissional).html(html_loading);

    $.ajax({
        url: "/admin/lotes/list-cbos/" + lote_profissional,
        cache: false,
        success: function (data) {
            $(".cbo-list").html("");
            $("#cbo-list-" + lote_profissional).html(data);
        }
    });
});

$(document).on("click", ".btn-submit-lote-profissional", function (e) {
    e.preventDefault();

    var profissional = $("#box-form-profissional #profissional").val();
    var lote = $("#box-form-profissional #lote").val();

    $.post("/admin/lotes/profissionais", {
        profissional: profissional,
        lote: lote,
        _token: token
    }, function (data) {
        if (data.success) {
            $("#box-form-profissional #profissional").val('');
            $("#box-form-profissional #profissional").trigger("chosen:updated");
            atualizaGridLoteProfissionais();
        } else {
            notyFail(data.message);
        }
    }, "json");
});

$(document).on("click", ".btn-remove-lote-profissional", function (e) {
    e.preventDefault();

    var lote_profissional = $(this).attr('id');

    if (confirm("Deseja realmente remover o medico deste lote")) {
        $.post("/admin/lotes/profissionais-delete", {
            lote_profissional: lote_profissional,
            _token: token
        }, function (data) {
            if (data.success) {
                atualizaGridLoteProfissionais();
            } else {
                notyFail(data.message);
            }
        }, "json");
    }
});

$(document).on("change", ".lote-profissional-cbo", function (e) {
    e.preventDefault();

    var lote_profissionais_id = $(this).parents('tr').attr('data-id');
    var lote = $(this).attr('data-lote');
    var profissional = $(this).attr('data-profissional');
    var cbo = $(this).attr('id');
    var checked = $(this).is(':checked') ? 1 : 0;

    $.post("/admin/lotes/lote-profissionais-cbo", {
        profissional: profissional,
        lote: lote,
        cbo: cbo,
        checked: checked,
        _token: token
    }, function (data) {
        if (data.success) {
            atualizaGridLoteProfissionaisCbo();
        } else {
            notyFail(data.message);
        }
    }, "json");
});

$(document).on("change", ".conduta_tipo_atendimentos", function (e) {
    e.preventDefault();

    var atendimento = $("input[name=atendimento]").val();
    var _token = $("input[name=_token]").val();
    var tipo_atendimento = $(this).val();

    $select = $("select.combo_condutas");
    $select_regulacao = $("select.combo_conduta_regulacao");
    $select.find('option').remove();
    $select_regulacao.find('option').remove();
    $('<option>').val(0).text("...").appendTo($select);
    $('<option>').val(0).text("...").appendTo($select_regulacao);
    $select.trigger("chosen:updated");
    $select_regulacao.trigger("chosen:updated");

    $.post("/services/condutas-tipo-atendimento", {
        atendimento: atendimento,
        tipo_atendimento: tipo_atendimento,
        _token: token
    }, function (response) {
        if (response.status) {

            $.each(response.data.geral, function (key, value) {
                $('<option>').val(key).text(value).appendTo($select);
            });

            $.each(response.data.regulacao, function (key, value) {
                $('<option>').val(key).text(value).appendTo($select_regulacao);
            });

            $select.trigger("chosen:updated");
            $select_regulacao.trigger("chosen:updated");
        }
    }, "json");
});

function atualizaGridLoteProfissionaisCbo() {

    var lote_profissional = $("#lote_profissional").val();
    var url = "/admin/lotes/grid-profissionais-cbo/" + lote_profissional;

    $.ajax({
        url: url,
        cache: false,
        success: function (data) {
            $("table tr#" + lote_profissional + " .box-cbos").html(data);
        }
    });
}

function atualizaGridLoteProfissionais() {
    var lote = $("#box-form-profissional #lote").val();
    var url = "/admin/lotes/grid-profissionais/" + lote;

    $("#box-grid-profissionais").html(html_loading);

    $.ajax({
        url: url,
        cache: false,
        success: function (data) {
            $("#box-grid-profissionais").html(data);
        }
    });
}

$(document).on("change", ".btn-lote-arena", function (e) {
    var arena = $(this).attr('rel');
    var lote = $("#lote").val();
    var checked = $(this).is(':checked');
    var token = $("input[name=_token]").val();

    $.post("/admin/lotes/arena-cadastro", {
        arena: arena,
        lote: lote,
        checked: checked,
        _token: token
    }, function (data) {
        if (data.success) {
            (checked) ? notySuccess('Registro cadastrado com sucesso!') : notyInfo('Registro removido com sucesso!');
        }
    }, "json");
});

$(document).on("change", ".btn-lote-linha_cuidado input", function (e) {
    e.preventDefault();

    var obj = $(this);
    var faturamento_lote = obj.attr('data-faturamento-lote');
    var linha_cuidado = obj.attr('data-linha-cuidado');
    var key = obj.attr('data-key');
    var value = obj.val();
    var token = $("input[name=_token]").val();

    $.post("/admin/faturamento/lote-linha-cuidado-cadastro", {
        faturamento_lote: faturamento_lote,
        linha_cuidado: linha_cuidado,
        key: key,
        value: value,
        _token: token
    }, function (data) {
        (data.success) ? obj.addClass('text-success') : obj.addClass('text-info');
    }, "json");

});

$(document).on("click", "#btn-relatorio-producao-arena-faturistas", function (e) {
    e.preventDefault();

    var arena = $("#filtro-relatorio-faturista #arena").val();
    var mes = $("#filtro-relatorio-faturista #mes").val();
    var ano = $("#filtro-relatorio-faturista #ano").val();
    var forma_faturamento = $("#filtro-relatorio-faturista #forma_faturamento").val();
    var token = $("input[name=_token]").val();

    if (ano.length <= 0 || mes.length <= 0) {
        notyFail("Informe um ano e mês para ver o relatorio");
    } else {
        $("#box-grid").html(html_loading);

        $.post("/admin/relatorio/faturista", {
            forma_faturamento: forma_faturamento,
            arena: arena,
            mes: mes,
            ano: ano,
            _token: token
        }, function (html) {
            $("#box-grid").html(html);
        });
    }
});

$(document).on("click", "#btn-relatorio-faturamento-gordura", function (e) {
    e.preventDefault();
    clearErrorInputs();

    var faturamento = $("#filtro-relatorio-faturamento-gordura #faturamento").val();
    var token = $("input[name=_token]").val();

    $("#box-grid").html(html_loading);

    $.post("/admin/relatorio/faturamento-gordura", {
        faturamento: faturamento,
        _token: token
    }, function (html) {
        $("#box-grid").html(html);
    })
        .fail(function (xhr, status, error) {
            printErrorField(xhr, false, true);
        });

});

$(document).on("click", ".btn-reatorio-gordura-visualizacao-mes", function (e) {
    e.preventDefault();

    var arena = $(this).attr('data-arena');
    var procedimento = $(this).attr('data-procedimento');
    var linha_cuidado = $(this).attr('data-linha_cuidado');
    var mes = $(this).attr('data-mes');
    var token = $("input[name=_token]").val();

    setModalLarge("Relatório", html_loading);

    $.post("/admin/relatorio/faturamento-gordura-visualizacao-mes", {
        arena: arena,
        procedimento: procedimento,
        linha_cuidado: linha_cuidado,
        mes: mes,
        _token: token
    }, function (html) {
        setModalBodyLarge(html);
    });

});

$(document).on("click", "#btn-relatorio-tempo-recepcao", function (e) {
    e.preventDefault();

    var arena = $("#relatorio-tempo-recepcao #arena").val();
    var mes = $("#relatorio-tempo-recepcao #mes").val();
    var ano = $("#relatorio-tempo-recepcao #ano").val();
    var linha_cuidado = $("#relatorio-tempo-recepcao #linha_cuidado").val();
    var exportar = $("#relatorio-tempo-recepcao #exportar").val();
    var token = $("input[name=_token]").val();

    if (ano.length <= 0 || mes.length <= 0) {
        notyFail("Informe um ano e mês para gerar  o relatório");
    } else {
        $("#box-grid").html(html_loading);

        $.post("/admin/relatorio/recepcao-tempo", {
            exportar: exportar,
            linha_cuidado: linha_cuidado,
            arena: arena,
            mes: mes,
            ano: ano,
            _token: token
        }, function (html) {
            $("#box-grid").html(html);
        });
    }
});

$(document).on("click", "#btn-faturamento-lote", function (e) {
    e.preventDefault();

    var faturamento = $(this).attr('data-faturamento');
    var token = $("input[name=_token]").val();

    $.post("/admin/faturamento/faturamento-lote", {
        faturamento: faturamento,
        _token: token
    }, function (html) {
        setModalLarge("Faturamento", html);
        $(".nav-atendimento li:first-child").click();
    });

});

$(document).on("click", ".btn-agendamento-remarcar", function (e) {
    e.preventDefault();

    var agenda = $(this).attr('data-id');
    var token = $("input[name=_token]").val();

    $.post("/admin/agendas/remarcar", {
        agenda: agenda,
        _token: token
    }, function (html) {
        setModal("Remarcação", html);
    });
});

$(document).on("click", "#btn-agenda-remarcacao-save", function (e) {
    e.preventDefault();

    var agenda = $("#box-agenda-remarcacao #agenda").val();
    var motivo_remarcacao = $("#box-agenda-remarcacao .motivo_remarcacao").val();
    var data = $("#box-agenda-remarcacao .date").val();
    var hora = $("#box-agenda-remarcacao .time").val();
    var token = $("input[name=_token]").val();

    $.post("/admin/agendas/remarcacao", {
        agenda: agenda,
        motivo_remarcacao: motivo_remarcacao,
        data: data,
        hora: hora,
        _token: token
    }, function (data) {
        if (data.success) {
            window.location.reload();
        } else {
            notyFail(data.message)
        }
    }, 'json');
});

$(document).on("click", ".btn-agendamento-descancelar", function (e) {
    e.preventDefault();

    var agenda = $(this).attr('data-id');

    $.post("/admin/agendas/descancelar", {
        agenda: agenda,
        _token: token
    }, function (data) {
        if (data.success) {
            $("#btn-search-agenda").trigger('click');
        } else {
            notyFail(data.message)
        }
    }, 'json');
});

function getValuesCheckedById(id) {
    var n = jQuery("#" + id).length;
    var all = [];

    if (n > 0) {
        jQuery("#" + id + ":checked").each(function () {
            all.push($(this).val());
        });
    }

    return (all.length) ? all.join() : "";
}

function loadingDataGrid() {
    loadGRID(getUri() + "/grid", null);
}

function loadingDataGridUsuarioImportacao() {
    loadGRID("/admin/importacao/usuario-grid", null);
}

function loadingDataGridAgendaImportacao() {
    loadGRID("/admin/importacao/agenda-grid", null);
}

function loadingDataGridAgendaImportacaoPdf() {
    loadGRID("/admin/importacao/agenda-pdf-grid", null);
}

function loadingDataGridPacienteImportacao() {
    loadGRID("/admin/pacientes/dados-correcao-importacao-grid", null);
}

function loadingDataGridAgendaImportacaoRemarcacao() {
    loadGRID("/admin/importacao/agenda-remarcacao-list", null);
}

function loadGRID(uri, _page) {
    $("#box-grid").html(html_loading);

    var url = uri.split("?");

    var page = 1;
    if (url[1] != undefined) {
        var pages = uri.split("=");
        page = pages[1];
    } else {
        page = _page;
    }

    var token = $("input[name=_token]").val();

    $.ajax({
        url: url[0].replace("/grid/", "/grid"),
        cache: true,
        data: {_token: token, page: page},
        success: function (data) {
            $("#box-grid").html(data);
        }
    });
}

function loadGRID2(uri, _page) {
    $("#box-grid").html(html_loading);

    var url = uri.split("?");

    var token = $("input[name=_token]").val();

    var _params = new Array();
    var i = 0;
    if (url[1] != undefined) {
        $(url[1].split("&")).each(function (i, text) {
            var params = text.split("=");

            _params[params[0]] = params[1];
        });
        _params["page"] = _page;
    } else {
        _params["page"] = _page;
        _params["_token"] = token;
    }

    $.ajax({
        url: url[0].replace("/grid/", "/grid"),
        cache: true,
        type: "GET",
        data: convArrToObj(_params),
        success: function (data) {
            $("#box-grid").html(data);
        }
    });
}

function convArrToObj(array) {
    var thisEleObj = new Object();
    if (typeof array == "object") {
        for (var i in array) {
            var thisEle = convArrToObj(array[i]);
            thisEleObj[i] = thisEle;
        }
    } else {
        thisEleObj = array;
    }
    return thisEleObj;
};


function notySuccess(text) {
    notys(text, 'success', false);
}

function notyInfo(text) {
    notys(text, 'information', false);
}

function notyWarning(text) {
    notys(text, 'warning', false);
}

function notyFail(text) {
    notys(text, 'error', true);
}

function notys(text, type, modal) {
    $.noty.clearQueue();
    $.noty.closeAll();

    var n = noty({
        layout: 'topRight',
        text: text,
        type: type,
        animation: {
            open: 'animated rollIn',
            close: 'animated rollOut',
            easing: 'swing',
            speed: 500
        },
        timeout: 100,
        killer: true,
        force: true,
        modal: modal,
        buttons: false
    });
}

getSearch();

$(".modal-body").html(html_loading);
$(".modal-header .modal-title").html('...');


$('.modal').on('hidden.bs.modal', function (e) {
    $(".modal-body").html(html_loading);
    $(".modal-header .modal-title").html('...');
});

$('.modal').on("hide.bs.modal", function (e) {

    if ($("#box-geral-atendimento #grid-laudo").length && user.perfil == "10") {
        var fecha_atendimento_laudo = $("#close-atendimento-laudo").val();

        if ($("#box-geral-atendimento #grid-laudo table tbody tr").length == 0 && fecha_atendimento_laudo == 0) {
            e.preventDefault();
            closeModalAtendimento(e);
        }
    }

});

function closeModalAtendimento(e) {
    noty({
        text: user['nome'] + ", o atendimento não possui resultado de laudo cadastrado. Deseja realmente sair sem nenhum laudo? ",
        animation: {
            open: 'animated bounceInLeft',
            close: 'animated bounceOutLeft',
            easing: 'fade',
            speed: 500
        },
        layout: 'center',
        type: 'danger',
        buttons: [
            {
                addClass: 'btn btn-danger', text: 'Não', onClick: function ($noty) {
                    $("#btn-check-in-medicina a").trigger('click');
                    $("#nav-box-laudo a").trigger('click');
                    $noty.close();
                }
            },
            {
                addClass: 'btn btn-primary', text: 'Sim', onClick: function ($noty) {
                    $("#close-atendimento-laudo").val('1');
                    $noty.close();
                    $('.modal').modal('hide');
                }
            }
        ],
        timeout: 100,
        killer: true,
        force: true,
        modal: modal
    });
}

function setModal(title, body) {
    $("#modal .modal-body").html(body);
    $("#modal .modal-header .modal-title").html(title);

    $('#modal').modal({keyboard: false});
    $('#modal').modal('show');

    $("select.chosen").trigger("chosen:updated");
}

function setModalBody(body) {
    $("#modal .modal-body").html(body);

    $("select.chosen").trigger("chosen:updated");
}

function setModalLarge(title, body) {
    $("#modal-large .modal-body").html(body);
    $("#modal-large .modal-header .modal-title").html(title);

    $('#modal-large').modal({keyboard: false});
    $('#modal-large').modal('show');

    $("select.chosen").trigger("chosen:updated");
}

function setModalBodyLarge(body) {
    $("#modal-large .modal-body").html(body);

    $("select.chosen").trigger("chosen:updated");
}

function closeModal() {
    $("#modal-large").modal('hide');
    $("#modal").modal('hide');
}

$('#modal-large ').on('show.bs.modal', function () {
    $(".chosen").chosen({width: '100%', search_contains: true});
});

function removeSelectDefault(id) {
    $("#" + id + " option[value='']").each(function () {
        $(this).remove();
    });

    $(".chosen").trigger("chosen:updated");
}

function truncar(texto, limite) {
    if (texto != undefined && texto.length > limite) {
        limite--;
        last = texto.substr(limite - 1, 1);
        while (last != ' ' && limite > 0) {
            limite--;
            last = texto.substr(limite - 1, 1);
        }
        last = texto.substr(limite - 2, 1);
        if (last == ',' || last == ';' || last == ':') {
            texto = texto.substr(0, limite - 2) + '...';
        } else if (last == '.' || last == '?' || last == '!') {
            texto = texto.substr(0, limite - 1);
        } else {
            texto = texto.substr(0, limite - 1) + '...';
        }
    }
    return texto;
}

function openDownload(link) {
    window.open(link, "download");
}

function setConfigUploadLaudo() {
    $(".atendimento_laudo_upload_lib").upload("destroy");
    $(".atendimento_laudo_upload_lib").upload({
        action: "/admin/atendimento/atendimento-laudo-upload-imagem",
        maxSize: 1073741824,
        beforeSend: onBeforeSend
    }).on("start.upload", onStart)
        .on("complete.upload", onComplete)
        .on("filestart.upload", onFileStart)
        .on("fileprogress.upload", onFileProgress)
        .on("filecomplete.upload", onFileComplete)
        .on("fileerror.upload", onFileError)
        .on("chunkstart.upload", onChunkStart)
        .on("chunkprogress.upload", onChunkProgress)
        .on("chunkcomplete.upload", onChunkComplete)
        .on("chunkerror.upload", onChunkError)
        .on("queued.upload", onQueued);
}

function onBeforeSend(formData, file) {
    formData.append("_token", $("input[name=_token]").val());
    formData.append("atendimento_laudo", $("#atendimento-laudo-id").val());

    return formData;
}

function onQueued(e, files) {
    var html = '';
    for (var i = 0; i < files.length; i++) {
        html += '<li data-index="' + files[i].index + '"><span class="content"><span class="file">' + files[i].name + '</span><span class="progress">Enfileiradas</span></span><span class="bar"></span></li>';
    }
    $(this).parents("form").find(".filelist.queue")
        .append(html);
}

function onStart(e, files) {
    $(this).parents("form").find(".filelist.queue")
        .find("li")
        .find(".progress").text("Esperando");
}

function onComplete(e) {
    window.setTimeout(function () {
        $("#box-laudo-upload-imagens .complete").html('');
        $("#box-laudo-upload-imagens .queue").html('');
    }, 15000);

    // $("#box-laudo-upload-imagens").addClass('hidden');
    uploadLaudocomImagens($("#atendimento-laudo-id").val());
}

function onFileStart(e, file) {
    $(this).parents("form").find(".filelist.queue")
        .find("li[data-index=" + file.index + "]")
        .find(".progress").text("0%");
}

function onFileProgress(e, file, percent) {
    var $file = $(this).parents("form").find(".filelist.queue").find("li[data-index=" + file.index + "]");
    $file.find(".progress").text(percent + "%")
    $file.find(".bar").css("width", percent + "%");
}

function onFileComplete(e, file, response) {
    if (response.trim() === "" || response.toLowerCase().indexOf("error") > -1) {
        $(this).parents("form").find(".filelist.queue")
            .find("li[data-index=" + file.index + "]").addClass("error")
            .find(".progress").text(response.trim());
    } else {
        var $target = $(this).parents("form").find(".filelist.queue").find("li[data-index=" + file.index + "]");
        $target.find(".file").text(file.name);
        $target.find(".progress").remove();
        $target.appendTo($(this).parents("form").find(".filelist.complete"));
    }
    getAtendimentoLaudoImagens();
}

function onFileError(e, file, error) {
    var errors = $.parseJSON(file.transfer.responseText);
    $(this).parents("form").find(".filelist.queue")
        .find("li[data-index=" + file.index + "]").addClass("error")
        .find(".progress").text(errors[Object.keys(errors)[0]]);
}

function onChunkStart(e, file) {

}

function onChunkProgress(e, file, percent) {

}

function onChunkComplete(e, file, response) {
    $("#box-laudo-upload-imagens").html('');
}

function onChunkError(e, file, error) {

}

$(document).ready(function () {
    if ($('#div-imgs-incluidas').hasClass('box-laudo-imagens')) {
        getAtendimentoLaudoImagens();
    }
});

function getAtendimentoLaudoImagens() {
    $('.box-laudo-imagens').empty();

    $.get("/admin/atendimento/laudo-imagens/" + $("#atendimento").val(), function (data) {
        if (data != "") {
            $('#box-laudo-imagens').html(data);
            $("#box-laudo-imagens").removeClass('hidden');
        }
    });
}

$(document).on("click", ".delete-atendimento-laudo-imagem", function (e) {
    var atendimento_laudo_imagem = $(this).attr('data-id');
    var box = $(this);

    $.get("/admin/atendimento/atendimento-laudo-imagem/" + atendimento_laudo_imagem, function (data) {
        if (data.success) {
            notyInfo("Imagem removida com sucesso!");
            box.parent().parent().parent().remove();
            if ($("#box-laudo-imagens .panel-body .box-atendimento-laudo-imagem").length == 0) {
                $("#box-laudo-imagens").addClass('hidden');
            }
        } else {
            notyFail("Não foi possivel remover a imagem!");
        }

    }, "json")
        .fail(function (xhr, status, error) {
            notyFail("Não foi possivel remover a imagem!");
        });
});

$(document).on("click", ".copyToHtml", function (e) {
    e.preventDefault();

    var element = $(this);
    var copyText = element.html();

    document.addEventListener('copy', function (e) {
        e.clipboardData.setData('text/plain', copyText);
        e.preventDefault();
        $(".copyToHtml").removeClass('copyToLink-danger');
        element.addClass('copyToLink-danger');
    }, true);

    document.execCommand('copy');
});

$.fn.serializeObject = function () {
    var obj = {};
    var arr = this.serializeArray();
    arr.forEach(function (item, index) {
        if (obj[item.name] === undefined) { // New
            obj[item.name] = item.value || '';
        } else {                            // Existing
            if (!obj[item.name].push) {
                obj[item.name] = [obj[item.name]];
            }
            obj[item.name].push(item.value || '');
        }
    });
    return obj;
};

function setError(xhr) {
    clearErrorInputs();

    if (xhr.status == 422) {
        var _errors = $.parseJSON(xhr.responseText);
        setErrorInputs(_errors);
    }

    if (xhr.status == 500) {
        var _errors = $.parseJSON(xhr.responseText);
        notyError(_errors.message);
    }
}

function notyError(message) {
    // Swal.fire({
    //     text: message,
    //     type: 'error',
    //     confirmButtonText: 'Fechar'
    // })
}

function setErrorInputs(errors) {
    clearErrorInputs();

    $.each(errors, function (key, value) {

        $.each(['input', 'textarea', 'select'], function (_key, field) {
            var obj = $("form " + field + "[name='" + key + "']").parent();

            if (obj.length == 0) {
                obj = $("form " + field + "[name='" + key + "']").parent();
            }

            if (obj.length) {
                obj.addClass('has-error');
                obj.append("<div class='invalid-feedback form-input-error'>" + value[0] + "</div>");
            }
        });

    });

    if ($("div.box-error-message").length > 0) {
        var box_errors = $("div.box-error-message");
        var _errors = "";
        $.each(errors, function (key, error) {
            _errors += error + '<br />';
        });

        box_errors.html('<div class="alert alert-danger">' + _errors + '</div>');
    }

}

function formAjax() {
    $(document).ready(function () {
        clearErrorInputs();

        var options = {
            type: 'post',
            beforeSubmit: function (xhr, status, error) {
                clearErrorInputs();
                $('input[type="submit"]').attr("disabled", true);
            },
            error: function (xhr, status, error) {
                $('input[type="submit"]').attr("disabled", false);
                setError(xhr);
            },
            success: function (response, status, error) {
                $('input[type="submit"]').attr("disabled", false);

                if (status == 'success') {
                    if (response.url) {
                        if (response.url == 'function') {
                            reloadFunctionPage();
                        } else {
                            window.location.href = response.url;
                        }
                    } else {
                        window.location.href = getUri();
                    }
                } else {
                    alert('ERRO não esperado!');
                }

                return true;
            }
        };

        return $(".frm-ajax").ajaxForm(options);
    });
}
