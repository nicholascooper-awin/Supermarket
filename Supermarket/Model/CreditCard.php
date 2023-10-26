<?php
namespace Model;

class CreditCard
{
    public function __construct(
        private string $expirationDate,
        )
    {
    }

    public function isExpired(\DateTime $today): bool
    {
        return DateTimeProvider::getLastMomentOfTheMonth($this->expirationDate) < $today;
    }

}