<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Exception\InvalidGrant;

use GlobyApp\Zettle\Client\Exception\InvalidGrantException;

final class TooManyFailedAttemptsException extends InvalidGrantException
{
}
