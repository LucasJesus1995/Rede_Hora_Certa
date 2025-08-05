<?php

class Status {

    public static function check($status = false) {
        echo $status;
    }

    public static function getColor($status = 0) {
        $color[0] = 'red';
        $color[1] = 'green';

        return $color[$status];
    }

}
