<?php
    if(!empty($grid->items())):
        ?>
        <table class="table table-striped table-responsive table-bordered  bg-light " >
            <thead>
                <tr role="row">
                    <th class="w-64">#</th>
                    <th>Código</th>
                    <th>Nome</th>
                    <th class="w-64">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grid AS $row)
                    <tr class="grid-status-{{$row->ativo}}">
                        <td>{{$row->id}}</td>
                        <td>{{$row->codigo}}</td>
                        <td>{{$row->nome}}</td>
                        <td nowrap>
                            <a href="javascript: void(0)" url="/admin/exames/linha-cuidado/{{$row->id}}" title="Exames" class="btn btn-rounded btn-xs btn-success waves-effect btn-linha_cuidado-exames"><i class="fa fa-list"></i></a>
                            <a href="/admin/exames/entry/{{$row->id}}"  class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-edit"></i></a>
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