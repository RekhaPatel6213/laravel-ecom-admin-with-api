<?php

namespace App\Services;

use App\Http\Resources\DistributorResource;
use App\Models\Distributor;
use App\Repositories\AddressRepository;
use App\Repositories\DistributorRepository;
use App\Traits\FileTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DistributorService
{
    use FileTrait;

    protected $repository;
    protected $addressRepository;

    public function __construct(DistributorRepository $distributorRepository, AddressRepository $addressRepository)
    {
        $this->repository = $distributorRepository;
        $this->addressRepository = $addressRepository;
    }

    public function apiList(array $requestData)
    {
        $distributorIds = Auth::user()->distributor_id;
        $zoneId = Auth::user()->zone_id;
        $search = $requestData['search'] ?? null;

        $distributors = $this->repository->getQueryBuilder(null, 'firstname', 'asc')
            ->select('id', 'firstname', 'lastname', 'firstname as fullname', 'email', 'mobile', 'country_id', 'state_id', 'city_id', 'address', 'pincode', 'zone_id', 'is_interested', 'cst_gst_no', 'area_of_operation', 'current_dealership')
            ->when($distributorIds !== null, function (Builder $query) use ($distributorIds) {
                $query->whereIn('id', $distributorIds);
            })
            ->where('status', config('constants.ACTIVE'))
            ->where('zone_id', $zoneId)
            // ->when((isset($requestData['notInterested']) && $requestData['notInterested'] === 'A'), function (Builder $query) {
            //     // $query->where('is_interested', '!=', config('constants.MEETING_NOT_INTERESTED'));
            // }, function (Builder $query) {
            //     $query->where('is_interested', '!=', config('constants.MEETING_NOT_INTERESTED'));
            // })
            ->when((isset($requestData['disType']) && (int) $requestData['disType'] !== 0), function (Builder $query) use ($requestData) {
                $query->where('is_interested', (int) $requestData['disType']);
            })
            ->when($search !== null, function (Builder $query) use ($search) {
                $query->where(function ($queryData) use ($search) {
                    $queryData->where('firstname', 'like', '%'.$search.'%')
                        ->orWhere('lastname', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('mobile', 'like', '%'.$search.'%')
                        ->orWhere('cst_gst_no', 'like', '%'.$search.'%')
                        ->orWhereHas('state', function ($subQuery1) use ($search) {
                            $subQuery1->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('city', function ($subQuery1) use ($search) {
                            $subQuery1->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('zone', function ($subQuery1) use ($search) {
                            $subQuery1->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('meetingType', function ($subQuery1) use ($search) {
                            $subQuery1->where('name', 'like', "%$search%");
                        });
                });
            })
            ->with([
                'country:id,name',
                'state:id,name',
                'city:id,name',
                'zone:id,name',
                'meetingType:id,name',
            ])
            ->get();

        return DistributorResource::collection($distributors);
    }

    public function apiCreate(array $requestData)
    {
        \Log::info($requestData);
        $requestData['status'] = config('constants.ACTIVE');
        $requestData['gst_doc'] = $this->storeFile('gst_doc');
        $requestData['pan_doc'] = $this->storeFile('pan_doc');
        $requestData['zone_id'] = Auth::user()->zone_id;

        $distributor = $this->repository->updateOrCreate($requestData, null);

        // Create Address
        $requestData['first_name'] = $requestData['firstname'];
        $requestData['last_name'] = $requestData['lastname'];
        $requestData['mobile_no'] = $requestData['mobile'];
        $requestData['flat'] = $requestData['address'] ?? '';
        $requestData['default_address'] = 1;
        $address = $distributor->addresses()->create($requestData);

        $user = Auth::user();
        $user->distributor_id = array_merge($user->distributor_id, [$distributor->id]);
        $user->save();

        return [['distributor_id' => $distributor->id]];
    }

    public function apiUpdate(array $requestData)
    {
        $distributor = Distributor::find($requestData['distributor_id']);
        $distributor->is_interested = $requestData['is_interested'];
        $distributor->save();

        return [['distributor_id' => $distributor->id]];
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'firstname') ?? 'firstname';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $distributors = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)
            ->select('id', 'firstname', 'lastname', 'email', 'mobile', 'status', 'zone_id', 'is_interested', 'area_of_operation', 'current_dealership','cst_gst_no')
            ->with(['zone:id,name', 'meetingType:id,name'])
            ->get();
        $distributorArray = [];

        if ($distributors) {
            foreach ($distributors as $key => $distributor) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$distributor->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['firstname'] = $distributor->firstname;
                $data['lastname'] = $distributor->lastname;
                $data['area_of_operation'] = $distributor->area_of_operation ?? '';
                $data['current_dealership'] = $distributor->current_dealership ?? '';
                $data['email'] = $distributor->email;
                $data['mobile'] = $distributor->mobile;
                $data['zone'] = $distributor->zone->name ?? '';
                $data['cst_gst_no'] = $distributor->cst_gst_no ?? '';
                // $data['is_interested'] = (int)$distributor->is_interested === 2 ? 'No' : ((int)$distributor->is_interested === 1 ? 'Yes' : '');
                $data['is_interested'] = $distributor->meetingType ? $distributor->meetingType->name : '';
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$distributor->id."' ".($distributor->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('distributor.edit', $distributor->id)."' title='Edit'><i class='fa fa-edit'></i></a>
                <a href='".route('address.index', ['distributor', $distributor->id])."' title='Address'><i class='fa fa-home'></i></a>";
                $distributorArray[] = $data;
            }
        }

        return $distributorArray;
    }

    public function create(array $requestData)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');
        $requestData['is_interested'] = $requestData['is_interested'] ?? 0;
        $requestData['gst_doc'] = $this->getFileImage($requestData['gst_doc']);
        $requestData['pan_doc'] = $this->getFileImage($requestData['pan_doc']);

        $distributor = $this->repository->updateOrCreate($requestData, null);

        // Create Address
        $requestData['first_name'] = $requestData['firstname'];
        $requestData['last_name'] = $requestData['lastname'];
        $requestData['mobile_no'] = $requestData['mobile'];
        $requestData['flat'] = $requestData['address'];
        $requestData['default_address'] = 1;
        $address = $distributor->addresses()->create($requestData);

        return $distributor;
    }

    public function update(array $requestData, Distributor $distributor)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');
        $requestData['is_interested'] = $requestData['is_interested'] ?? 0;
        $requestData['gst_doc'] = $this->getFileImage($requestData['gst_doc'], $requestData['edit_gst_doc']);
        $requestData['pan_doc'] = $this->getFileImage($requestData['pan_doc'], $requestData['edit_pan_doc']);

        $distributor = $this->repository->updateOrCreate($requestData, $distributor);

        // Update Address
        $address = $distributor->addresses->first();

        $requestData['first_name'] = $requestData['firstname'];
        $requestData['last_name'] = $requestData['lastname'];
        $requestData['mobile_no'] = $requestData['mobile'];
        $requestData['flat'] = $requestData['address'];

        if (empty($address)) {
            $distributor->addresses()->create($requestData);
        } else {
            // $distributor->addresses()->updateOrCreate($requestData, $address->toArray());
            $this->addressRepository->updateOrCreate($requestData, $address);
        }

        return $distributor;
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
