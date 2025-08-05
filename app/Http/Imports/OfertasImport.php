<?php


namespace App\Http\Imports;


use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\ExcelFile;

class OfertasImport extends ExcelFile
{
    private $mes;
    private $ano;


    /**
     * Get file
     * @return string
     */
    public function getFile()
    {
        $file = Input::file('file');
        $this->ano = Input::get('ano');
        $this->mes = Input::get('mes');



        return  $this->doSomethingLikeUpload($file);
    }

}