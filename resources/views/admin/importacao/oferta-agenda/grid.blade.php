<?php
if(!empty($arenas)):
?>
<input id="importacao-oferta-lote" type="hidden" value="{!! $lote !!}"/>
<input id="importacao-oferta-ano" type="hidden" value="{!! $ano !!}"/>
<input id="importacao-oferta-mes" type="hidden" value="{!! $mes !!}"/>

<table class="table table-striped table-responsive table-bordered  bg-light table-conde ">
    <thead>
    <tr role="row">
        <th>Arena</th>
        @foreach($linha_cuidado AS $linha)
            <th alt="{!! $linha->nome !!}" alt="{!! $linha->nome !!}" style="width: 45px !important; height: 100px; padding: 2px; font-size: 10px;">
                <div class="graus90">
                    {!! $linha->abreviacao !!}
                </div>
            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($arenas AS $row)
        <?php
        $arr_linha_cuidado
        ?>
        <tr class="grid-status-{{$row->ativo}}">
            <td>{{$row->nome}}</td>
            @foreach($linha_cuidado AS $linha)
                <?php
                $qtd = (!empty($ofertas[$row->id]) && !empty($ofertas[$row->id][$linha->id])) ? $ofertas[$row->id][$linha->id] : null;
                ?>
                <td style="padding: 2px 5px; text-align: center; vertical-align: middle">
                    <input class="form-control number importacao-oferta" data-arena="{!! $row->id !!}" data-linha-cuidado="{!! $linha->id !!}" style="font-size: 12px!important; padding: 1px" maxlength="5" value="{!! $qtd !!}" title="{!! $linha->nome !!}" alt="{!! $linha->nome !!}"/>
                </td>
            @endforeach
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
<script>
    loadingMask();
</script>
