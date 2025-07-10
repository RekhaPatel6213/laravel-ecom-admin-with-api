<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('user.service');
    }

    public function profile(UserRequest $request)
    {
        try {
            $user = $this->service->apiUpdate($request->all());

            return $this->sendResponse($user, __('message.submitSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    public function detail(Request $request)
    {
        try {
            $user = $this->service->getUserDetail($request->all());

            return $this->sendResponse($user, __('message.submitSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    public function list(Request $request)
    {
        try {
            $users = $this->service->getUserList($request->all());

            return $this->sendResponse($users, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }
}
