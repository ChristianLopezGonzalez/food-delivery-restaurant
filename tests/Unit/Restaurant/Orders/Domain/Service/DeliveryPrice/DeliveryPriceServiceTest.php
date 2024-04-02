<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Orders\Domain\Service\DeliveryPrice;

use App\Restaurant\Deliveries\Application\DeliveryResponse;
use App\Restaurant\Orders\Domain\Service\DeliveryPrice\DeliveryPriceService;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Bus\Response;
use App\Shared\Domain\ValueObject\Price;
use App\Tests\Unit\Restaurant\Deliveries\Domain\DeliveryMother;
use App\Tests\Unit\Shared\Infrastructure\PHPUnit\UnitTestCase;
use Mockery;

final class DeliveryPriceServiceTest extends UnitTestCase
{
    /** @test */
    public function it_should_return_delivery_price(): void
    {
        $delivery = DeliveryMother::create();
        $response = DeliveryResponse::fromDelivery($delivery);
        $handler = new DeliveryPriceService(
            $this->getBusMock($response),
        );
        $price = $handler->__invoke();

        $this->assertInstanceOf(Price::class, $price);
        $this->assertEquals($delivery->price()->value(), $price->value());
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
