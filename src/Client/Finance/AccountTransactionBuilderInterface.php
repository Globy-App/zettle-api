<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Finance;

use GlobyApp\Zettle\API\Finance\AccountTransaction;

interface AccountTransactionBuilderInterface
{
    /**
     * @return AccountTransaction[]
     */
    public function buildFromJson(string $json);
}
