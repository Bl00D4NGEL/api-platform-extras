<?php

declare(strict_types=1);

namespace DominikPeters\ApiPlatformExtras;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use App\Shared\Infrastructure\ApiPlatform\Exception\OperationNotImplementedException;
use App\Shared\Infrastructure\ApiPlatform\Exception\ResourceNotSupportedException;

/**
 * @template T of object
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    public function __construct()
    {
    }

    /**
     * @param T|mixed $data
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     *
     * @return ($operation is Delete ? null : T)
     *
     * @throws ResourceNotSupportedException
     * @throws OperationNotImplementedException
     */
    final public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): ?object {
        if (!$this->supportsResource($data, $uriVariables, $context)) {
            throw new ResourceNotSupportedException($data);
        }

        /** @var T $resource */
        $resource = $data;

        switch ($operation::class) {
            case Post::class:
                return $this->handlePostOperation($resource, $operation, $uriVariables, $context);
            case Put::class:
                return $this->handlePutOperation($resource, $operation, $uriVariables, $context);
            case Patch::class:
                return $this->handlePatchOperation($resource, $operation, $uriVariables, $context);
            case Delete::class:
                $this->handleDeleteOperation($resource, $operation, $uriVariables, $context);
                return null;
            default:
                throw new OperationNotImplementedException($operation);
        }
    }

    /**
     * This function returns false if the given resource object is not supported.
     *
     * @param T|mixed $resource
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     */
    abstract protected function supportsResource(mixed $resource, array $uriVariables, array $context): bool;

    /**
     * @param T $resource
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     *
     * @return T
     *
     * @throws OperationNotImplementedException
     */
    protected function handlePostOperation(
        $resource,
        Post $operation,
        array $uriVariables,
        array $context
    ): object {
        throw new OperationNotImplementedException($operation);
    }

    /**
     * @param T $resource
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     *
     * @return T
     *
     * @throws OperationNotImplementedException
     */
    protected function handlePatchOperation(
        $resource,
        Patch $operation,
        array $uriVariables,
        array $context
    ): object {
        throw new OperationNotImplementedException($operation);
    }

    /**
     * @param T $resource
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     *
     * @return T
     *
     * @throws OperationNotImplementedException
     */
    protected function handlePutOperation(
        $resource,
        Put $operation,
        array $uriVariables,
        array $context
    ): object {
        throw new OperationNotImplementedException($operation);
    }

    /**
     * @param T $resource
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     *
     * @throws OperationNotImplementedException
     */
    protected function handleDeleteOperation(
        $resource,
        Delete $operation,
        array $uriVariables,
        array $context
    ): void {
        throw new OperationNotImplementedException($operation);
    }
}
