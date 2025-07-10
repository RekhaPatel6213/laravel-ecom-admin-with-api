<?php

namespace App\Services;

use App\Models\City;
use App\Repositories\CityRepository;

class CityService
{
    protected $repository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->repository = $cityRepository;
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'sort_order') ?? 'sort_order';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $cities = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)
            ->select('id', 'name', 'state_id', 'sort_order', 'status')
            ->with(['state:id,name,country_id', 'state.country:id,name'])
            ->get();
        $cityArray = [];

        if ($cities) {
            foreach ($cities as $key => $city) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$city->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['country_name'] = $city->state->country->name ?? '';
                $data['state_name'] = $city->state->name ?? '';
                $data['name'] = $city->name;
                $data['sort_order'] = $city->sort_order;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$city->id."' ".($city->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('city.edit', $city->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $cityArray[] = $data;
            }
        }

        return $cityArray;
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

    public function update(array $requestData, City $city)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, $city);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDeleteDependancy($requestData, 'areas', 'City');
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
