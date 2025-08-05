<div id="impressao" class="impressao-small" style="margin-top: -20px">
    <div class="margin10 description">
        <h1 class="align-center"><strong>TERMO DE CONSENTIMENTO EM ENDOSCOPIA</strong></h1><br/>
        <div class="margin15">
            @foreach(\App\Http\Helpers\Anamnese::termoConsentimento($agenda->linha_cuidado)  as $k=> $block_01)
                @if(is_array($block_01))
                    @foreach($block_01 as $k2 => $block_02)
                        @if(is_array($block_02))
                            @foreach($block_02 as $k3 => $block_03)
                                @if(is_array($block_03))
                                    @foreach($block_03 as $k4 => $block_04)
                                        <p style="margin-left: 30px;">{!! !empty($k4) ? $k4 ." - "  : null !!}{!! $block_04 !!}</p>
                                    @endforeach
                                @else
                                    <p style="margin-left: 20px;">{!! !empty($k3) ? $k3 ." - "  : null !!}{!! $block_03 !!}</p>
                                @endif
                            @endforeach
                        @else
                            <p style="margin-left: 20px;">{!! !empty($k2) ? $k2 ." - "  : null !!}{!! $block_02 !!}</p>
                        @endif
                    @endforeach
                @else
                    <p style="margin-left: 10px;">{!! !empty($k) ? $k ." - "  : null !!}{!! $block_01 !!}</p>
                @endif
            @endforeach

            <div style="margin-top: 10px">
                @include('elements.layout.kit.aux.lei-lgpd')
            </div>
        </div>


        <div style="margin-top: 0px; text-align: right">
            {!! \App\Http\Helpers\Util::dateExtensoCidade(@$agenda->data, "São Paulo"); !!}

            <br/><br/><br/><br/>
            @include('elements.layout.kit.assinaturas.paciente_e_responsavel')
        </div>

    </div>
</div>
