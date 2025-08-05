@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>
                {{Lang::get('app.listagem-de-dados')}}
            </h2>
            <small>{{Lang::get('description.paciente')}}</small>
        </div>
        <div class="card-tools"><br />
            <a href="/admin/pacientes/dados-correcao-importacao" class="btn btn-success">Importar (Pacientes)</a>
        </div>
        <div class="card-body bg-light lt">
            <div id="box-grid-paciente-dados-invalido">
                @if(!empty($pacientes) && !empty($pacientes->items()))

                    {!! $pacientes->render() !!}

                    <table class="table table-striped table-responsive table-bordered  bg-light ">
                        <thead>
                        <tr role="row">
                            <th class="w-64" colspan="2">#</th>
                            <th>Nome</th>
                            <th>Sexo</th>
                            <th>Raça/Cor</th>
                            <th>Endereço</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pacientes AS $k => $row)
                            <tr>
                                <td>{!! $k+1 !!}</td>
                                <td nowrap>
                                    <strong class="copyToHtml">{!! $row->cns !!}</strong> <br/>
                                    {!! \App\Http\Helpers\Util::DBTimestamp2User2($row->created_at) !!}
                                </td>
                                <td nowrap>
                                    <strong>{!! $row->nome !!}</strong> ({!! \App\Http\Helpers\Util::DB2User($row->nascimento) !!})<br/>
                                    Mãe: {!! $row->mae !!}
                                </td>
                                <td>
                                    @if(!empty($row->sexo))
                                        {!! \App\Http\Helpers\Util::Sexo($row->sexo) !!}
                                    @endif
                                </td>
                                <td>
                                    @if(intval($row->raca_cor)  != 0)
                                        {!! \App\Http\Helpers\Util::RacaCor($row->raca_cor) !!}<br/>
                                    @endif
                                    @if(intval($row->nacionalidade)  != 0)
                                        {!! \App\Http\Helpers\Util::Nacionalidade($row->nacionalidade) !!}<br/>
                                    @endif

                                </td>
                                <td>
                                    <strong>{!! $row->endereco !!}, {!! $row->numero !!}</strong><br/>
                                    CEP: {!! $row->cep !!}  -  {!! $row->bairro !!}<br/>
                                    @if(!empty($row->cidade))
                                        {!! \App\Http\Helpers\Util::getCidadeNameById($row->cidade) !!}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {!! $pacientes->render() !!}
                @else
                    <div class='panel bg-danger pos-rlt'>
                        <span class='arrow top  b-danger '></span>
                        <div class='panel-body'>Nehum registro encontrado!</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@stop
