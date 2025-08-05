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

                $("select.linha_cuidado").trigger("chosen:updated");
            }
        });

    }
});

$(document).on("change", "select.combo-linha-cuidado", function (e) {
    var id = $(this).val();

    $select_medico = $("select.medico");
    $select_medico.find('option').remove();
    $('<option>').val('').text('...').appendTo($select_medico);

    $("select.medico").trigger("chosen:updated");

    if (id) {

        var uri = '/admin/linha-cuidado/profissionais/' + id;
        $.getJSON(uri, function (response) {
            if (response.status) {

                $.each(response.data, function (key, value) {
                    $('<option>').val(key).text(value).appendTo($select_medico);
                });

                $("select.medico").chosen({width: '100%', search_contains: true});
                $("select.medico").trigger("chosen:updated");
            }
        });

    }
});