<?php
class DateTimeProvider implements DateTimeProviderInterface
{
    public function getDateTime(): \DateTime
    {
        return new \DateTime();
    }

    public function getLastMomentOfTheMonth(string $monthYear): \DateTime
    {
        $dateTime = \DateTime::createFromFormat('dmY H:i:s', '01' . $monthYear . ' 23:59:59');
        $dateTime->modify('+1 month -1 day');
        return $dateTime;
    }

}