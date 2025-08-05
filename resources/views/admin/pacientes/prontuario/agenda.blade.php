<div class="p-md bg-light lt b-b font-bold">Agenda</div>

<div class="margin10">
    @if(count($agendas))
        <br/>
        <table class="table table-striped table-responsive table-bordered  bg-light" id="table-agenda-atendimento">
            <thead>
            <tr role="row">
                <th>{!!Lang::get('app.agenda')!!}</th>
                <th>{!!Lang::get('app.data')!!}</th>
                <th>{!!Lang::get('app.arena')!!}</th>
                <th>{!!Lang::get('app.status')!!}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($agendas AS $row)
                <tr>
                    <td>{!! $row->id !!}</td>
                    <td>{!! \App\Http\Helpers\Util::DBTimestamp2User2($row->data) !!}</td>
                    <td><strong>{!! $row->arena !!}</strong><br/>{!! $row->linha_cuidado !!}</td>
                    <td>{!! \App\Http\Helpers\Util::StatusAgenda($row->status) !!}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class='panel bg-danger pos-rlt'>
            <span class='arrow top  b-danger '></span>
            <div class='panel-body'>{!! Lang::get('grid.nenhum-registro-encontrado') !!}</div>
        </div>
    @endif
</div>