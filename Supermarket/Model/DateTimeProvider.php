<?php
namespace Model;

class DateTimeProvider
{
    public static function getNow(): \DateTime
    {
        return new \DateTime();
    }

    public static function getLastMomentOfTheMonth(string $monthYear): \DateTime
    {
        $dateTime = \DateTime::createFromFormat('dmY H:i:s', '01' . $monthYear . ' 23:59:59');
        $dateTime->modify('+1 month -1 day');
        return $dateTime;
    }

}