# API Platform Extras

Based on the popular library [API platform](https://api-platform.com/) this library adds an abstraction layer on top of the Processor/Provider interfaces that can be extended for custom logic.

This library can be used if you find yourself needing to implement custom processors/providers for API platform and don't want to re-implement the boilerplate code over and over again. Additionally the abstract classes have phpstan templating support.

While extending the abstract processor you are not required to extend all abstract methods but instead only those that you actually want to be supported. If your resource should only be supporting `POST` and `PATCH` then you would only have to extend the `handlePostOperation` and `handleDeleteOperation`. This also enables you to create a single processor per HTTP method.

When extending the abstract provider you are free to extend either the `provideCollection` and `provideItem` function. By default those return an empty array and null, respectively. If your application has custom logic to determine when a collection is to be returned you can extend the `canProvideCollection` function. If it returns true the abstract provider will call the `provideCollection` function, otherwise it will call the `provideItem` function.


## Example usage
Example of a custom processor (supporting all HTTP methods):

```php
<?php

declare(strict_types=1);

namespace App\Processor;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use DominikPeters\ApiPlatformExtras\AbstractProcessor;

final class CustomProcessor extends AbstractProcessor
{
    protected function supportsResource(mixed $resource, array $uriVariables, array $context): bool
    {
        // In the read world this would check whether $resource is an instance
        // of your API platform resource
        return $resource !== null;
    }
    
    protected function handlePostOperation($resource, Post $operation, array $uriVariables, array $context): object
    {
        // Custom persistence logic goes here
        return $resouce;
    }
    
    protected function handlePutOperation($resource, Put $operation, array $uriVariables, array $context): object
    {
        // Custom replacement logic goes here
        return $resouce;
    }
    
    protected function handlePatchOperation($resource, Patch $operation, array $uriVariables, array $context): object
    {
        // Custom update logic goes here
        return $resouce;
    }
    
    protected function handleDeleteOperation($resource, Delete $operation, array $uriVariables, array $context): object
    {
        // Custom deletion logic goes here
    }
}
```

Example of a custom provider:

```php
<?php

declare(strict_types=1);

namespace App\Provider;

use ApiPlatform\Metadata\GetCollection;use ApiPlatform\Metadata\Operation;use DominikPeters\ApiPlatformExtras\AbstractProvider;

final class CustomProvider extends AbstractProvider
{
    protected function canProvideCollection(Operation $operation, array $uriVariables, array $context) : bool
    {
        if ($operation instanceof GetCollection) {
            return true;
        }
        
        // We can/should provide a collection if no uuid is given
        return !array_key_exists('uuid', $uriVariables);
    }
    
    protected function provideCollection(array $uriVariables,array $context) : array{
        // Custom collection fetch logic here
        return [];
    }
    
    protected function provideItem(array $uriVariables,array $context) : ?object{
        // Custom fetch logic here, return null if not found
        return null;
    }
}
```