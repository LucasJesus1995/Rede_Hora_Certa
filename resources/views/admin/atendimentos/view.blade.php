@if(!empty($atendimento))
    <?php
    $resposta = \App\Http\Helpers\Util::getRespostaAtendimento($atendimento->id);
    $view_enfermagem = (
        \App\Http\Helpers\Util::CheckPermissionAction('enfermagem_questionario_especifico', 'view') ||
        \App\Http\Helpers\Util::CheckPermissionAction('enfermagem_diagnotico', 'view') ||
        \App\Http\Helpers\Util::CheckPermissionAction('enfermagem_sinais_vitais', 'view') ||
        \App\Http\Helpers\Util::CheckPermissionAction('enfermagem_ficha_acolhimento', 'view') ||
        \App\Http\Helpers\Util::CheckPermissionAction('enfermagem_evolucao', 'view') ||
        \App\Http\Helpers\Util::CheckPermissionAction('enfermagem_intercorrencia', 'view') ||
        \App\Http\Helpers\Util::CheckPermissionAction('enfermagem_rastreabilidade', 'view')
    );

    $view_medico = (
        \App\Http\Helpers\Util::CheckPermissionAction('medicina_anamnese', 'view') ||
        \App\Http\Helpers\Util::CheckPermissionAction('medicina_procedimentos', 'view') ||
        \App\Http\Helpers\Util::CheckPermissionAction('medicina_medicamentos', 'view') ||
        \App\Http\Helpers\Util::CheckPermissionAction('medicina_laudo', 'view')
    );

    ?>
    <div id="box-geral-atendimento" data-id-atendimento="{!! $atendimento->id !!}">
        <input type="hidden" name="atendimento" id="atendimento" value="{{$atendimento->id}}"/>
        <input type="hidden" name="agenda" id="atendimento_agenda" value="{{$atendimento->agenda}}"/>
        <input type="hidden" name="antendimento_status" id="atendimento-status" value="{{$atendimento->status}}"/>
        <input type="hidden" name="linha_cuidado_especialidade" id="atendimento-linha_cuidado_especialidade" value="{!! $linha_cuidado->especialidade !!}"/>

        <div class="md-whiteframe-z0 bg-white">
            @include('elements.anamnese.header', ['paciente'=>$atendimento->paciente, 'chegada' => $atendimento->chegada, 'agendamento' => $atendimento->agendamento,  'atendimento' => $atendimento])

            @if(!$view_enfermagem && !$view_medico)
                <div class="alert alert-info margin10">Nenhuma aba ativa!</div>
            @endif

            <ul class="nav nav-lines nav-tabs nav-justified nav-atendimento">
                @if($view_enfermagem)
                    <li class="active">
                        <a class="text-sm btn btn-lg btn-rounded btn-stroke btn-info m-r waves-effect" data-target="#tab-master-enfermagem" data-toggle="tab"
                           href="" data-atendimento="{{$atendimento->id}}" data-secao="enfermagem">
                            <i class="fa fa-stethoscope fa-3x pull-left"></i>
                            <span class="block clear text-left m-v-xs"> {{Lang::get('app.anamnese')}}<b
                                        class="text-lg block font-bold">{{Lang::get('app.enfermagem')}}</b></span>
                        </a>
                    </li>
                @endif
                @if($view_medico)
                    <li id="btn-check-in-medicina" class="<?php echo (!$view_enfermagem) ? "active" : null; ?>" data-atendimento="{{$atendimento->id}}"
                        data-secao="medico">
                        <a class="text-sm btn btn-lg btn-rounded btn-stroke btn-info m-r waves-effect" data-target="#tab-master-medicina" data-toggle="tab"
                           href="">
                            <i class="fa fa-user-md fa-3x pull-left"></i>
                            <span class="block clear text-left m-v-xs"> &nbsp;<b class="text-lg block font-bold">{{Lang::get('app.medicina')}}</b></span>
                        </a>
                    </li>
                @endif
            </ul>
            <div class="tab-content p m-b-md clear b-t b-t-2x">
                @if($view_enfermagem)
                    <div id="tab-master-enfermagem" class="tab-pane animated fadeInDown active" role="tabpanel">
                        <ul class="nav nav-lines nav-tabs  b-warning">
                            @if(\App\Http\Helpers\Util::CheckPermissionAction('enfermagem_questionario_especifico','view'))
                                @if(in_array($agenda->linha_cuidado, array(1, 2, 9, 8)))
                                    <li class=""><a data-target="#tab-enfermagem-especifico" data-toggle="tab" href=""
                                                    aria-expanded="true">{{Lang::get('app.questionario-especifico')}}</a></li>
                                @endif
                            @endif
                            @if(\App\Http\Helpers\Util::CheckPermissionAction('enfermagem_diagnotico','view'))
                                <li class=""><a data-target="#tab-enfermagem-acolhimento" data-toggle="tab" href=""
                                                aria-expanded="true">{{Lang::get('app.ficha-acolhimento')}}</a></li>
                            @endif
                            @if(\App\Http\Helpers\Util::CheckPermissionAction('enfermagem_sinais_vitais','view'))
                                <li class=""><a data-target="#tab-sinais-vitais" data-toggle="tab" href=""
                                                aria-expanded="true">{{Lang::get('app.sinais-vitais')}}</a></li>
                            @endif
                            @if(\App\Http\Helpers\Util::CheckPermissionAction('enfermagem_ficha_acolhimento','view'))
                                <li class=""><a data-target="#tab-enfermangem-diagnostico" data-toggle="tab" href=""
                                                aria-expanded="true">{{Lang::get('app.diagnostico')}}</a></li>
                            @endif
                            @if(in_array($agenda->linha_cuidado, array(1, 2)))
                                @if(\App\Http\Helpers\Util::CheckPermissionAction('enfermagem_evolucao','view'))
                                    <li class=""><a data-target="#tab-evolucao" data-toggle="tab" href="" aria-expanded="true">{{Lang::get('app.evolucao')}}</a>
                                    </li>
                                @endif
                                @if(\App\Http\Helpers\Util::CheckPermissionAction('enfermagem_anotacao','view'))
                                    <li class=""><a data-target="#tab-anotacao" data-toggle="tab" href="" aria-expanded="true">{{Lang::get('app.anotacao')}}</a>
                                    </li>
                                @endif
                                @if(\App\Http\Helpers\Util::CheckPermissionAction('enfermagem_intercorrencia','view'))
                                    <li class=""><a data-target="#tab-intercorrencia" data-toggle="tab" href=""
                                                    aria-expanded="true">{{Lang::get('app.intercorrencia')}}</a></li>
                                @endif
                            @endif
                            @if(\App\Http\Helpers\Util::CheckPermissionAction('enfermagem_rastreabilidade','view'))
                                <li class=""><a data-target="#tab-rasteabilidade" data-toggle="tab" href=""
                                                aria-expanded="true">{{Lang::get('app.rasteabilidade')}}</a></li>
                            @endif
                        </ul>
                        <div class="clearfix well well-sm">
                            <div class="tab-content p b-a no-b-t bg-white m-b-md clear">
                                <div id="tab-enfermagem-especifico" class="tab-pane animated fadeInUp" role="tabpanel">
                                    @include('admin.atendimentos.tabs.enfermagem-questionario-especifico', ['agenda' => $agenda, 'atendimento' => $atendimento])
                                </div>
                                <div id="tab-enfermangem-diagnostico" class="tab-pane animated fadeInUp" role="tabpanel">
                                    @include('admin.atendimentos.tabs.enfermagem-diagnostico', ['agenda' => $agenda, 'atendimento' => $atendimento])
                                </div>
                                <div id="tab-rasteabilidade" class="tab-pane animated fadeInUp" role="tabpanel">
                                    @include('admin.atendimentos.tabs.enfermagem-rasteabilidade', ['agenda' => $agenda, 'atendimento' => $atendimento])
                                </div>
                                <div id="tab-sinais-vitais" class="tab-pane animated fadeInUp" role="tabpanel">
                                    @include('admin.atendimentos.tabs.enfermagem-sinais-vitais', ['agenda' => $agenda, 'atendimento' => $atendimento])
                                </div>
                                <div id="tab-enfermagem-acolhimento" class="tab-pane animated fadeInUp" role="tabpanel">
                                    @include('admin.atendimentos.tabs.enfermagem-acolhimento', ['agenda' => $agenda, 'atendimento' => $atendimento])
                                </div>
                                @if(in_array($agenda->linha_cuidado, array(1, 2)))
                                    <div id="tab-evolucao" class="tab-pane animated fadeInDown" role="tabpanel">
                                        <hr class="no-margin "/>
                                        <div>@include('admin.atendimentos.tabs.enfermagem-evolucao', ['agenda' => $agenda, 'atendimento' => $atendimento])</div>
                                    </div>
                                    <div id="tab-anotacao" class="tab-pane animated fadeInDown" role="tabpanel">
                                        <hr class="no-margin "/>
                                        <div>@include('admin.atendimentos.tabs.enfermagem-anotacao', ['agenda' => $agenda, 'atendimento' => $atendimento])</div>
                                    </div>
                                    <div id="tab-intercorrencia" class="tab-pane animated fadeInDown" role="tabpanel">
                                        <hr class="no-margin "/>
                                        <div>@include('admin.atendimentos.tabs.enfermagem-intercorrencia', ['agenda' => $agenda, 'atendimento' => $atendimento])</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @if($view_medico)
                    <div id="tab-master-medicina" class="tab-pane animated fadeInDown <?php echo (!$view_enfermagem) ? "active" : null; ?>" role="tabpanel">
                        <ul class="nav nav-lines nav-tabs  b-warning">
                            @if(\App\Http\Helpers\Util::CheckPermissionAction('medicina_anamnese','view'))
                                @if(in_array($agenda->linha_cuidado, array(5, 7)))
                                    <li class=""><a data-target="#tab-medicina-11" data-toggle="tab" href=""
                                                    aria-expanded="true">{{Lang::get('app.anamnese-ultrassom')}}</a></li>
                                @endif
                            @endif
                            @if(\App\Http\Helpers\Util::CheckPermissionAction('medicina_procedimentos','view'))
                                <li class="">
                                    <a data-target="#tab-medicina-2" data-toggle="tab" href="" aria-expanded="true">{{Lang::get('app.procedimentos')}}</a>
                                </li>
                            @endif
                            @if($linha_cuidado->especialidade == 1)
                                @if(\App\Http\Helpers\Util::CheckPermissionAction('medicina_medicamentos','view'))
                                    <li class="">
                                        <a data-target="#tab-medicina-4" data-toggle="tab" href="" aria-expanded="true">{{Lang::get('app.medicamentos')}}</a>
                                    </li>
                                @endif
                                @if(\App\Http\Helpers\Util::CheckPermissionAction('medicina_laudo','view'))
                                    <li id="nav-box-laudo" class="">
                                        <a data-target="#tab-medicina-3" data-toggle="tab" href="" aria-expanded="true">{{Lang::get('app.laudo')}}</a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                        <div class="clearfix well well-sm">
                            <div class="tab-content p b-a no-b-t bg-white m-b-md clear">
                                <div id="tab-medicina-11" class="tab-pane animated fadeInUp " role="tabpanel">
                                    <hr class="no-margin "/>
                                    <div>@include('admin.atendimentos.tabs.medico-anamnese-ultrasom', ['agenda' => $agenda, 'atendimento' => $atendimento])</div>
                                </div>
                                <div id="tab-medicina-2" class="tab-pane animated fadeInUp  " role="tabpanel">
                                    <hr class="no-margin"/>
                                    <div>@include('admin.atendimentos.tabs.medico-procedimento', ['agenda' => $agenda, 'atendimento' => $atendimento])</div>
                                </div>
                                @if($linha_cuidado->especialidade == 1)
                                    <div id="tab-medicina-3" class="tab-pane animated fadeInUp " role="tabpanel">
                                        <hr class="no-margin"/>
                                        <div>@include('admin.atendimentos.tabs.medico-laudo', ['agenda' => $agenda, 'atendimento' => $atendimento])</div>
                                    </div>
                                    <div id="tab-medicina-4" class="tab-pane animated fadeInUp " role="tabpanel">
                                        <hr class="no-margin "/>
                                        <div>@include('admin.atendimentos.tabs.medico-medicamentos', ['agenda' => $agenda, 'atendimento' => $atendimento])</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <script>
        $("#tab-master-enfermagem li:first-child a").trigger("click");
        $("#tab-master-medicina li:first-child a").trigger("click");

        $(function () {
            $('.nav-atendimento li').bind('click', function (e) {
                var secao = $(this).attr('data-secao');
                var atendimento = $(this).attr('data-atendimento');

                if (secao == 'medico') {
                    checkInMedicina(atendimento);
                }
            });
        });

        $("#value_descricao_10").addClass('numbers');
        $("#value_descricao_11").addClass('numbers');
        $("#value_descricao_28").addClass('numbers');
        $("#value_descricao_29").addClass('numbers');
        $("#value_descricao_30").addClass('numbers');

        $("#value_descricao_205").addClass('numbers');
        $("#value_descricao_206").addClass('numbers');
        $("#value_descricao_207").addClass('numbers');
        $("#value_descricao_208").addClass('numbers');
        $("#value_descricao_209").addClass('numbers');
        $("#value_descricao_210").addClass('numbers');
        $("#value_descricao_211").addClass('numbers');
        $("#value_descricao_212").addClass('numbers');
        $("#value_descricao_213").addClass('numbers');
        $("#value_descricao_214").addClass('numbers');
        $("#value_descricao_215").addClass('numbers');
        $("#value_descricao_216").addClass('numbers');
        $("#value_descricao_217").addClass('numbers');
        $("#value_descricao_218").addClass('numbers');
        $("#value_descricao_219").addClass('numbers');
        $("#value_descricao_220").addClass('numbers');

        $('#value_descricao_221').addClass('numbers');
        $('#value_descricao_222').addClass('numbers');
        $('#value_descricao_223').addClass('numbers');
        $('#value_descricao_224').addClass('numbers');
        $('#value_descricao_225').addClass('numbers');
        $('#value_descricao_226').addClass('numbers');
        $('#value_descricao_227').addClass('numbers');
        $('#value_descricao_228').addClass('numbers');
        $('#value_descricao_229').addClass('numbers');
        $('#value_descricao_230').addClass('numbers');
        $('#value_descricao_231').addClass('numbers');
        $('#value_descricao_232').addClass('numbers');
        $('#value_descricao_233').addClass('numbers');
        $('#value_descricao_234').addClass('numbers');
        $('#value_descricao_235').addClass('numbers');

        $('#value_descricao_236').addClass('numbers');
        $('#value_descricao_237').addClass('numbers');
        $('#value_descricao_238').addClass('numbers');
        $('#value_descricao_239').addClass('numbers');
        $('#value_descricao_240').addClass('numbers');

        $('#value_descricao_245').addClass('numbers');
        $('#value_descricao_242').addClass('numbers');
        $('#value_descricao_244').addClass('numbers');
        $('#value_descricao_247').addClass('numbers');
        $('#value_descricao_240').addClass('numbers');
        $('#value_descricao_240').addClass('numbers');

        $("#value_descricao_10").addClass('cal-imc');
        $("#value_descricao_11").addClass('cal-imc');

        $("#value_descricao_16").addClass('date');
        $("#value_descricao_246").addClass('date');

        $(".cal-imc").change();

        loadingMask();

        var __block = window.setInterval(checkAtendimentoStatus, 1000);

        window.setTimeout(function () {
            clearInterval(__block);
        }, 10000);

        loadingLaudoAtendimento();
        atualizaAtendimentoAgenda();
    </script>
@else
    @if(!empty($error))
        <div class="alert alert-danger">{!! $error !!}</div>
    @else
        <div class="alert alert-danger">Atendimento não encontrado!</div>
    @endif
@endif
