<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Purchase;

use GlobyApp\Zettle\API\Purchase\PurchaseHistory;

interface PurchaseHistoryBuilderInterface
{
    public function buildFromJson(string $jsonData): PurchaseHistory;
}
