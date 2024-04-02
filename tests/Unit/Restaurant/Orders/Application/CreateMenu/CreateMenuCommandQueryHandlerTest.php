<?php

declare(strict_types=1);

namespace App\Tests\Unit\Restaurant\Orders\Application\CreateMenu;

use App\Restaurant\Orders\Application\CreateMenu\CreateMenuOrder;
use App\Restaurant\Orders\Application\CreateMenu\CreateMenuOrderCommandQuery;
use App\Restaurant\Orders\Application\CreateMenu\CreateMenuOrderCommandQueryHandler;
use App\Restaurant\Orders\Application\OrderResponse;
use App\Restaurant\Orders\Domain\AbstractOrder;
use App\Restaurant\Orders\Domain\Errors\DeliveryOrderRequiresEqualTotalPrice;
use App\Restaurant\Orders\Domain\Errors\LineItemAmountIsNotBetweenRequiredValues;
use App\Restaurant\Orders\Domain\Errors\PickUpOrderRequiresReachTotalPrice;
use App\Restaurant\Orders\Domain\Errors\RequiredConceptsNotFound;
use App\Restaurant\Orders\Domain\OrderRepository;
use App\Restaurant\Orders\Domain\Service\DeliveryPrice\DeliveryPriceService;
use App\Restaurant\Orders\Domain\Service\ProductPrice\ProductPriceService;
use App\Restaurant\Products\Domain\Product;
use App\Shared\Domain\ValueObject\Price;
use App\Tests\Unit\Restaurant\Orders\Domain\OrderMother;
use App\Tests\Unit\Restaurant\Products\Domain\ProductMother;
use App\Tests\Unit\Shared\Domain\MotherCreator;
use App\Tests\Unit\Shared\Domain\ValueObjets\PriceMother;
use App\Tests\Unit\Shared\Infrastructure\PHPUnit\UnitTestCase;
use Mockery;

final class CreateMenuCommandQueryHandlerTest extends UnitTestCase
{
    /** @test */
    public function it_should_create_menu_order(): void
    {
        $isDelivery = MotherCreator::random()->boolean();
        $drinksQty = MotherCreator::random()->numberBetween(0, 2);
        $food = ProductMother::create(code: 'pizza', price: 100);
        $drink = ProductMother::create(code: 'drink', price: 100);
        $orderLines = [
            ['concept' => $food->code()->value(), 'price' => $food->price()->value(), 'amount' => 1],
            ['concept' => $drink->code()->value(), 'price' => $drink->price()->value(), 'amount' => $drinksQty],
        ];

        $order = $isDelivery
            ? OrderMother::createWithDelivery(orderLines: $orderLines)
            : OrderMother::createWithPickup(orderLines: $orderLines);

        $query = new CreateMenuOrderCommandQuery(
            $order->id->value(),
            $food->code()->value(),
            $order->totalPrice()->toFloat(),
            $isDelivery,
            $drinksQty,
        );

        $handler = new CreateMenuOrderCommandQueryHandler(
            new CreateMenuOrder(
                $this->getProductPriceServiceMock($food, $drink),
                $this->getDeliveryPriceServiceMock($order->deliveryPrice()),
                $this->getOrderRepositoryMock($order),
            ),
        );
        $response = $handler->__invoke($query);

        $this->assertInstanceOf(OrderResponse::class, $response);
    }

    /** @test */
    public function it_should_throw_DeliveryOrderRequiresEqualTotalPrice_error(): void
    {
        $this->expectException(DeliveryOrderRequiresEqualTotalPrice::class);

        $isDelivery = true;
        $drinksQty = MotherCreator::random()->numberBetween(0, 2);
        $food = ProductMother::create(code: 'pizza', price: 100);
        $drink = ProductMother::create(code: 'drink', price: 100);
        $orderLines = [
            ['concept' => $food->code()->value(), 'price' => $food->price()->value(), 'amount' => 1],
            ['concept' => $drink->code()->value(), 'price' => $drink->price()->value(), 'amount' => $drinksQty],
        ];

        $order = OrderMother::createWithDelivery(orderLines: $orderLines);

        $query = new CreateMenuOrderCommandQuery(
            $order->id->value(),
            $food->code()->value(),
            $order->totalPrice()->add(PriceMother::create())->toFloat(),
            $isDelivery,
            $drinksQty,
        );

        $handler = new CreateMenuOrderCommandQueryHandler(
            new CreateMenuOrder(
                $this->getProductPriceServiceMock($food, $drink),
                $this->getDeliveryPriceServiceMock($order->deliveryPrice()),
                $this->getOrderRepositoryMock($order),
            ),
        );
        $response = $handler->__invoke($query);

        $this->assertInstanceOf(OrderResponse::class, $response);
    }

