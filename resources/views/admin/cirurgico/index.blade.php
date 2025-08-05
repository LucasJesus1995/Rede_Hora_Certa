@extends('admin')

@section('content')

<div class="card">
  <div class="card-heading">
      <h2>
          {{Lang::get('app.listagem-de-dados')}}
      </h2>
  </div>
  <div class="card-tools">
      <ul class="list-inline">
          <li class="dropdown">
              <a class="md-btn md-flat md-btn-circle waves-effect" data-toggle="dropdown" md-ink-ripple="" aria-expanded="false">
                  <i class="mdi-navigation-more-vert text-md"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">
                  <li><a href="" class="btn-back-listagem ">{{Lang::get('app.listagem')}}</a></li>
              </ul>
          </li>
      </ul>
  </div>
  <div class="card-body bg-light " id="">

{!!Form::model('Cirurgico', array('url' => '/admin/cirurgico/filtro', 'class' => 'form-vertical', 'autocomplete' => 'off'))!!}
{{-- <button type="submit" class="btn btn-primary">Filtrar</button> --}}
<div class="col-md-2">
    <div class="form-group">
      <label for="exampleInputName2">Data Início</label>
      <input required class="form-control date" id="data_inicio_cirurgico" name="data_inicio_cirurgico" type="text" value="{{ $data_inicio_cirurgico }}" placeholder="99/99/9999">
    </div>
</div>
<div class="col-md-2">
    <div class="form-group">
      <label for="exampleInputName2">Data Fim</label>
      <input class="form-control date" id="data_final_cirurgico" name="data_final_cirurgico" type="text" value="{{ $data_final_cirurgico }}">

    </div>
    <span class='text-descricao-input text-muted block text-xs'>Deixar em branco se for uma data específica</span>

</div>
<div class="col-md-3">
    <div class="form-group">
      <label for="exampleInputName2">Paciente</label>
      <input class="form-control" id="paciente" name="paciente" type="text" value="{{ $paciente }}">
    </div>
</div>
<div class="col-md-3">
  <div class="form-group">
    <label for="exampleInputName2">Médico</label>
    <select id="medico" class="form-control chosen" name="medico">
        <option value>...</option>
        @foreach ($medicos as $medico)
          <option {{ ($medico->id == $selMedico ? 'selected': '') }} value="{{ $medico->id }}">{{ $medico->nome }}</option>
        @endforeach
    </select>
  </div>
</div>
<div class="col-md-2">
  <div class="align-center">
      <div class="form-group">
          <label class="" style="display: block;" >&nbsp;</label>
          <div class="row">
            <div class="col-md-4"><button type="submit" title="Filtrar" class="btn col-md-12 btn-info waves-effect"><i class="fa fa-search"></i></button></div>
            @if(count($agendas) > 0)
              <div class="col-md-5"><a href="/admin/cirurgico/list/false/true" title="Exportar a filtragem" class="btn col-md-12 btn-success waves-effect"><i class="fa fa-file-excel-o"></i></a></div>
            @endif
          </div>
      </div>
  </div>
</div>
{!!Form::close()!!}

@if(isset($agendas))
<table class="table table-hover">
    <thead>
        <tr>
            {{-- <th></th> --}}
            <th>Arena</th>
            <th>Data Atendimento</th>
            <th>Data Saída</th>
            <th>Paciente</th>
            {{-- <th>CNS</th> --}}
            <th>Linha Cuidado</th>
            <th>Médico</th>
            <th>Tipo Atendimento</th>
            <th>Conduta Principal</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    
        @foreach ($agendas as $agenda)

            <tr>
                {{-- <td><input type="checkbox" name="selecionados[]" value="{{$agenda->id}},{{$agenda->paciente}}"></td> --}}
                <td>{{ $agenda->arena }}</td>
                <td>{{ date('d/m/Y', strtotime($agenda->data_atendimento)) }}</td>             
                <td>
                  @if(!empty($agenda->saida))
                    {{ date('d/m/Y', strtotime($agenda->saida)) }}
                  @endif
                </td>
                <td>{{ $agenda->paciente }}</td>
                {{-- <td>{{ $agenda->sus }}</td> --}}
                <td>{{ $agenda->especialidade }}</td>
                <td>{{ $agenda->medico }}</td>
                <td>{{ $agenda->tipo_atendimento }}</td>
                <td>{{ $agenda->conduta_principal }}</td>
                {{-- <td>{{ $agenda->tipo_secundaria }}</td> --}}
                <td><button type="button" class="btn btn-primary" onclick="contatos({{ $agenda->paciente_id }},{{ $agenda->agenda_id }})">Contatos</button></td>
                <td><button type="button" class="btn btn-primary" onclick="agendasIndex({{ $agenda->paciente_id }}, '{{ $agenda->data_atendimento }}')">Agendas</button></td>
            </tr>
            
        @endforeach
    @endif
    </tbody>
</table>
@if(isset($agendas))
  {!! $agendas->render() !!}
  <input type="hidden" id="url-pagination" value="{{urldecode($_SERVER['REQUEST_URI'])}}" />
@endif
  </div>
</div>

<div class="modal fade" id="modalAgendas" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id="conteudoAgendas">
          <p>One fine body&hellip;</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  <div class="modal fade" id="modalContatos" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h3>Contatos</h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id="conteudoContatos">
          <p>One fine body&hellip;</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
@endsection
<script>
    
    
</script>