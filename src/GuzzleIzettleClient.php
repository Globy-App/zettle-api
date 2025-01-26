<?php

declare(strict_types=1);

namespace GlobyApp\Zettle;

use GlobyApp\Zettle\API\Universal\IzettlePostable;
use GlobyApp\Zettle\Client\ApiScope;
use GlobyApp\Zettle\Client\Exception\AccessTokenExpiredException;
use GlobyApp\Zettle\Client\Exception\AccessTokenNotRefreshableException;
use GlobyApp\Zettle\Client\Exception\GuzzleClientExceptionHandler;
use GlobyApp\Zettle\Data\AccessToken;
use GlobyApp\Zettle\Exception\UnprocessableEntityException;
use GlobyApp\Zettle\Traits\DeserializerTrait;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

class GuzzleIzettleClient implements IzettleClientInterface
{
    use DeserializerTrait;

    /**
     * @var ClientInterface|Client
     */
    private $guzzleClient;

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var AccessToken
     */
    private $accessToken;

    public function __construct(ClientInterface $guzzleClient, string $clientId, string $clientSecret)
    {
        $this->guzzleClient = $guzzleClient;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function setAccessToken(AccessToken $accessToken): void
    {
        $this->accessToken = $accessToken;
        $this->validateAccessToken();
    }

    public function authoriseUserLogin(string $redirectUrl, ApiScope $apiScope): string
    {
        $url = self::API_AUTHORIZE_USER_LOGIN_URL;
        $url .= '?response_type=code';
        $url .= '&redirect_uri=' . $redirectUrl;
        $url .= '&client_id=' . $this->clientId;
        $url .= '&scope=' . $apiScope->getUrlParameters();
        $url .= '&state=oauth2';

        return $url;
    }

    public function getAccessTokenFromAuthorizedCode(string $redirectUrl, string $code): AccessToken
    {
        $options = [
            'form_params' => [
                'grant_type' => self::API_ACCESS_TOKEN_CODE_GRANT,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $redirectUrl,
                'code' => $code,
            ],
        ];

        try {
            $this->setAccessToken($this->requestAccessToken(self::API_ACCESS_TOKEN_REQUEST_URL, $options));
        } catch (ClientException $exception) {
            GuzzleClientExceptionHandler::handleClientException($exception);
        }

        return $this->accessToken;
    }

    public function getAccessTokenFromUserLogin(string $username, string $password): AccessToken
    {
        $options = [
            'form_params' => [
                'grant_type' => self::API_ACCESS_TOKEN_PASSWORD_GRANT,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'username' => $username,
                'password' => $password,
            ],
        ];

        try {
            $this->setAccessToken($this->requestAccessToken(self::API_ACCESS_TOKEN_REQUEST_URL, $options));
        } catch (ClientException $exception) {
            GuzzleClientExceptionHandler::handleClientException($exception);
        }

        return $this->accessToken;
    }

    // TODO: move this to an actual factory
    public function getAccessTokenFromApiTokenAssertion(string $assertion): AccessToken
    {
        $options = [
            'form_params' => [
                'grant_type' => self::API_ACCESS_ASSERTION_GRANT,
                'client_id' => $this->clientId,
                'assertion' => $assertion,
            ],
        ];

        try {
            $this->setAccessToken($this->requestAccessToken(self::API_ACCESS_TOKEN_REQUEST_URL, $options));
        } catch (ClientException $exception) {
            GuzzleClientExceptionHandler::handleClientException($exception);
        }

        return $this->accessToken;
    }

    public function refreshAccessToken(?AccessToken $accessToken =  null): AccessToken
    {
        $accessToken ??= $this->accessToken;

        $refreshToken = $accessToken->getRefreshToken();
        if ($refreshToken === null) {
            throw new AccessTokenNotRefreshableException('This access token cannot be renewed.');
        }

        $options = [
            'form_params' => [
                'grant_type' => self::API_ACCESS_TOKEN_REFRESH_TOKEN_GRANT,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $refreshToken,
            ],
        ];

        $this->setAccessToken($this->requestAccessToken(self::API_ACCESS_TOKEN_REFRESH_TOKEN_URL, $options));

        return $this->accessToken;
    }

    public function get(string $url, ?array $queryParameters = null): ResponseInterface
    {
        $options =  array_merge(['headers' => $this->getAuthorizationHeader()], ['query' => $queryParameters]);

        try {
            $response = $this->guzzleClient->get($url, $options);
        } catch (RequestException $exception) {
            GuzzleClientExceptionHandler::handleRequestException($exception);
        }

        return $response;
    }

    /**
     * @throws UnprocessableEntityException
     */
    public function post(string $url, IzettlePostable $postable): ResponseInterface
    {
        $headers = array_merge(
            $this->getAuthorizationHeader(),
            [
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ]
        );

        $options =  array_merge(['headers' => $headers], ['body' => $postable->getPostBodyData()]);

        try {
            return $this->guzzleClient->post($url, $options);
        } catch (ClientException $exception) {
            throw new UnprocessableEntityException($exception->getResponse()->getBody()->getContents());
        }
    }

    /**
     * @throws UnprocessableEntityException
     */
    public function put(string $url, string $jsonData): void
    {
        $headers = array_merge(
            $this->getAuthorizationHeader(),
            [
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ]
        );

        $options =  array_merge(['headers' => $headers], ['body' => $jsonData]);

        try {
            $this->guzzleClient->put($url, $options);
        } catch (ClientException $exception) {
            throw new UnprocessableEntityException($exception->getResponse()->getBody()->getContents());
        }
    }

    /**
     * @throws UnprocessableEntityException
     */
    public function delete(string $url): void
    {
        try {
            $this->guzzleClient->delete($url, ['headers' => $this->getAuthorizationHeader()]);
        } catch (ClientException $exception) {
            throw new UnprocessableEntityException($exception->getResponse()->getBody()->getContents());
        }
    }

    public function getJson(ResponseInterface $response): string
    {
        return $response->getBody()->getContents();
    }

    private function getAuthorizationHeader(): array
    {
        $this->validateAccessToken();

        return ['Authorization' => sprintf('Bearer %s', $this->accessToken->getToken())];
    }

    private function validateAccessToken(): void
    {
        if ($this->accessToken->isExpired()) {
            throw new AccessTokenExpiredException(sprintf('Access Token was valid till \'%s\' it\'s now \'%s\'', $this->accessToken->getExpiry()->format('Y-m-d H:i:s.u'), (new \DateTime())->format('Y-m-d H:i:s.u')));
        }
    }

    /**
     * @throws GuzzleException
     */
    private function requestAccessToken($url, $options): AccessToken
    {
        $headers = ['headers' => ['Content-Type' => 'application/x-www-form-urlencoded']];
        $options = array_merge($headers, $options);

        $response = $this->guzzleClient->post($url, $options);
        var_dump($response->getBody()->getContents());

        // Deserialize the response from the zettle API to an AccessToken object
        return $this->deserializeJson($response->getBody()->getContents(), AccessToken::class);
    }
}
