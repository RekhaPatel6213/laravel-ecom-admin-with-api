<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginService
{
    public function getMobilUser(string $loginWithKey, string $loginWithValue, int $roleId)
    {
        return User::select('id')->where($loginWithKey, $loginWithValue)->where('role_id', $roleId)->where('status',config('constants.ACTIVE'))->first();
    }

    public function update(array $requestData): array
    {
        $user = Auth::user();
        $user->tokens()->delete();

        $data = $user->only('id', 'firstname', 'lastname', 'email', 'mobile', 'designation_id', 'role_id');
        $data['role'] = $user->role->name ?? null;
        $data['designation'] = $user->designation->name ?? null;
        $data['token'] = $user->createToken(env('APP_NAME'))->plainTextToken;

        $user->fcm_token = $requestData['fcm_token'] ?? null;
        $user->device_id = $requestData['device_id'] ?? null;
        $user->save();

        return [$data];
    }
}
