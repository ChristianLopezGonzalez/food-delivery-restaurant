<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Infrastructure\PHPUnit;

use PHPUnit\Framework\TestCase;

abstract class UnitTestCase extends TestCase
{
    use MocksTestCaseTrait;

    // No need to create a constructor.
}
