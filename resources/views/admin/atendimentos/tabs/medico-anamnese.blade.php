<?php
    $linha_cuidado = \App\Http\Helpers\Util::getLinhaCuidado($agenda->linha_cuidado);
    $perguntas = \App\Http\Helpers\Anamnese::PerguntasByLinhaCuidado($linha_cuidado->id);
    
    $resposta = \App\Http\Helpers\Util::getRespostaAtendimento($atendimento->id)[4];

    $disabled = (\App\Http\Helpers\Util::CheckPermissionAction('medicina_anamnese','created')) ? null:  "disabled";

?>
<h5>
    <strong>{{Lang::get('app.linha-cuidado')}}:</strong>
    <span class="label bg-success pos-rlt m-r-xs">
        <b class="arrow bottom"></b>{{$linha_cuidado->nome}}
    </span>
</h5>


<div class="panel panel-card">
    <div class="panel-body">{{Lang::get('description.insercao-anamnese-medico')}}
        @if(!empty($perguntas))
            <h5></h5>
            <div class="well well-sm">
                <div class="row">
                @foreach($perguntas AS $row)
                    <?php
                        $id = $row['id'];
                        $checked = (array_key_exists($id, $resposta) && $resposta[$id]['value'] == $id) ? "checked='checked'"  : null;
                    ?>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-1 btn-perguntas-4" style="padding-top: 5px;">
                                <input name="{{$row['id']}}" id="value_{{$row['id']}}" type="checkbox"  multi='0' value="{{$row['id']}}" rel="{{$row['id']}}" {{$checked}} {{$disabled}} />
                            </div>
                            <div class="col-md-9" style="padding-top: 5px;">
                                <label for="value_{{$row['id']}}" class="text-medium">{{$row['nome']}}</label>
                            </div>
                            <div class="col-md-2 form-group-sm text-medium">{{$row['cid']}}</div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        @else
            <div class="alert alert-danger">{{Lang::get('app.nenhum-registro-encontrado')}}</div>
        @endif
    </div>
</div>