    /** @test */
    public function it_should_throw_PickUpOrderRequiresReachTotalPrice_error(): void
    {
        $this->expectException(PickUpOrderRequiresReachTotalPrice::class);

        $isDelivery = false;
        $drinksQty = MotherCreator::random()->numberBetween(0, 2);
        $food = ProductMother::create(code: 'pizza', price: 100);
        $drink = ProductMother::create(code: 'drink', price: 100);
        $orderLines = [
            ['concept' => $food->code()->value(), 'price' => $food->price()->value(), 'amount' => 1],
            ['concept' => $drink->code()->value(), 'price' => $drink->price()->value(), 'amount' => $drinksQty],
        ];

        $order = OrderMother::createWithDelivery(orderLines: $orderLines);

        $query = new CreateMenuOrderCommandQuery(
            $order->id->value(),
            $food->code()->value(),
            $order->totalPrice()->subtract(PriceMother::create(1))->toFloat(),
            $isDelivery,
            $drinksQty,
        );

        $handler = new CreateMenuOrderCommandQueryHandler(
            new CreateMenuOrder(
                $this->getProductPriceServiceMock($food, $drink),
                $this->getDeliveryPriceServiceMock($order->deliveryPrice()),
                $this->getOrderRepositoryMock($order),
            ),
        );
        $response = $handler->__invoke($query);

        $this->assertInstanceOf(OrderResponse::class, $response);
    }

    /** @test */
    public function it_should_throw_RequiredConceptsNotFound_error(): void
    {
        $this->expectException(RequiredConceptsNotFound::class);

        $isDelivery = MotherCreator::random()->boolean();
        $drinksQty = MotherCreator::random()->numberBetween(0, 2);
        $food = ProductMother::create(code: 'kebap', price: 100);
        $drink = ProductMother::create(code: 'drink', price: 100);
        $orderLines = [
            ['concept' => $food->code()->value(), 'price' => $food->price()->value(), 'amount' => 1],
            ['concept' => $drink->code()->value(), 'price' => $drink->price()->value(), 'amount' => $drinksQty],
        ];

        $order = $isDelivery
            ? OrderMother::createWithDelivery(orderLines: $orderLines)
            : OrderMother::createWithPickup(orderLines: $orderLines);

        $query = new CreateMenuOrderCommandQuery(
            $order->id->value(),
            $food->code()->value(),
            $order->totalPrice()->toFloat(),
            $isDelivery,
            $drinksQty,
        );

        $handler = new CreateMenuOrderCommandQueryHandler(
            new CreateMenuOrder(
                $this->getProductPriceServiceMock($food, $drink),
                $this->getDeliveryPriceServiceMock($order->deliveryPrice()),
                $this->getOrderRepositoryMock($order),
            ),
        );
        $handler->__invoke($query);
    }

    /** @test */
    public function it_should_throw_LineItemAmountIsNotBetweenRequiredValues_error(): void
    {
        $this->expectException(LineItemAmountIsNotBetweenRequiredValues::class);

        $isDelivery = MotherCreator::random()->boolean();
        $drinksQty = MotherCreator::random()->numberBetween(3, 10);
        $food = ProductMother::create(code: 'pizza', price: 100);
        $drink = ProductMother::create(code: 'drink', price: 100);
        $orderLines = [
            ['concept' => $food->code()->value(), 'price' => $food->price()->value(), 'amount' => 1],
            ['concept' => $drink->code()->value(), 'price' => $drink->price()->value(), 'amount' => $drinksQty],
        ];

        $order = $isDelivery
            ? OrderMother::createWithDelivery(orderLines: $orderLines)
            : OrderMother::createWithPickup(orderLines: $orderLines);

        $query = new CreateMenuOrderCommandQuery(
            $order->id->value(),
            $food->code()->value(),
            $order->totalPrice()->toFloat(),
            $isDelivery,
            $drinksQty,
        );

        $handler = new CreateMenuOrderCommandQueryHandler(
            new CreateMenuOrder(
                $this->getProductPriceServiceMock($food, $drink),
                $this->getDeliveryPriceServiceMock($order->deliveryPrice()),
                $this->getOrderRepositoryMock($order),
            ),
        );
        $handler->__invoke($query);
    }

    private function getProductPriceServiceMock(Product ...$products): ProductPriceService
    {
        $mock = Mockery::mock(ProductPriceService::class);

        foreach ($products as $product) {
            $mock = $mock->expects('__invoke')
                ->with($product->code()->value())
                ->andReturn($product->price())
                ->getMock();
        }

        return $mock;
    }

    private function getDeliveryPriceServiceMock(Price $price): DeliveryPriceService
    {
        return Mockery::mock(DeliveryPriceService::class)
            ->expects('__invoke')
            ->withNoArgs()
            ->andReturn($price)
            ->getMock();
    }

    private function getOrderRepositoryMock(AbstractOrder $order): OrderRepository
    {
        return Mockery::mock(OrderRepository::class)
            ->expects('create')
            ->with($this->similarTo($order))
            ->andReturnNull()
            ->getMock();
    }
}
