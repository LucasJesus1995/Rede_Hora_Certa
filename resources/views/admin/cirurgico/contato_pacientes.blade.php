<h3>{{ $paciente->nome }}</h3>
<form>
    <input type="hidden" id="paciente_id" value="{{$paciente_id}}">
    <input type="hidden" id="agenda_id" value="{{$agenda}}">
    <input type="hidden" id="contato_id">
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <div class="checkbox">
            <label for="rd_s"><input type="radio" name="status" id="rd_s" checked> Sucesso</label>
            <label for="rd_i"><input type="radio" name="status" id="rd_i"> Insucesso</label>
      </div>
    <div class="form-group">
      <label for="descricao">Descricão</label>
      <textarea class="form-control" id="descricao"></textarea>
    </div>
    
    
    <button type="button"  onclick="salvarContato()" class="btn btn-primary btn-default">Salvar</button>
  </form>




<table class="table table-hover">
    <thead>
        <tr>
            <th>Data Hora</th>
            <th>Usuário do sistema</th>
            <th>Status</th>
            <th>Descrição</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($contatos as $contato)
            <tr>
                <td>{{ date('d/m/Y H:i', strtotime($contato->created_at)) }}</td>
                <td>{{ $contato->usuario }}</td>
                <td>{{ ($contato->status == 's' ? 'Sucesso' : 'Insucesso') }}</td>
                <td>{{ $contato->descricao }}</td>
                <td><button type="button" onclick="editarContato({{$contato->id}})">Editar</button>
            </tr>
        @endforeach
    </tbody>
</table>