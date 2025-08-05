<?php
$is_access = (in_array(App\User::getId(), [110]) || App\User::getPerfil() == 1);
if(!empty($grid)):
?>
<table class="table table-striped table-responsive table-bordered  bg-light ">
    <thead>
    <tr role="row">
        <th>Código</th>
        <th>Importação</th>
        <th nowrap>Importação</th>
        <th>Falhas</th>
        <th>Inserções</th>
        <th>Total</th>
        <th class="w-64">Log</th>
        @if($is_access)
            <th class="w-64">Ação</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($grid AS $key => $row)
        <?php
        $log = !empty($row['log']) ? @unserialize($row['log']) : null;
        $params = $log['params'];
        ?>
        <tr class="">
            <td>{{$row['id']}}</td>
            <td>{{$row['date']}}<br/>{{$row['time']}}</td>
            <td>
                <strong>{{$row['arena']}}</strong> - {{$row['linha_cuidado']}}<br/>
                {!! $params['data'] !!}
            </td>
            <td class="align-center">{{$row['error']}}</td>
            <td class="align-center">{{$row['insert']}}</td>
            <td class="align-center">{{$row['total']}}</td>
            <td class="align-center" nowrap>
                <a href="" rel="{{$key}}" class="box-line-relatorio"><i class="fa fa-file-code-o"></i></a>
            </td>
            @if($is_access)
                <td class="align-center" nowrap>
                    <a href="" data-id="{{$row['id']}}" class="btn-remove-agendamento"><i class="fa fa-remove"></i></a>
                </td>
            @endif
        </tr>
        <tr id="box-line-{{$key}}" style="display: none">
            <td colspan="100%">
                <pre>
                    <?php
                    if (!empty($row['log'])) {

                        if (is_array($log)) {
                            print("<pre>" . print_r($log, true) . "</pre>");
                        }
                    }
                    ?>
                </pre>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<?php
else:
    echo "<div class='panel bg-danger pos-rlt'>
                <span class='arrow top  b-danger '></span>
                <div class='panel-body'>" . Lang::get('grid.nenhum-registro-encontrado') . "</div>
              </div>";
endif;
?>