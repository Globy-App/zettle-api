<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Tests\Integration\Client;

use GlobyApp\Zettle\API\Image;
use GlobyApp\Zettle\Client\Image\ImageUrlUpload;
use GlobyApp\Zettle\IzettleClientFactory;

/**
 * @medium
 */
final class ImageClientTest extends AbstractClientTest
{
    /**
     * @test
     */
    public function getPurchaseHistory(): void
    {
        $json = file_get_contents(__DIR__ . '/files/ImageClient/postImage.json');
        $data = json_decode($json, true);
        $iZettleClient = $this->getGuzzleIzettleClient(200, $json);
        $imageClient = IzettleClientFactory::getImageClient($iZettleClient);

        $image = $imageClient->postImage(new ImageUrlUpload(''));

        $this->assertInstanceOf(Image::class, $image);
    }
}
