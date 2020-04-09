<?php

namespace App\GraphQL\Types;

use App\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UserType
{
    public function image(User $user, array $args, GraphQLContext $context, ResolveInfo $info)
    {
        $url = '';

        // dump($context);

        $media = $user->getFirstMedia('main');

        if ($media) {
            $url = $media->getFullUrl();
        }

        return $url;
    }
}
