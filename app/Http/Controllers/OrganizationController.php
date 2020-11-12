<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\OrganizationRequest;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\OrganizationResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Organization::class);
    }
    /**
     * Display a listing of the resource.
     *
     @return JsonResponse
     */
    public function index()
    {
        $organizations = Organization::all();
        return OrganizationResource::collection($organizations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  OrganizationRequest  $request
     * @return JsonResponse
     */
    public function store(OrganizationRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $organization = $user->organizations()->create($request->validated());
        //$organization = Organization::make($request->validated());
        //$organization->employer_id = Auth::user()->id;
        //$user->organizations()->save($organization);
        return $this->created(OrganizationResource::make($organization));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Organization  $organization
     * @return JsonResponse
     */
    public function show(Organization $organization)
    {
       //return response()->json($organization, 200);
        return $this->success(OrganizationResource::make($organization->load(['vacancies', 'vacancies.vacancyUsers'])));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Organization  $organization
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $organization)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Organization  $organization
     * @return JsonResponse
     */
    public function update(OrganizationRequest $request, Organization $organization)
    {
        //$organization->update($request->all());
       // return response()->json($organization, 200);
        $organization->update($request->validated());
        return $this->success(OrganizationResource::make($organization));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Organization $organization
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Organization $organization)
    {
        try {
            $organization->delete();
        }
        catch (Exception $e) {
            return null;
        }
        //return response()->json(null, 204);
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }

    public function organizations_count(){
        $this->authorize(User::class);
        $organizations = Organization::count();
        return $this->success($organizations);
    }

}
