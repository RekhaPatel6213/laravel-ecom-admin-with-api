<?php

namespace App\Services;

use App\Models\Area;
use App\Repositories\AreaRepository;
use App\Repositories\ShopRepository;

class AreaService
{
    protected $repository;

    public function __construct(AreaRepository $areaRepository)
    {
        $this->repository = $areaRepository;
    }

    public function apiList($requestData)
    {
        $distributorId = isset($requestData['distributor_id']) && $requestData['distributor_id'] > 0 ? [$requestData['distributor_id']] : null;
        /*$cityId = isset($requestData['city_id']) && $requestData['city_id'] > 0 ? [$requestData['city_id']] : null;
        $areaIds = null;
        if ($distributorId !== null) {
            $distributorId = isset($requestData['distributor_id']) && $requestData['distributor_id'] > 0 ? $requestData['distributor_id'] : null;
            $shopRepository = new ShopRepository;
            $areaIds = $shopRepository->getPluck('area_id', 'area_id', 'distributor_id', $distributorId); // ->toArray();
        }*/

        $areas = Area::select('id', 'name')
            /*->when($cityId !== null, function ($query) use ($cityId) {
                $query->whereIn('city_id', $cityId);
            })
            ->when($areaIds != null, function ($query) use ($areaIds) {
                $query->whereIn('id', $areaIds);
            })*/
            ->when($distributorId != null, function ($query) use ($distributorId) {
                $query->where('distributor_id', $distributorId);
            })
            ->where('status', config('constants.ACTIVE'))
            ->orderBy('name', 'ASC')
            ->get();

        return $areas;
    }

    public function apiCreate(array $requestData)
    {
        $requestData['status'] = config('constants.ACTIVE');
        $requestData['sort_order'] = $this->getLastSortId();
        $area = $this->repository->updateOrCreate($requestData, null);

        return [['area_id' => $area->id]];
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'sort_order') ?? 'sort_order';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $areas = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)
            ->select('id', 'name', 'distributor_id', 'sort_order', 'status')
            ->with('distributor:id,firstname,lastname')
            ->get();
        $areaArray = [];

        if ($areas) {
            foreach ($areas as $key => $area) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$area->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['name'] = $area->name;
                $data['distributor_name'] = $area->distributor ? $area->distributor->firstname.' '.$area->distributor->lastname : '-';
                $data['sort_order'] = $area->sort_order;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$area->id."' ".($area->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('area.edit', $area->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $areaArray[] = $data;
            }
        }

        return $areaArray;
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

    public function update(array $requestData, Area $area)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, $area);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDeleteDependancy($requestData, 'distributor', 'Area');
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
