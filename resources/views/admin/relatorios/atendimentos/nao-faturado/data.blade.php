@if(!empty($link))
    <div class="alert alert-success"><a href="<?php echo $link; ?>" target="_blank" id="btn-click-download"><strong>Clique aqui</strong></a> para fazer download do arquivo em excel.</div>
    @else
    <div class="alert alert-danger">Não foi possivel gerar o relatorio. Não existe informação para os parametros informado</div>
@endif
