<?php
    $perguntas = \App\AnamnesePerguntas::Questionario(4);
    $resposta = \App\Http\Helpers\Util::getRespostaAtendimento($atendimento->id);
?>

<div id="tab-enfermagem-acolhimento" class="tab-pane animated fadeInUp  " role="tabpanel">
    <hr class="no-margin" />
    <div>
        <div class="list-group list-group-gap">
            @foreach($perguntas AS $pergunta)
                <div class="list-group-item md-whiteframe-z0" href="">
                  <div class="row">
                    <div class="col-md-4 text-medium">{{$pergunta['nome']}}</div>
                    <div class="col-md-8 btn-perguntas-4">
                        {{\App\Http\Helpers\Anamnese::MountASK($pergunta['id'], $pergunta['tipo_resposta'], $pergunta['multiplas'], $resposta[4], null, \App\Http\Helpers\Util::CheckPermissionAction('enfermagem_intercorrencia','created'))}}
                    </div>
                  </div>
                </div>
            @endforeach
        </div>
    </div>
</div>