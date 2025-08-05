<?php
$kit_white = !empty($kit_white) ? $kit_white : false;


$linha_cuidado = (Object)\App\LinhaCuidado::get($agenda->linha_cuidado);

$hidden_default_kit = (in_array($agenda->tipo_atendimento, [3, 7]) && in_array($agenda->linha_cuidado, [19]));
?>
@extends('pdf')

@section('content')
    <title>{!!$title!!}</title>

    <div id="print-kits" class="kit-impressao">
        @if(!empty($messagem_error))
            <h4 class="center">{!! $messagem_error !!}</h4>
        @else
            @if($local == null)

                {{-- CIRURGIA --}}
                @if($linha_cuidado->especialidade == 2)
                    <?php
                    $__kit_impressao = \App\Http\Helpers\Atendimento\KitImpressaoHelpers::getKitEspecialidade($agenda->linha_cuidado, $sub_especialidade);
                    ?>
                    @if(!empty($__kit_impressao))
                        @foreach ($__kit_impressao as $file)
                            @include($file, ['agenda'=>$agenda, 'linha_cuidado' => $linha_cuidado, 'sub_especialidade' => $sub_especialidade])
                        @endforeach
                    @else

                        <div class="">
                            @if(in_array($sub_especialidade, [3]))
                                @include('elements.layout.kit.cirurgico.cirurgia-oftamologica-yag-laser', ['agenda'=>$agenda])
                            @else
                                @include('elements.layout.kit.cirurgico.planejamento-assistencia-enfermagem', ['agenda'=>$agenda])
                                <div style='page-break-before:always;'>
                                    @if(in_array($linha_cuidado->id, [45]) && in_array($sub_especialidade, [2]))
                                        @include('elements.layout.kit.cirurgico.termo-consentimento.pterigio', ['agenda'=>$agenda])
                                    @elseif(in_array($linha_cuidado->id, [9]))
                                        @include('elements.layout.kit.cirurgico.termo-consentimento-escleroterapia', ['agenda'=>$agenda])
                                        @include('elements.layout.kit.cirurgico.termo-consentimento-escleroterapia-politica_privacidade', ['agenda'=>$agenda])
                                        @include('elements.layout.kit.cirurgico.termo-consentimento-escleroterapia-varizes_membros', ['agenda'=>$agenda])
                                    @else
                                        @include('elements.layout.kit.cirurgico.termo-consentimento', ['agenda'=>$agenda])
                                    @endif
                                </div>
                                <div style='page-break-before:always;'>
                                    @include('elements.layout.kit.cirurgico.ficha-internacao', ['agenda'=>$agenda])
                                </div>
                                @if(!in_array($agenda->linha_cuidado, [9]))
                                    <div style='page-break-before:always;'>
                                        @include('elements.layout.kit.cirurgico.check-list', ['agenda'=>$agenda])
                                    </div>
                                @else
                                    <div style='page-break-before:always;'></div>
                                @endif
                                <div style='page-break-before:always;'>
                                    @include('elements.layout.kit.cirurgico.descricao-cirurgica', ['agenda'=>$agenda])
                                </div>
                                <div style='page-break-before:always;'>
                                    @include('elements.layout.kit.cirurgico.avaliacao-pos-operatoria', ['agenda'=>$agenda])
                                </div>
                                <div style='page-break-before:always;'>
                                    @include('elements.layout.kit.cirurgico.alta-hospitalar', ['agenda'=>$agenda, 'orientacoes' => false])
                                </div>
                                <div style='page-break-before:always;'></div>
                                <div style='page-break-before:always;'>
                                    @include('elements.layout.kit.cirurgico.alta-hospitalar', ['agenda'=>$agenda, 'orientacoes' => true])
                                </div>
                                <div style='page-break-before:always;'>
                                    @include('elements.layout.kit.cirurgico.declaracao-recebimento-informacoes', ['agenda'=>$agenda])
                                </div>
                                <div style='page-break-before:always;'>
                                    @include('elements.layout.kit.cirurgico.relacao-impressos', ['agenda'=>$agenda])
                                </div>
                                <div style='page-break-before:always;'>
                                    @include('elements.layout.kit.cirurgico.folha-debito', ['agenda'=>$agenda])
                                </div>
                                <div style='page-break-before:always;'>
                                    @include('elements.layout.kit.cirurgico.receita', ['agenda'=>$agenda])
                                </div>

                                @if(in_array($linha_cuidado->id, [45]) && in_array($sub_especialidade, [1]))
                                    <div style='page-break-before:always;'></div>
                                    <div style='page-break-before:always;'>
                                        @include('elements.layout.kit.cirurgico.oftalmologica-calendario', ['agenda'=>$agenda])
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                @else
                    <?php if(!$hidden_default_kit){?>
                    <div>
                        @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => true, 'logo_drsaude' => false, 'hidden_logo_header' => true))
                        @include('elements.layout.kit.questionario-especifico', array())
                        @include('elements.layout.kit.acolhimento', array())
                        @include('elements.layout.kit.prescricao-medica', array())
                        <div style="position: absolute; bottom: -50px">
                            @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id, 'info_lgpd' => 1])
                        </div>
                    </div>
                    <?php }?>

                    @if(in_array($agenda->linha_cuidado , array(20, 3)))
                        <div style='page-break-before:always;' class="kit-impressao">
                            <
                            <div>
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))
                                @include('elements.layout.kit.termo-nasofibro', array('agenda'=>$agenda))
                                <div style="position: absolute; bottom: -50px">
                                    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(in_array($agenda->linha_cuidado , array(22, 26, 28)))
                        <div style='page-break-before:always;' class="kit-impressao">
                            <div>
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => true, 'exibe_linha' => true, 'logo_drsaude' => false))
                                @include('elements.layout.kit.urologia', array('agenda'=>$agenda))
                            </div>

                            <div style="position: absolute; bottom: -50px">
                                @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                            </div>
                        </div>
                    @endif

                    @if(in_array($agenda->linha_cuidado , array(28)))
                        <div style='page-break-before:always;' class="kit-impressao">
                            <div>
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => true, 'exibe_linha' => true, 'logo_drsaude' => false))
                                @include('elements.layout.kit.dermatologia', array('agenda'=>$agenda))
                            </div>

                            <div style="position: absolute; bottom: -50px">
                                @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                            </div>
                        </div>
                    @endif

                    @if(in_array($agenda->linha_cuidado , array(24)))
                        <div style='page-break-before:always;' class="kit-impressao">
                            <div>
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => true, 'exibe_linha' => true, 'logo_drsaude' => false))
                                @include('elements.layout.kit.coloproctologia', array('agenda'=>$agenda))
                            </div>

                            <div style="position: absolute; bottom: -50px">
                                @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                            </div>
                        </div>
                    @endif

                    @if(in_array($agenda->linha_cuidado , array(1,2,8,9)))
                        <div style='page-break-before:always;' class="kit-impressao">
                            <div>
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => false))
                                @include('elements.layout.kit.assistencia-enfermagem', array('agenda'=>$agenda))
                            </div>

                            <div style="position: absolute; bottom: -50px">
                                @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                            </div>
                        </div>
                    @endif

                    @if(in_array($agenda->linha_cuidado , array(42)))

                        <div style='page-break-before:always;'>
                            <div>
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => true, 'exibe_linha' => true, 'logo_drsaude' => true))
                                @include('elements.layout.kit.mamografia', array('agenda'=>$agenda))
                                <div style="position: absolute; bottom: -50px">
                                    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                                </div>
                            </div>
                        </div>

                        <div style='page-break-before:always;'>
                            <div>
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'logo_drsaude' => false, 'hidden_logo_header' => true))
                                @include('elements.layout.kit.termo-mamografia', array('agenda'=>$agenda))
                                <div style="position: absolute; bottom: -50px">
                                    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                                </div>
                            </div>
                        </div>

                    @endif

                    @if(in_array($agenda->linha_cuidado , array(27)))
                        <div style='page-break-before:always;'>
                            <div>
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => false, 'logo_drsaude' => true))
                                @include('elements.layout.kit.ressonancia', array('agenda'=>$agenda))
                                <div style="position: absolute; bottom: -50px">
                                    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                                </div>
                            </div>
                        </div>
                        <div>
                            <div>
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => false, 'logo_drsaude' => true))
                                @include('elements.layout.kit.ressonancia-termo-consentimento', array('agenda'=>$agenda))
                                <div style="position: absolute; bottom: -50px">
                                    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(in_array($agenda->linha_cuidado , array(23)))
                        <div style='page-break-before:always;'>
                            <div>
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => false, 'logo_drsaude' => true))
                                @include('elements.layout.kit.tomografia', array('agenda'=>$agenda))
                                <div style="position: absolute; bottom: -50px">
                                    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                                </div>
                            </div>
                        </div>
                        <div>
                            <div>
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => false, 'logo_drsaude' => true))
                                @include('elements.layout.kit.tomografia-termo-consentimento', array('agenda'=>$agenda))
                                <div style="position: absolute; bottom: -50px">
                                    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(in_array($agenda->linha_cuidado , array(19)))
                        @if(in_array($agenda->tipo_atendimento, array(7)))
                            <div>
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => true,  'logo_drsaude' => true))
                                @include('elements.layout.kit.oftamologia', array('agenda'=>$agenda))
                                <div style="position: absolute; bottom: -50px">
                                    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                                </div>
                            </div>
                        @else
                            <?php if (!$hidden_default_kit) { ?>
                            <div style='page-break-before:always;'>
                                <?php }?>
                                <div>
                                    @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => true,  'logo_drsaude' => true))
                                    @include('elements.layout.kit.oftamologia', array('agenda'=>$agenda))
                                    <div style="position: absolute; bottom: -50px">
                                        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                                    </div>
                                </div>

                                <div style='page-break-before:always;'>
                                    @include('elements.layout.kit.oftamologia-mapeamento-retina', array('agenda'=>$agenda))
                                </div>

                                <div style='page-break-before:always;'>
                                    @include('elements.layout.kit.oftamologia-prescricao', array('agenda'=>$agenda))
                                </div>

                                <?php if (!$hidden_default_kit) { ?>
                            </div>
                            <?php }?>
                        @endif
                    @endif

                    @if(in_array($agenda->linha_cuidado , array(3,4)))
                        <div style='page-break-before:always;'>
                            <div style="margin: 0; padding: 0">
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false))
                                @include('elements.layout.kit.ecocargiograma',  array('agenda'=>$agenda))
                                <div style="position: absolute; bottom: -60px; ">
                                    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(in_array($agenda->linha_cuidado , array(49)))
                        <div style='page-break-before:always;'>
                            <div>
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => true, 'logo_drsaude' => true))
                                @include('elements.layout.kit.vascular',  array('agenda'=>$agenda))
                                <div style="position: absolute; bottom: -50px">
                                    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                                </div>
                            </div>
                        </div>
                        <div style='page-break-before:always;'>
                            @include('elements.layout.kit.vascular-adicional',  array('agenda'=>$agenda))
                        </div>
                    @endif

                    @if(in_array($agenda->linha_cuidado , array(45)))
                        <div style='page-break-before:always;'>
                            <div>
                                @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => false, 'logo_drsaude' => true))
                                @include('elements.layout.kit.yag-laser',  array('agenda'=>$agenda))
                                <div style="position: absolute; bottom: -50px">
                                    @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                                </div>
                            </div>
                        </div>
                    @endif

                @endif

            @endif

            @if($local == 'medica')
                @if(in_array($agenda->linha_cuidado , array(5,7)) || 1 == 1)
                    <div style='page-break-before:always;'>
                        @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false))
                        @include('elements.layout.kit.utrassom', array('agenda'=>$agenda))
                        <div style="position: absolute; bottom: -50px">
                            @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                        </div>
                    </div>
                @endif
            @endif

            @include('elements.agenda.termo')
            @include('elements.agenda.ficha_atendimento', array('agenda'=> $agenda))

            @if(in_array($agenda->linha_cuidado , array(1,2)))
                <div style='page-break-before:always;' class="kit-impressao">
                    <div>
                        @include('elements.layout.pdf.kit.header', array('agenda'=>$agenda->id, 'box' => false, 'exibe_linha' => false))
                        @include('elements.layout.kit.sistematizacao-assistencia-enfermagem')
                    </div>

                    <div style="position: absolute; bottom: -50px">
                        @include('elements.layout.kit.logo-sus-prefeitura', ['agenda'=>$agenda->id])
                    </div>
                </div>
            @endif
        @endif
    </div>
    <script type="text/javascript">
        @if(env('APP_ENV') == 'production')
            try {
            this.print();
        } catch (e) {
            window.onload = window.print;
        }
        @endif
    </script>

@stop