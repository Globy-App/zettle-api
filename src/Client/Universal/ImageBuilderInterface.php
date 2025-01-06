<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Universal;

use GlobyApp\Zettle\API\Image;
use GlobyApp\Zettle\API\ImageCollection;

interface ImageBuilderInterface extends BuilderInterface
{
    public function buildFromArray(array $images): ImageCollection;

    public function buildFromJson(string $json): Image;
}
