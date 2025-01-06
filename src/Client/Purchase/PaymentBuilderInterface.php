<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Purchase;

use GlobyApp\Zettle\API\Purchase\AbstractPayment;
use Money\Currency;

interface PaymentBuilderInterface
{
    /**
     * @return AbstractPayment[]
     */
    public function buildFromArray(array $payments, Currency $currency): array;

    public function build(array $payment, Currency $currency): AbstractPayment;
}
