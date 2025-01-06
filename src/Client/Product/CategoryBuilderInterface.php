<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Product;

use GlobyApp\Zettle\API\Product\Category;
use GlobyApp\Zettle\API\Product\CategoryCollection;
use GlobyApp\Zettle\Client\Universal\BuilderInterface;

interface CategoryBuilderInterface extends BuilderInterface
{
    /**
     * @return Category[]
     */
    public function buildFromJson(string $json): array;

    public function buildFromArray($categories): CategoryCollection;
}
