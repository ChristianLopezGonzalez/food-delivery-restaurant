<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Orders\Application\SearchAllWithDelivery;

use App\Restaurant\Orders\Application\OrderResponses;
use App\Restaurant\Orders\Application\SearchAllWithDelivery\SearchAllOrdersWithDelivery;
use App\Restaurant\Orders\Application\SearchAllWithDelivery\SearchAllOrdersWithDeliveryQuery;
use App\Restaurant\Orders\Application\SearchAllWithDelivery\SearchAllOrdersWithDeliveryQueryHandler;
use App\Restaurant\Orders\Domain\AbstractOrder;
use App\Restaurant\Orders\Domain\OrderRepository;
use App\Tests\Unit\Restaurant\Orders\Domain\OrderMother;
use App\Tests\Unit\Shared\Domain\MotherCreator;
use App\Tests\Unit\Shared\Infrastructure\PHPUnit\UnitTestCase;
use Mockery;

final class SearchAllOrdersWithDeliveryQueryHandlerTest extends UnitTestCase
{
    /** @test */
    public function it_should_search_all_orders_with_delivery(): void
    {
        $ordersQty = MotherCreator::random()->numberBetween(0, 10);
        $orders = [];
        for ($i = 1; $i <= $ordersQty; $i++) {
            $orders[] = OrderMother::createWithDelivery();
        }

        $query = new SearchAllOrdersWithDeliveryQuery();
        $handler = new SearchAllOrdersWithDeliveryQueryHandler(
            new SearchAllOrdersWithDelivery(
                $this->getOrderRepositoryMock(...$orders),
            ),
        );
        $response = $handler->__invoke($query);

        $this->assertInstanceOf(OrderResponses::class, $response);
        $this->assertEquals($ordersQty, count($response->orders));
    }

    private function getOrderRepositoryMock(AbstractOrder ...$orders): OrderRepository
    {
        return Mockery::mock(OrderRepository::class)
            ->expects('searchOrderByType')
            ->withAnyArgs()
            ->andReturn($orders)
            ->getMock();
    }
}
