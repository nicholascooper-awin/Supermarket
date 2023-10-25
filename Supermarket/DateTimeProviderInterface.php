<?php

interface DateTimeProviderInterface
{
    public function getDateTime(): \DateTime;

    public function getLastMomentOfTheMonth(string $monthYear): \DateTime;
}