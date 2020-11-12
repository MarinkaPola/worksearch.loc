<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        if ($this->isMethod('post')) {
            $rules = [
            'title' => 'required|string|min:3|max:20',
            'country'=> 'required|string|min:2|max:30',
            'city' => 'required|string|min:3|max:30',
        ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'title' => 'required|string|min:3|max:20',
                'country' => 'required|string|min:2|max:30',
                'city' => 'required|string|min:3|max:30',
            ];
        }
            return $rules;
    }

}
