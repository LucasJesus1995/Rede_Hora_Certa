<?php

HTML::macro('table', function($fields = array(), $table_fields = array(), $data = array(), $resource, $showEdit = false, $showDelete = false, $showView = false) {
    $table = '<table id="sample-table-1" class="table table-striped table-hover table-condensed table-responsive ">';
    $table .='<thead>';
    $table .='<tr>';
    foreach ($table_fields as $table_field) {
        $table .= "<th class='center' >" . studly_case($table_field) . '</th>';
    }

    if ($showEdit || $showDelete || $showView) {
        $table .= '<th></th>';
    }

    $table .= '</tr>';
    $table .='</thead>';

    foreach ($data as $d) {

        $id = isset($d->id) ? $d->id : $d->codigo;
        $status = isset($d->active) ? $d->active : false;

        $class_status = (!$status) ? 'danger' : null;

        $table .= "<tr class='{$class_status}' >";
        foreach ($fields as $key) {
            $table .= '<td class="' . formatColumnClass($key) . '">' . formatColumn($d->$key, $key) . '</td>';
        }

        if ($showEdit || $showDelete || $showView) {
            $table .= '<td class="td-actions center" style="width: 110px;">';
            $table .= '<div class="visible-md visible-lg hidden-sm hidden-xs ">';
            if ($showEdit) {
                $table .= '&nbsp;<a class="btn btn-xs btn-blue tooltips" href="/' . $resource . '/entry/' . $id . '" data-toggle="tooltip" data-original-title="Editar Registro"> <i class="fa fa-edit"></i> </a>';
            }
            if ($showView) {
                $table .= '&nbsp;<a class="btn btn-success btn-xs tooltips" href="/' . $resource . '/show/' . $id . '" data-toggle="tooltip" data-original-title="Visualizar Registro"> <i class="glyphicon glyphicon-search"></i> </a>';
            }
            if ($showDelete) {
                $table .= '&nbsp;<a class="btn btn-danger btn-xs tooltips link-delete" href="/' . $resource . '/delete/' . $id . '" data-toggle="tooltip" data-original-title="Apagar Registro"> <i class="glyphicon glyphicon-trash"></i> </a>';
            }
            $table .= '</div>';
            $table .= '</td>';
        }
        $table .= '</tr>';
    }
    $table .= '</table>';
    return $table;
});

function formatColumn($value, $key) {
    if (in_array($key, array('created_at', 'data', 'updated_at')) && !empty($value)) {
        $value = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->toFormattedDateString();
    }

    if (in_array($key, array('end', 'begin')) && !empty($value)) {
        $value = \Carbon\Carbon::createFromFormat('Y-m-d', $value)->toFormattedDateString();
    }

    if (in_array($key, array('month')) && !empty($value)) {
        $value = App\Http\Helper\Util::months($value);
    }

    if (in_array($key, array('area')) && !empty($value)) {
        $value = App\Http\Helper\Util::AreaList($value);
    }

    if (in_array($key, array('type')) && !empty($value)) {
        $value = App\Http\Helper\Util::outdoorType($value);
    }

    if (in_array($key, array('link', 'url'))) {
        $value = "<a href='{$value}' target='_blank'>{$value}</a>";
    }

    return $value;
}

function formatColumnClass($key) {
    if (in_array($key, array('id', 'created_at')))
        return 'center';
}
