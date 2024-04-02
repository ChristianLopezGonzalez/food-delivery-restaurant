<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Deliveries\Application\CalculateRate;

use App\Restaurant\Deliveries\Application\CalculateRate\CalculateDeliveryRate;
use App\Restaurant\Deliveries\Application\CalculateRate\CalculateDeliveryRateQuery;
use App\Restaurant\Deliveries\Application\CalculateRate\CalculateDeliveryRateQueryHandler;
use App\Restaurant\Deliveries\Application\DeliveryResponse;
use App\Tests\Unit\Restaurant\Deliveries\Domain\DeliveryMother;
use App\Tests\Unit\Shared\Infrastructure\PHPUnit\UnitTestCase;

final class CalculateDeliveryRateQueryHandlerTest extends UnitTestCase
{
    /** @test */
    public function it_should_calculate_a_delivery_rate(): void
    {
        $expectedRate = DeliveryMother::create(150);

        $query = new CalculateDeliveryRateQuery();
        $handler = new CalculateDeliveryRateQueryHandler(
            new CalculateDeliveryRate(),
        );
        $response = $handler->__invoke($query);

        $this->assertInstanceOf(DeliveryResponse::class, $response);
        $this->assertEquals($response->amount, $expectedRate->price()->value());
        $this->assertEquals($response->currency, $expectedRate->price()->currency()->value());
    }
}
