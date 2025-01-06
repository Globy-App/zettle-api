<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Purchase;

use Money\Currency;
use Money\Money;

final class VatBuilder implements VatBuilderInterface
{
    public function buildFromArray(array $vatAmounts, Currency $currency): array
    {
        $data = [];
        foreach ($vatAmounts as $vat => $amount) {
            $data[$vat] = new Money($amount, $currency);
        }

        return $data;
    }
}
