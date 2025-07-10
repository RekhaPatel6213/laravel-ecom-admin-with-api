<?php

namespace App\Services;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Traits\FileTrait;

class CategoryService
{
    use FileTrait;

    protected $repository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->repository = $categoryRepository;
    }

    public function apiList($requestData)
    {
        $categories = $this->repository->getQueryBuilder(null, 'name', 'asc')
            ->select('id', 'name', 'app_image')
            ->where('status', config('constants.ACTIVE'))
            ->when($requestData->category_type_id !== null, function ($query) use ($requestData) {
                $query->where('category_type_id', $requestData->category_type_id);
            })
            ->get();

        return CategoryResource::collection($categories);
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'name') ?? 'name';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $categories = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)
            ->select('id', 'name', 'sort_order', 'status', 'parent_category_id', 'category_type_id')
            ->with(['parent_category:id,name', 'categoryType:id,name'])
            ->get();
        $categoryArray = [];

        if ($categories) {
            foreach ($categories as $key => $category) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$category->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['category_type'] = $category->categoryType->name ?? '-';
                $data['parent_category'] = $category->parent_category->name ?? '-';
                $data['name'] = $category->name;
                $data['sort_order'] = $category->sort_order;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$category->id."' ".($category->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('category.edit', $category->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $categoryArray[] = $data;
            }
        }

        return $categoryArray;
    }

    public function getLastSortId()
    {
        return $this->repository->getLastSortId('sort_order');
    }

    public function create(array $requestData)
    {
        $requestData['image'] = $this->getFileImage($requestData['image']);
        $requestData['app_image'] = $this->getFileImage($requestData['app_image']);
        $requestData['is_parent'] = $requestData['is_parent'] ?? 0;
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, null);
    }

    public function update(array $requestData, Category $category)
    {
        $requestData['image'] = $this->getFileImage($requestData['image'], $requestData['edit_image']);
        $requestData['app_image'] = $this->getFileImage($requestData['app_image'], $requestData['edit_app_image']);
        $requestData['is_parent'] = $requestData['is_parent'] ?? 0;
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, $category);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDelete($requestData);
    }

    public function bulkUpdate(string $columnName, array $requestData)
    {
        $status = false;
        $message = __('message.oopsError');

        if ($this->repository->bulkUpdate($columnName, $requestData)) {
            $status = true;
            $message = __('message.statusSuccessUpdate');
        }

        return ['status' => $status, 'message' => $message];
    }
}
