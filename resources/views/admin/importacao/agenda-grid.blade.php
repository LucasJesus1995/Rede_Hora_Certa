<?php
    $is_access = (in_array(App\User::getId(), [110]) || App\User::getPerfil() == 1);

    if(!empty($grid)):
        ?>
        <table class="table table-striped table-responsive table-bordered  bg-light " >
            <thead>
                <tr role="row">
                    <th>#</th>
                    <th nowrap>Importação</th>
                    <th>Informação</th>
                    <th>Falhas</th>
                    <th>Inserções</th>
                    <th>Total</th>
                    <th class="w-64">Arquivo</th>
                    <th class="w-64">Log</th>
                    @if($is_access)
                        <th class="w-64">Ação</th>
                    @endif
                </tr>
        </thead>
            <tbody>
                @foreach($grid AS $key => $row)
                    <tr class="">
                        <td>
                            {{$row['id']}}<br />
                            <strong style="color: red">{!! $row['equipamento'] !!}</strong>
                        </td>
                        <td>{{$row['date']}}<br />{{$row['time']}}</td>
                        <td><strong>{{$row['arena']}}</strong> - {{$row['linha_cuidado']}}<br />{{$row['data']}}</td>
                        <td class="align-center">{{$row['error']}}</td>
                        <td class="align-center">{{$row['insert']}}</td>
                        <td class="align-center">{{$row['total']}}</td>
                        <td class="align-center" nowrap>
                           @if(!empty($row['file']))
                                <a href="/admin/importacao/file?file={{$row['file']}}"  rel="{{$key}}"><i class="fa fa-file"></i></a>
                           @endif
                        </td>
                        <td class="align-center" nowrap>
                            <a href=""  rel="{{$key}}" class="box-line-relatorio"><i class="fa fa-file-code-o"></i></a>
                        </td>
                        @if($is_access)
                            <td class="align-center" nowrap>
                                <a href=""  data-id="{{$row['id']}}" class="btn-remove-agendamento"><i class="fa fa-remove"></i></a>
                            </td>
                        @endif
                    </tr>
                    <tr id="box-line-{{$key}}" style="display: none">
                        <td colspan="100%">
                            <pre style="font-size: 10px"><?php print_r(unserialize($row['log'])); ?></pre>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <?php
    else:
        echo "<div class='panel bg-danger pos-rlt'>
                <span class='arrow top  b-danger '></span>
                <div class='panel-body'>".Lang::get('grid.nenhum-registro-encontrado')."</div>
              </div>";
    endif;
?>