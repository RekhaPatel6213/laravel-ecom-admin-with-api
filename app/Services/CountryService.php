<?php

namespace App\Services;

use App\Models\Country;
use App\Repositories\CountryRepository;

class CountryService
{
    protected $repository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->repository = $countryRepository;
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'sort_order') ?? 'sort_order';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $countries = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)
            ->select('id', 'name', 'code', 'sort_order', 'status')
            ->get();
        $countryArray = [];

        if ($countries) {
            foreach ($countries as $key => $country) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$country->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['name'] = $country->name;
                $data['code'] = $country->code;
                $data['sort_order'] = $country->sort_order;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$country->id."' ".($country->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('country.edit', $country->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $countryArray[] = $data;
            }
        }

        return $countryArray;
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

    public function update(array $requestData, Country $country)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, $country);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDeleteDependancy($requestData, 'states', 'Country');
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
