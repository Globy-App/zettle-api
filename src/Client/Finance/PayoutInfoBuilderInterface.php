<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Finance;

use GlobyApp\Zettle\API\Finance\PayoutInfo;

interface PayoutInfoBuilderInterface
{
    public function buildFromJson(string $json): PayoutInfo;
}
