<?php

namespace App\Http\Controllers;

/**
 * Description of TraitController
 *
 * @author edersonsandre
 */
trait TraitController {

    public function getIndex() {
        return redirect("admin/{$this->layout}/list");
    }

    public function getList() {
        return View("admin.{$this->layout}.list")->with('title', $this->title);
    }

    public function getEntry($id = null) {
        $view = View("admin.{$this->layout}.entry")->with('title', $this->title);

        $entry = null;
        if ($id) {
            $entry = $this->objModel->find($id);
        }

        $view->entry = $entry;

        return $view;
    }

    public function getGrid(){
        $view = View("admin.{$this->layout}.grid");

        $sql  = $this->objModel->select('*')
            ->orderBy('id','desc');

        $params = !empty($_GET['q']) ? $_GET['q'] : null;
        if(!empty($params)){
            $columns = \DB::connection()->getSchemaBuilder()->getColumnListing($this->objModel->getTable());

            if($columns){
                foreach($columns AS $col){
                    if(in_array($col, array('created_at','updated_at','ativo')))
                        continue;

                    $sql->oRwhere($col, 'LIKE', "%{$params}%");
                }
            }
        }

        $view->grid = $sql->paginate(PAGINATION_PAGES);

        return $view;
    }

    public function getDelete($id) {
        $object =  $this->objModel->find($id);

        if(isset($object->id)) {
            $object->ativo = 0;
            $object->save();
        }

        return json_encode(['status'=>true]);
    }

}
