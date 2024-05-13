<?php

namespace  App\Core\Utils;

class DateUtils
{
    public static function format_date_d_m_y():string
    {
        return 'format_date_d_m_y';
    }

    public static function getDate(): string
    {
        return date('d');
    }

    public static function getMonth(string $format = 'm'): string
    {
        return date($format);
    }

    public static function getYear():string
    {
        return date('Y');
    }
}