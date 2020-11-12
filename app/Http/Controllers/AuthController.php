<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * return JsonResponse
     * @throws Exception
     */
    public function register (RegisterRequest $request): JsonResponse {
    /** @var User $user */
    $user= User::create($request->validated());
    $data = UserResource::make($user)->toArray($request) +
['api_token'=> $user->createToken('api_token')->plainTextToken];
    return $this->created($data);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request)//: JsonResponse
    {
        if (!auth()->once( $request->validated())) {
            throw ValidationException::withMessages([
                'email' => 'Wrong Email or Password',
            ]);
        }
        /** @var User $user */
        $user = auth()->user();
        $data= UserResource::make($user)->toArray($request) +
            ['api_token'=> $user->createToken('api_token')->plainTextToken];
        return $this->created($data);
    }

        /**
         * Log the user out (Invalidate the token).
         *
         * @return JsonResponse
         */
        public function logout()
    {
        /** @var User $user */
        $user = auth()->user();
        $user->currentAccessToken()->delete();
        return $this->success('Successfully logged out');
    }

        /**
         * Fallback route action
         *
         * @return JsonResponse
         */
        public function fallback()
    {
        return $this->error('Page Not Found', Response::HTTP_NOT_FOUND);
    }
}
