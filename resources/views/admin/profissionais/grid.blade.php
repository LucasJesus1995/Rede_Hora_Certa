<?php
    if(!empty($grid->items())):
        ?>

        <table class="table table-striped table-responsive table-bordered  bg-light " >
            <thead>
                <tr role="row">
                    <th class="w-64">#</th>
                    <th>{!!Lang::get('app.nome')!!}</th>
                    <th>{!!Lang::get('app.tipo')!!}</th>
                    <th>{!!Lang::get('app.cpf')!!}</th>
                    <th>{!!Lang::get('app.cns')!!}</th>
                    <th>{!!Lang::get('app.cro')!!}</th>
                    <th class="w-64">{!!Lang::get('grid.acao')!!}</th>
                </tr>
        </thead>
            <tbody>
                @foreach($grid AS $row)
                    <?php
                        $tipo = \App\Http\Helpers\Util::TypeProfissional($row->type);
                        $tipo = is_array($tipo) ? null : $tipo;
                    ?>
                    <tr class="grid-status-{{$row->ativo}}">
                        <td>{{$row->id}}</td>
                        <td>{{$row->nome}}</td>
                        <td>{{$tipo}}</td>
                        <td>{{ \App\Http\Helpers\Mask::Cpf($row->cpf)}}</td>
                        <td>{{$row->cns}}</td>
                        <td>{{$row->cro}}</td>
                        <td nowrap>
                        <a href="/admin/profissionais/entry/{{$row->id}}"  class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-edit"></i></a>
                        <a href="/admin/profissionais/delete/{{$row->id}}" class="btn-grid-delete btn btn-rounded btn-xs btn-danger waves-effect"><i class="fa fa-remove"></i></a>
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