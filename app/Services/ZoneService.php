<?php

namespace App\Services;

use App\Models\Zone;
use App\Repositories\ZoneRepository;

class ZoneService
{
    protected $repository;

    public function __construct(ZoneRepository $zoneRepository)
    {
        $this->repository = $zoneRepository;
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'name') ?? 'name';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $zones = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)->get();
        $zoneArray = [];

        if ($zones) {
            foreach ($zones as $key => $zone) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$zone->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['name'] = $zone->name;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$zone->id."' ".($zone->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('zone.edit', $zone->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $zoneArray[] = $data;
            }
        }

        return $zoneArray;
    }

    public function create(array $requestData)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, null);
    }

    public function update(array $requestData, Zone $zone)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, $zone);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDelete($requestData);
        // return $this->repository->bulkDeleteDependancy($requestData, 'productVariant', 'Zone');
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
