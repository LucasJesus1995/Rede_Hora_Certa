@if(!empty($relatorio))
    <table class="table table-striped table-responsive table-bordered  bg-light" style="width: 100%">
        @foreach($relatorio AS $key => $data)
            <tr>
                <th colspan="100%"><h4 style="text-align: left; font-weight: bold; color: #ff0000">{!! \App\Http\Helpers\Util::StatusAgenda($key) !!}</h4></th>
            </tr>
            <tr>
                <th>Arena</th>
                <th>Especialidade</th>
                <th>Agenda</th>
                <th>Data</th>
                <th>Paciente</th>
                <th>Total</th>
            </tr>
            @foreach($data AS $row)
                <tr>
                    <td>{!! $row['arena'] !!}</td>
                    <td>{!! $row['linha_cuidado'] !!}</td>
                    <td>{!! $row['agenda_id'] !!}</td>
                    <td>{!! \App\Http\Helpers\Util::DB2User($row['data']) !!}</td>
                    <td>{!! $row['paciente'] !!}</td>
                    <td>{!! $row['total'] !!}</td>
                </tr>
            @endforeach
             <tr>
                 <td colspan="100%"><hr/></td>
             </tr>
        @endforeach
    </table>
@else
    <div class='panel bg-danger pos-rlt'>
        <span class='arrow top  b-danger '></span>
        <div class='panel-body'>{!! Lang::get('grid.nenhum-registro-encontrado') !!}</div>
    </div>
@endif