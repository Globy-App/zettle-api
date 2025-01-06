<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Purchase\Exception;

use Exception;
use GlobyApp\Zettle\Exception\IzettleApiException;

final class PaymentTypeNotConfiguredException extends Exception implements IzettleApiException
{
}
