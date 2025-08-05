@extends('admin')

@section('content')

    <div class="card">
        <div class="card-heading">
            <h2>
                Rotinas (CRONs)
            </h2>
            <small></small>
        </div>
        <div class="card-tools">

        </div>
        <div class="card-body bg-light lt">

            <div id="box-grid">
                <table class="table table-striped table-responsive table-bordered  bg-light ">
                    <thead>
                    <tr role="row">
                        <th class="w-64">#</th>
                        <th>Serviço</th>
                        <th class="w-64">Processar</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($jobs AS $k => $row)
                        <tr class="">
                            <td>{!! $k !!}</td>
                            <td>{!! $row['name'] !!}</td>
                            <td nowrap align="center">
                                <a href="/admin/monitoramento/crons/services/{!! $k !!}" class="btn btn-rounded btn-xs btn-success waves-effect btn-force-run-cron"><i class="fa fa-download"></i></a>
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
    $(document).on("click", "a.btn-force-run-cron", function (e) {
        e.preventDefault();

        setModalLarge("Processamento", html_loading);

        $.ajax({
             url: $(this).attr('href'),
             type: "GET",
             success: function () {
                 setModalBodyLarge("<div>O serviço está sendo executado, evite rodar em intervalos menores de 30 minutos.</div>");
             }
        });
    });
@stop