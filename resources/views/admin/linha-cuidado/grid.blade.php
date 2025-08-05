<?php
    if(!empty($grid->items())):
        ?>

        <table class="table table-striped table-responsive table-bordered  bg-light " >
            <thead>
                <tr role="row">
                    <th class="w-64">#</th>
                    <th>{!!Lang::get('app.ordem')!!}</th>
                    <th>{!!Lang::get('grid.abreviacao')!!}</th>
                    <th>{!!Lang::get('grid.nome')!!}</th>
                    <th class="w-64">{!!Lang::get('grid.acao')!!}</th>
                </tr>
        </thead>
            <tbody>
                @foreach($grid AS $row)
                    <tr class="grid-status-{{$row->ativo}}">
                        <td>{{$row->id}}</td>
                        <td>{{$row->ordem}}</td>
                        <td>{{$row->abreviacao}}</td>
                        <td>{{$row->nome}}</td>
                        <td nowrap>
                            <a href="javascript: void(0)" url="/admin/linha-cuidado/medicamentos/{{$row->id}}" title="Medicamentos" class="btn btn-rounded btn-xs btn-success waves-effect btn-linha_cuidado-medicamentos"><i class="fa fa-list"></i></a>
                            <a href="/admin/linha-cuidado/cids/{{$row->id}}" title="CIDs" class="btn btn-rounded btn-xs btn-warning waves-effect btn-linha_cuidado-cids"><i class="fa fa-list"></i></a>
                            <a href="/admin/linha-cuidado/entry/{{$row->id}}"  class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-edit"></i></a>
                            <a href="/admin/linha-cuidado/delete/{{$row->id}}" class="btn-grid-delete btn btn-rounded btn-xs btn-danger waves-effect"><i class="fa fa-remove"></i></a>
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