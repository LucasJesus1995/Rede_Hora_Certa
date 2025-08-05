<?php

?>
<h5>
    <strong>{{Lang::get('app.linha-cuidado')}}:</strong>
    <span class="label bg-success pos-rlt m-r-xs">
	    <b class="arrow bottom"></b>{{$linha_cuidado['nome']}}
	</span>
</h5>
<div class="well well-sm">
    <div class="panel panel-card">
        <div class="panel-body">
            {{Lang::get('description.insercao-procedimentos-medico')}}
            @if(empty($procedimentos))
                <div class="alert alert-danger">{{Lang::get('app.nenhum-registro-encontrado')}}</div>
            @else
                <table class="table table-striped table-hover table-condensed table-bordered">
                    <thead>
                    <tr>
                        <th>
                            @if(in_array(\App\Http\Helpers\Util::getNivel(), [1, 11, 19]))
                                <input class="checked-all-procedimentos" type="checkbox" onclick="check_uncheck_checkbox(this.checked);"/>
                            @else
                                #
                            @endif
                        </th>
                        <th>{{Lang::get('app.procedimentos')}}</th>
                        <th>Máx</th>
                        <th width="230">Autorização</th>
                        <th width="100">Qtd</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($procedimentos AS $row)
                        <?php
                        $id = $row['id'];
                        $checked = !empty($row['atendimento_procedimentos_id']) ? "checked='checked'" : null;
                        $qtd = !empty($checked) ? $row['atendimento_procedimentos_quantidade'] : null;
                        $autorizacao = !empty($checked) ? $row['autorizacao'] : null;
                        $disabledAuth = !empty($row['auth']) ? $row['auth'] : null;
                        ?>
                        <tr data-id="{!! $id !!}">
                            <td>
                                <input class="checked-atendimento-procedimento" id="atendimento-procedimento-{{$id}}" type="checkbox" name="" value="{{$id}}" rel="{{$id}}" {{$checked}} />
                            </td>
                            <td class="text-medium">{{$row['sus']}} - {{$row['nome']}}</td>
                            <td>
                                <div class="align-center text-medium">{{$row['maximo']}}</div>
                            </td>
                            <td>
                                <input {{($disabledAuth) ? null : 'disabled'}} class="quantidade-procedimento-medicina-quantidade col-md-12 autorizacao" value="{{$autorizacao}}"
                                       id="atendimento-autorizacao-{{$id}}" type="text" name="autorizacao"/>
                            </td>
                            <td>
                                <input class="quantidade-procedimento-medicina-quantidade quantidade-procedimento-medicina-quantidade-check col-md-12" id="atendimento-quantidade-{{$id}}" type="text"
                                       name="quantidade" value="{{$qtd}}"/>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <input type="hidden" id="atendimento-agenda" value="{!! $atendimento->agenda !!}"/>
                <input type="hidden" id="atendimento-id" value="{!! $atendimento->id !!}"/>
            @endif
        </div>
        <script>
            loadingMask();

            function check_uncheck_checkbox(isChecked) {
                var _procedimentos = [];

                if (isChecked) {
                    $('input.checked-atendimento-procedimento').each(function () {
                        this.checked = isChecked;

                        let _quantidade = $(this).parents('tr').find('.quantidade-procedimento-medicina-quantidade-check')
                        if (_quantidade.val() == undefined || _quantidade.val() == 0) {
                            _quantidade.val(1);
                        }

                        _procedimentos.push($(this).val());
                    });
                } else {
                    $('input.checked-atendimento-procedimento').each(function () {
                        this.checked = isChecked;

                        _procedimentos.push($(this).val());
                    });
                }

                $.post("/admin/atendimento/atendimento-medico-procedimento-massa", {
                    checked: isChecked ? 1 : 0,
                    _procedimentos: _procedimentos,
                    atendimento: $("#atendimento-id").val(),
                    _token: $("input[name=_token]").val()
                }, function (data) {
                    if (data.success) {
                        var agenda = $("#atendimento-agenda").val();

                        var _agenda = [];
                        _agenda.push(agenda);

                        getDadosComplementaresFaturamentoAgendas(_agenda);
                    }
                }, "json");
            }
        </script>
    </div>
</div>
