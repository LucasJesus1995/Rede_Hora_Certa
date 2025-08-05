<div class="row">
    <div class="col-md-6"></div>
    <div class="col-md-5">
        {!!Form::open( array('class' => 'form-vertical','id' => 'form-pesquisa','method'=>'GET'))!!}
        <div class="row">
            <div class="col-md-6">
                {!!Form::selectField('field', ['cns'=>'CNS','nome'=>'Nome'], null, 'cns', array('class' => 'form-control chosen','id'=>'input-field'))!!}
            </div>
            <div class="col-md-6">
                {!!Form::textField('q', false, null, array('class' => 'form-control','placeholder'=>Lang::get('app.pesquisa'),'id'=>'input-search'))!!}
            </div>
        </div>
        {!!Form::close()!!}
    </div>
    <div class="col-md-1">
        <a id="btn-search-grid" class="btn btn-icon btn-info waves-effect"><i class="fa fa-search"></i></a>
    </div>
</div>
