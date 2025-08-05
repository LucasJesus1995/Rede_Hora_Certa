@if(empty($date['inicial']) || empty($date['final']))
    <div class="alert alert-danger">Selecione uma data inicial e final para geração do relatorio.</div>
@else
    <?php
        $profissionais = \App\Http\Helpers\Relatorios::RelatorioCondutaProfissionais($date, $arena, $linha_cuidado,  $medico, $digitador);
    ?>
    @if($profissionais)
        <table class="table table-striped table-responsive table-bordered  bg-light "  >
            <thead>
                <tr role="row">
                    <th class="w-64">CRM</th>
                    <th>Médico</th>
                    <th width="120">Condutas</th>
                    <th class="w-64">{!!Lang::get('grid.acao')!!}</th>
                </tr>
        </thead>
            <tbody>
                @foreach($profissionais AS $row)
                    <tr class="">
                        <td>{{$row->cro}}</td>
                        <td>{{$row->nome}}</td>
                        <td>{{$row->total}}</td>
                        <td nowrap>
                            <form id="relatorio-conduta-{{$row->id}}" style="display: inline">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="arena" value="{{$arena}}">
                                <input type="hidden" name="linha_cuidado" value="{{$linha_cuidado}}">
                                <input type="hidden" name="date" value="{{json_encode($date)}}">
                                <input type="hidden" name="profissional" value="{{$row->id}}">
                                <input type="hidden" name="detalhado" value="0">

                                <a id="{{$row->id}}" href="/admin/relatorio/conduta-data" class="btn-relatorio-conduta-medico btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-line-chart"></i></a>
                            </form>

                            <form id="relatorio-conduta-{{$row->id}}2" style="display: inline">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="arena" value="{{$arena}}">
                                <input type="hidden" name="linha_cuidado" value="{{$linha_cuidado}}">
                                <input type="hidden" name="date" value="{{json_encode($date)}}">
                                <input type="hidden" name="profissional" value="{{$row->id}}">
                                <input type="hidden" name="detalhado" value="1">

                                <a id="{{$row->id}}2" href="/admin/relatorio/conduta-data" class="btn-relatorio-conduta-medico btn btn-rounded btn-xs btn-success waves-effect"><i class="fa fa-area-chart"></i></a>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger">Sem registro!</div>
    @endif
@endif

