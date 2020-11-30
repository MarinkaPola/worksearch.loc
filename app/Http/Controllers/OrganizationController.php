<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
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
use Illuminate\Support\Facades\DB;

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
        /** @var User $user */
        $user = auth()->user();
        if ($user->role === User::ROLE_ADMIN){
        $organizations = Organization::all();
        return OrganizationResource::collection($organizations);
        }
        else if ($user->role === User::ROLE_EMPLOYER){
        $organizations = Organization::where('employer_id', '=', $user->id)->paginate();
        return $this->success(OrganizationResourceCollection::make($organizations));
        }
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

            switch (request()->input('vacancies')) {
                case "0":
                    if (request()->boolean('workers')) {
                        $vacancies = $organization->vacancies()->has('vacancyUsers')->get();
                        foreach ($vacancies as $vacancy) {
                            $users = $vacancy->vacancyUsers()->get();
                            return $this->success($organization->toArray() + (['users' => $users]));
                        }
                    } else {
                        return $this->success(OrganizationResource::make($organization));
                    }
                    break;
                case "1":
                    if (request()->boolean('workers')) {
                        $vacancies_active = $organization->vacancies()->has('vacancyUsers', '<', DB::raw('workers_amount'))->get();
                        $vacancies = $organization->vacancies()->has('vacancyUsers')->get();
                        foreach ($vacancies as $vacancy) {
                            $users = $vacancy->vacancyUsers()->get();
                            return $this->success($organization->toArray() + (['vacancies_active' => $vacancies_active->toArray()]) + (['users' => $users]));
                        }
                    } else {
                        return $this->success(OrganizationResource::make($organization->load((['vacancies' => function ($query) {
                            $query->has('vacancyUsers', '<', DB::raw('workers_amount'));
                        }]))));
                    }
                    break;
                case "2":
                    if (request()->boolean('workers')) {
                        $vacancies_inactive = $organization->vacancies()->has('vacancyUsers', '>=', DB::raw('workers_amount'))->get();
                        $vacancies = $organization->vacancies()->has('vacancyUsers')->get();
                        foreach ($vacancies as $vacancy) {
                            $users = $vacancy->vacancyUsers()->get();
                            return $this->success($organization->toArray() + (['vacancies_active' => $vacancies_inactive->toArray()]) + (['users' => $users]));
                        }
                        }
                    else {
                        return $this->success(OrganizationResource::make($organization->load((['vacancies' => function ($query) {
                            $query->has('vacancyUsers', '>=', DB::raw('workers_amount'));
                        }]))));
                    }
                    break;
                case "3":
                    if (request()->boolean('workers')) {
                        $vacancies_all = $organization->vacancies()->get();
                        $vacancies = $organization->vacancies()->has('vacancyUsers')->get();
                        foreach ($vacancies as $vacancy) {
                            $users = $vacancy->vacancyUsers()->get();
                            return $this->success($organization->toArray() + (['vacancies_all' => $vacancies_all->toArray()]) + (['users' => $users]));
                        }
                        }
                    else {
                    return $this->success(OrganizationResource::make($organization->load(['vacancies'])));
                    break;
                }
            }
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
