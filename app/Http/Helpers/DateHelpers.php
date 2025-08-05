<?php

namespace App\Http\Helpers;


class DateHelpers
{

    public static function getFeriados()
    {
        return [];
    }

    public static function getDiasUteis($startDate, $endDate)
    {
        $begin = strtotime($startDate);
        $end = strtotime($endDate);

        if ($begin > $end) {
            echo "startdate is in the future! <br />";
            return 0;
        } else {
            $holidays = self::getFeriados();
            $weekends = 0;
            $no_days = 0;
            $holidayCount = 0;

            while ($begin <= $end) {
                $no_days++; // no of days in the given interval
                if (in_array(date("d/m", $begin), $holidays)) {
                    $holidayCount++;
                }
                $what_day = date("N", $begin);
                if ($what_day > 5) { // 6 and 7 are weekend days
                    $weekends++;
                };
                $begin += 86400; // +1 day
            };

            $working_days = $no_days - $weekends - $holidayCount;

            return $working_days;
        }
    }

    public static function getSabados($startDate, $endDate)
    {
        $sab = 0;

        list($ano1, $mes1, $dia1) = explode("-", $startDate);
        list($ano2, $mes2, $dia2) = explode("-", $endDate);

        $fimMK = mktime(0, 0, 0, $mes2, $dia2, $ano2);

        for ($i = 1; $i > 0; $i++) {
            $calcula = mktime(0, 0, 0, $mes1, $dia1 + $i, $ano1);

            if (date('w', $calcula) == 6) {
                $sab++;
            }

            if ($calcula == $fimMK) {
                break;
            }
        }

        return $sab;
    }

}