@if(empty($error))
    <table class="table table-striped table-responsive table-bordered  bg-light " border="0" >
        <thead>
            <tr role="row">
                <th>Procedimento</th>
                @foreach($linha_cuidado AS $r)
                    <th>{{$r['abreviacao']}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($response AS $k => $r)
                <tr>
                    <td width="*">{{$r['procedimento']['nome']}}</td>
                    @foreach($r['linhas_cuidado'] AS $pr)
                        <td width="30" style="padding: 12px 10px: text-align: center">
                             <input
                                @if($params['ano'] < date('Y') || ($params['ano'] == date('Y') && $params['mes'] < date('m')))
                                    disabled="disabled"
                                    enabled="enabled"
                                @else
                                    class="procedimento-meta-input numbers"
                                @endif
                                maxlength="5"
                                style="width: 40px"
                                data-id="{{$pr['id']}}"
                                data-arena="{{$params['arena']}}"
                                data-linha_cuidado="{{$pr['linha_cuidado_id']}}"
                                data-ano="{{$params['ano']}}"
                                data-mes="{{$params['mes']}}"
                                data-procedimento="{{$pr['procedimento_id']}}"
                                name="valor" class="" maxlength=""   value="{{$pr['valor']}}" />
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

@else
    <div class="alert alert-info">{{$error}}</div>
@endif

<script>
    loadingMask();
</script>