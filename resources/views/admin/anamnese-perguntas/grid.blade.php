<?php
    if(!empty($grid->items())):
        ?>

        <table class="table table-striped table-responsive table-bordered  bg-light " >
            <thead>
                <tr role="row">
                    <th class="w-64">#</th>
                    <th>{!!Lang::get('grid.nome')!!}</th>
                    <th>{!!Lang::get('app.codigo-cid')!!}</th>
                    <th>{!!Lang::get('app.tipo-resposta')!!}</th>
                    <th>{!!Lang::get('app.multiplas')!!}</th>
                    <th class="w-64">{!!Lang::get('grid.acao')!!}</th>
                </tr>
        </thead>
            <tbody>
                @foreach($grid AS $row)
                    <tr class="grid-status-{{$row->ativo}}">
                        <td>{{$row->id}}</td>
                        <td class="no-lower">{{$row->nome}}</td>
                        <td>{{$row->cid}}</td>
                        <td>{{$row->tipo_resposta}}</td>
                        <td>{{\App\Http\Helpers\Util::Ativo($row->multiplas)}}</td>
                        <td nowrap>
                        <a href="/admin/anamnese-perguntas/entry/{{$row->id}}"  class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-edit"></i></a>
                        <a href="/admin/anamnese-perguntas/delete/{{$row->id}}" class="btn-grid-delete btn btn-rounded btn-xs btn-danger waves-effect"><i class="fa fa-remove"></i></a>
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