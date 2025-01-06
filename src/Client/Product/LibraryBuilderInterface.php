<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Product;

use GlobyApp\Zettle\API\Product\Library;

interface LibraryBuilderInterface
{
    public function buildFromJson(string $json): Library;
}
