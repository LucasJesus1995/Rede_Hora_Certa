<?php
$linha_cuidado = \App\Http\Helpers\Util::getLinhaCuidado($agenda->linha_cuidado);
$procedimentos = \App\Http\Helpers\Util::getProcedimentoByLinhaCuidado($linha_cuidado->id);

$procedimentos_atendimento = \App\Atendimentos::getProcedimentosByAtendimento($atendimento->id);

$disabled = (\App\Http\Helpers\Util::CheckPermissionAction('medicina_procedimentos', 'created')) ? null : "disabled";
?>
<h5>
    <strong>{{Lang::get('app.linha-cuidado')}}:</strong>
    <span class="label bg-success pos-rlt m-r-xs">
	    <b class="arrow bottom"></b>{{$linha_cuidado->nome}}
	</span>
</h5>

{{Lang::get('description.insercao-procedimentos-medico')}}
@if(empty($procedimentos))
    <div class="alert alert-danger">{{Lang::get('app.nenhum-registro-encontrado')}}</div>
@else
    <table class="table table-striped table-hover table-condensed table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>{{Lang::get('app.procedimentos')}}</th>
            <th>&nbsp;</th>
            <th>{{Lang::get('app.qtd')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($procedimentos AS $row)
            <?php
            $id = $row['id'];
            $checked = (array_key_exists($id, $procedimentos_atendimento)) ? 'checked = "checked"' : null;
            $qtd = ($checked) ? $procedimentos_atendimento[$id] : null;
            ?>
            <tr>
                <td>
                    <input class="checked-procedimento-medicina" id="check_medicina_{{$id}}" type="checkbox" name="" value="{{$id}}" rel="{{$id}}" {{$checked}} {{$disabled}} />
                </td>
                <td class="text-medium">{{$row['sus']}} - {{$row['nome']}}</td>
                <td>
                    <div class="align-center text-medium">{{$row['maximo']}}</div>
                </td>
                <td>
                    <input class="quantidade-procedimento-medicina" id="quantidade-medicina-{{$id}}" type="text" name="quantidade" value="{{$qtd}}" class="form-control" rel="{{$id}}" {{$disabled}} />
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
