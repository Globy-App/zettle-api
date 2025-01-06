<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Client\Purchase;

use GlobyApp\Zettle\API\Purchase\PurchaseHistory;
use Psr\Http\Message\ResponseInterface;

final class PurchaseHistoryBuilder implements PurchaseHistoryBuilderInterface
{
    private $purchaseBuilder;

    public function __construct(PurchaseBuilderInterface $purchaseBuilder)
    {
        $this->purchaseBuilder = $purchaseBuilder;
    }

    public function buildFromJson(string $jsonData): PurchaseHistory
    {
        $data =  json_decode($jsonData, true);

        return new PurchaseHistory(
            $data['firstPurchaseHash'],
            $data['lastPurchaseHash'],
            $this->purchaseBuilder->buildFromArray($data['purchases'])
        );
    }
}
