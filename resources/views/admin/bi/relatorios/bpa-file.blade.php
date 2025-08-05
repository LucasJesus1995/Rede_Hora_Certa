@if($file)
    <div class="row">
        <div class="col-md-1 align-right">
            <i class="fa fa-file-text-o" style="font-size: 60px; color: #30709B"></i>
        </div>
        <div class="col-md-11">
            <div class="alert alert-info">
                <h4>
                    <a href="/{!! $link !!}" target="_blank" rel="{!! $link !!}" class="upper" id="bpa-file-download">clique aqui</a> para fazer download do arquivo.
                </h4>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-danger">{{Lang::get('app.nenhum-registro-encontrado')}}</div>
@endif