<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Purchase;

use GlobyApp\Zettle\API\Purchase\Purchase;

interface PurchaseBuilderInterface
{
    public function buildFromArray(array $purchases): array;
    public function buildFromJson(string $jsonData): Purchase;
}
