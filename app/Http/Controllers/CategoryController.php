<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('category.service');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return response()->json(['data' => $this->service->list($request)]);
            }

            return view('category.index');
        } catch (Exception $exception) {
            return $this->abortJsonResponse($exception);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $sortId = $this->service->getLastSortId();
            $categories = Category::whereNull('parent_category_id')->where('is_parent', 1)->where('status', 1)->pluck('name', 'id')->toArray();

            return view('category.create', compact('categories', 'sortId'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        try {
            $this->service->create($request->all());

            return redirect()->route('category.index')->with('success_message', __('message.submitSuccess'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return redirect()->route('category.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        try {
            $categories = Category::whereNull('parent_category_id')->where('is_parent', 1)->whereNotIn('id', [$category->id])->where('status', 1)->pluck('name', 'id')->toArray();

            return view('category.create', compact('category', 'categories'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $this->service->update($request->all(), $category);

            return redirect()->route('category.index')->with('success_message', __('message.submitSuccess'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    public function delete_image(Request $request)
    {
        if ($request->ajax()) {
            try {
                return response()->json($this->service->bulkUpdate($request->column_name, ['status_id' => $request->id, 'status' => null]));
            } catch (Exception $e) {
                return response()->json(['result' => false, 'message' => $e->getMessage(),
                ]);
            }
        } else {
            return back()->with('error', __('message.oopsError'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }

    public function multiple_delete(Request $request)
    {
        if ($request->ajax()) {
            try {
                return response()->json($this->service->bulkDelete($request->all()));
            } catch (Exception $e) {
                return response()->json(['result' => false, 'message' => $e->getMessage(),
                ]);
            }
        } else {
            return back()->with('error', __('message.oopsError'));
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            try {
                return response()->json($this->service->bulkUpdate('status', $request->all()));
            } catch (Exception $e) {
                return response()->json(['result' => false, 'message' => $e->getMessage(),
                ]);
            }
        } else {
            return back()->with('error', __('message.oopsError'));
        }
    }

    public function category_list(Request $request)
    {
        if ($request->ajax()) {
            try {
                $categoryTypes = Category::where('category_type_id', $request->category_type_id)->where('status', config('constants.ACTIVE'))->orderBy('name', 'ASC')->pluck('name', 'id');
                $options = '<option value="">Please Select</option>';

                foreach ($categoryTypes as $categoryTypeId => $categoryTypeName) {

                    $selected = (int) $request->category_id === (int) $categoryTypeId ? 'selected' : '';

                    $options .= '<option value="'.$categoryTypeId.'" '.$selected.'>'.$categoryTypeName.'</option>';
                }

                return response()->json(['result' => false, 'message' => __('Data Get Successfully'), 'data' => $options]);
            } catch (Exception $e) {
                return response()->json(['result' => false, 'message' => $e->getMessage(), 'data' => null]);
            }
        } else {
            return back()->with('error', __('message.oopsError'));
        }
    }
}
