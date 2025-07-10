<?php

namespace App\Services;

use App\Models\Distributor;
use App\Models\Meeting;
use App\Models\Route;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService
{
    protected $repository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;

        $this->meetingService = app('meeting.service');
        $this->orderService = app('order.service');
    }

    public function apiUpdate(array $requestData)
    {
        $user = Auth::user();
        $isPassword = true;
        if ($requestData['password'] === null) {
            unset($requestData['password']);
            $isPassword = false;
        }

        $user = $this->repository->updateOrCreate($requestData, $user, $isPassword);

        $data = $user->only('id', 'firstname', 'lastname', 'email', 'mobile', 'designation_id', 'role_id');
        $data['role'] = $user->role->name ?? null;
        $data['designation'] = $user->designation->name ?? null;

        return [$data];
    }

    public function getUserDetail(array $requestData)
    {
        $user = Auth::user();
        $data = ['is_route' => 0, 'is_meeting' => 0, 'route_id' => '', 'route_time' => '', 'is_meeting' => '', 'meeting_id' => '', 'meeting_time' => '', 'distributor_id' => '', 'distributor' => '', 'is_menu' => 0];

        $route = Route::where('user_id', $user->id)->whereNull(['end_time', 'end_latitude', 'end_longitude'])->first();
        if ($route) {
            $data['is_route'] = 1;
            $data['route_id'] = $route->id;
            $data['route_time'] = $route->start_time;
        }

        $meeting = Meeting::where('user_id', $user->id)->whereNull(['end_time', 'end_latitude', 'end_longitude'])->with('distributor:id,firstname,lastname,is_interested')->first();
        if ($meeting) {
            $data['is_meeting'] = 1;
            $data['meeting_id'] = $meeting->id;
            $data['meeting_time'] = $meeting->start_time;
            $data['distributor_id'] = $meeting->distributor_id;
            $data['distributor'] = ($meeting->distributor->firstname ?? '') . ' ' . ($meeting->distributor->lastname ?? '');
        }

        $users = $this->getUserList($requestData);
        if ($users) {
            $data['is_menu'] = 1;
        }

        $startDate = $endDate = date('Y-m-d');
        $meetingData = new Request([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'user_id' => $user->id,
            'report_parameter' => 'R'
        ]);

        $data['is_interested'] = $meeting->distributor->is_interested ?? 0;
        $data['todayMeetingList'] = $this->meetingService->apiList($meetingData);
        $data['todayMeetingCount'] = count($data['todayMeetingList']) ?? 0;
        $data['allMeetingCount'] = $this->meetingService->apiList(new Request(['user_id' => $user->id]))->count();

        $todayRetailSales = $this->orderService->apiList($meetingData)->toArray(request());
        $allRetailSales = $this->orderService->apiList(new Request(['user_id' => $user->id]))->toArray(request());

        $data['todayRetailSales'] = (count($todayRetailSales) ?? 0) . ' / ' . config('constants.currency_symbol') . ' ' . number_format(array_sum(data_get($todayRetailSales, '*.total_amount_no')));
        $data['allRetailSales'] = (count($allRetailSales) ?? 0) . ' / ' . config('constants.currency_symbol') . ' ' . number_format(array_sum(data_get($allRetailSales, '*.total_amount_no')));
        $data['daily_allowance'] = $user->daily_allowance;

        $orderCount = count($todayRetailSales) ?? 0;
        $noOrderCount = noOrderCount($user->id, $startDate, $endDate);
        $data['total_orders'] = $noOrderCount + $orderCount;
        $data['productive_orders'] = $orderCount;
        $data['unproductive_orders'] = $noOrderCount;
        $data['total_order_value'] = config('constants.currency_symbol') . ' ' . number_format(array_sum(data_get($todayRetailSales, '*.total_amount_no')));

        return [$data];
    }

    public function getUserList(array $requestData)
    {
        $designationId = Auth::user()->designation_id;
        $zoneId = Auth::user()->zone_id;

        $users = User::select('users.id as id', DB::raw('CONCAT(firstname," ",lastname) as name'), 'designations.name as designation')
            ->leftJoin('designations', 'users.designation_id', '=', 'designations.id')
            ->where('users.status', config('constants.ACTIVE'))
            ->where('users.designation_id', '>', $designationId)
            ->where('zone_id', $zoneId)
            ->orderBy('name', 'ASC')->get();

        return $users;
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'firstname') ?? 'firstname';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $users = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)
            ->select('id', 'firstname', 'lastname', 'email', 'mobile', 'status', 'designation_id', 'zone_id', 'daily_allowance', 'distributor_id')
            ->where('role_id', config('constants.COMPANY_ROLE_ID'))
            ->with(['designation:id,name', 'zone:id,name'])
            ->get();
        $userArray = [];

        if ($users) {
            foreach ($users as $key => $user) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="' . $user->id . '" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['name'] = $user->firstname . ' ' . $user->lastname;
                $data['email'] = $user->email;
                $data['mobile'] = $user->mobile;
                $data['designation'] = $user->designation->name ?? '';
                $data['zone'] = $user->zone->name ?? '';
                $data['daily_allowance'] = $user->daily_allowance ?? '';
                $distributorIds = is_array($user->distributor_id)
                    ? $user->distributor_id
                    : ($user->distributor_id ? explode(',', $user->distributor_id) : []);
                $data['distributors'] = !empty($distributorIds)
                    ? Distributor::whereIn('id', $distributorIds)->pluck('firstname')->implode(', ')
                    : '-';
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='" . $user->id . "' " . ($user->status == config('constants.ACTIVE') ? 'checked' : '') . '/></div>';
                $data['action'] = "<a href='" . route('user.edit', $user->id) . "' title='Edit'><i class='fa fa-edit'></i></a>";
                $userArray[] = $data;
            }
        }

        return $userArray;
    }

    public function create(array $requestData)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');
        $requestData['role_id'] = config('constants.COMPANY_ROLE_ID');

        return $this->repository->updateOrCreate($requestData, null, true);
    }

    public function update(array $requestData, User $user)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');
        $isPassword = true;
        if ($requestData['password'] === null) {
            unset($requestData['password']);
            $isPassword = false;
        }

        return $this->repository->updateOrCreate($requestData, $user, $isPassword);

    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDelete($requestData);
        // return $this->repository->bulkDeleteDependancy($requestData, 'state', 'Country');
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
