<?php


namespace App\Traits;


trait ModelTraits
{

    public function _save(array $data)
    {
        $_data = !empty($data['id']) ? self::find($data['id']) : $this;
        $columns = \DB::connection()->getSchemaBuilder()->getColumnListing($this->getTable());

        if (count($columns)) {
            try {
                foreach ($columns AS $column) {
                    if (array_key_exists($column, $data)) {
                        $_data->$column = $data[$column];
                    }
                }

                $_data->save();
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }

        return $_data;
    }


}