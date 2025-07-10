<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CategoryTypeIdRequest;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('category.service');
        $this->categoryTypeService = app('category.type.service');
    }

    /**
     * Handle the incoming request.
     */
    public function category(CategoryTypeIdRequest $request)
    {
        try {
            $list = $this->service->apiList($request);

            return $this->sendResponse($list, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    /**
     * Handle the incoming request.
     */
    public function category_type(Request $request)
    {
        try {
            $list = $this->categoryTypeService->apiList($request);

            return $this->sendResponse($list, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }
}
