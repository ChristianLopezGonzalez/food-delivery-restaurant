<?php

declare(strict_types=1);

namespace App\Restaurant\Orders\Infrastructure\Persistence;

use App\Restaurant\Orders\Domain\AbstractOrder;
use App\Restaurant\Orders\Domain\OrderRepository;
use App\Restaurant\Orders\Domain\OrderType;
use App\Restaurant\Orders\Domain\OrderWithDelivery;
use App\Restaurant\Orders\Domain\OrderWithPickUp;
use RuntimeException;

final class FileOrderRepository implements OrderRepository
{
    private const FILE_PATH = __DIR__ . '/documents';

    public function create(AbstractOrder $order): void
    {
        $id = $order->id()->value();
        $type = $order->type()->value();
        file_put_contents($this->fileName($id, $type), serialize($order));
    }

    public function searchOrderByType(OrderType $type): array
    {
        $collection = [];
        $directory = $this->directory($type->value());

        $handle = opendir($directory);
        while (false !== ($orderId = readdir($handle))) {
            if ($orderId !== "." && $orderId !== "..") {
                $fileName = $this->fileName($orderId, $type->value());
                $collection[] = unserialize(
                    file_get_contents($fileName),
                    [OrderWithDelivery::class, OrderWithPickUp::class],
                );
            }
        }
        closedir($handle);

        return $collection;
    }

    private function fileName(string $orderId, string $orderType): string
    {
        $directory = $this->directory($orderType);

        return sprintf('%s/%s', $directory, $orderId);
    }

    private function directory(string $orderType): string
    {
        $directory = sprintf('%s/%s', self::FILE_PATH, $orderType);
        $this->tryToCreateDirectory($directory);

        return $directory;
    }

    private function tryToCreateDirectory(string $directory): void
    {
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $directory));
        }
    }
}
