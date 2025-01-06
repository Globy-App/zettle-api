<?php

declare(strict_types=1);

namespace GlobyApp\Zettle\Tests\Unit\Client;

use GlobyApp\Zettle\API\ImageCollection;
use GlobyApp\Zettle\API\Product\Category;
use GlobyApp\Zettle\API\Product\CategoryCollection;
use GlobyApp\Zettle\API\Product\Discount;
use GlobyApp\Zettle\API\Product\DiscountCollection;
use GlobyApp\Zettle\API\Product\Library;
use GlobyApp\Zettle\API\Product\Product;
use GlobyApp\Zettle\API\Product\ProductCollection;
use GlobyApp\Zettle\API\Product\Variant;
use GlobyApp\Zettle\API\Product\VariantCollection;
use GlobyApp\Zettle\Client\Product\CategoryBuilderInterface;
use GlobyApp\Zettle\Client\Product\DiscountBuilderInterface;
use GlobyApp\Zettle\Client\Product\LibraryBuilderInterface;
use GlobyApp\Zettle\Client\Product\ProductBuilderInterface;
use GlobyApp\Zettle\Client\ProductClient;
use GlobyApp\Zettle\IzettleClientInterface;
use GlobyApp\Zettle\Tests\Unit\MockeryAssertionTrait;
use Mockery;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

/**
 * @small
 */
final class ProductClientTest extends AbstractClientTest
{
    /**
     * @test
     */
    public function getCategories(): void
    {
        $organizationUuid = Uuid::uuid1();
        $url = sprintf(ProductClient::GET_CATEGORIES, (string) $organizationUuid);
        $data = ['getCategoriesTest'];

        $izettleClientMock = $this->getIzettleGetMock($url, $data);

        [$categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock] = $this->getDependencyMocks();

        $categoryBuilderMock->shouldReceive('buildFromJson')->with(json_encode($data))->once();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );
        $productClient->getCategories();
    }

    /**
     * @test
     */
    public function createCategory(): void
    {
        $organizationUuid = Uuid::uuid1();
        $category = Category::new('name');
        $url = sprintf(ProductClient::POST_CATEGORY, (string) $organizationUuid);

        $izettleClientMock = $this->getIzettlePostMock($url, $category);
        [$categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock] = $this->getDependencyMocks();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->createCategory($category);
    }

    /**
     * @test
     */
    public function getDiscounts(): void
    {
        $organizationUuid = Uuid::uuid1();
        $data = ['getDiscountsTest'];
        $url = sprintf(ProductClient::GET_DISCOUNTS, $organizationUuid);

        $izettleClientMock = $this->getIzettleGetMock($url, $data);
        [$categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock] = $this->getDependencyMocks();
        $discountBuilderMock->shouldReceive('buildFromJson')->with(json_encode($data))->once();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->getDiscounts();
    }

    /**
     * @test
     */
    public function createDiscount(): void
    {
        $discount = $this->getDiscount();
        $organizationUuid = Uuid::uuid1();
        $url = sprintf(ProductClient::POST_DISCOUNT, (string) $organizationUuid);

        $izettleClientMock = $this->getIzettlePostMock($url, $discount);
        [$categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock] = $this->getDependencyMocks();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->createDiscount($discount);
    }

    /**
     * @test
     */
    public function deleteDiscount(): void
    {
        $discount = $this->getDiscount();
        $organizationUuid = Uuid::uuid1();
        $url = sprintf(ProductClient::DELETE_DISCOUNT, (string) $organizationUuid, (string) $discount->getUuid());

        $izettleClientMock = $this->getIzettleDeleteMock($url);
        [$categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock] = $this->getDependencyMocks();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->deleteDiscount($discount);
    }

    /**
     * @test
     */
    public function getLibrary(): void
    {
        $organizationUuid = Uuid::uuid1();
        $data = ['getLibraryTest'];
        $url = sprintf(ProductClient::GET_LIBRARY, $organizationUuid);

        $izettleClientMock = $this->getIzettleGetMock($url, $data);
        [$categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock] = $this->getDependencyMocks();
        $libraryBuilderMock->shouldReceive('buildFromJson')->with(json_encode($data))->once()
            ->andReturn(new Library(
                Uuid::uuid1(),
                Uuid::uuid1(),
                new ProductCollection(),
                new DiscountCollection(),
                new ProductCollection(),
                new DiscountCollection()
            ));

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->getLibrary();
    }

    /**
     * @test
     */
    public function getProducts(): void
    {
        $organizationUuid = Uuid::uuid1();
        $data = ['getProductsTest'];
        $url = sprintf(ProductClient::GET_PRODUCTS, $organizationUuid);

        $izettleClientMock = $this->getIzettleGetMock($url, $data);
        [$categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock] = $this->getDependencyMocks();
        $productBuilderMock->shouldReceive('buildFromJson')->with(json_encode($data))->once();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->getProducts();
    }

    /**
     * @test
     */
    public function createProduct(): void
    {
        $product = $this->getProduct();
        $organizationUuid = Uuid::uuid1();
        $url = sprintf(ProductClient::POST_PRODUCT, (string) $organizationUuid);

        $izettleClientMock = $this->getIzettlePostMock($url, $product);
        [$categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock] = $this->getDependencyMocks();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->createProduct($product);
    }

    /**
     * @test
     */
    public function deleteProduct(): void
    {
        $product = $this->getProduct();
        $organizationUuid = Uuid::uuid1();
        $url = sprintf(ProductClient::DELETE_PRODUCT, (string) $organizationUuid, (string) $product->getUuid());

        $izettleClientMock = $this->getIzettleDeleteMock($url);
        [$categoryBuilderMock, $discountBuilderMock, $libraryBuilderMock, $productBuilderMock] = $this->getDependencyMocks();

        $productClient = new ProductClient(
            $izettleClientMock,
            $organizationUuid,
            $categoryBuilderMock,
            $discountBuilderMock,
            $libraryBuilderMock,
            $productBuilderMock
        );

        $productClient->deleteProduct($product);
    }

    private function getDiscount(): Discount
    {
        return Discount::new('name', 'description', new ImageCollection());
    }

    private function getProduct(): Product
    {
        return Product::new(
            'name',
            'description',
            new CategoryCollection(),
            new ImageCollection(),
            new VariantCollection([ Variant::new(
                null,
                null,
                null,
                null,
                1,
                null,
                Money::EUR(0),
                null,
                21
            )])
        );
    }

    protected function getDependencyMocks(): array
    {
        return [
            Mockery::mock(CategoryBuilderInterface::class),
            Mockery::mock(DiscountBuilderInterface::class),
            Mockery::mock(LibraryBuilderInterface::class),
            Mockery::mock(ProductBuilderInterface::class),
        ];
    }
}
