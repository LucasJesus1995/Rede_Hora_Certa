@if(in_array($agenda->linha_cuidado,[9, 49]))
    <table width="100%" style="margin-top: 5px">
        <tr>
            @if(in_array($agenda->linha_cuidado, [9]))
                <th width="100%" colspan="5" >LATERALIDADE: _____________________________________________________</th>
            @else
                <th width="20%">LATERALIDADE</th>
                <td width="20%">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) ESQUERDO</td>
                <td width="20%">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) DIREITO</td>
                <td width="20%">&nbsp;</td>
                <td width="20%">&nbsp;</td>
            @endif

        </tr>
        @if(!in_array($agenda->linha_cuidado,[9]))
            <tr>
                <th width="20%">TRATAMENTO SAFENA</th>
                <td width="20%">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) LASER</td>
                <td width="20%">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) POLIDOCANOL</td>
                <td width="20%">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) LIGADURA</td>
                <td width="20%">&nbsp;</td>
            </tr>
        @endif
    </table>
@endif
<br/>
@if(in_array($agenda->linha_cuidado,[9, 49]))
    <table width="100%" class="border" style="margin-top: 5px">
        <?php
        $key_kit = ($agenda->linha_cuidado == 9) ? 2 : 1;
        ?>
        @foreach(\App\Http\Helpers\Anamnese::getDescricaoCirurgica($key_kit) AS $i => $row)
            <?php
            $i++;
            $cor_list = (@$cor_list == "even") ? "odd" : "even";
            ?>
            <tr class="{!! $cor_list !!}">
                <td width="5%" style="text-align: center">{!! $i++ !!}</td>
                <td width="85%">{!! $row !!}</td>
            </tr>
        @endforeach
    </table>
@endif

@if(in_array($agenda->linha_cuidado,[45, 19]))
    <table width="100%" style="margin-top: 0px">
        <tr>
            <th width="20%">LATERALIDADE</th>
            <td width="20%">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) <strong>OD</strong></td>
            <td width="20%">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) <strong>OE</strong></td>
            <td width="20%">&nbsp;</td>
            <td width="20%">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2">
                @foreach(\App\Http\Helpers\Anamnese::getDescricaoCirurgicaReceita($sub_especialidade) AS $i => $row)
                    {!! $i !!}) {!! $row !!}<br/>
                @endforeach
            </td>
        </tr>
    </table>
    <div class="bloco " style="margin: 0; margin-top: 40px;">
        <h2 style="text-align: left">INTERCORRÊNCIA</h2>
        <div style="margin: 5px 5px; height: 60px">


        </div>
    </div>
@endif


@if(in_array($sub_especialidade,[4]))
    <table width="100%" style="margin-top: 0px" class="border">
        <tr>
            <th class="title" width="26%" style="text-align: left">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) HÉRNIA EPIGÁSTRICA</th>
            <th class="title" width="26%" style="text-align: left">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) HÉRNIA UMBILICAL</th>
            <th class="title" width="26%" style="text-align: left">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) HÉRNIA _____________________</th>
            <th class="title" width="20%" style="text-align: left">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) _____________________</th>
        </tr>
        <?php
        $hernias = \App\Http\Helpers\Anamnese::getHernia();
        ?>
        @for($i = 1; $i <= 20; $i++)
            @if(!empty($hernias[1][$i]) || !empty($hernias[2][$i]) || !empty($hernias[3][$i]))
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                    <td>@if(!empty($hernias[1][$i])) {!! $i !!}. {!! $hernias[1][$i] !!} @endif</td>
                    <td>@if(!empty($hernias[2][$i])) {!! $i !!}. {!! $hernias[2][$i] !!} @endif</td>
                    <td>@if(!empty($hernias[3][$i])) {!! $i !!}. {!! $hernias[3][$i] !!} @endif</td>
                    <td>&nbsp;</td>
                </tr>
            @endif
        @endfor
    </table>
    <div style="margin-top: 5px; margin-bottom: 60px">
        <p>Intercorrência:</p>
    </div>
@endif

@if(1 == 2 && in_array($agenda->linha_cuidado,[9, 49]))
    <table width="100%" style="margin-top: 0px" class="border">
        <tr>
            <th class="title" width="26%" style="text-align: left">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) POSTECTOMIA</th>
            <th class="title" width="26%" style="text-align: left">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) VASECTOMIA</th>
            <th class="title" width="26%" style="text-align: left">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) EXCISÃO DE LESÃO</th>
            <th class="title" width="20%" style="text-align: left">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) _____________________</th>
        </tr>
        <?php
        $hernias = \App\Http\Helpers\Anamnese::getHernia();
        ?>
        @for($i = 1; $i <= 20; $i++)
            @if(!empty($hernias[1][$i]) || !empty($hernias[2][$i]) || !empty($hernias[3][$i]))
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                    <td>@if(!empty($hernias[1][$i])) {!! $i !!}. {!! $hernias[1][$i] !!} @endif</td>
                    <td>@if(!empty($hernias[2][$i])) {!! $i !!}. {!! $hernias[2][$i] !!} @endif</td>
                    <td>@if(!empty($hernias[3][$i])) {!! $i !!}. {!! $hernias[3][$i] !!} @endif</td>
                    <td>&nbsp;</td>
                </tr>
            @endif
        @endfor
    </table>
    <div style="margin-top: 5px; margin-bottom: 80px">
        <p>Intercorrência:</p>
    </div>
@endif

@if(in_array($agenda->linha_cuidado,[47]))
    <table width="100%" style="margin-top: 0px" class="border">
        <tr>
            <th class="title" width="60%" style="text-align: left">VASECTOMIA</th>
            <th class="title" width="40%" style="text-align: left">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;) __________________________</th>
        </tr>
        <?php
        $hernias = \App\Http\Helpers\Anamnese::getUrologia();
        ?>
        @for($i = 1; $i <= 20; $i++)
            @if(!empty($hernias[1][$i]) || !empty($hernias[2][$i]) || !empty($hernias[3][$i]))
                <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
{{--                    <td>@if(!empty($hernias[1][$i])) {!! $i !!}. {!! $hernias[1][$i] !!} @endif</td>--}}
                    <td>@if(!empty($hernias[2][$i])) {!! $i !!}. {!! $hernias[2][$i] !!} @endif</td>
{{--                    <td>@if(!empty($hernias[3][$i])) {!! $i !!}. {!! $hernias[3][$i] !!} @endif</td>--}}
                    <td>&nbsp;</td>
                </tr>
            @endif
        @endfor
    </table>
    <div class="bloco" style="">
        <h2 style="text-align: left">Intercorrência</h2>
        <div style="margin: 10px 5px; line-height: 17px; height: 40px; font-style: italic ">

        </div>
    </div>
@endif
