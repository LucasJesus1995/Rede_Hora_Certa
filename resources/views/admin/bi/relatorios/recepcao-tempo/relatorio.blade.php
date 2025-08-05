@if(!empty($download))
    <div class="alert alert-success">
        <a href="{!! $download !!}" target="_blank" ><strong>Clique aqui</strong></a> para fazer download do arquivo em excel.
    </div>
@endif

<div class="row">
    <div class="col-md-6">
        {!! $chart_tempo->render() !!}
    </div>
    <div class="col-md-6">
        {!! $chart_date->render() !!}
    </div>
</div><br />
<div class="row">
    <div class="col-md-6">
        {!! $chart_arena->render() !!}
    </div>
    <div class="col-md-6">
        {!! $chart_linha_cuidado->render() !!}
    </div>
</div>