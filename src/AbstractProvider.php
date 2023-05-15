<?php

declare(strict_types=1);

namespace DominikPeters\ApiPlatformExtras;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

/**
 * @template T of object
 */
abstract class AbstractProvider implements ProviderInterface
{
    /**
     * Determines whether to return a collection (array) or a single resource.
     *
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     *
     * @return T|T[]|null
     */
    final public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($this->canProvideCollection($operation, $uriVariables, $context)) {
            return $this->provideCollection($uriVariables, $context);
        }

        return $this->provideItem($uriVariables, $context);
    }

    /**
     * Check whether the provider can provide a collection for the given input.
     * This function can be overwritten to create custom logic as to when to return a collection or just a single item.
     *
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     */
    protected function canProvideCollection(Operation $operation, array $uriVariables, array $context): bool
    {
        return $operation instanceof GetCollection;
    }


    /**
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     *
     * @return T[]
     */
    protected function provideCollection(array $uriVariables, array $context): array {
        return [];
    }

    /**
     * Provides a single resource. Should return null if resource is not found.
     *
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     *
     * @return T|null
     */
    protected function provideItem(array $uriVariables, array $context): ?object {
        return null;
    }
}
