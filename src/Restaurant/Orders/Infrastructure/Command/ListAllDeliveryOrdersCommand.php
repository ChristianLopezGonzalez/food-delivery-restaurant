<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Infrastructure\Command;

use App\Restaurant\Orders\Application\OrderResponses;
use App\Restaurant\Orders\Application\SearchAllWithDelivery\SearchAllOrdersWithDeliveryQuery;
use App\Shared\Domain\Bus\Query\QueryBus;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:order:list:delivery',
    description: 'List all delivery orders registered.',
    aliases: ['app:order:list:delivery'],
    hidden: false,
)]
class ListAllDeliveryOrdersCommand extends Command
{
    public function __construct(private readonly QueryBus $bus)
    {
        parent::__construct();
    }
    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $query = new SearchAllOrdersWithDeliveryQuery();

        try {
            /** @var OrderResponses $response */
            $response = $this->bus->run($query);

            foreach ($response->orders as $order) {
                $output->writeln('');
                $output->writeln(sprintf('+ Order %s', $order->id));
                foreach ($order->lines as $line) {
                    $output->writeln(
                        sprintf('|- %s x %sud: %s', $line['concept'], $line['amount'], $line['totalPrice']),
                    );
                }
                $output->writeln(sprintf('|- delivery: %s:', $order->deliveryPrice));
                $output->writeln(sprintf('|- total: %s:', $order->totalPrice));
            }
        } catch (Exception $exception) {
            $output->writeln($exception->getMessage());
        }


        return Command::SUCCESS;
    }
}
