<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\ApiPlatform\Exception;

use ApiPlatform\Metadata\Operation;
use Exception;

final class OperationNotImplementedException extends Exception
{
    public function __construct(public readonly Operation $operation)
    {
        parent::__construct(sprintf('Operation of type %s is not implemented', $operation::class));
    }
}
