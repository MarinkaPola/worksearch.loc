<?php

namespace App\Http\Resources;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrganizationResource
 * @package App\Http\Resources
 * @mixin Organization
 */

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'country' => $this->country,
            'city' => $this->city,
            'vacancies_organization' => VacancyResource::collection($this->whenLoaded('vacancies')),
        ];
    }
}
