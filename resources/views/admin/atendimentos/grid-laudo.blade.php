<?php
    if(!empty($grid) && count($grid)):
        $visible_laudo = \App\Http\Helpers\Util::getHideLaudoAtendimento($atendimento['status'], $atendimento['agenda']);
        ?>
        <table class="table table-striped table-bordered  bg-light " >
            <thead>
                <tr role="row">
                    <th>{!!Lang::get('app.id')!!}</th>
                    <th>{!!Lang::get('app.laudo')!!}</th>
                    <th>{!!Lang::get('app.biopsia')!!}</th>
                    <th>{!!Lang::get('app.cid')!!}</th>
                    <th>{!!Lang::get('grid.acao')!!}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grid AS $row)

                    <tr class="">
                        <td nowrap>
                            <strong>{{$row->id}}</strong><br />
                            {{\App\Http\Helpers\Util::DBTimestamp2UserDate($row->created_at)}}
                        </td>
                        <td nowrap>
                            @if(!empty($row->resultado))
                                <strong>
                                    {!! \App\Http\Helpers\Util::getLaudoResultados($row->resultado) !!}
                                </strong><br />
                            @endif
                            {{ \App\LaudoMedico::getNomeLaudo($row->laudo)}}
                        </td>
                        <td >{!! $row->biopsia !!}</td>
                        <td >{!! \App\Cid::getNomeByCid($row->cid) !!}</td>
                        <td nowrap >
                            <div class="btn-group dropdown">
                                <button type="button" class="btn btn-default waves-effect" data-toggle="dropdown">
                                    {{ Lang::get('app.acao') }} <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu animated fadeIn">
                                    <li class="align-left"><a id="btn-laudo-print" data-id="{{$row->id}}" href="javascript: void(0);" class="waves-effect"><i class="fa fa-print"></i> {{ Lang::get('app.imprimir') }}</a></li>
                                    @if($visible_laudo)
                                        <li class="align-left"><a id="btn-laudo-edit" data-id="{{$row->id}}" href="javascript: void(0);" class=" waves-effect"><i class="fa fa-edit"></i> {{ Lang::get('app.editar') }}</a></li>
                                        {{--@if(\App\User::homologacao())--}}
                                            {{--<li class="align-left"><a data-id="{{$row->id}}" href="javascript: void(0);" class="btn-laudo-imagens waves-effect"><i class="fa fa-photo"></i> Imagens</a></li>--}}
                                       {{--@endif--}}
                                    <li class="divider"></li>
                                    <li class="align-left"><a id="btn-laudo-delete" data-id="{{$row->id}}" href="javascript: void(0);" class="waves-effect"><i class="fa fa-remove"></i> {{ Lang::get('app.remover') }}</a></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="well well-sm hidden" id="box-laudo-upload-imagens">
            <input hidden id="atendimento-laudo-id" value="" />
            <form method="GET" class="form demo_form">
                <div class="atendimento_laudo_upload_lib upload-imgs"></div>
                <div class="filelists">
                    <div class="filelist complete"></div>
                    <div class="filelist queue"></div>
                </div>
            </form>
        </div>
        <div id="box-laudo-imagens"></div>
        <script>
            getAtendimentoLaudoImagens();
        </script>
        <?php
    endif;
