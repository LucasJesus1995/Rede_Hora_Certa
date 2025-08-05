<?php

namespace App;

use App\Http\Helpers\Util;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Usuarios extends Model
{
    public $table = 'users';

    public function saveData($data)
    {
        Cache::flush();
        try {
            if (isset($data['_token']))
                unset($data['_token']);

            if (isset($data['combo-profissional']))
                unset($data['combo-profissional']);

            $password = $data['password'];
            if (!empty($password))
                $data['password'] = bcrypt($data['password']);
            else
                unset($data['password']);

            $model = empty($data['id']) ? new Usuarios() : $this->find($data['id']);
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $model->$key = $value;
                }
            }
            $model->save();

            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public static function getFaturistas()
    {
        $data = [];
        $key = 'get-faturistas';

        if (!Cache::has($key)) {
            $data = Usuarios::select(
                [
                    'id',
                    'name',
                    'email',
                ]
            )
                ->where('level', '11')
                ->whereNotIn('id', [139, 191])
                ->where('active', true)
                ->orderBy('name', 'asc')
                ->get()
                ->toArray();

            if (count($data))
                Cache::put($key, $data, CACHE_SHORT);
        } else {
            $data = Cache::get($key);
        }

        return $data;
    }

    public static function getFaturistasCombo()
    {
        $data = [];
        $faturistas = self::getFaturistas();

        if ($faturistas) {
            foreach ($faturistas as $row) {
                $data[$row['id']] = Util::String2DB($row['name']);
            }
        }

        return $data;
    }

    public static function getUsers()
    {
        $sql = Usuarios::select(
            [
                'roles.role_title AS perfil',
                'users.*'
            ]
        )
            ->leftJoin('roles', 'roles.id', '=', 'users.level');

        return $sql->get();;
    }


}
