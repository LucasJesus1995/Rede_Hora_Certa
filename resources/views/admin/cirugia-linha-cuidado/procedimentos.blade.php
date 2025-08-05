<?php
    $count = 1;
?>

<div class="card">
    <div class="card-heading">
        <div class="row">
            <div class="col-md-12">
                <input type="text" name="pesquisa-procedimentos" id="input-cirugia-procedimentos" class="col-md-12 form-control" />
            </div>
        </div>
    </div>

    <div class="card-body bg-light lt" id="">

        <table id="table-cirugia-procedimentos" class="table table-striped table-responsive table-bordered bg-light " >
            <thead>
            <tr role="row">
                <th>#</th>
                <th>{!!Lang::get('app.nome')!!}</th>
                <th>{!!Lang::get('app.qtd')!!}</th>
                <th>{!!Lang::get('app.acao')!!}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($procedimentos AS $row)
                <tr data-procedimento="{{ $row['procedimento'] }}" data-linha-cuidado="{{ $linha_cuidado }}" >
                    <td>{{ $count }}</td>
                    <td><small>{{$row->procedimentos_nome}}</small></td>
                    <td>
                        <input class="col-md-12 form-control numbers quantidade " name="qtd"  maxlength="3" value="{{ $row->cirugia_linha_cuidado_procedimentos_qtd }}" />
                    </td>
                    <td nowrap>
                        <a href="" class="btn btn-rounded btn-xs btn-success waves-effec btn-save-cirugia-linha-cuidado-procedimentos"><i class="fa fa-save"></i></a>
                        <a href="" class="btn btn-rounded btn-xs btn-danger waves-effec btn-delete-cirugia-linha-cuidado-procedimentos"><i class="fa fa-remove"></i></a>
                    </td>
                </tr>
                <?php $count++ ?>
            @endforeach
            </tbody>
        </table>

    </div>
</div>