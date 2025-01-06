<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Product;

use GlobyApp\Zettle\API\Product\ProductCollection;

interface ProductBuilderInterface
{
    public function buildFromJson(string $json): array;

    public function buildFromArray(array $products): ProductCollection;
}
