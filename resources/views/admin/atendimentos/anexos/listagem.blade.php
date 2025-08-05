@if(!empty($anexos) && count($anexos))
    <?php
    $tipos = \App\Http\Helpers\AtendimentoHelpers::getTipoAnexoAtendimento();
    ?>

    <table class="table table-striped table-bordered  bg-light ">
        <thead>
        <tr role="row">
            @if(\App\Http\Helpers\UsuarioHelpers::isNivelCirurgico())
                <th>Agendamento</th>
            @endif
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
                @if(\App\Http\Helpers\UsuarioHelpers::isNivelCirurgico())
                    <td nowrap>
                        {!! $row->agenda!!}<br/>
                        {!! \App\Http\Helpers\Util::DBTimestamp2UserDate($row->agenda_data) !!}
                    </td>
                @endif
                <td nowrap>{!! array_key_exists($row->tipo, $tipos) ? $tipos[$row->tipo] : null; !!}</td>
                <td>{!! $row->anotacao !!}</td>
                <td nowrap>
                    <a href="{!! $file !!}" target="_blank">
                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                    </a>
                    &nbsp;&nbsp;&nbsp;
                    @if(\App\Http\Helpers\UsuarioHelpers::isNivelCirurgico() && in_array($atendimento['status'], [2,8]))
                        <a href="" class="btn-fechamento-anexo-remove" data-id="{!! $row->id !!}" data-atendimento="{!! $atendimento['id'] !!}"  data-agenda="{!! $atendimento['agenda'] !!}">
                            <i class="fa fa-remove"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-danger">Nenhum arquivo encontrado!</div>
@endif
