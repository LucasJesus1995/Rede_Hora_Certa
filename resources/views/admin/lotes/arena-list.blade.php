<div class="card">
    <div class="card-heading">
      <h2>{{$lote->codigo}} - {{$lote->nome}}</h2>
      <small>Gerenciamento de arenas por lote</small>
    </div>

    <div class="card-body bg-light lt" id="box-grid">
        <div class="text-center m-b">
            <input type="hidden" name="lote" id="lote" value="{{$lote->id}}" />
            <?php
                if(!empty($arenas[0])):
                    ?>
                    <table class="table table-striped table-responsive table-bordered  bg-light " >
                        <thead>
                            <tr role="row">
                                <th></th>
                                <th>{!!Lang::get('app.arenas')!!}</th>
                                <th>Linhas de cuidado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($arenas AS $row)
                                <?php
                                    $_lote = \App\LotesArena::getArenaLote($lote->id, $row['id']);
                                    $checked = !empty($_lote[0]) ? "checked='checked'" : null;


                                    $lote_arena = \App\LotesArena::where('arena','=',$row['id'])->where('lote','<>',$lote->id)->get()->toArray();
                                    $arena = !empty($lote_arena[0]['arena']) ? $lote_arena[0]['arena'] : null;
                                ?>
                                <tr class="">
                                    <td valign="middle">
                                        @if(empty($lote_arena[0]))
                                            <input type="checkbox" id="lote-arena-{{$row['id']}}" rel="{{$row['id']}}"  {{$checked}} class="btn-lote-arena" />
                                        @endif
                                    </td>
                                    <td class="align-left">
                                        <strong >{{$row['cnes']}} - {{$row['nome']}}</strong> - {{$row['descricao']}}<br />
                                        @if(!empty($row['responsavel']))
                                            <?php
                                                $responsavel = \App\Profissionais::find($row['responsavel']);
                                                if(!empty($responsavel->nome)){
                                                    echo $responsavel->cns." - ".$responsavel->nome;
                                                }
                                            ?>
                                        @endif
                                    </td>
                                    <td>
                                        <?php
                                            $linhas_cuidado = \App\LinhaCuidado::ByArena($row['id']);
                                            if($linhas_cuidado){
                                                foreach ($linhas_cuidado AS $line){
                                                    echo "<span class='text-descricao-input text-muted block text-xxs align-left'>- {$line}</span>";
                                                }
                                            }
                                        ?>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <?php
                else:
                    echo "<div class='panel bg-danger pos-rlt'>
                            <span class='arrow top  b-danger '></span>
                            <div class='panel-body'>Nenhuma Arena foi encontrada!</div>
                          </div>";
                endif;
            ?>
        </div>
    </div>
</div>