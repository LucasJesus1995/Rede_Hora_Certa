@if(!empty($grid))
    <table class="table table-striped table-responsive table-bordered bg-light " >
        <thead>
            <tr role="row">
                <th>{!!Lang::get('app.profissional')!!}</th>
                <th>{!!Lang::get('app.cns')!!}</th>
                <th>{!!Lang::get('app.cbo')!!}</th>
                <th class="w-64">{!!Lang::get('grid.acao')!!}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grid AS $row)
                <?php
                    $cbos = \App\LoteProfissionalCbo::getCbos($row->lote_profissionais_id);
                ?>
                <tr class="grid-status-{{$row->ativo}}" data-id="{!! $row->lote_profissionais_id !!}" id="{!! $row->lote_profissionais_id !!}">
                    <td>{{$row->profissionais_nome}}</td>
                    <td>{{$row->profissionais_cns}}</td>
                    <td nowrap>
                        <div class="box-cbos">
                            @if(!empty($cbos))
                                @foreach($cbos AS $item)
                                    <small>{!! $item->codigo !!} - {!! $item->nome !!}</small><br />
                                @endforeach
                            @endif
                        </div>
                        <div style="text-align: center; font-size: 10px">
                            <button md-ink-ripple="" class="md-btn md-flat m-b btn-fw text-info waves-effect btn-lote-procedimento-lista-cbos col-md-12">CBO'S</button>
                        </div>
                    </td>
                    <td nowrap>
                        <a href="javascript: void(0);" class="btn-remove-lote-profissional btn btn-rounded btn-xs btn-danger waves-effect" id="{!! $row->lote_profissionais_id !!}">
                            <i class="fa fa-remove"></i>
                        </a>
                    </td>
                </tr>
                <tr></tr>
                <tr>
                    <td colspan="4" class="cbo-list" id="cbo-list-{!! $row->lote_profissionais_id !!}">

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class='panel bg-danger pos-rlt'><span class='arrow top  b-danger '></span><div class='panel-body'>{!! Lang::get('grid.nenhum-registro-encontrado') !!}</div></div>
@endif