<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Purchase;

use GlobyApp\Zettle\API\Purchase\Product;
use Money\Currency;

interface ProductBuilderInterface
{
    /**
     * @return Product[]
     */
    public function buildFromArray(array $products, Currency $currency): array;
}
