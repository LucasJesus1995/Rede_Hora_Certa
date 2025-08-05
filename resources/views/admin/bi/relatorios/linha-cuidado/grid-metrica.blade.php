@if(!empty($report))
    <div class="alert alert-success">
        <div class="row">
            <div class="col-md-1">
                <a href="" target="_blank"  class="upper" id="relatorio-procedimento-contrato-xls">
                    <span class="fa fa-file-excel-o" style="color: green; font-size: 40px;" />
                </a>
            </div>
            <div class="col-md-10">
                Relatorio gerado com sucess!<br />
                Para fazer download do arquivo em excel, clique no icone.
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <table class="table table-striped table-responsive table-bordered  bg-light"  >
                <thead>
                <tr role="row">
                    <th>{!! Lang::get('app.procedimentos') !!}</th>
                    <th class="w-64">{!!Lang::get('app.total')!!}</th>
                </tr>
                </thead>
                <tbody>
                <?php $key = 1;?>
                @foreach($report_procedimentos AS $procedimentos => $sum)
                    <tr>
                        <td>{{$procedimentos}}</td>
                        <td>
                            {!! array_sum($sum) !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-5">
            <table class="table table-responsive table-bordered  bg-light "  >
                <thead>
                <tr role="row">
                    <th>{!! Lang::get('app.linha-cuidado') !!}</th>
                    <th class="w-64">{!!Lang::get('app.total')!!}</th>
                </tr>
                </thead>
                <tbody>
                <?php $key = 1;?>
                @foreach($report AS $linha_cuidado => $procedimentos)
                    <tr data-key="{!! $key !!}" class="<?php echo ($key % 2) ?  "odd" : "even";?>">
                        <td><a href="javascript: void(0);" data-key="{!! $key !!}" class="display_grid btn-link" >{{$linha_cuidado}}</a></td>
                        <td>
                            <?php $sum = [];?>
                            @foreach($procedimentos AS $procedimento => $row)
                                <?php
                                $sum[] = $row['total'];
                                ?>
                            @endforeach
                            {!! array_sum($sum) !!}
                        </td>
                    </tr>
                    @if(!empty($procedimentos))
                        <tr style="display: none" class="display_grid_child" id="display-{!! $key !!}">
                            <td colspan="100%" style="padding: 3px">
                                <table class="table table-striped table-responsive table-bordered  bg-light">
                                    <thead>
                                    <tr>
                                        <th>{!! Lang::get('app.procedimento') !!}</th>
                                        <th>{!! Lang::get('app.total') !!}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($procedimentos AS $row)
                                        <tr>
                                            <td>{!! $row['procedimento'] !!}</td>
                                            <td>{!! $row['total'] !!}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endif
                    <?php $key ++;?>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@else
    <div class="alert alert-danger">{!! Lang::get('app.sem-registro') !!}</div>
@endif