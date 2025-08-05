<div class="box" style="width: 103%; margin: -15px">
    <div class="col-md-3">
        <div>
            <div class="p-lg bg-white-overlay text-center">
                <div>
                    <i class="glyphicon glyphicon-user" style="margin: 10px 12px; font-size: 80px; color: #CCC"></i>
                </div>
                <div class="m-b m-t-sm h4">
                    <span class="text-black">{!! $paciente['nome'] !!}</span>
                </div>
                <p>
                    @if(!empty($paciente['nascimento']))
                        {!! \App\Http\Helpers\Util::Idade($paciente['nascimento']) !!} anos
                    @endif
                </p>
            </div>
        </div>
        <ul class="nav nav-lists b-t" ui-nav="">
            <li class="active">
                <a href="javascript: void(0);" id="btn-prontuario-paciente-agenda" data-id="{!! $paciente['id'] !!}">Agenda</a>
            </li>
            <li class="active">
                <a href="javascript: void(0);" id="btn-anexo-paciente-agenda" data-id="{!! $paciente['id'] !!}">Anexos</a>
            </li>
        </ul>
    </div>
    <div id="box-prontuario-paciente" class="col-md-9 b-l bg-white bg-auto">
        <div class="p-md bg-light lt b-b font-bold">Agenda</div>

    </div>
</div>
<script>
    $("#btn-prontuario-paciente-agenda").click()
</script>