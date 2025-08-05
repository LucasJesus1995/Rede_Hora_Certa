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
                <div class="col-md-6"><strong>PREFERENCIAL</strong></div>
                <div class="col-md-6 align-right"></div>
            </div>
            <div class="row">
                <div class="col-md-6"><strong>DIGITAÇÃO</strong></div>
                <div class="col-md-6 align-right"></div>
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
    </div>
    </div>