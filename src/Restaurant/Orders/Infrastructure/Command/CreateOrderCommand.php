<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Infrastructure\Command;

use App\Restaurant\Orders\Application\CreateMenu\CreateMenuOrderCommandQuery;
use App\Restaurant\Orders\Application\OrderResponse;
use App\Shared\Domain\Bus\CommandQuery\CommandQueryBus;
use Exception;
use Ramsey\Uuid\UuidFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:order:register',
    description: 'Registers a new order.',
    aliases: ['app:order:register'],
    hidden: false,
)]
class CreateOrderCommand extends Command
{
    private const SUCCESS_WITH_DRINKS = 'Your order with drinks included has been registered.';
    private const SUCCESS_WITHOUT_DRINKS = 'Your order has been registered.';

    public function __construct(private readonly CommandQueryBus $bus)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('Registers a new order in the system');

        $this
            ->addArgument('selectedFood', InputArgument::REQUIRED, 'Type of food')
            ->addArgument('money', InputArgument::REQUIRED, 'Amount of money given by the user')
            ->addArgument(
                'isDelivery',
                InputArgument::REQUIRED,
                'Is delivered or user must get the food from the restaurant',
            )
            ->addArgument('drinks', InputArgument::OPTIONAL, 'Number of drinks', 0);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $orderId = (new UuidFactory())->uuid4()->toString();
        $isDelivery = $input->getArgument('isDelivery') === 'true';
        $amount = (float) $input->getArgument('money');
        $drinksQty = (int) $input->getArgument('drinks');

        $query = new CreateMenuOrderCommandQuery(
            $orderId,
            $input->getArgument('selectedFood'),
            $amount,
            $isDelivery,
            $drinksQty,
        );

        try {
            /** @var OrderResponse $order */
            $order = $this->bus->run($query);
            $output->writeln($order->hasDrink ? self::SUCCESS_WITH_DRINKS : self::SUCCESS_WITHOUT_DRINKS);
        } catch (Exception $exception) {
            $output->writeln($exception->getMessage());
        }

        return 1;
    }
}
