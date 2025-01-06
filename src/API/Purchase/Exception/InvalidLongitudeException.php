<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\API\Purchase\Exception;

use Exception;
use GlobyApp\Zettle\Exception\IzettleApiException;

final class InvalidLongitudeException extends Exception implements IzettleApiException
{
}
