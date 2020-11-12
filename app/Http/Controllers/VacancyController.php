<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vacancy;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\VacancyRequest;
use App\Http\Resources\VacancyResource;
use App\Http\Resources\VacancyResourceCollection;
use Illuminate\Support\Facades\DB;

class VacancyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Vacancy::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $vacancies_active = Vacancy::has('vacancyUsers', '<', DB::raw('workers_amount'))->paginate();
        return $this->success(VacancyResourceCollection::make($vacancies_active));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(VacancyRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $vacancy = $user->vacancies()->create($request->validated());
        return $this->created(VacancyResource::make($vacancy));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Vacancy $vacancy
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Vacancy $vacancy)
    {
        //return response()->json($vacancy, 200);
        return $this->success(VacancyResource::make($vacancy)->load(['vacancyUsers']));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Vacancy $vacancy
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(VacancyRequest $request, Vacancy $vacancy)
    {

       // $vacancy->update($request->all());
        //return response()->json($vacancy, 200);
        $vacancy->update($request->validated());
        return $this->success(VacancyResource::make($vacancy));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Vacancy $vacancy
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Vacancy $vacancy)
    {
        try {
            $vacancy->delete();
        } catch (Exception $e) {
            return null;
        }
       // return response()->json(null, 204);
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }

    public function vacancy_book(Request $request)
    {
        /** @var Vacancy $vacancy */
        $vacancy = Vacancy::find($request->vacancy_id);
        $this->authorize('vacancy_book', $vacancy);
        if ($vacancy->vacancyUsers()->where('id', $request->user_id)->exists()) {
            return $this->error('You are already signed up for this vacancy');

        }

        else if ($vacancy->workers_amount > $vacancy->vacancyUsers()->count()) {

            $vacancy->vacancyUsers()->attach($request->user_id);
            return $this->success('you signed up for a vacancy' . $vacancy->vacancy_name);
        } else {
            return $this->error('this vacancy is closed');
        }
    }

    public function vacancy_unbook(Request $request)
    {
        /** @var Vacancy $vacancy */

        $vacancy = Vacancy::find($request->vacancy_id);
        $this->authorize('vacancy_unbook', $vacancy);
        $vacancy->vacancyUsers()->detach($request->user_id);

        return $this->success('You are no longer subscribed to this vacancy');
    }
    public function vacancies_count(){
        $this->authorize(User::class);
        $vacancies_all = Vacancy::count();
        $vacancies_active = Vacancy::has('vacancyUsers', '<', DB::raw('workers_amount'))->count();
        $vacancies_inactive = Vacancy::has('vacancyUsers', '>=', DB::raw('workers_amount'))->count();
        return $this->success(['vacancies_all'=>$vacancies_all, 'vacancies_active'=>$vacancies_active, 'vacancies_inactive'=>$vacancies_inactive]);
    }

}
