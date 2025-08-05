<?php
    $linha_cuidado = \App\Http\Helpers\Util::getLinhaCuidado($agenda->linha_cuidado);
    $perguntas = \App\Http\Helpers\Anamnese::PerguntasByLinhaCuidado($linha_cuidado->id);
    
    $resposta = \App\Http\Helpers\Util::getRespostaAtendimento($atendimento->id)[5];

    $anotacao = null;

    $disabled = (\App\Http\Helpers\Util::CheckPermissionAction('enfermagem_anotacao','created')) ? null:  "disabled";
?>
<h5>
    <strong>{{Lang::get('app.linha-cuidado')}}:</strong>
    <span class="label bg-success pos-rlt m-r-xs">
        <b class="arrow bottom"></b>{{$linha_cuidado->nome}}
    </span>
</h5>

<div class="panel panel-card">
    <div class="panel-body">
        {{Lang::get('description.anotacao')}}
        <hr />
        <div class="col-md-12 btn-perguntas-5" >
            {!!Form::textareaField('evolucao',Lang::get('app.anotacao'), $anotacao, array('class'=>'no-style form-control col-md-12 ', 'rows'=>'15','id'=>'textarea-anotacao', $disabled))!!}
        </div>
    </div>
</div>
