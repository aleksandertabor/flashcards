<?php

namespace App\Providers;

use App\GraphQL\Enums\VisibilityEnum;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;

class GraphQLServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param TypeRegistry $typeRegistry
     *
     * @return void
     */
    public function boot(TypeRegistry $typeRegistry) : void
    {
        $typeRegistry = app(TypeRegistry::class);

        $typeRegistry->register(VisibilityEnum::get());
    }
}
