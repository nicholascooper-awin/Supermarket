<?php
class CreditCard
{
    public function __construct(
        private DateTimeProviderInterface $dateTimeProvider,
        private string $expirationDate,
        )
    {
    }

    public function isExpired(): bool
    {
        return $this->dateTimeProvider->getLastMomentOfTheMonth($this->expirationDate) < $this->dateTimeProvider->getDateTime();
    }

}