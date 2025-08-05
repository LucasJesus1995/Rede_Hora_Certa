<?php
    $arr[1] = array(
        '' => 'NORMAL',
        'K20' => 'Esofagite',
        'K22.7' => 'Esôfago de Barret',
        'K44.9' => 'Hérnia hiatal',
        'K22.8' => 'Outras doenças especificadas do esôfago',
        'K29' => 'Gastrite e Duodenite',
        'K25' => 'Úlcera gástrica',
        'k31.7' => 'Pólipo de estômago e duodeno',
        'k26' => 'Úlcera duodenal',
        'K28' => 'Úlcera gastrojejunal',
    );

    $arr[2] = array(
        'C17' => 'Neoplasia maligna do intestino delgado (duodenal)',
        'K50' => 'Doença de Chron (enterite regional)',
        'K51' => 'Colite Ulcerativa',
        'K57' => 'Doença Diverticular do Intestino',
        'C18' => 'Neoplasia maligna do cólon',
        'C20' => 'Neoplasia maligna do reto',
        'K63' => 'Outras doenças do Intestino',
        'K63.5' => 'Pólipo de cólon',
        'C16' => 'Neoplasia maligna do estômago',
        '' => 'Outros',
    );
?>
<table width="100%" cellspacing="0" cellpadding="0">
    <tbody>
        <tr class="">
            <td width="50%">
                <table width="100%" cellspacing="0" cellpadding="2" class="table-border">
                    @foreach($arr[1] AS $key => $label)
                        <tr>
                            <td width="80%">{{$label}}</td>
                            <td width="10%">{{$key}}</td>
                            <td width="10%"></td>
                        </tr>
                    @endforeach
                </table>
            </td>
            <td width="50%">
                <table width="100%" cellspacing="0" cellpadding="2" class="table-border">
                    @foreach($arr[2] AS $key => $label)
                        <tr>
                            <td width="80%">{{$label}}</td>
                            <td width="10%">{{$key}}</td>
                            <td width="10%"></td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </tbody>
</table>