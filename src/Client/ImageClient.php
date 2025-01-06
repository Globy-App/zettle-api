<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client;

use GlobyApp\Zettle\API\Image;
use GlobyApp\Zettle\Client\Image\ImageUploadRequestInterface;
use GlobyApp\Zettle\Client\Universal\ImageBuilderInterface;
use GlobyApp\Zettle\IzettleClientInterface;
use Ramsey\Uuid\UuidInterface;

final class ImageClient
{
    public const BASE_URL = 'https://image.izettle.com/organizations/%s';
    public const POST_IMAGE = self::BASE_URL . '/products';

    private $client;
    private $organizationUuid = 'self';
    private $imageBuilder;

    public function __construct(
        IzettleClientInterface $client,
        ?UuidInterface $organizationUuid,
        ImageBuilderInterface $imageBuilder
    ) {
        $this->client = $client;
        $this->organizationUuid = (string) $organizationUuid;
        $this->imageBuilder = $imageBuilder;
    }

    public function postImage(ImageUploadRequestInterface $imageUploadRequest): Image
    {
        $url = sprintf(self::POST_IMAGE, $this->organizationUuid);
        $response = $this->client->post($url, $imageUploadRequest);

        return $this->imageBuilder->buildFromJson($this->client->getJson($response));
    }
}
