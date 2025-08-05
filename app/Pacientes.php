<?php

namespace App;

use App\Http\Helpers\Util;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use PDOException;
use Zend\Filter\Digits;
use Illuminate\Support\Facades\Cache;

class Pacientes extends Model
{

    protected $table = 'pacientes';

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            foreach ($model->getAttributes() AS $key => $value) {
                $model->$key = Util::String2DB($value);
            }

            $digits = new Digits();

            $model->cep = $digits->filter($model->cep);
            $model->cpf = $digits->filter($model->cpf);
            $model->celular = $digits->filter($model->celular);
            $model->numero = $digits->filter($model->numero);
            $model->telefone_comercial = $digits->filter($model->telefone_comercial);
            $model->telefone_residencial = $digits->filter($model->telefone_residencial);

            if (empty($model->tipo_sanguineo)) {
                unset($model->tipo_sanguineo);
            }

            if (empty($model->nascimento_estado)) {
                unset($model->nascimento_estado);
            }

            if (empty($model->religiao)) {
                unset($model->religiao);
            }

            if (empty($model->cidade)) {
                unset($model->cidade);
            }

            if (empty($model->estabelecimento)) {
                unset($model->estabelecimento);
            }
        });

        Cache::flush();
    }

    public static function getDadosInvalidos()
    {

        $data = Pacientes::select(
            [
                'pacientes.*'
            ]
        )
            ->where(function ($query) {
                $query->orWhere('nome', '=', '')
                    ->orWhere('sexo', '=', '')
                    ->orWhere('nascimento', '=', '')
                    ->orWhere('nascimento', '=', null)
                ;
            })
            ->whereNotNull('cns')
            ->orderBy('id', 'desc')
            ->paginate(100);


        return !empty($data[0]) ? $data : null;
    }

    public function getNomeSocialLayout()
    {
        $nome = $this->nome;
        if (!empty($this->nome_social)) {
            $nome = self::nomeSocialLayout($this->nome, $this->nome_social);
        }

        return $nome;
    }

    public static function nomeSocialLayout($nome, $nome_social = null)
    {
        return (strlen(trim($nome_social)) == 0) ? $nome : "<strong>{$nome_social}</strong> <span style='color: #666; font-size: 80%'>({$nome})</span>";
    }

    public static function get($id)
    {
        $key = 'get-paciente-' . $id;

        if (!Cache::has($key)) {
            $data = Pacientes::find($id)->toArray();

            if (count($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function Combo()
    {
        $key = 'pacientes-combo';

        if (!Cache::has($key)) {
            $data = [];
            foreach (self::select('nome', 'cns', 'id')->get() as $row) {
                $data[$row->id] = $row->cns . ' - ' . $row['nome'];
            }

            if (count($data)) {
                Cache::put($key, $data, CACHE_DAY);
            }

        } else {
            $data = Cache::get($key);
        }
        return $data;
    }

    public static function getByCNS($cns)
    {
        $paciente = self::select('cns', 'id')->where('cns', $cns)->get();

        return !empty($paciente['0']) ? $paciente['0'] : false;
    }

    public static function getByCNSCompleto($cns)
    {
        $paciente = self::where('cns', '=', $cns)->get();

        return !empty($paciente['0']) ? $paciente['0'] : false;
    }

    public static function getPacienteByCNS($cns)
    {
        $paciente = self::select('*')->where('cns', $cns)->get();

        return !empty($paciente['0']) ? $paciente['0'] : false;
    }

    public static function getByCNSNascimento($cns, $nascimento)
    {
        $paciente = self::select('cns', 'id')->where('cns', $cns)->where('nascimento', $nascimento)->orderBy('ativo',
            'desc')->orderBy('id', 'desc')->get();

        return !empty($paciente['0']) ? $paciente['0'] : false;
    }

    /**
     * @param $search
     * @param int $limit
     * @return array
     */
    public static function Search($search, $limit = 5)
    {
        $data = array();

        $search = Util::digits($search);

        if (strlen($search) > 9) {
            $sql = self::select('nome', 'cns', 'id')
                ->orderBy('nome', 'ASC')
                ->where('ativo', true)
                ->limit($limit);

            if (in_array(strlen($search), [10, 11])) {
                $sql->where('cpf', $search);
            } else {
                if (strlen($search) >= 14) {
                    $sql->where('cns', $search);
                }
            }

            $pacientes = $sql->get();
            if ($pacientes) {
                foreach ($pacientes as $row) {
                    $data[$row->id] = $row->cns . ' - ' . $row['nome'];
                }
            }
        }

        return $data;
    }

    public static function getByCNSAndCPF($cns, $cpf)
    {
        $paciente = self::select('*')->where('cns', $cns)->where('cpf', $cpf)->get();

        return (!count($paciente)) ? false : $paciente['0'];
    }

    public static function getCPF($cpf)
    {
        $paciente = self::where('cpf', $cpf)->get();

        return !empty($paciente[0]) ? $paciente[0] : null;
    }

    public function saveData($data)
    {
        try {
            if (isset($data['_token'])) {
                unset($data['_token']);
            }

            if (isset($data['estado'])) {
                unset($data['estado']);
            }

            $data['nascimento'] = Util::Date2DB($data['nascimento']);

            $model = empty($data['id']) ? new Pacientes() : $this->find($data['id']);
            if (is_array($data)) {
                foreach ($data AS $key => $value) {
                    $model->$key = $value;
                }
            }
            $model->save();

            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

}
