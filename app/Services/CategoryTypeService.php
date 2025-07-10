<?php

namespace App\Services;

use App\Models\CategoryType;
use App\Repositories\CategoryTypeRepository;

class CategoryTypeService
{
    protected $repository;

    public function __construct(CategoryTypeRepository $categorytypeRepository)
    {
        $this->repository = $categorytypeRepository;
    }

    public function apiList($requestData)
    {
        $types = $this->repository->getQueryBuilder(null, 'name', 'asc')
            ->select('id', 'name')
            ->where('status', config('constants.ACTIVE'))
            ->get();

        return $types;
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'name') ?? 'name';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $types = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)->get();
        $typeArray = [];

        if ($types) {
            foreach ($types as $key => $type) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$type->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['name'] = $type->name;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$type->id."' ".($type->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('categorytype.edit', $type->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $typeArray[] = $data;
            }
        }

        return $typeArray;
    }

    public function create(array $requestData)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');
        $categorytype = $this->repository->updateOrCreate($requestData, null);
        \Cache::forget('categorytypes');

        return $categorytype;
    }

    public function update(array $requestData, CategoryType $categorytype)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');
        $categorytype = $this->repository->updateOrCreate($requestData, $categorytype);
        \Cache::forget('categorytypes');

        return $categorytype;
    }

    public function bulkDelete(array $requestData)
    {
        $categorytype = $this->repository->bulkDelete($requestData);
        \Cache::forget('categorytypes');

        return $categorytype;
    }

    public function bulkUpdate(string $columnName, array $requestData)
    {
        $status = false;
        $message = __('message.oopsError');

        if ($this->repository->bulkUpdate($columnName, $requestData)) {
            $status = true;
            $message = __('message.statusSuccessUpdate');
        }
        \Cache::forget('categorytypes');

        return ['status' => $status, 'message' => $message];
    }
}
