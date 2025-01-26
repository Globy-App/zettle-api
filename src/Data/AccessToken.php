<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Data;

use Symfony\Component\Serializer\Attribute\SerializedPath;

final class AccessToken
{
    #[SerializedPath('[access_token]')]
    private string $accessToken;

    /* Although this field is never read, it is required for deserialization */
    #[SerializedPath('[expires_in]')]
    private int $expiresIn;

    private \DateTimeImmutable $expiry;

    #[SerializedPath('[refresh_token]')]
    private ?string $refreshToken;

    /**
     * @throws \LogicException should not happen, as the date modify string is always properly formatted
     */
    public function __construct(string $accessToken, int $expiresIn, ?string $refreshToken)
    {
        $this->accessToken = $accessToken;
        $this->expiresIn = $expiresIn;

        try {
            $this->expiry = (new \DateTimeImmutable())->modify('+' . $expiresIn . ' seconds');
        } catch (\DateMalformedStringException) {
            throw new \LogicException('Date modification in AccessToken failed.');
        }
        $this->refreshToken = $refreshToken;
    }

    public function getToken(): string
    {
        return $this->accessToken;
    }

    public function getExpiry(): \DateTimeImmutable
    {
        return $this->expiry;
    }

    public function isExpired(): bool
    {
        return (new \DateTime()) > $this->expiry;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }
}
