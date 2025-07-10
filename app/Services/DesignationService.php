<?php

namespace App\Services;

use App\Models\Designation;
use App\Repositories\DesignationRepository;

class DesignationService
{
    protected $repository;

    public function __construct(DesignationRepository $designationRepository)
    {
        $this->repository = $designationRepository;
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'sort_order') ?? 'sort_order';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $designations = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)->select('id', 'name', 'sort_order', 'status')->get();
        $designationArray = [];

        if ($designations) {
            foreach ($designations as $key => $designation) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$designation->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['name'] = $designation->name;
                $data['sort_order'] = $designation->sort_order;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$designation->id."' ".($designation->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('designation.edit', $designation->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $designationArray[] = $data;
            }
        }

        return $designationArray;
    }

    public function getLastSortId()
    {
        return $this->repository->getLastSortId('sort_order');
    }

    public function create(array $requestData)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, null);
    }

    public function update(array $requestData, Designation $designation)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, $designation);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDeleteDependancy($requestData, 'users', 'Designation');
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
