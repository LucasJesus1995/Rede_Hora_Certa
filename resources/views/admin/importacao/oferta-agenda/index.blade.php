@extends('admin')

@section('content')

<div class="card">
	<div class="card-heading">
		<h2>
			Registro
		</h2>
		<small></small>
	</div>
	<div class="card-tools">
		<ul class="list-inline">
			<li class="dropdown">
				<a class="md-btn md-flat md-btn-circle waves-effect" data-toggle="dropdown" md-ink-ripple="" aria-expanded="false">
				<i class="mdi-navigation-more-vert text-md"></i>
				</a>
			</li>
		</ul>
	</div>
	<div class="card-body  " id="">
    	@include('elements.layout.form-error')

    	{!! Form::model(null) !!}
    	    {!! csrf_field() !!}

		<div class="row">
			<div class="col-md-3">
				{!!Form::selectField('lote', \App\Lotes::Combo(), Lang::get('app.lote'), 7, array('class' => 'form-control combo-arena chosen','id'=>'arena'))!!}
			</div>
			<div class="col-md-3">
				{!!Form::selectField('ano', \App\Http\Helpers\Util::getUltimosAnos(2),  Lang::get('app.ano'), date('Y'), array('class' => 'form-control chosen','id'=>'ano'))!!}
			</div>
			<div class="col-md-3">
				{!! Form::selectField('mes', \App\Http\Helpers\Util::getMes(), Lang::get('app.mes'), date('m'), array('class' => 'form-control chosen','id'=>'mes')) !!}
			</div>
			<div class="col-md-3">
			    <div class="form-group" style="margin-top: 24px">
                    <button id="btn-visualizar-agenda" class="btn btn-success waves-effect col-md-12" type="button">Visualizar</button>
			    </div>
			</div>
		</div>

        {!! Form::close() !!}
	</div>
    <div class="card-body bg-light lt">
        <div id="box-grid">
            <div class="text-center m-b">
              <i class="fa fa-circle-o-notch fa-spin text-lg text-muted-lt"></i>
            </div>
        </div>
    </div>
</div>

@stop

@section('script')
    $(".combo-arena").change();
	$("#btn-visualizar-agenda").trigger('click');
@stop
