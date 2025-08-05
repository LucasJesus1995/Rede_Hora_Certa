@if(!empty($anexos) && count($anexos))
    <?php
    $tipos = \App\Http\Helpers\AtendimentoHelpers::getTipoAnexoAtendimento();
    ?>

    <table class="table table-striped table-bordered  bg-light ">
        <thead>
        <tr role="row">
            <th>#</th>
            <th>Tipo</th>
            <th>Anotação</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($anexos AS $row)
            <?php
            $file = \App\Http\Helpers\Upload::getAtendimentoArquivos($row->arquivo);
            ?>
            <tr class="">
                <td nowrap>{!! $row->id !!}</td>
                <td nowrap>{!! array_key_exists($row->tipo, $tipos) ? $tipos[$row->tipo] : null; !!}</td>
                <td>{!! $row->anotacao !!}</td>
                <td nowrap>
                    <a href="{!! $file !!}" target="_blank">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-danger">Nenhum arquivo encontrado!</div>
@endif
