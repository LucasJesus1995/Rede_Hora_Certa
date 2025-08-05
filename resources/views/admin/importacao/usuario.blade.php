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
	<div class="card-body bg-light " id="">
    	@include('elements.layout.form-error')

    	{!! Form::model(null,array('url' => '/admin/importacao/usuario','enctype'=>'multipart/form-data')) !!}
    	    {!! csrf_field() !!}

		<div class="row">
			<div class="col-md-9">
				{!! Form::fileField('file', 'Arquivo de Importação (TXT)') !!}
			</div>
			<div class="col-md-3">
			    <div class="form-group"><br />
                    <button class="btn-submit btn btn-success waves-effect col-md-12" type="button">Importar</button>
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
loadingDataGridUsuarioImportacao();
@stop
