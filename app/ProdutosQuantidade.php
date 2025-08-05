<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProdutosQuantidade extends Model
{
    protected $table = 'produtos_quantidade';

    protected $fillable = ['produto', 'lote', 'arena', 'central', 'quantidade'];
}
