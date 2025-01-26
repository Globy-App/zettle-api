<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Tests\Unit\Data;

use GlobyApp\Zettle\Data\AccessToken;
use GlobyApp\Zettle\Traits\DeserializerTrait;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class AccessTokenTest extends TestCase
{
    use DeserializerTrait;

    /**
     * @throws \LogicException
     */
    public function testIsExpired(): void
    {
        $accessToken = new AccessToken('', -200, '');

        $this->assertTrue($accessToken->isExpired());
    }

    /**
     * @throws \LogicException
     */
    public function testIsJustExpired(): void
    {
        $accessToken = new AccessToken('', 0, '');

        $this->assertTrue($accessToken->isExpired());
    }

    /**
     * @throws \LogicException
     */
    public function testDeserialization(): void
    {
        $json = '{"access_token": "fyuqhw87fhqwfjqiuwfhq987hf298h", "expires_in": 7200, "refresh_token": "fqwjuifwhqu9fhq98fh9u"}';
        $expected = new AccessToken('fyuqhw87fhqwfjqiuwfhq987hf298h', 7200, 'fqwjuifwhqu9fhq98fh9u');
        $deserialized = $this->deserializeJson($json, AccessToken::class);

        // Assert that the timestamp of both objects is within 5 seconds, this should be plenty of wiggle room
        $this->assertEqualsWithDelta($expected->getExpiry()->getTimestamp(), $deserialized->getExpiry()->getTimestamp(), 5);
        $this->assertEquals($expected->getToken(), $deserialized->getToken());
        $this->assertEquals($expected->getRefreshToken(), $deserialized->getRefreshToken());
        $this->assertFalse($deserialized->isExpired());
        $this->assertEquals($expected->isExpired(), $deserialized->isExpired());
    }
}
