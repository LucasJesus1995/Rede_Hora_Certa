<hr class="no-margin" />
<div>
    <div class="list-group list-group-gap">
        @foreach($questionario AS $pergunta)
            <div class="list-group-item md-whiteframe-z0" href="">
                <div class="row">
                    <div class="col-md-4 text-medium">
                        {{$pergunta['nome']}}
                        <div id="info-{{$pergunta['id']}}"></div>
                    </div>
                    <div class="col-md-8  btn-perguntas-1 ">
                        {{\App\Http\Helpers\Anamnese::MountASK($pergunta['id'], $pergunta['tipo_resposta'], $pergunta['multiplas'], $resposta[1], null, \App\Http\Helpers\Util::CheckPermissionAction('enfermagem_questionario_especifico','created'))}}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>