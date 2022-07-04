<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelpers
{
    private static $timeZone = 'Asia/Colombo';

    public static function getWeekStartTime()
    {
        return now()->startOfWeek();
    }

    public static function getWeekEndTime()
    {
        return now()->endOfWeek();
    }

    public static function getMonthStartTime()
    {
        return now()->startOfMonth();
    }

    public static function getMonthEndTime()
    {
        return now()->endOfMonth();
    }

    public static function getDayStartTime()
    {
        return now()->startOfDay();
    }

    public static function getDayEndTime()
    {
        return now()->endOfDay();
    }

    public static function getYearStartTime()
    {
        return now()->startOfYear();
    }

    public static function getYearEndTime()
    {
        return now()->endOfYear();
    }

    public static function getFormattedYearFromWeekNumber(string $date): string
    {
        $pos = (int) strpos($date, '-');
        $year = (int) substr($date, 0, $pos);
        $weekNumber = (int) substr($date, $pos + 1);

        $month = self::getMonthValueFromYeayWeekNumber($year, $weekNumber);

        $monthStartWeekNumber = Carbon::createFromDate($year, $month)->weekOfYear;

        if ($monthStartWeekNumber === $weekNumber || ($diff = $monthStartWeekNumber - $weekNumber) <= 0) {
            return $year . '-' . $month . '-' .  '1' ;
        }

        return $year . '-' . $month . '-' . $diff;
    }

    public static function getMonthValueFromYeayWeekNumber(int $year, int $week): int
    {
        return (int)(new \DateTime())->setISODate($year, $week)->format('m');
    }
}
