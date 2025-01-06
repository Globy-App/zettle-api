<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\API\Finance;

use GlobyApp\Zettle\API\Finance\Enum\Periodicity;
use Money\Money;

final class PayoutInfo
{
    private $totalBalance;
    private $nextPayoutAmount;
    private $discountRemaining;
    private $periodicity;

    public function __construct(Money $totalBalance, Money $nextPayoutAmount, Money $discountRemaining, Periodicity $periodicity)
    {
        $this->totalBalance = $totalBalance;
        $this->nextPayoutAmount = $nextPayoutAmount;
        $this->discountRemaining = $discountRemaining;
        $this->periodicity = $periodicity;
    }

    public function getTotalBalance(): Money
    {
        return $this->totalBalance;
    }

    public function getNextPayoutAmount(): Money
    {
        return $this->nextPayoutAmount;
    }

    public function getDiscountRemaining(): Money
    {
        return $this->discountRemaining;
    }

    public function getPeriodicity(): Periodicity
    {
        return $this->periodicity;
    }
}
