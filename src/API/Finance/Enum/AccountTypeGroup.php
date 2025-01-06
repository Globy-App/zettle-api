<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\API\Finance\Enum;

enum AccountTypeGroup: string
{
    case LIQUID = 'LIQUID';
    case PRELIMINARY = 'PRELIMINARY';
}
