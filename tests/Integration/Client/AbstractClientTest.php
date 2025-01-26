<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Tests\Integration\Client;

use GlobyApp\Zettle\Data\AccessToken;
use GlobyApp\Zettle\GuzzleIzettleClient;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

abstract class AbstractClientTest extends TestCase
{
    public const string CLIENT_ID = 'clientId';
    public const string CLIENT_SECRET = 'clientSecret';

    protected function getGuzzleIzettleClient(int $status, string $body): GuzzleIzettleClient
    {
        $mock = new MockHandler([new Response($status, [], $body)]);
        $handler = HandlerStack::create($mock);

        $izettleClient = new GuzzleIzettleClient(new GuzzleClient(['handler' => $handler]), self::CLIENT_ID, self::CLIENT_SECRET);
        $izettleClient->setAccessToken($this->getAccessToken());

        return $izettleClient;
    }

    /**
     * @throws \LogicException
     */
    private function getAccessToken(): AccessToken
    {
        return new AccessToken('', 84600, '');
    }
}
