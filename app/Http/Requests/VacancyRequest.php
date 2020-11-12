<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VacancyRequest extends FormRequest
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
                'vacancy_name' => 'required|string|min:4|max:20',
                'workers_amount' => 'required|integer|min:1|max:10',
                'organization_id'=> 'required|integer|exists:organizations,id',
                'salary' => 'required|integer|min:1|max:10000000',
            ];
        } elseif ($this->isMethod('put')) {
            $rules = [
                'vacancy_name' => 'required|string|min:4|max:20',
                'workers_amount' => 'required|integer|min:1|max:10',
                'organization_id'=> 'required|integer|exists:organizations,id',
                'salary' => 'required|integer|min:1|max:10000000',
            ];
        }
        return $rules;
    }

}
