<?php
namespace App\GraphQL;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Execution\DataLoader\BatchLoader;

class WithCountLoader extends BatchLoader
{
    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var string
     */
    protected $relation;

    /**
     * @param Builder $builder
     * @param string  $relation
     */
    public function __construct(Builder $builder, string $relation)
    {
        $this->builder = $builder;
        $this->relation = $relation;
    }

    /**
     * Resolve the keys.
     *
     * The result has to be a map: [key => result]
     */
    public function resolve(): array
    {
        $parents = collect(Arr::pluck($this->keys, 'parent'));

        return $this->builder->withCount($this->relation)
            ->findMany($parents->pluck('id'))
            ->mapWithKeys(function ($post) {
                $property = "{$this->relation}_count";

                return [$post->getKey() => $post->{$property}];
            })
            ->all();
    }
}
