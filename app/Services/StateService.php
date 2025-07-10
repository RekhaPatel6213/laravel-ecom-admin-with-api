<?php

namespace App\Services;

use App\Models\State;
use App\Repositories\StateRepository;

class StateService
{
    protected $repository;

    public function __construct(StateRepository $stateRepository)
    {
        $this->repository = $stateRepository;
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'sort_order') ?? 'sort_order';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $states = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)
            ->select('id', 'name', 'country_id', 'sort_order', 'status', 'zone_id')
            ->with(['country:id,name', 'zone:id,name'])
            ->get();
        $stateArray = [];

        if ($states) {
            foreach ($states as $key => $state) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$state->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['country_name'] = $state->country->name ?? '';
                $data['zone_name'] = $state->zone->name ?? '';
                $data['name'] = $state->name;
                $data['sort_order'] = $state->sort_order;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$state->id."' ".($state->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('state.edit', $state->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $stateArray[] = $data;
            }
        }

        return $stateArray;
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

    public function update(array $requestData, State $state)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, $state);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDeleteDependancy($requestData, 'cities', 'State');
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
