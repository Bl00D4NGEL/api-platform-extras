<?php

declare(strict_types=1);

namespace ApiPlatformExtras\Tests;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use DominikPeters\ApiPlatformExtras\AbstractProvider;
use Exception;
use PHPUnit\Framework\TestCase;
use stdClass;

final class AbstractProviderTest extends TestCase
{
    public function testItReturnsEmptyArrayForDefaultImplementationOnGetCollectionOperation(): void
    {
        $out = new class() extends AbstractProvider {
        };

        $this->assertSame([], $out->provide(new GetCollection()));
    }

    public function testItReturnsNullForDefaultImplementationOnNotGetOperation(): void
    {
        $out = new class() extends AbstractProvider {
        };

        $this->assertSame(null, $out->provide(new Get()));
    }

    public function testItReturnsExtendingImplementationValueForGetCollectionOperation(): void
    {
        $out = new class() extends AbstractProvider {
            protected function provideCollection(array $uriVariables, array $context): array
            {
                return [1, 2, 3];
            }
        };

        $this->assertSame([1, 2, 3], $out->provide(new GetCollection()));
    }

    public function testItReturnsExtendingImplementationValueForGetOperation(): void
    {
        $expected = new stdClass();

        $out = new class($expected) extends AbstractProvider {
            public function __construct(private readonly object $expectedItem)
            {
            }

            protected function provideItem(array $uriVariables, array $context): ?object
            {
                return $this->expectedItem;
            }
        };

        $this->assertSame($expected, $out->provide(new Get()));
    }

    public function testItCallsGetCollectionIfSupported(): void
    {
        $out = new class() extends AbstractProvider {
            protected function canProvideCollection(Operation $operation, array $uriVariables, array $context): bool
            {
                return true;
            }

            protected function provideCollection(array $uriVariables, array $context): array
            {
                return [1, 2, 3];
            }

            protected function provideItem(array $uriVariables, array $context): ?object
            {
                throw new Exception(sprintf('%s should not have been called', __METHOD__));
            }
        };

        $this->assertSame([1, 2, 3], $out->provide(new Get()));
    }
}
