<?php

namespace App\Services;

use App\Models\VariantValue;
use App\Repositories\VariantValueRepository;

class VariantValueService
{
    protected $repository;

    public function __construct(VariantValueRepository $variantValueRepository)
    {
        $this->repository = $variantValueRepository;
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'name') ?? 'name';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $variantValues = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)->get();
        $variantValueArray = [];

        if ($variantValues) {
            foreach ($variantValues as $key => $variantValue) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$variantValue->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['name'] = $variantValue->name;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$variantValue->id."' ".($variantValue->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('variantvalue.edit', $variantValue->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $variantValueArray[] = $data;
            }
        }

        return $variantValueArray;
    }

    public function create(array $requestData)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, null);
    }

    public function update(array $requestData, VariantValue $variantvalue)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, $variantvalue);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDeleteDependancy($requestData, 'productVariant', 'VariantValue');
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
