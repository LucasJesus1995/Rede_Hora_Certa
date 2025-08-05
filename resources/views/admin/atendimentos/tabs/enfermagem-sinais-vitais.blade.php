<hr class="no-margin" />
<div>
    <div class="list-group list-group-gap">
        @foreach(\App\AnamnesePerguntas::Questionario(6) AS $pergunta)
            <div class="list-group-item md-whiteframe-z0" href="">
                <div class="row">
                    <div class="col-md-4 text-medium">{{$pergunta['nome']}}<br /><span id='info-<?php echo $pergunta['id'];?>'></span></div>
                    <div class="col-md-8 btn-perguntas-2">
                        {!! \App\Http\Helpers\Anamnese::MountASK($pergunta['id'], $pergunta['tipo_resposta'], $pergunta['multiplas'], $resposta[2], null, \App\Http\Helpers\Util::CheckPermissionAction('enfermagem_ficha_acolhimento','created')) !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>