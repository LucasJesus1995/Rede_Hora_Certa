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
                            <th class="w-64">Nome</th>
                            <th class="w-64">Download</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($kits AS $k => $v)
                            <tr class="">
                                <td class="text-left">{!! $v !!}</td>
                                <td nowrap>
                                    <a href="/admin/exportacao/kit-impressao-avulso-download/{!! $k !!}" target="_blank" class="btn btn-rounded btn-xs btn-info waves-effect"><i class="fa fa-download"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
@stop

@section('script')

@stop