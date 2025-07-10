<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('login.service');
    }

    /**
     * Login api
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $loginWith = filter_var($request->mobile_no, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        $user = $this->service->getMobilUser($loginWith, $request->get('mobile_no'), config('constants.COMPANY_ROLE_ID'));
        // !empty($user) &&

        if (! empty($user) && Auth::attempt([$loginWith => $request->mobile_no, 'password' => $request->password])) {

            $success = $this->service->update($request->all());

            return $this->sendResponse($success, "You\'ve successfully Logged");
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised'], 200);
        }
    }

    public function logout(Request $request)
    {
        // Get the current authenticated user
        $user = Auth::user();

        if ($user) {
            // Revoke the current access token if using Sanctum
            $request->user()->currentAccessToken()->delete();

            // Optionally revoke all tokens (for all devices)
            $user->tokens()->delete();

            return $this->sendResponse([], 'Logged out successfully');
        }

        return $this->sendError('No user to log out.', ['error' => 'No user to log out'], 200);
    }
}
