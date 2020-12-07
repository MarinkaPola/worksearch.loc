<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VacanciesUsersRequest extends FormRequest
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
                'user_id'=> 'required|integer|exists:users,id',
                'vacancy_id'=> 'required|integer|exists:vacancies,id',
            ];
        }
        return $rules;
    }
}
