<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:order:register',
    description: 'Registers a new order.',
    aliases: ['app:order:register'],
    hidden: false
)]
class OrderCommand extends Command
{
    protected function configure(): void
    {
        $this->setHelp('Registers a new order in the system');

        $this
            ->addArgument('selectedFood', InputArgument::REQUIRED, 'Type of food')
            ->addArgument('money', InputArgument::REQUIRED, 'Amount of money given by the user')
            ->addArgument('isDelivery', InputArgument::REQUIRED, 'Is delivered or user must get the food from the restaurant')
            ->addArgument('drinks', InputArgument::OPTIONAL, 'Number of drinks', 0);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getArgument('selectedFood') == 'pizza' || $input->getArgument('selectedFood') == 'burger' || $input->getArgument('selectedFood') == 'sushi') {
            $foodAmount = 0;

            if ($input->getArgument('selectedFood') == 'pizza') {
                $foodAmount = 12.5;
            } elseif ($input->getArgument('selectedFood') == 'burger') {
                $foodAmount = 9;
            } elseif ($input->getArgument('selectedFood') == 'sushi') {
                $foodAmount = 24;
            }

            if ($input->getArgument('drinks') < 0 || $input->getArgument('drinks') > 2) {
                $output->writeln('Number of drinks should be between 0 and 2.');
                return Command::FAILURE;
            } else {
                if ($input->getArgument('isDelivery') == true) {
                    $totalOrderAmount = $foodAmount + ($input->getArgument('drinks') * 2) + 1.5;
                    if ($input->getArgument('money') < $totalOrderAmount || $input->getArgument('money') > $totalOrderAmount) {
                        $output->writeln('Money must be the exact order amount on delivery orders.');
                        return Command::FAILURE;
                    }
                } else {
                    $totalOrderAmount = $foodAmount + ($input->getArgument('drinks') * 2);
                    if ($input->getArgument('money') < $totalOrderAmount) {
                        $output->writeln('Money does not reach the order amount.');
                        return Command::FAILURE;
                    }
                }

                if ($input->getArgument('drinks') > 0) {
                    $drinksIncludedString = 'with drinks included ';
                } else {
                    $drinksIncludedString = '';
                }

                $output->writeln('Your order '.$drinksIncludedString.'has been registered.');
                return Command::SUCCESS;
            }
        } else {
            $output->writeln('Selected food must be pizza, burger or sushi.');
            return Command::FAILURE;
        }
    }
}