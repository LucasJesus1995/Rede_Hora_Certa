@if(!empty($data))
    <html>
    <link media="all" type="text/css" rel="stylesheet" href="src/css/relatorio/diretoria.css">

    <table width="100%" border="1">
        <tr>
            <th width="13">Código</th>
            <th width="20">Complexidade</th>
            <th width="30">Modalidade</th>
            <th width="60">CID Primário</th>
            <th width="60">CID Secundário</th>
            <th width="90">Nome</th>
            <th width="15">Quantidade</th>
            <th width="15">Obrigatório</th>
            <th width="15">SUS</th>
            <th width="20">Forma Faturamento</th>
            <th width="15">Maximo</th>
            <th width="15">CBO</th>
            <th width="15">Contador</th>
            <th width="15">Ativo</th>
            <th width="15">Ordem</th>
            <th width="20">Serviço BPA</th>
            <th width="20">Class BPA</th>
            <th width="20">Multiplicador</th>
            <th width="20">Multiplicador Médico</th>
            <th width="15">Sexo</th>
            <th width="20">Autorização</th>
            <th width="15">Principal</th>
            <th width="20">Atualização</th>
            <th width="20">Criação</th>

        </tr>
        @foreach($data AS $row)
            <tr class="line {!! $zebra =  (@$zebra == "even") ? "odd" : "even" !!}">
                <td class="left">{!! $row->id !!}</td>
                <td class="left">{!! $row->complexidade !!}</td>
                <td class="left">{!! $row->modalidade !!}</td>
                <td class="left">{!! $row->cid_primario !!}</td>
                <td class="left">{!! $row->cid_secundario !!}</td>
                <td class="left">{!! $row->nome !!}</td>
                <td class="center">{!! $row->quantidade !!}</td>
                <td class="center">{!! $row->obrigatorio !!}</td>
                <td class="center">{!! $row->sus !!}</td>
                <td class="center">{!! $row->forma_faturamento !!}</td>
                <td class="center">{!! $row->maximo !!}</td>
                <td class="center">{!! $row->cbo !!}</td>
                <td class="center">{!! $row->contador !!}</td>
                <td class="center">{!! $row->ativo !!}</td>
                <td class="left">{!! $row->ordem !!}</td>
                <td class="left">{!! $row->servico_bpa !!}</td>
                <td class="left">{!! $row->class_bpa !!}</td>
                <td class="center">{!! $row->multiplicador !!}</td>
                <td class="center">{!! $row->multiplicador_medico !!}</td>
                <td class="center">{!! $row->sexo !!}</td>
                <td class="center">{!! $row->autorizacao !!}</td>
                <td class="center">{!! $row->principal !!}</td>
                <td class="center">{!! $row->updated_at !!}</td>
                <td class="center">{!! $row->created_at !!}</td>
            </tr>
        @endforeach
    </table>

    </html>
@endif