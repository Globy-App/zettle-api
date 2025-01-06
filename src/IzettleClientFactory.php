<?php

declare(strict_types=1);

namespace GlobyApp\Zettle;

use GlobyApp\Zettle\Client\Finance\AccountTransactionBuilder;
use GlobyApp\Zettle\Client\Finance\PayoutInfoBuilder;
use GlobyApp\Zettle\Client\FinanceClient;
use GlobyApp\Zettle\Client\ImageClient;
use GlobyApp\Zettle\Client\Product\CategoryBuilder;
use GlobyApp\Zettle\Client\Product\DiscountBuilder;
use GlobyApp\Zettle\Client\Product\LibraryBuilder;
use GlobyApp\Zettle\Client\Product\ProductBuilder as ProductProductBuilder;
use GlobyApp\Zettle\Client\Product\VariantBuilder;
use GlobyApp\Zettle\Client\ProductClient;
use GlobyApp\Zettle\Client\Purchase\CoordinatesBuilder;
use GlobyApp\Zettle\Client\Purchase\PaymentBuilder;
use GlobyApp\Zettle\Client\Purchase\ProductBuilder as PurchaseProductBuilder;
use GlobyApp\Zettle\Client\Purchase\PurchaseBuilder;
use GlobyApp\Zettle\Client\Purchase\PurchaseHistoryBuilder;
use GlobyApp\Zettle\Client\Purchase\VatBuilder;
use GlobyApp\Zettle\Client\PurchaseClient;
use GlobyApp\Zettle\Client\Universal\ImageBuilder;
use Ramsey\Uuid\UuidInterface;

final class IzettleClientFactory
{
    public static function getProductClient(IzettleClientInterface $client, ?UuidInterface $organizationUuid = null): ProductClient
    {
        $categoryBuilder = new CategoryBuilder();
        $imageBuilder = new ImageBuilder();
        $variantBuilder = new VariantBuilder();
        $discountBuilder = new DiscountBuilder($imageBuilder);
        $productBuilder = new ProductProductBuilder($categoryBuilder, $imageBuilder, $variantBuilder);
        $libraryBuilder = new LibraryBuilder($productBuilder, $discountBuilder);

        return new ProductClient(
            $client,
            $organizationUuid,
            $categoryBuilder,
            $discountBuilder,
            $libraryBuilder,
            $productBuilder
        );
    }

    public static function getPurchaseClient(IzettleClientInterface $client): PurchaseClient
    {
        $coordinatesBuilder = new CoordinatesBuilder();
        $purchaseProductBuilder = new PurchaseProductBuilder();
        $paymentBuilder = new PaymentBuilder();
        $vatBuilder = new VatBuilder();
        $purchaseBuilder = new PurchaseBuilder($coordinatesBuilder, $purchaseProductBuilder, $paymentBuilder, $vatBuilder);
        $purchaseHistoryBuilder = new PurchaseHistoryBuilder($purchaseBuilder);

        return new PurchaseClient(
            $client,
            $purchaseHistoryBuilder,
            $purchaseBuilder
        );
    }

    public static function getFinanceClient(IzettleClientInterface $client, ?UuidInterface $organizationUuid = null): FinanceClient
    {
        return new FinanceClient(
            $client,
            $organizationUuid,
            new AccountTransactionBuilder(),
            new PayoutInfoBuilder()
        );
    }

    public static function getImageClient(IzettleClientInterface $client, ?UuidInterface $organizationUuid = null): ImageClient
    {
        return new ImageClient($client, $organizationUuid, new ImageBuilder());
    }
}
