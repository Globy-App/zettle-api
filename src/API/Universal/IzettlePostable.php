<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\API\Universal;

interface IzettlePostable
{
    public function getPostBodyData(): string;
}
