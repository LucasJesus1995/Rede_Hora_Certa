@extends('pdf')

@section('content')
    @if(!empty($relatorio[0]))
        <title>{!! $arena->nome !!}</title>

        <div id="print-kits" class="kit-impressao">

            <table width="100%">
                <tr>
                    <td>
                        <h1 style="margin-top: 0px; text-align: left">
                            {!! $arena->nome !!} <br/>
                            @if(!empty($linha_cuidado->nome))
                                <span>{!! $linha_cuidado->nome !!}</span>
                            @endif
                        </h1>
                    </td>
                    <td width="100px" style="text-align: right">
                        <img style='height: 50px; margin-bottom: 10px' src='src/image/logo/cies.png'>
                    </td>
                </tr>
            </table>

            <hr/>

            <div class="bloco" style="padding: 5px">
                <div style="text-align: right; font-weight: bold; margin-bottom: 5px; ">LISTAGEM DE ATENDIMENTO {!! $agendamento !!}</div>

                <table class="table-border">
                    <tr>
                        <th class="title">#</th>
                        <th class="title">Agenda</th>
                        <th class="title" colspan="2">Paciente</th>
                        <th class="title">Especialidade / Status</th>
                    </tr>

                    @foreach($relatorio AS $row)
                        <tr class="<?php echo $cor_list = (@$cor_list == "even") ? "odd" : "even";?>">
                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td>
                                <b>{!! $row->id !!}</b><br/>
                                {!! \App\Http\Helpers\Util::DBTimestamp2User($row->data) !!}
                            </td>
                            <td>
                                <b>{!! $row->paciente_nome !!}</b><br/>
                                {!! $row->paciente_cns !!}
                            </td>
                            <td>
                                <b>{!! \App\Http\Helpers\Mask::Cpf($row->paciente_cpf) !!}</b><br/>
                                {!! \App\Http\Helpers\Util::DB2User($row->paciente_nascimento) !!} ({!! \App\Http\Helpers\Util::calculaIdade($row->paciente_nascimento) !!} anos)
                            </td>
                            <td>
                                <b>{!! $row->linha_cuidado !!}</b><br/>
                                {!! \App\Http\Helpers\Util::StatusAgenda(intval($row->status)) !!}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>


            @include('elements.layout.pdf.footer')
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
    @else
        <div class="alert alert-danger">{{Lang::get('app.nenhum-registro-encontrado')}}</div>
    @endif
@stop