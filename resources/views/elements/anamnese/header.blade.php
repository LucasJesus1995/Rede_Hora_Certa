<?php
$medico = !empty($atendimento->medico) ? $atendimento->medico : \App\Http\Helpers\Util::getDataDigitadora('doctor');
?>
<div class="row">
    <div class="col-sm-9">
        <div class="margin5">
            @include('elements.paciente.header', ['paciente'=>$paciente])
            <div id="progress-atendimento" class="progress progress-striped progress-sm no-radius"></div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="well well-sm margin5 red-50">
            <div class="row">
                <div class="col-md-6"><strong>CHEGADA</strong></div>
                <div class="col-md-6 align-right">{{explode(" ",($chegada))[1]}}</div>
            </div>
            <div class="row">
                <div class="col-md-6"><strong>AGENDAMENTO</strong></div>
                <div class="col-md-6 align-right">{{explode(" ",($agendamento))[1]}}</div>
            </div>
            <div class="row">
                <div class="col-md-6"><strong>ENFERMAGEM</strong></div>
                <div class="col-md-6 align-right"></div>
            </div>
            <div class="row">
                <div class="col-md-6"><strong>MÉDICO</strong></div>
                <div class="col-md-6 align-right"></div>
            </div>
            <div class="row">
                <div class="col-md-6"><strong>EXAME</strong></div>
                <div class="col-md-6 align-right"></div>
            </div>
        </div>
        <div class="row">
            @if(\App\Http\Helpers\Util::getNivel() == 10 && $atendimento->status == 2)
                <div class="col-md-12 align-center">
                    <a href="" id="btn-finalizar-atendimento" class="btn btn-success btn-mini col-md-11 margin5" data-atendimento="{!! $atendimento->id !!}"> Finalizar Atendimento</a>
                </div>
            @endif
        </div>
    </div>

</div>