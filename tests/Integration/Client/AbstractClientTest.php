<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Tests\Integration\Client;

use DateTimeImmutable;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GlobyApp\Zettle\Client\AccessToken;
use GlobyApp\Zettle\GuzzleIzettleClient;
use PHPUnit\Framework\TestCase;

abstract class AbstractClientTest extends TestCase
{
    public const CLIENT_ID = 'clientId';
    public const CLIENT_SECRET = 'clientSecret';

    protected function getGuzzleIzettleClient(int $status, string $body): GuzzleIzettleClient
    {
        $mock = new MockHandler([new Response($status, [], $body)]);
        $handler = HandlerStack::create($mock);

        $izettleClient = new GuzzleIzettleClient(new GuzzleClient(['handler' => $handler]), self::CLIENT_ID, self::CLIENT_SECRET);
        $izettleClient->setAccessToken($this->getAccessToken());

        return $izettleClient;
    }

    private function getAccessToken(): AccessToken
    {
        return new AccessToken('', new DateTimeImmutable('+ 1 day'), '');
    }
}
