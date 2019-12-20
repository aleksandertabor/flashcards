<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserEditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'username' => $this->username,
            'email' => $this->email,
            $this->mergeWhen(Auth::check(), function () {
                return [
                    'editable' => (Auth::user()->id === $this->id ? true : false),
                ];
            }),

        ];

    }
}
