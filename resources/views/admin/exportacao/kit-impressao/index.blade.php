@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>
                Listagem
            </h2>
            <small></small>
            <hr/>

            <div class="card-body bg-light lt" id="box-grid">
                <div class="text-center m-b">

                    <div id="box-body"></div>

                    <table class="table table-striped table-responsive table-bordered  bg-light ">
                        <thead>
                        <tr role="row">
                            <th class="w-64">#</th>
                            <th class="w-64">Alias</th>
                            <th class="w-64">Especialidade</th>
                            <th>Tipo</th>
                            <th class="w-64">Download</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($especialidades AS $row)
                            <?php
                            $sub_especialidade = \App\Http\Helpers\Cirurgico\KitImpressaoHelpers::getSubEspecialidades($row->id);

                            $keys = !empty($sub_especialidade) ? array_keys($sub_especialidade) : [0]
                            ?>
                            @foreach($keys AS $key)
                                <?php
                                $id = $key != 0 ? $row->id."-".$key : $row->id
                                ?>
                                <tr class="">
                                    <td class="text-left" nowrap>{!! $id !!}</td>
                                    <td class="text-left">{!! $row->abreviacao !!}</td>
                                    <td class="text-left">
                                        @if ($row->especialidade == 1)
                                            Diagnóstico
                                        @else
                                            Cirurgica
                                        @endif
                                    </td>

                                    <td class="text-left">
                                        {!! $row->nome !!}
                                        @if($key != 0)
                                            - {!! $sub_especialidade[$key] !!}
                                        @endif
                                    </td>
                                    <td nowrap>
                                        <a href="/admin/exportacao/kit-impressao-download/{!! $id !!}" target="_blank" class="btn btn-rounded btn-xs btn-info waves-effect"><i
                                                    class="fa fa-download"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
@stop

@section('script')

@stop