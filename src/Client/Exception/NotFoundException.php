<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Exception;

use Exception;
use GlobyApp\Zettle\Exception\IzettleApiException;

class NotFoundException extends Exception implements IzettleApiException
{
}
