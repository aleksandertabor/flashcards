<?php

namespace App\GraphQL\Directives;

use Illuminate\Validation\Rule;
use Nuwave\Lighthouse\Schema\Directives\ValidationDirective;

class EditProfileDirective extends ValidationDirective
{
    /**
     * Name of the directive.
     *
     * @return string
     */
    public function name(): string
    {
        return 'editProfile';
    }

    /**
     * @return mixed[]
     */
    public function rules(): array
    {
        return [
            'id' => ['required'],
            'username' => ['sometimes', 'required', 'string', 'min:3', Rule::unique('users', 'username')->ignore($this->args['id'])],
            'email' => ['sometimes', 'required', 'string', 'email', Rule::unique('users', 'email')->ignore($this->args['id'])],
            'password' => ['sometimes', 'required', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique' => 'The chosen username is not available',
            'email.unique' => 'The chosen email is not available',
        ];
    }
}
