@if(!empty($link))
    <div class="alert alert-success"><a href="<?php echo $link; ?>" target="_blank" id="btn-click-download"><strong>Clique aqui</strong></a> para fazer download do arquivo em excel.</div>
    <script type="text/javascript">
        $("#btn-click-download").click();
    </script>
@else
    <div class="alert alert-danger">Não existe informação para os parametros informado</div>
@endif
