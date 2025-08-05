<?php
	$linha_cuidado = \App\Http\Helpers\Util::getLinhaCuidado($agenda->linha_cuidado);
	$medicamentos = \App\Medicamentos::ByLinhaCuidado($linha_cuidado->id);

	$medicamentos_atendimento = \App\Atendimentos::getMedicamentosByAtendimento($atendimento->id);
	
	$disabled = (\App\Http\Helpers\Util::CheckPermissionAction('medicina_medicamentos','created')) ? null:  "disabled";
	?>
<h5>
	<strong>{{Lang::get('app.linha-cuidado')}}:</strong>
	<span class="label bg-success pos-rlt m-r-xs">
	    <b class="arrow bottom"></b>{{$linha_cuidado->nome}}
	</span>
	<a href="/admin/agendas/print-atendimento-medico/{{$atendimento->id}}" target="_blank" id="btn-imprimir-medicamento" class="pull-right btn btn-success btn-xs" style="margin-right: 10px">Imprimir Receitas</a>
</h5>
<div class="panel panel-card">
	<div class="panel-body">
		{{Lang::get('description.insercao-medicamento-medico')}}
		@if(!empty($medicamentos))
		<div class="well well-sm">
			<div class="row">
				@foreach($medicamentos AS $key => $label)
				<?php
					$checked = (array_key_exists($key, $medicamentos_atendimento)) ? 'checked = "checked"' : null;
					$qtd = ($checked) ? $medicamentos_atendimento[$key] : null;
					?>
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-1" style="padding-top: 5px;">
							<input name="{{$key}}" id="check-quantidade-{{$key}}" rel="{{$key}}" type="checkbox" value="{{$key}}" class="check-medicamento" {{$checked}} {{$disabled}} />
						</div>
						<div class="col-md-8" style="padding-top: 5px;">
							<label for="check-quantidade-{{$key}}">{{$label}}</label>
						</div>
						<div class="col-md-3 form-group-sm">
							{!! Form::textField('quantidade', null, $qtd, array('class' => 'form-control no-margim quantidade-medicamento','rel'=>$key, 'id' => 'quantidade-medicamento-'.$key, $disabled)) !!}
						</div>
					</div>
				</div>
				@endforeach
			</div>
		</div>
		@else
		<div class="alert alert-danger">{{Lang::get('app.nenhum-registro-encontrado')}}</div>
		@endif
	</div>
</div>
