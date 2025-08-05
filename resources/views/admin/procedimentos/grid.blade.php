<?php
if(!empty($grid->items())):
?>

<table class="table table-striped table-responsive table-bordered bg-light ">
    <thead>
    <tr role="row">
        <th class="w-64">#</th>
        <th>Operacional</th>
        <th>SUS</th>
        <th>Nome</th>
        <th class="w-64">{!!Lang::get('grid.acao')!!}</th>
    </tr>
    </thead>
    <tbody>
    @foreach($grid AS $row)
        <tr class="grid-status-{{$row->ativo}}">
            <td>{{$row->id}}</td>
            <td>{!! \App\Http\Helpers\Util::Ativo($row->operacional) !!}</td>
            <td nowrap>{{ \App\Http\Helpers\Mask::CodigoProcedimento($row->sus) }}</td>
            <td>{{$row->nome}}</td>
            <td nowrap>
                <a href="/admin/procedimentos/cids/{{$row->id}}" title="Listagem de Cids"
                   class="btn btn-rounded btn-xs btn-success waves-effect btn-procedimentos-cids"><i
                            class="fa fa-list"></i></a>
                <a href="/admin/procedimentos/entry/{{$row->id}}"
                   class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-edit"></i></a>
                <a href="/admin/procedimentos/delete/{{$row->id}}"
                   class="btn-grid-delete btn btn-rounded btn-xs btn-danger waves-effect"><i
                            class="fa fa-remove"></i></a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

{!! $grid->render() !!}
<input type="hidden" id="url-pagination" value="{{urldecode($_SERVER['REQUEST_URI'])}}"/>
<?php
else:
    echo "<div class='panel bg-danger pos-rlt'>
                <span class='arrow top  b-danger '></span>
                <div class='panel-body'>" . Lang::get('grid.nenhum-registro-encontrado') . "</div>
              </div>";
endif;
?>