<?php

namespace App\GraphQL\Types;

use App\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name' => 'User',
        'description' => 'A user',
        'model' => User::class,
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of the user',
                // Use 'alias', if the database column is different from the type name.
                // This is supported for discrete values as well as relations.
                // - you can also use `DB::raw()` to solve more complex issues
                // - or a callback returning the value (string or `DB::raw()` result)
                // 'alias' => 'user_id',
            ],
            'username' => [
                'type' => Type::string(),
                'description' => 'The username of user',
                'resolve' => function ($root, $args) {
                    // If you want to resolve the field yourself,
                    // it can be done here
                    return strtolower($root->username);
                },
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of user',
                'resolve' => function ($root, $args) {
                    // If you want to resolve the field yourself,
                    // it can be done here
                    return strtolower($root->email);
                },
            ],
            'token' => [
                'type' => Type::string(),
                'description' => 'The token of user',
                'resolve' => function ($root, $args) {
                    return $root->token;
                },
                'privacy' => function (array $args): bool {
                    return $args['id'] == Auth::id();
                },
            ],
            // Uses the 'getIsMeAttribute' function on our custom User model
            'isMe' => [
                'type' => Type::boolean(),
                'description' => 'True, if the queried user is the current user',
                'selectable' => false, // Does not try to query this from the database
            ],
        ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    protected function resolveEmailField($root, $args)
    {
        return strtolower($root->email);
    }
}
