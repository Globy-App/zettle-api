<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Purchase;

use GlobyApp\Zettle\API\Purchase\Coordinates;

final class CoordinatesBuilder implements CoordinatesBuilderInterface
{
    public function buildFromArray(array $coordinates): Coordinates
    {
        return new Coordinates($coordinates['latitude'], $coordinates['longitude'], $coordinates['accuracyMeters']);
    }
}
