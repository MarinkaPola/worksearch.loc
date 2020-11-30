<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     *
    @return JsonResponse
     */
    public function index()
    {
        if (request()->input('search') =='') {
            $users = User::all();
            return UserResource::collection($users);
        }
      //  $users = User::where('city', 'like', '%request()->city%')
          //  ->orWhere('country', 'like', '%request()->country%')
          //  ->orWhere('first_name', 'like', '%request()->first_name%')
           // ->orWhere('last_name', 'like', '%request()->last_name%')
          //  ->get();
        else {
            $users = User::where(function ($query) {
                $query->where('city', 'like', request()->input('search'))
                    ->orWhere('country', 'like', request()->input('search'))
                    ->orWhere('first_name', 'like', request()->input('search'))
                    ->orWhere('last_name', 'like', request()->input('search'));
            })->paginate();
            return $this->success(UserResourceCollection::make($users));
        }
    }


    /**
     * Display the specified resource.
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
            //return response()->json($user, 200);
        return $this->success(UserResource::make($user));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, User $user)
    {
        //$user->update($request->all());
       // return response()->json($user, 200);
        $user->update($request->validated());
        return $this->success(UserResource::make($user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
       try{
           $user ->delete();
       }
        catch (Exception $e) {
           return null;
        }
        //return response()->json(null, 204);
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }
    public function users_roles_count() {
        $this->authorize(User::class);
        $users_role_employer = DB::table('users')->where('role', 'employer')->count();
        $users_role_worker = DB::table('users')->where('role', 'worker')->count();
        $users_role_admin = DB::table('users')->where('role', 'admin')->count();
        return $this->success(['users_role_worker'=>$users_role_worker, 'users_role_employer'=>$users_role_employer, 'users_role_admin'=>$users_role_admin]);

    }
}
