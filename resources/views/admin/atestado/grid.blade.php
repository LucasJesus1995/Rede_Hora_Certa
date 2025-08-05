<?php
    if(!empty($grid->items())):
        ?>
        <table class="table table-striped table-responsive table-bordered  bg-light " >
            <thead>
                <tr role="row">
                    <th class="w-64">#</th>
                    <th>Atendimento</th>
                    <th>Empresa</th>
                    <th>Codigo CID</th>
                    <th>Descrição CID</th>
                    <th class="w-64">{!!Lang::get('grid.acao')!!}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grid AS $row)
                    <tr class="grid-status-{{$row->ativo}}">
                        <td>{{$row->id}}</td>
                        <td>{{$row->atendimento}}</td>
                        <td>{{$row->empresa}}</td>
                        <td>{{$row->codigo}}</td>
                        <td>{{$row->descricao}}</td>
                        <td nowrap>
                            <a href="/admin/atestado/print-atestado/{{$row->atendimento}}" target="_blank" title="Imprimir" class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-print"></i></a>
                            <a href="/admin/atestado/entry/{{$row->id}}" class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-edit"></i></a>
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