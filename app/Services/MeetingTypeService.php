<?php

namespace App\Services;

use App\Models\MeetingType;
use App\Repositories\MeetingTypeRepository;

class MeetingTypeService
{
    protected $repository;

    public function __construct(MeetingTypeRepository $meetingTypeRepository)
    {
        $this->repository = $meetingTypeRepository;
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'name') ?? 'name';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $meetingTypes = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)->get();
        $meetingTypeArray = [];

        if ($meetingTypes) {
            foreach ($meetingTypes as $key => $meetingType) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$meetingType->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['name'] = $meetingType->name;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$meetingType->id."' ".($meetingType->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('meetingtype.edit', $meetingType->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $meetingTypeArray[] = $data;
            }
        }

        return $meetingTypeArray;
    }

    public function create(array $requestData)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, null);
    }

    public function update(array $requestData, MeetingType $meetingtype)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, $meetingtype);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDeleteDependancy($requestData, 'meetings', 'MeetingType');
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
