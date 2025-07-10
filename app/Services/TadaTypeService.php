<?php

namespace App\Services;

use App\Models\TadaType;
use App\Repositories\TadaTypeRepository;

class TadaTypeService
{
    protected $repository;

    public function __construct(TadaTypeRepository $tadaTypeRepository)
    {
        $this->repository = $tadaTypeRepository;
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'name') ?? 'name';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $tadaTypes = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)->get();
        $tadaTypeArray = [];

        if ($tadaTypes) {
            foreach ($tadaTypes as $key => $tadaType) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$tadaType->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['name'] = $tadaType->name;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$tadaType->id."' ".($tadaType->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('tadatype.edit', $tadaType->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $tadaTypeArray[] = $data;
            }
        }

        return $tadaTypeArray;
    }

    public function create(array $requestData)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, null);
    }

    public function update(array $requestData, TadaType $tadatype)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');
        $requestData['is_date'] = $requestData['is_date'] ?? config('constants.BOOLEANINACTIVE');
        $requestData['is_amount'] = $requestData['is_amount'] ?? config('constants.BOOLEANINACTIVE');
        $requestData['is_photo'] = $requestData['is_photo'] ?? config('constants.BOOLEANINACTIVE');
        $requestData['is_location'] = $requestData['is_location'] ?? config('constants.BOOLEANINACTIVE');
        $requestData['is_expense_name'] = $requestData['is_expense_name'] ?? config('constants.BOOLEANINACTIVE');

        return $this->repository->updateOrCreate($requestData, $tadatype);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDeleteDependancy($requestData, 'tadas', 'TadaType');
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
