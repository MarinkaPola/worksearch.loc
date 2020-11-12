<?php

namespace App\Http\Resources;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class VacancyResource
 * @package App\Http\Resources
 * @mixin Vacancy
 */
class VacancyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'vacancy_name' => $this->vacancy_name,
            'workers_amount' => $this->workers_amount,
            'salary' => $this->salary,
            'vacancy_subscribers' => UserResource::collection($this->whenLoaded('vacancyUsers')),
        ];
    }
}
