<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Exception;

use Exception;
use GlobyApp\Zettle\Exception\IzettleApiException;
use RuntimeException;

final class AccessTokenNotRefreshableException extends RuntimeException implements IzettleApiException
{
}
