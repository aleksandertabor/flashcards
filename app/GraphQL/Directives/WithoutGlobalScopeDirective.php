<?php

namespace App\GraphQL\Directives;

use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Database\Eloquent\Collection;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\DefinedDirective;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class WithoutGlobalScopeDirective extends BaseDirective implements DefinedDirective, FieldResolver
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'withoutGlobalScope';
    }

    public static function definition(): string
    {
        return /* @lang GraphQL */<<<'SDL'
directive @all(
  """
  Specify the class name of the model to use.
  This is only needed when the default model resolution does not work.
  """
  model: String

  """
  Apply scopes to the underlying query.
  """
  scopes: [String!]
) on FIELD_DEFINITION
SDL;
    }

    /**
     * Resolve the field directive.
     *
     * @param  \Nuwave\Lighthouse\Schema\Values\FieldValue  $fieldValue
     * @return \Nuwave\Lighthouse\Schema\Values\FieldValue
     */
    public function resolveField(FieldValue $fieldValue): FieldValue
    {
        // $modelClass = get_class($builder->getModel());
        // dump($modelClass);
        // $builder = $modelClass::withoutGlobalScopes();
        // try {
        //     return $builder;

        return $fieldValue->setResolver(
            function ($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection {
                return $resolveInfo
                    ->argumentSet
                    ->enhanceBuilder(
                        $this->getModelClass()::withoutGlobalScopes(),
                        $this->directiveArgValue('scopes', [])
                    )
                    ->get();
            }
        );
    }
}
