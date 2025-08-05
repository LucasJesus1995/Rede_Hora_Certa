<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AtendimentoLaudoImagens extends Model{
    
    protected $table = 'atendimento_laudo_imagens';

    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {

        });

        static::deleting(function ($model){

        });

    }

    /**
     * @param $atendimento_laudo
     * @param $arquivo
     * @return AtendimentoLaudoImagens
     */
    public static function saveImagem($atendimento_laudo, $arquivo)
    {
        $atendimento_laudo_imagem = new AtendimentoLaudoImagens();
        $atendimento_laudo_imagem->atendimento_laudo = $atendimento_laudo;
        $atendimento_laudo_imagem->arquivo = $arquivo;
        $atendimento_laudo_imagem->user = Util::getUser();
        $atendimento_laudo_imagem->save();

        return $atendimento_laudo_imagem;
    }

    public static function getByAtendimento($atendimento)
    {
        $data = AtendimentoLaudoImagens::select("atendimento_laudo_imagens.*")
            ->join('atendimento_laudo', 'atendimento_laudo.id', '=','atendimento_laudo_imagens.atendimento_laudo')
            ->where('atendimento_laudo.atendimento', $atendimento)
            ->where('atendimento_laudo_imagens.ativo', true)
            ->orderBy('atendimento_laudo_imagens.id','desc')
            ->get();

        return !empty($data[0]) ? $data : null;
    }

    public static function getImagensGrupo($atendimento){
        $imagens = null;
        $data = self::getByAtendimento($atendimento);

        if(!is_null($data)){
            foreach ($data AS $row){
                $imagens[$row->atendimento_laudo][] = $row->toArray();
            }
        }

        return $imagens;
    }

    public static function getByAtendimentoLaudo($atendimento_laudo)
    {
        $data = AtendimentoLaudoImagens::select("atendimento_laudo_imagens.*")
            ->where('atendimento_laudo_imagens.atendimento_laudo', $atendimento_laudo)
            ->where('atendimento_laudo_imagens.ativo', true)
            ->orderBy('atendimento_laudo_imagens.id','desc')
            ->get();

        return !empty($data[0]) ? $data : null;
    }


}
