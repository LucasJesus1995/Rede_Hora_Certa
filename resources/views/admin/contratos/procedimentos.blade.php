<div class="card">
    <div class="card-heading">
        <h2>{{$contrato->codigo}} - {{$contrato->nome}}</h2>
        <small></small>
    </div>


    <div class="md-whiteframe-z0 bg-white">
        <ul class="nav nav-lines nav-tabs nav-justified nav-atendimento">
            <?php use App\Lotes;$i = 0;?>
            @foreach($lotes AS $lote)
                <li class="{!! ($i == 0) ? 'active' : null !!}">
                    <a class="text-sm btn btn-lg btn-rounded btn-stroke btn-info m-r waves-effect" data-target="#tab-lote-{!! $lote->id !!}" data-toggle="tab" href="" data-atendimento="">
                        <span class="block clear text-left m-v-xs"> <b class="text-lg block font-bold">{!! $lote->nome !!}</b></span>
                    </a>
                </li>
                <?php $i++;?>
            @endforeach
        </ul>

        <div class="tab-content p m-b-md clear b-t b-t-2x">
            <?php $i = 0;?>
            @foreach($lotes AS $lote)
                <?php
                    $arenas = Lotes::getArenas([$lote->id]);
                    $procedimentos = \App\Procedimentos::getByArenas($arenas);
                ?>
                <div id="tab-lote-{!! $lote->id !!}" class="tab-pane animated fadeInDown {!! ($i == 0) ? 'active' : null !!}" role="tabpanel">
                    {!! $lote->nome !!}
                    <table class="table table-striped table-responsive table-bordered  bg-light " >
                    <thead>
                    <tr role="row">
                        <th>{!!Lang::get('app.procedimento')!!}</th>
                        <th>Demanda</th>
                        <th>Qtd.</th>
                        <th>Valor</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($procedimentos))
                        @foreach($procedimentos AS $k => $row)
                            <?php
                            $contrato_procedimento = \App\ContratoProcedimentos::getContratoProcedimentoByContratoProcedimentoLote($contrato->id, $row->id, $lote->id);
                            ?>
                            <tr data-contrato="{!! $contrato->id !!}" data-procedimento="{!! $row->id !!}" data-lote="{!! $lote->id !!}">
                                <th>{!! $row->nome !!} </th>
                                <td class="<?php echo ($k % 2) ? "col-destaque-even" :  "col-destaque-odd" ?>">{!!Form::textField('demanda', null, !empty($contrato_procedimento->demanda) ? $contrato_procedimento->demanda : null , array('class' => 'form-control numbers data-procedimento-contrato demanda', 'placeholder'=>'', 'maxlength'=> 6))!!}</td>
                                <td>{!!Form::textField('quantidade', null, !empty($contrato_procedimento->quantidade) ? $contrato_procedimento->quantidade : null , array('class' => 'form-control numbers data-procedimento-contrato quantidade', 'placeholder'=>'Qtd', 'maxlength'=> 6))!!}</td>
                                <td>{!!Form::textField('valor_unitario', null, !empty($contrato_procedimento->valor_unitario) ? $contrato_procedimento->valor_unitario : null, array('class' => 'form-control money  data-procedimento-contrato valor_unitario', 'placeholder'=>'Valor', 'maxlength'=> 10))!!}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                    </table>
                </div>
                <?php $i++;?>
            @endforeach
        </div>
    </div>


<script>
    loadingMask();
</script>