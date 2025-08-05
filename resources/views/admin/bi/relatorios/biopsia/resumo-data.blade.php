<?php

    $status_laudo = \App\Http\Helpers\Util::getLaudoResultados();
    $_arena = null;
?>
<?php
if(!empty($_data)):
?>

<table border="1" width="100%" class="table table-striped table-responsive table-bordered  bg-light " >
    <thead>
        <tr role="row">
            <th rowspan="2" class="align-center">{!! strtoupper(Lang::get('app.arena')) !!}</th>
            <th rowspan="2" class="align-center">{!! strtoupper(Lang::get('app.linha-cuidado')) !!}</th>
            <th colspan="{!! count($status_laudo) !!}" class="align-center">{!! strtoupper(Lang::get('app.status')) !!}</th>
        </tr>
        <tr role="row">
            @foreach($status_laudo AS $key => $status)
                <th>{!! strtoupper($status) !!}</th>
                <?php $_total[$key] = null;?>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($_data AS $arena => $linhas_cuidado)
            @foreach($linhas_cuidado AS $linha_cuidado => $rows)
                <tr>
                    @if($_arena != $arena)
                        <td rowspan="{!! count($linhas_cuidado) !!}"  class="font-bold middle">{!! $arena !!}</td>
                    @endif
                    <td>{!! $linha_cuidado !!}</td>
                    @foreach($status_laudo AS $key => $status)
                            <td class="align-center">
                                @foreach($rows AS $_key =>  $row)
                                    @if($_key == $key)
                                    <?php $_total[$key][] = count($row);?>
                                    {!! count($row) !!}
                                    @endif
                                @endforeach
                            </td>
                    @endforeach
                </tr>
                <?php
                $_arena = $arena;
                ?>
            @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <td colspan="2" class="align-right font-bold">Total</td>
        @foreach($status_laudo AS $key => $status)
            <td class="align-center">{!! (!empty($_total[$key]) && is_array($_total[$key])) ? array_sum($_total[$key]) : 0 !!}</td>
        @endforeach
    </tfoot>
</table>

<?php
else:
    echo "<div class='panel bg-danger pos-rlt'>
                <span class='arrow top  b-danger '></span>
                <div class='panel-body'>".Lang::get('grid.nenhum-registro-encontrado')."</div>
              </div>";
endif;
?>
