
@if($laudo)
    <?php
        $arena = \App\Arenas::find($agenda->arena);
    ?>

    @extends('pdf')

    @section('content')
    <title>laudo-atendimento_{{$atendimento->id}}</title>
    <div style=" padding: 15px; " class="bg-white">

        <table style="width: 100%" class="no-border">
            <tr>
                <td style="text-align: right; width: 65%" class="no-border">
                    @if($arena->id == 36)
                        <img src="src/image/logo/cies-300.jpg" class="img-responsive" style="width: 105px;; margin-top: 0" />
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    @else
                        <img src="src/image/hora-certa.png" class="img-responsive" style="width: 200px; margin-left: 0px; margin-top: 0px" />
                    @endif
                </td>
                <td style="width: 35%; text-align: right" class="no-border">
                    <img src="{!! ($qrcode) !!}" alt="" width="90" />
               <img src="" />
                </td>
            </tr>
        </table>

        <div style=" padding-left: 70px; margin-top: 15px; margin-bottom: 10px " class="bg-white">
            <table style="width: 100%" class="no-border">
                <tr>
                    <td style="width: 635px;" class="no-border">
                        @include('elements.paciente.header-laudo-pdf', ['paciente'=>$agenda,'arena' => $arena, 'agenda'=>$agenda, 'tipo'=>'laudo'])
                    </td>
                </tr>
            </table>
        </div>

        <?php
            $usuario = \App\Usuarios::find($laudo->user);
            $profissional = \App\Profissionais::find($atendimento->medico);
            $paciente = \App\Pacientes::find($agenda->paciente);
            $linha_cuidado = \App\LinhaCuidado::get($agenda->linha_cuidado);
        ?>
        <hr style="margin-bottom: 10px"  />
            <div class="center" style="font-size: 24px; font-weight: bold">LAUDO MÉDICO - {!! $linha_cuidado['nome'] !!}</div>
        <hr style="margin-top: 10px" />

        <div id="laudo" >
            {!! \App\Http\Helpers\Util::CloseTags(urldecode($laudo->descricao)) !!}
        </div>

        <div style="margin-top: 70px; display: inline-block">
            <table style="width: 715px"  >
                <tr>
                    <td width="200" class="no-border" >&nbsp;</td>
                    <td width="200" class="no-border">
                        <hr style="border-color: #000" />
                        @if(!empty($profissional))
                            <div style="margin-top: -15px; font-weight: bold; text-align: center" >{{$profissional->nome}} (CRM: {{$profissional->cro}}) </div>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

    <div class="footer" style="bottom: -20px" >
        <table>
            <tr>
                <td class="no-border">
                    <div class="">
                        @if(!empty($laudo->id))
                            <span class="datetime">{!! \App\Http\Helpers\Util::DBTimestamp2User2($laudo->created_at) !!}</span><br />
                            <i>{!! $laudo->id !!} - {!! $laudo->atendimento !!}</i><br />
                        @endif
                        <b>Página <span class="pagenum"></span></b><br />
                    </div>
                </td>
                <td class="right no-border">
                    <b>CENTRO DE INTEGRAÇÃO DE EDUCAÇÃO E SAÚDE</b><br />
                    Rua Salvador Simões, 801 – 10ª andar<br />
                    CEP 04276-000 – Alto do Ipiranga – São Paulo
                </td>
            </tr>
        </table>
    </div>

    @if(!is_null($imagens) && !empty($imagens[0]))
        @foreach($imagens AS $imagem)
            <div class="new-page">
                <table class="no-border" style="margin-top: 50px; width: 730px; ">
                    <tr>
                        <td class="no-border" style="text-align: center; width: 100% ;border: 1px solid #F00 ">
                            <?php
                                $imagem = \App\Http\Helpers\Upload::getImagemLaudo($atendimento, $imagem, false, true);
                            ?>
                            <div style="text-align: center">
                                <img class="" src="<?php echo $imagem; ?>" style="max-width: 730px; padding: 2px; border: 1px solid #000" />
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach
    @endif

    <script type="text/javascript">
        try {
            this.print();
        }
    </script>

    @stop

@else
    <div class="alert alert-danger">Nenhum laudo gravado encontrado!</div>
@endif