<?php
    if(!empty($grid->items())):
        ?>

        <table class="table table-striped table-responsive table-bordered  bg-light " >
            <thead>
                <tr role="row">
                    <th class="w-64">#</th>
                    <th>{!!Lang::get('app.mes')!!}</th>
                    <th class="w-64">{!!Lang::get('app.faturamento')!!}</th>
                    <th class="w-64">{!!Lang::get('app.status')!!}</th>
                    <th class="w-64">{!!Lang::get('grid.acao')!!}</th>
                </tr>
        </thead>
            <tbody>
                @foreach($grid AS $row)
                    <tr class="grid-status-{{$row->ativo}}">
                        <td>{{$row->id}}</td>
                        <td>
                            {{$row->ano}}/{{$row->mes}}
                        </td>
                        <td>
                            @if(in_array($row->status, [1,3]) && $row->ano == date('Y') && $row->mes == date('m'))
                                <a id="btn-faturamento-ativar" data-faturamento="{{$row->id}}" href="" class="btn btn-rounded btn-xs btn-success waves-effect">
                                    Ativar
                                </a>
                            @endif
                            @if(in_array($row->status, [2]))
                                <a id="btn-faturamento-fechar" data-faturamento="{{$row->id}}" href="" class="btn btn-rounded btn-xs btn-danger waves-effect">
                                    Fechar
                                </a>
                            @endif
                        </td>
                        <td>{!! \App\Http\Helpers\Util::getStatusFaturamentoLabel($row->status) !!}</td>
                        <td nowrap>
                            @if(in_array($row->status, [1,2,3]))
                                <a id="btn-faturamento-lote" data-faturamento="{!! $row->id !!}" href="" class="btn btn-rounded btn-xs btn-success waves-effect"><i class="fa fa-money"></i></a>
                            @endif
                            @if(in_array($row->status, [1]))
                                <a href="/admin/faturamento/entry/{{$row->id}}"  class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-edit"></i></a>
                            @endif

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

       {!! $grid->render() !!}
       <input type="hidden" id="url-pagination" value="{{urldecode($_SERVER['REQUEST_URI'])}}" />
        <?php
    else:
        echo "<div class='panel bg-danger pos-rlt'>
                <span class='arrow top  b-danger '></span>
                <div class='panel-body'>".Lang::get('grid.nenhum-registro-encontrado')."</div>
              </div>";
    endif;
?>