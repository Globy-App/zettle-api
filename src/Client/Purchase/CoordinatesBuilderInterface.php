<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Purchase;

use GlobyApp\Zettle\API\Purchase\Coordinates;

interface CoordinatesBuilderInterface
{
    public function buildFromArray(array $coordinates): Coordinates;
}
