<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Products\Application\FindByCode;

use App\Restaurant\Products\Application\FindByCode\FindByProductCode;
use App\Restaurant\Products\Application\FindByCode\FindByProductCodeQuery;
use App\Restaurant\Products\Application\FindByCode\FindByProductCodeQueryHandler;
use App\Restaurant\Products\Application\ProductResponse;
use App\Restaurant\Products\Domain\Errors\ProductNotFound;
use App\Restaurant\Products\Domain\Product;
use App\Restaurant\Products\Domain\ProductCode;
use App\Restaurant\Products\Domain\ProductRepository;
use App\Tests\Unit\Restaurant\Products\Domain\ProductCodeMother;
use App\Tests\Unit\Restaurant\Products\Domain\ProductMother;
use App\Tests\Unit\Shared\Infrastructure\PHPUnit\UnitTestCase;
use Mockery;

final class FindByCodeQueryHandlerTest extends UnitTestCase
{
    /** @test */
    public function it_should_find_product_by_code(): void
    {
        $productCode = ProductCodeMother::create();
        $product = ProductMother::create(code: $productCode->value());

        $query = new FindByProductCodeQuery($productCode->value());
        $handler = new FindByProductCodeQueryHandler(
            new FindByProductCode(
                $this->getProductRepositoryMock($productCode, $product),
            ),
        );
        $response = $handler->__invoke($query);

        $this->assertInstanceOf(ProductResponse::class, $response);
        $this->assertEquals($response->code, $product->code()->value());
        $this->assertEquals($response->amount, $product->price()->value());
        $this->assertEquals($response->currency, $product->price()->currency()->value());
    }

    /** @test */
    public function it_should_throw_ProductNotFound_error(): void
    {
        $this->expectException(ProductNotFound::class);

        $productCode = ProductCodeMother::create();

        $query = new FindByProductCodeQuery($productCode->value());
        $handler = new FindByProductCodeQueryHandler(
            new FindByProductCode(
                $this->getProductRepositoryMock($productCode, null),
            ),
        );

        $handler->__invoke($query);
    }

    private function getProductRepositoryMock(ProductCode $productCode, ?Product $product): ProductRepository
    {
        return Mockery::mock(ProductRepository::class)
            ->expects('searchByCode')
            ->with($this->similarTo($productCode))
            ->andReturn($product)
            ->getMock();
    }
}
