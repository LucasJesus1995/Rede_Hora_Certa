<?php
	$linha_cuidado = \App\Http\Helpers\Util::getLinhaCuidado($agenda->linha_cuidado);

	$medico = \App\Http\Helpers\Util::getDataDigitadora();
	$doctor = !empty($medico['doctor']) ? $medico['doctor'] : null;

	$laudos = \App\LaudoMedico::ByLinhaCuidadoMedico($linha_cuidado->id, $doctor);
	$cids = \App\Cid::getAll();

	$disabled = (\App\Http\Helpers\Util::CheckPermissionAction('medicina_laudo','created')) ? null:  "disabled";

    $visible_laudo_edit = \App\Http\Helpers\Util::getHideLaudoAtendimento($atendimento->status, $atendimento->agenda);
?>
<div id="grid-laudo">
	<div class="text-center m-b" style="padding: 30px">
		<i class="fa fa-circle-o-notch fa-spin text-lg text-muted-lt"></i>
	</div>1
</div>

@if($visible_laudo_edit)
    <div id="box-atendimento-laudo">
        <input type="hidden" id="close-atendimento-laudo"  value="0" />
        <h5>
            <strong>{{Lang::get('app.linha-cuidado')}}:</strong>
            <span class="label bg-success pos-rlt m-r-xs">
            <b class="arrow bottom"></b>{{$linha_cuidado->nome}}
            </span>
        </h5>
        <div class="well well-sm">
            <div class="row">
                <div class="col-md-9">
                    <div class="well well-small">
                        <div class="row">
                            <div class="col-md-6">
                                <label class=" marginT10 " for="id-field-procedimentos">{{Lang::get('app.laudo')}}</label>
                                <input type="hidden" name="laudo-id" id="laudo-id" value="" />
                                <select name="laudo" id="laudo" class="form-control chosen "  {{$disabled}} >
                                    <option value="" selected="selected">...</option>
                                    @foreach($laudos AS $key => $rows)
                                    <?php
                                        if(is_array($rows)){
                                            echo "<optgroup label='{$key}'>";
                                                foreach ($rows AS $row){
                                                    $selected = null;

                                                    $option =  \App\Http\Helpers\Util::limitarTexto($row['nome'], 200);
                                                    ?>
                                                        <option id="laudo-{{$row['id']}}" value="{{$row['id']}}" {{$selected}}>{!! $option !!}</option>
                                                    <?php
                                                }
                                            echo "<optgroup>";
                                        }
                                        ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class=" marginT10 " for="id-field-procedimentos">{{Lang::get('app.cid')}}</label>
                                <select name="cid" id="cid" class="form-control chosen"  {{$disabled}} >
                                    <option value="" selected="selected">...</option>
                                    @foreach($cids AS $key => $label)
                                        <option value="{!! $key !!}">{!! $label !!}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="marginT10" style="margin-top: 10px">
                            <label class=" marginT10 " for="id-field-resultado-laudo">{{Lang::get('app.resultado-laudo')}}</label>
                            <div class="row">
                                @foreach(App\Http\Helpers\Util::getLaudoResultados() as $key => $value)
                                    <div class="col-md-4">{!! Form::radioField('resultado_laudo', $value, $key, null, ['class'=>'resultado-laudo-biopsia']) !!}</div>
                                @endforeach
                            </div>
                            <div class="row">
                                <div id="resultado-laudo-biopsia" class="col-md-12 hidden">
                                    <div class="alert alert-success clear">
                                        {!! Form::textField('biopsia', Lang::get('app.descricao'), null, ['id'=>'resultado-laudo-biopsia-input','class'=>'form-control col-md-12']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="alert alert-info">
                        Se o paciente tiver suspeita de neoplasia, será necessário informar uma descrição da suspeita.
                    </div>
                    <a href="javascript: void(0)" id="btn-save-laudo" class="btn btn-primary btn-xs col-md-12" {{$disabled}}>Salvar</a>
                </div>
            </div>

            {!!Form::textareaField('descricao',Lang::get('app.laudo-descricao'), null, array('class'=>'no-style form-control ckeditor-simplificado', 'rows'=>'15','id'=>'laudo-description',$disabled))!!}
        </div>
    </div>
@endif

<script type="text/javascript">
	$(".chosen").chosen({ width: '100%' });
	$('textarea.ckeditor').ckeditor();
	$('textarea.ckeditor-simplificado').ckeditor();

	CKEDITOR.config.toolbar = [
		['Styles','Format','Font','FontSize','Source'],
		'/',
		['Bold','Italic','Underline','StrikeThrough','-','Undo','Redo','-','Cut','Copy','Paste','-','Outdent','Indent'],
		['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Image','Table','-','TextColor','BGColor']
	] ;

</script>

