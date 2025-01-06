<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Product;

use GlobyApp\Zettle\API\Product\VariantCollection;

interface VariantBuilderInterface
{
    public function buildFromArray(array $data): VariantCollection;
}
