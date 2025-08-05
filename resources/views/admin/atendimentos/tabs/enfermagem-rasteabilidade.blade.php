<hr class="no-margin" />
<div>
    <div class="list-group list-group-gap">
        @foreach(\App\AnamnesePerguntas::Questionario(7) AS $pergunta)
            <div class="list-group-item md-whiteframe-z0" href="">
                <div class="row">
                    <div class="col-md-4 text-medium">{{$pergunta['nome']}}</div>
                    <div class="col-md-8 btn-perguntas-global">
                        {{\App\Http\Helpers\Anamnese::MountASK($pergunta['id'], $pergunta['tipo_resposta'], $pergunta['multiplas'], $resposta[7],7, \App\Http\Helpers\Util::CheckPermissionAction('enfermagem_rastreabilidade','created'))}}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>