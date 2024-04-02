<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Orders\Domain\Service\ProductPrice;

use App\Restaurant\Orders\Domain\Service\DeliveryPrice\DeliveryPriceService;
use App\Restaurant\Products\Application\ProductResponse;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Bus\Response;
use App\Shared\Domain\ValueObject\Price;
use App\Tests\Unit\Restaurant\Products\Domain\ProductMother;
use App\Tests\Unit\Shared\Infrastructure\PHPUnit\UnitTestCase;
use Mockery;

final class ProductPriceServiceTest extends UnitTestCase
{
    /** @test */
    public function it_should_return_product_price(): void
    {
        $product = ProductMother::create();
        $response = ProductResponse::fromProduct($product);
        $handler = new DeliveryPriceService(
            $this->getBusMock($response),
        );
        $price = $handler->__invoke();

        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals($product->price()->value(), $price->value());
    }

    private function getBusMock(Response $response): QueryBus
    {
        return Mockery::mock(QueryBus::class)
            ->expects('run')
            ->withAnyArgs()
            ->andReturn($response)
            ->getMock();
    }
}
