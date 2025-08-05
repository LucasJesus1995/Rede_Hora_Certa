<?php
    $linha_cuidado = \App\Http\Helpers\Util::getLinhaCuidado($agenda->linha_cuidado);
    $perguntas =  \App\AnamnesePerguntas::Questionario(9);

    $resposta = \App\Http\Helpers\Util::getRespostaAtendimento($atendimento->id)[10];
?>
<div class="row">
    <div class="col-md-10">
        <h5>
            <strong>{{Lang::get('app.linha-cuidado')}}:</strong>
            <span class="label bg-success pos-rlt m-r-xs">
                <b class=" "></b>{{$linha_cuidado->nome}}
            </span>
        </h5>
    </div>
    <div class="col-md-2">
        <a href="/admin/agendas/kit-impressao/{{$agenda->id}}/medica" target="_blank" class="btn btn-success btn-xs col-md-12" style="margin-top: 5px">Imprimir</a>
    </div>
</div>


<div class="panel panel-card">
    <div class="panel-body">{{Lang::get('description.insercao-anamnese-medico')}}
        @if(!empty($perguntas))
            <h5></h5>
            <div class="well well-sm">
                <div>
                    <div class="list-group list-group-gap">
                        @foreach($perguntas AS $pergunta)
                        <div class="list-group-item md-whiteframe-z0" href="">
                            <div class="row">
                                <div class="col-md-4 text-medium">{{$pergunta['nome']}}</div>
                                <div class="col-md-8 btn-perguntas-global">
                                    {{\App\Http\Helpers\Anamnese::MountASK($pergunta['id'], $pergunta['tipo_resposta'], $pergunta['multiplas'], $resposta, 10, \App\Http\Helpers\Util::CheckPermissionAction('medicina_anamnese','created'))}}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-danger">{{Lang::get('app.nenhum-registro-encontrado')}}</div>
        @endif
    </div>
</div>
