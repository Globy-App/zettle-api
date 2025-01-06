<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Exception;

use Exception;
use GlobyApp\Zettle\Exception\IzettleApiException;

final class CantCreateProductException extends Exception implements IzettleApiException
{
}
