<?php

declare(strict_types=1);

namespace ApiPlatformExtras\Tests;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use DominikPeters\ApiPlatformExtras\Exception\OperationNotImplementedException;
use DominikPeters\ApiPlatformExtras\Exception\ResourceNotSupportedException;
use DominikPeters\ApiPlatformExtras\AbstractProcessor;
use PHPUnit\Framework\TestCase;
use stdClass;

final class AbstractProcessorTest extends TestCase
{
    public function testProcessThrowsExceptionIfResourceNotSupported(): void
    {
        $this->expectException(ResourceNotSupportedException::class);

        $out = new class () extends AbstractProcessor {
            protected function supportsResource(mixed $resource, array $uriVariables, array $context): bool
            {
                return false;
            }
        };

        $out->process(new stdClass(), new Post());
    }

    public function testBaseImplementationsThrowException(): void
    {
        $out = new class () extends AbstractProcessor {
            protected function supportsResource(mixed $resource, array $uriVariables, array $context): bool
            {
                return true;
            }
        };

        foreach ([new Post(), new Patch(), new Delete()] as $operation) {
            try {
                $out->process(new stdClass(), $operation);

                $this->fail('Processing should have failed with exception');
            } catch (OperationNotImplementedException $exception) {
                $this->assertSame($operation, $exception->operation);
            }
        }
    }

    public function testItThrowsExceptionIfOperationNotSupported(): void
    {
        $out = new class () extends AbstractProcessor {
            protected function supportsResource(mixed $resource, array $uriVariables, array $context): bool
            {
                return true;
            }
        };

        $this->expectException(OperationNotImplementedException::class);

        $operation = new Get();
        $out->process(new stdClass(), $operation);
    }

    public function testItHandlesPost(): void
    {
        $out = new class () extends AbstractProcessor {
            protected function supportsResource(mixed $resource, array $uriVariables, array $context): bool
            {
                return true;
            }

            protected function handlePostOperation(
                $resource,
                Post $operation,
                array $uriVariables,
                array $context
            ): object {
                return $resource;
            }
        };

        $expected = new stdClass();

        $this->assertSame($expected, $out->process($expected, new Post()));
    }

    public function testItHandlesPatch(): void
    {
        $out = new class () extends AbstractProcessor {
            protected function supportsResource(mixed $resource, array $uriVariables, array $context): bool
            {
                return true;
            }

            protected function handlePatchOperation(
                $resource,
                Patch $operation,
                array $uriVariables,
                array $context
            ): object {
                return $resource;
            }
        };

        $expected = new stdClass();

        $this->assertSame($expected, $out->process($expected, new Patch()));
    }

    public function testItHandlesPut(): void
    {
        $out = new class () extends AbstractProcessor {
            protected function supportsResource(mixed $resource, array $uriVariables, array $context): bool
            {
                return true;
            }

            protected function handlePutOperation(
                $resource,
                Put $operation,
                array $uriVariables,
                array $context
            ): object {
                return $resource;
            }
        };

        $expected = new stdClass();

        $this->assertSame($expected, $out->process($expected, new Put()));
    }

    public function testItHandlesDelete(): void
    {
        $out = new class () extends AbstractProcessor {
            protected function supportsResource(mixed $resource, array $uriVariables, array $context): bool
            {
                return true;
            }

            protected function handleDeleteOperation(
                $resource,
                Delete $operation,
                array $uriVariables,
                array $context
            ): void {
                // No-op
            }
        };

        $expected = new stdClass();

        $out->process($expected, new Delete());

        // We can't really test anything here as the delete operation returns null
        $this->addToAssertionCount(1);
    }
}
