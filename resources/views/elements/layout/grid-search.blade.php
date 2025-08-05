<div class="row">
    <div class="col-md-8"></div>
    <div class="col-md-3">
        {!!Form::open( array('class' => 'form-vertical','id' => 'form-pesquisa','method'=>'GET'))!!}
            {!!Form::textField('q', false, null, array('class' => 'form-control','placeholder'=>Lang::get('app.pesquisa'),'id'=>'input-search'))!!}
        {!!Form::close()!!}
    </div>
    <div class="col-md-1">
        <a id="btn-search-grid" class="btn btn-icon btn-rounded btn-info waves-effect"><i class="fa fa-search"></i></a>
    </div>
</div>