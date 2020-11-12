<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'password' => 'required|string|min:6|max:20',
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:40',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'phone' => 'required|string|max:30',
        ];
    }
}
