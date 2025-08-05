<!DOCTYPE html>
<html>
<head>
    <title>BI - CIES</title>
    <link rel="stylesheet" href="/structure/layout/material/libs/jquery/bootstrap/dist/css/bootstrap.css?{{NCACHE}}" type="text/css" />
    <style>
        html, body {
            background: #ebc211 !important;
            margin: 0px;
            padding: 0px;
            overflow: hidden;
            text-align: center;
        }

        iframe, object {
            overflow: hidden;
            overflow-x: hidden;
            overflow-y: hidden;
            position: absolute;
            top: 0px;
            left: 0px;
            right: 0px;
            bottom: 0px;
            background: #000000 !important;
            margin: 0 auto;
            width: 98.4%;
            height: 100%;
        }
    </style>

</head>

<body>

<?php

$data[] = [
    'label' => 'Produção',
    'link' => 'https://app.powerbi.com/reportEmbed?reportId=672bd2dc-77bb-4a59-b3d4-0347d17bcbd7&groupId=56615d51-a586-49a0-ad88-6c60840e55fb&autoAuth=true&ctid=d44d905d-4f9f-413f-9599-ea2455237168',
];

$data[] = [
    'label' => 'Faturamento',
    'link' => 'https://app.powerbi.com/reportEmbed?reportId=d4ca3a1d-e2f7-4598-a648-17a89a31ab13&groupId=56615d51-a586-49a0-ad88-6c60840e55fb&autoAuth=true&ctid=d44d905d-4f9f-413f-9599-ea2455237168',
];
?>

<div style="width: 1200px; margin: 30px auto; ">
    <div class="row">
        @foreach($data AS $row)
            <div class="col-md-1" style="text-align: center">
                <a href="{!! $row['link'] !!}" target="_blank" class="btn-default">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c9/Power_bi_logo_black.svg/220px-Power_bi_logo_black.svg.png" class="img-responsive "  style="margin: 0 auto" />
                <div>{!! $row['label'] !!}</div>
                </a>
            </div>
        @endforeach
    </div>
</div>

</body>
</html>