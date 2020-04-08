<?php

namespace App\GraphQL\Mutations\Auth;

use App\Exceptions\RefreshTokenException;
use App\Traits\PassportTokenTrait;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Cookie;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class RefreshTokenMutator
{
    use PassportTokenTrait;

    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $credentials['refresh_token'] = Cookie::get('_refresh_token');

        $response = $this->issueToken($credentials, 'refresh_token', '');

        $decodedResponse = json_decode($response->getContent(), true);

        if ($response->getStatusCode() === 200) {
            $dt = Carbon::now();
            $decodedResponse['expires_in'] = $dt->seconds($decodedResponse['expires_in'])->toDateTimeString();
            Cookie::queue('_refresh_token', $decodedResponse['refresh_token'], $dt->diffInMinutes($dt->copy()->addDays(30)), '/', $context->request->getHost(), false, true);

            return $decodedResponse;
        }
        throw new RefreshTokenException('So you are guest.', $decodedResponse['message']);
    }
}
