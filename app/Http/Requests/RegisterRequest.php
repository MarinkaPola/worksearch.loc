<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:20',
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:40',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
            'role' => ['required', 'string', Rule::in(array_diff(User::ROLES, [User::ROLE_ADMIN]))]
        ];

    }



}
