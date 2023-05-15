<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\ApiPlatform\Exception;

use Exception;

final class ResourceNotSupportedException extends Exception
{
    public function __construct(mixed $resource)
    {
        parent::__construct(sprintf('Resource of type %s is not supported', get_debug_type($resource)));
    }
}
