<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Tests\Unit\Client;

use DateTimeImmutable;
use GlobyApp\Zettle\Client\AccessToken;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class AccessTokenTest extends TestCase
{
    /**
     * @test
     */
    public function isExpired(): void
    {
        $accessToken = new AccessToken('', new DateTimeImmutable(), '');

        $this->assertTrue($accessToken->isExpired());
    }
}
