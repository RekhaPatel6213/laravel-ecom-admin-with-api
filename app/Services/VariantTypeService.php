<?php

namespace App\Services;

use App\Models\VariantType;
use App\Repositories\VariantTypeRepository;

class VariantTypeService
{
    protected $repository;

    public function __construct(VariantTypeRepository $variantTypeRepository)
    {
        $this->repository = $variantTypeRepository;
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'name') ?? 'name';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $variantTypes = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)->get();
        $variantTypeArray = [];

        if ($variantTypes) {
            foreach ($variantTypes as $key => $variantType) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$variantType->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['name'] = $variantType->name;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$variantType->id."' ".($variantType->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('varianttype.edit', $variantType->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $variantTypeArray[] = $data;
            }
        }

        return $variantTypeArray;
    }

    public function create(array $requestData)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, null);
    }

    public function update(array $requestData, VariantType $varianttype)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, $varianttype);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDeleteDependancy($requestData, 'productVariant', 'VariantType');
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
