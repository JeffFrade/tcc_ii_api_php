<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function parse(string $date)
    {
        if ($date != null) {
            return Carbon::createFromFormat('Y-m-d', $date);
        }
    }

    public static function diffDates(Carbon $date1, Carbon $date2)
    {
        return $date1->diffInDays($date2, false);
    }

    public static function validateDate(string $date)
    {
        preg_match_all('/[\d]{4}\-[\d]{2}\-[\d]{2}/i', $date, $matches);

        return $matches[0][0] ?? false;
    }

    public static function convertDate(string $date)
    {
        return Carbon::parse($date)
            ->setTimezone(env('APP_TIMEZONE', 'UTC'))
            ->format('d/m/Y H:i:s');
    }
}
