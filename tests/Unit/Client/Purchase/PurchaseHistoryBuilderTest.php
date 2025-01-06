<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Tests\Unit\Client\Purchase;

use GlobyApp\Zettle\API\Purchase\PurchaseHistory;
use GlobyApp\Zettle\Client\Purchase\PurchaseBuilderInterface;
use GlobyApp\Zettle\Client\Purchase\PurchaseHistoryBuilder;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * @small
 */
final class PurchaseHistoryBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function createFromResponse(): void
    {
        $data = [
            'firstPurchaseHash' => $firstPurchaseHash = 'hash1',
            'lastPurchaseHash' => $lastPurchaseHash = 'hash2',
            'purchases' => ['createFromResponseTest'],
        ];

        $purchaseBuilderMock = Mockery::mock(PurchaseBuilderInterface::class);
        $purchaseBuilderMock->shouldReceive('buildFromArray')->once()->with($data['purchases'])->andReturn([]);

        $builder = new PurchaseHistoryBuilder($purchaseBuilderMock);
        $purchaseHistory = $builder->buildFromJson(json_encode($data));

        $this->assertInstanceOf(PurchaseHistory::class, $purchaseHistory);
        $this->assertSame($firstPurchaseHash, $purchaseHistory->getFirstPurchaseHash());
        $this->assertSame($lastPurchaseHash, $purchaseHistory->getLastPurchaseHash());
    }
}
