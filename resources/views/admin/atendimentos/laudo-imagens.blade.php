@if(!empty($arquivos))
    <hr />
    <div class="row">
        @foreach($arquivos AS $k => $imagens)
            <?php
                $col_md = count($arquivos) == 1 ? "col-md-12" : "col-md-6";
                $col_md_sub = count($arquivos) == 1 ? "col-md-3" : "col-md-6";
                ?>
            @if(!empty($imagens))
                <div class="{!! $col_md !!}">
                    <div class="panel panel-default">
                        <div class="panel-heading">{!! $k !!}</div>
                        <div class="panel-body">
                            @foreach($imagens AS $row)
                                <?php
                                   $imagem = \App\Http\Helpers\Upload::getImagemLaudo($atendimento, $row);
                                ?>
                                @if(!empty($imagem))
                                    <div class="{!! $col_md_sub !!} box-atendimento-laudo-imagem">
                                        <span class="position-relative">
                                            <img class="img-responsive img-thumbnail thumbnail {!! $row['id'] !!}-imagem-laudo" src="{!! $imagem !!}" style="width: 100%; height: 120px" />
                                            <div class="btn-delete-atendimento-laudo-imagem">
                                                <i data-id="{!! $row['id'] !!}" class="glyphicon glyphicon-remove delete-atendimento-laudo-imagem"></i>
                                            </div>
                                        </span>

                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif