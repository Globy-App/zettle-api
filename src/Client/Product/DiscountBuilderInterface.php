<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Product;

use GlobyApp\Zettle\API\Product\DiscountCollection;
use GlobyApp\Zettle\Client\Universal\BuilderInterface;

interface DiscountBuilderInterface extends BuilderInterface
{
    public function buildFromJson(string $json): array;

    public function buildFromArray(array $discounts): DiscountCollection;
}
