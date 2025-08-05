<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    public $layout;
    public $objModel;

    public function __construct()
    {
        if (isset($this->model)) {
            $this->layout = snake_case($this->model, '-');

            $namespaceModel = '\\App\\' . $this->model;

            $this->objModel = new $namespaceModel();
        }
    }

    private function _postAll($data)
    {

        if (isset($data['_token']))
            unset($data['_token']);

        return $data;
    }

    protected function _saveData($object, $request, $id = null)
    {
        try {
            $data = $request->all();
            if (!empty($data['id'])) {
                $object = $object->find($data['id']);
            }

            if (is_array($data)) {
                foreach ($this->_postAll($data) AS $key => $value) {
                    $object->$key = $value;
                }

                $object->save();
            }

            return $object->getAttributes();
        } catch (\Exception $exc) {
            echo $exc->getMessage();
        }
    }
}