<?php

namespace App\Services;

use App\Models\OrderStatus;
use App\Repositories\OrderStatusRepository;

class OrderStatusService
{
    protected $repository;

    public function __construct(OrderStatusRepository $orderstatusRepository)
    {
        $this->repository = $orderstatusRepository;
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'order_status_name') ?? 'order_status_name';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $orderstatuses = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)->get();
        $orderstatusArray = [];

        if ($orderstatuses) {
            foreach ($orderstatuses as $key => $orderstatus) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$orderstatus->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $orderstatus->id; // $key + 1;
                $data['order_status_name'] = $orderstatus->order_status_name;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$orderstatus->id."' ".($orderstatus->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('orderstatus.edit', $orderstatus->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $orderstatusArray[] = $data;
            }
        }

        return $orderstatusArray;
    }

    public function create(array $requestData)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, null);
    }

    public function update(array $requestData, OrderStatus $orderstatus)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, $orderstatus);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDeleteDependancy($requestData, 'orders', 'OrderStatus');
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
