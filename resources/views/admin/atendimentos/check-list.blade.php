<?php
	$resposta = \App\Http\Helpers\Util::getRespostaAtendimento($entry->atendimento);
	$disabled_paciente = (\App\Http\Helpers\Util::CheckPermissionAction('pacientes','created')) ? null:  "disabled";
?>
<div class="well">
	<div class="md-list md-whiteframe-z0 bg-white m-b">
		<ul class="nav nav-lines nav-tabs nav-justified">
			<li class="active">
				<a class="text-sm btn btn-lg btn-rounded btn-stroke btn-info m-r waves-effect" data-target="#tab-questionario" data-toggle="tab" href="">
				{{Lang::get('app.valida-cadastro')}}
				</a>
			</li>
			<li class="">
				<a class="text-sm btn btn-lg btn-rounded btn-stroke btn-info m-r waves-effect" data-target="#tab-triagem" data-toggle="tab" href=""  aria-expanded="true">
				{{Lang::get('app.triagem')}}
				</a>
			</li>
		</ul>
		<div class="tab-content p m-b-md clear b-t b-t-2x">
			<div id="tab-questionario" class="tab-pane animated fadeInDown active" role="tabpanel">
				<div class="well well-sm">
					{!!Form::model($entry, array('url' => '/admin/arenas', 'class' => 'form-vertical form-check-list'))!!}
					{!!Form::hidden('id', null,  array('id' => 'check-list'))!!}
					{!!Form::hidden('agenda', null,  array('id' => 'agenda'))!!}
					{!!Form::hidden('atendimento', null,  array('id' => 'atendimento'))!!}

					<div class="md-list-item inset">
						<div class="md-list-item-left circle bg-light">
							<i class="mdi-action-spellcheck i-24 text-muted"></i>
						</div>
						<div class="md-list-item-content">
							<h3 class="text-md">{{Lang::get('app.valida-cadastro')}}</h3>
							<small class="font-thin">{{Lang::get('description.valida-cadastro')}}</small>
							<div class="well">
								<div class="row">
									<div class="col-md-6">{!!Form::textField('nome', Lang::get('app.nome'), null, array('class' => 'form-control', $disabled_paciente))!!}</div>
									<div class="col-md-6">{!!Form::textField('nome_social', "Nome Social", null, array('class' => 'form-control', $disabled_paciente))!!}</div>
								</div>
								<div class="row">
									<div class="col-md-12">{!!Form::textField('mae', Lang::get('app.nome-mae'), null, array('class' => 'form-control', $disabled_paciente))!!}</div>
								</div>
								<div class="row">
									<div class="col-md-3">{!!Form::textField('nascimento', Lang::get('app.nascimento'), null, array('class' => 'form-control date', $disabled_paciente))!!}</div>
									<div class="col-md-3">{!!Form::textField('cep', Lang::get('app.cep'), null, array('class' => 'form-control cep', $disabled_paciente))!!}</div>
									<div class="col-md-3">{!!Form::selectField('sexo', \App\Http\Helpers\Util::Sexo(), Lang::get('app.sexo'), null, array('class' => 'form-control', $disabled_paciente))!!}</div>
									<div class="col-md-3">{!!Form::textField('cns', Lang::get('app.cns'), null, array('class' => 'form-control', $disabled_paciente))!!}</div>
								</div>
								<div class="row">
									<div class="col-md-4">{!!Form::selectField('nacionalidade', App\Http\Helpers\Util::Nacionalidade(), Lang::get('app.nacionalidade'), "010", array('class' => 'form-control', $disabled_paciente))!!}</div>
									<div class="col-md-4">{!!Form::selectField('raca_cor', App\Http\Helpers\Util::RacaCor(), Lang::get('app.raca-cor'), null, array('class' => 'form-control', $disabled_paciente))!!}</div>
                                    <div class="col-md-4">{!!Form::selectField('preferencial', \App\Http\Helpers\Util::Ativo(), Lang::get('app.preferencial'), null, array('class' => 'form-control linha_cuidado', $disabled_paciente))!!}</div>
								</div>
								<div class="row">
									<div class="col-md-12">{!!Form::selectField('linha_cuidado', $linhas_cuidado, Lang::get('app.linha-cuidado'), null, array('class' => 'form-control','disabled' => 'disabled', $disabled_paciente))!!}</div>
								</div>
								<div class="row">
									<div class="col-md-3">{!!Form::selectField('endereco_tipo', App\Http\Helpers\Util::EnderecoTipo(), Lang::get('app.tipo'), "081", array('class' => 'form-control', $disabled_paciente))!!}</div>
									<div class="col-md-9">{!!Form::textField('endereco', Lang::get('app.endereco'), null, array('class' => 'form-control', $disabled_paciente))!!}</div>
								</div>
								<div class="row">
								<div class="col-md-2">{!!Form::textField('numero', Lang::get('app.numero'), null, array('class' => 'form-control', $disabled_paciente))!!}</div>
								    <div class="col-md-4">{!!Form::textField('complemento', Lang::get('app.complemento'), null, array('class' => 'form-control', $disabled_paciente))!!}</div>
								    <div class="col-md-6"> {!!Form::textField('bairro', Lang::get('app.bairro'), null, array('class' => 'form-control', $disabled_paciente))!!}</div>
								</div>
								<div class="row">
                                    <div class="col-md-3">{!!Form::textField('celular', Lang::get('app.celular'), null, array('class' => 'form-control cell-phone', $disabled_paciente))!!}</div>
									<div class="col-md-3">{!!Form::textField('telefone_residencial', Lang::get('app.telefone'), null, array('class' => 'form-control cell-phone', $disabled_paciente))!!}</div>
									<div class="col-md-3">{!!Form::textField('cpf', Lang::get('app.cpf'), null, array('class' => 'form-control cpf', $disabled_paciente))!!}</div>
                                    <div class="col-md-3">{!!Form::textField('rg', Lang::get('app.rg'), null, array('class' => 'form-control ', $disabled_paciente))!!}</div>
								</div>
								<div class="row">
									<div class="col-md-4">{!!Form::selectField('estado', \App\Estados::Combo(), Lang::get('app.estado'), "26", array('class' => 'chosen combo-estado', $disabled_paciente))!!}</div>
									<div class="col-md-8">
										{!!Form::selectField('cidade', isset($cidades) ? $cidades : [], Lang::get('app.cidade'), null, array('class' => 'chosen combo-cidade', $disabled_paciente))!!}
										{!!Form::hidden('cidade', null, ['id'=>'combo-cidade'])!!}
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="tab-triagem" class="tab-pane animated fadeInDown " role="tabpanel">
				<div class="well well-sm">
					<div id="tab-enfermagem-especifico" class="tab-pane animated fadeInUp " role="tabpanel">
						<hr class="no-margin" />
						<div>
							<div class="list-group list-group-gap">
								@foreach($questionario AS $pergunta)
								<div class="list-group-item md-whiteframe-z0" href="">
									<div class="row">
										<div class="col-md-4 text-medium">
											{{$pergunta['nome']}}
											<div id="info-{{$pergunta['id']}}"></div>
										</div>
										<div class="col-md-8  btn-perguntas-global">
											{{\App\Http\Helpers\Anamnese::MountASK($pergunta['id'], $pergunta['tipo_resposta'], $pergunta['multiplas'], $resposta[5], 5, \App\Http\Helpers\Util::CheckPermissionAction('agenda_check_list','created'))}}
										</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{!!Form::close()!!}
<script type="text/javascript">
	$(".combo-estado").change();
	$(".chosen").chosen({ width: '100%', include_group_label_in_selected:true, search_contains: true});
    updateCombos();
</script>