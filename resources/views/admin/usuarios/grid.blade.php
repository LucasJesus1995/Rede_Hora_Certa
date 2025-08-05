<?php
    if(!empty($grid->items())):
        $levels = \App\Roles::Combo();
        ?>

        <table class="table table-striped table-responsive table-bordered  bg-light " >
            <thead>
                <tr role="row">
                    <th class="w-64">#</th>
                    <th>Lote</th>
                    <th>Nivel</th>
                    <th>{!!Lang::get('app.nome')!!}</th>
                    <th>{!!Lang::get('app.login')!!}</th>
                    <th class="w-64">{!!Lang::get('grid.acao')!!}</th>
                </tr>
        </thead>
            <tbody>
                @foreach($grid AS $row)
                    <tr class="grid-status-{{$row->active}}">
                        <td>{{$row->id}}</td>
                        <td>{{$row->nome}}</td>
                        <td>
                            {!! array_key_exists($row->level, $levels) ? $levels[$row->level]  : null!!}
                        </td>
                        <td>{{$row->name}}</td>
                        <td class="email">{{$row->email}}</td>
                        <td nowrap>
                            <a href="/admin/usuarios/entry/{{$row->id}}"  class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-edit"></i></a>
                            <a href="/admin/usuarios/delete/{{$row->id}}" class="btn-grid-delete btn btn-rounded btn-xs btn-danger waves-effect"><i class="fa fa-remove"></i></a>
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