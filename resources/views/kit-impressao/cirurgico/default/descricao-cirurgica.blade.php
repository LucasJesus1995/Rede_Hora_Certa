<?php
$profissional = null;
$cbos = null;

if (!empty($agenda->medico)) {
    $profissional = \App\Profissionais::getMedicoByID($agenda->medico);

    $cbos = \App\ProfissionaisCbo::getCboByProfissional($profissional->id);
}

$ids = \App\Http\Helpers\Cirurgico\KitImpressaoHelpers::getProcedimentos($agenda->linha_cuidado, $sub_especialidade);
if (!empty($ids)) {
    $procedimentos = \App\Procedimentos::whereIn('id', $ids)->orderBy('nome', 'asc')->get();
}
?>
<div style='page-break-before:always;' class="kit-impressao">
    @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))

    <h1 class="title">Descrição de Procedimento Ambulatorial</h1>

    <div class="bloco " style="margin: 0; margin-top: 5px;">
        <h2 style="text-align: left">EQUIPE</h2>
        <div style="margin: 5px 5px; line-height: 22px">
            <table width="100%" class="border">
                <tr>
                    <th width="30%" class="title"><strong>CIRURGIÃO</strong></th>
                    <th width="7%" class="title">CRM</th>
                    <th width="15%" class="title">CNS</th>
                    <th width="48%" class="title">CBOs</th>
                </tr>
                <tr>
                    <td>{!! (!empty($profissional->nome)) ?  $profissional->nome : null  !!}&nbsp;</td>
                    <td>{!! (!empty($profissional->cro)) ?  $profissional->cro : null  !!}&nbsp;</td>
                    <td>{!! (!empty($profissional->cns)) ?  \App\Http\Helpers\Mask::SUS($profissional->cns) : null  !!}&nbsp;</td>
                    <td>
                        @if(!empty($cbos))
                            @foreach($cbos AS $cbo)
                                <div>{!! $cbo->codigo !!} - {!! $cbo->nome !!}</div>
                            @endforeach
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

    @if(!empty($procedimentos))
        <div class="bloco " style="margin: 0; margin-top: 10px;">
            <h2 style="text-align: left">Procedimentos realizado</h2>
            <div style="margin: 10px 5px; line-height: 22px">
                <table width="100%" class="border">
                    <tr>
                        @if(!in_array($agenda->linha_cuidado, [9]))
                            @if(count($procedimentos) > 1)
                                <th width="3%" class="line-height-16 title"></th>
                            @endif
                            <th width="12%" class="line-height-16 title"><strong>Código SUS</strong></th>
                        @endif
                        <th width="85%" class="line-height-16 title"><strong>Descrição</strong></th>
                    </tr>
                    @foreach($procedimentos AS $procedimento)
                        <?php $cor_list = (@$cor_list == "even") ? "odd" : "even";?>
                        <tr class="{!! $cor_list !!}">
                            @if(!in_array($agenda->linha_cuidado, [9]))
                                @if(count($procedimentos) > 1)
                                    <td width="5%" style="text-align: center">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                                @endif
                                <td width="10%">{!! \App\Http\Helpers\Mask::ProcedimentoSUS($procedimento->sus) !!}</td>
                            @endif
                            <td width="85%">
                                @if(in_array($agenda->linha_cuidado, [9]))
                                    {!! str_replace("UNILATERAL", "________________________", $procedimento->nome) !!}
                                @else
                                    {!! $procedimento->nome !!}
                                @endif

                                @if(in_array($sub_especialidade, [5]) || in_array($agenda->linha_cuidado, [46]))
                                    <br/><br/><br/><br/><br/><br/>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>

            </div>
        </div>
    @endif

    @include('elements.layout.kit.cirurgico.kits.lateralidade')

    @if(in_array($agenda->linha_cuidado,[9]))
        <div class="bloco " style="margin: 0; margin-top: 10px;">
            <table>
                <tr>
                    <th width="50%" class="center">MEMBRO INFERIOR ESQUERDO</th>
                    <th width="50%" class="center">MEMBRO INFERIOR DIREITO</th>
                </tr>
                <tr>
                    <td class="center">
                        <img src="src/image/kit-impressao/vascular-direito.png" width="270px">
                    </td>
                    <td class="center">
                        <img src="src/image/kit-impressao/vascular-esquerdo.png" width="270px">
                    </td>
                </tr>
                <tr>
                    <th colspan="2" class="center">
                        VARIZES TRATADAS COM ESCLEROTERAPIA
                    </th>
                </tr>
            </table>
        </div>
    @endif

    <div style="margin-top: 60px">
        @include('kit-impressao.cirurgico.default.assinaturas.assinatura-carimbo-medico')
    </div>


    <div style="position: absolute; bottom: -50px">
        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
    </div>
</div>