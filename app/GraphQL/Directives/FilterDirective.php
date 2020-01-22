<?php

namespace App\GraphQL\Directives;

use GraphQL\Language\AST\FieldDefinitionNode;
use GraphQL\Language\AST\InputValueDefinitionNode;
use GraphQL\Language\AST\NonNullTypeNode;
use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Nuwave\Lighthouse\Exceptions\DefinitionException;
use Nuwave\Lighthouse\Schema\AST\DocumentAST;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Support\Contracts\ArgBuilderDirective;
use Nuwave\Lighthouse\Support\Contracts\ArgDirectiveForArray;
use Nuwave\Lighthouse\Support\Contracts\ArgManipulator;
use Nuwave\Lighthouse\Support\Contracts\DefinedDirective;

class FilterDirective extends BaseDirective implements ArgBuilderDirective, ArgDirectiveForArray, ArgManipulator, DefinedDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name() : string
    {
        return 'filter';
    }

    public static function definition() : string
    {
        return /* @lang GraphQL */<<<'SDL'
"""
Sort a result list by one or more given fields.
"""
directive @filter on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION
SDL;
    }

    /**
     * Apply an "FILTER" clause.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $builder
     * @param  mixed  $value
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function handleBuilder($builder, $value)
    {
        foreach ($value as $FilterClause) {
            $relation = $FilterClause['orderByCount']['relation'] ?? null;
            $model = $FilterClause['orderByCount']['model'] ?? null;
            $constraints = null;

            if (isset($model) && isset($relation)) {
                $className = '\\App\\'.$model;
                $model = new $className();
                $constraints = function (?Model $model, $relation) {
                    return $model->withCount([$relation]);
                };
            }

            if (isset($builder->query) && $constraints) {
                $builder->constrain($constraints($model, $relation));
            }
            if (! isset($builder->query) && $constraints !== null) {
                $builder->withCount([$relation]);
            }

            if ($FilterClause['order'] === 'RAND' && $FilterClause['random']) {
                $builder->orderBy(
                    DB::raw('RAND('.$FilterClause['random'].')')
                );
            } else {
                $builder->orderBy(
                    $FilterClause['field'],
                    $FilterClause['order']
                );
            }
        }

        return $builder;
    }

    /**
     * Validate the input argument definition.
     *
     * @param \Nuwave\Lighthouse\Schema\AST\DocumentAST $documentAST
     * @param \GraphQL\Language\AST\InputValueDefinitionNode $argDefinition
     * @param \GraphQL\Language\AST\FieldDefinitionNode $parentField
     * @param \GraphQL\Language\AST\ObjectTypeDefinitionNode $parentType
     * @return void
     *
     * @throws \Nuwave\Lighthouse\Exceptions\DefinitionException
     */
    public function manipulateArgDefinition(
        DocumentAST &$documentAST,
        InputValueDefinitionNode &$argDefinition,
        FieldDefinitionNode &$parentField,
        ObjectTypeDefinitionNode &$parentType
    ) : void {
        // Users may define this as NonNull if they want
        // Because we need to validate the structure regardless,
        // we unwrap it by one level if it is
        $expectedFilterClause = $argDefinition->type instanceof NonNullTypeNode
        ? $argDefinition->type
        : $argDefinition;

        if (
            data_get(
                $expectedFilterClause,
                // Must be a list
                'type'
                // of non-nullable
                 .'.type'
                // input objects
                 .'.type.name.value'
                // that are exactly of type
            ) !== 'FilterClause'
        ) {
            throw new DefinitionException(
                "Must define the argument type of {$argDefinition->name->value} on field {$parentField->name->value} as [FilterClause!]."
            );
        }
    }
}
