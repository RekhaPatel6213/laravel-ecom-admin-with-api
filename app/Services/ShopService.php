<?php

namespace App\Services;

use App\Http\Resources\ShopResource;
use App\Models\Distributor;
use App\Models\Shop;
use App\Repositories\ShopRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ShopService
{
    protected $repository;

    public function __construct(ShopRepository $shopRepository)
    {
        $this->repository = $shopRepository;
    }

    public function apiList(array $requestData, ?Distributor $distributor)
    {
        $areaId = isset($requestData['area_id']) && $requestData['area_id'] > 0 ? $requestData['area_id'] : null;
        $distributorIds = (isset($distributor->id)) ? [$distributor->id] : Auth::user()->distributor_id;
        $search = $requestData['search'] ?? null;

        $shops = $this->repository->getQueryBuilder(null, 'name', 'asc')
            ->select('id', 'name', 'contact_person_name', 'email', 'mobile', 'country_id', 'state_id', 'city_id', 'area_id', 'address', 'pincode', 'gstin_no')
            ->when($distributorIds != null, function ($query) use ($distributorIds) {
                $query->whereIn('distributor_id', $distributorIds);
            })
            ->where('status', config('constants.ACTIVE'))
            ->when($areaId != null, function ($query) use ($areaId) {
                $query->where('area_id', $areaId);
            })
            ->when($search !== null, function (Builder $query) use ($search) {
                $query->where(function ($queryData) use ($search) {
                    $queryData->where('name', 'like', '%' . $search . '%')
                        ->orWhere('contact_person_name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%')
                        ->orWhere('mobile', 'like', '%' . $search . '%')
                        ->orWhere('gstin_no', 'like', '%' . $search . '%')
                        ->orWhereHas('state', function ($subQuery1) use ($search) {
                            $subQuery1->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('city', function ($subQuery1) use ($search) {
                            $subQuery1->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('area', function ($subQuery1) use ($search) {
                            $subQuery1->where('name', 'like', "%$search%");
                        });
                });
            })
            ->with([
                'country:id,name',
                'state:id,name',
                'city:id,name',
                'area:id,name',
            ])
            ->get();

        return ShopResource::collection($shops);
    }

    public function apiCreate(array $requestData)
    {
        \Log::info($requestData);
        $requestData['status'] = config('constants.ACTIVE');
        $shop = $this->repository->updateOrCreate($requestData, null);

        return [['shop_id' => $shop->id]];
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'name') ?? 'name';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $shops = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)
            ->select('id', 'name', 'contact_person_name', 'email', 'mobile', 'status', 'zone_id', 'state_id', 'city_id', 'area_id', 'gstin_no', 'pincode', 'shop_area', 'distributor_id')
            ->with(['zone:id,name', 'area', 'state', 'city', 'distributor'])
            ->get();
        $shopArray = [];

        if ($shops) {
            foreach ($shops as $key => $shop) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="' . $shop->id . '" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['name'] = $shop->name;
                $data['contact_person_name'] = $shop->contact_person_name;
                $data['distributor'] = ($shop->distributor->firstname ?? '') . ' ' . ($shop->distributor->lastname ?? '');
                $data['gstin_no'] = $shop->gstin_no;
                $data['email'] = $shop->email;
                $data['mobile'] = $shop->mobile;
                $data['state'] = $shop->state->name ?? '';
                $data['city'] = $shop->city->name ?? '';
                $data['area'] = $shop->area->name ?? '';
                $data['shop_area'] = $shop->shop_area ?? '';
                $data['zone'] = $shop->zone->name ?? '';
                $data['pincode'] = $shop->pincode ?? '';
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='" . $shop->id . "' " . ($shop->status == config('constants.ACTIVE') ? 'checked' : '') . '/></div>';
                $data['action'] = "<a href='" . route('shop.edit', $shop->id) . "' title='Edit'><i class='fa fa-edit'></i></a>";
                $shopArray[] = $data;
            }
        }

        return $shopArray;
    }

    public function create(array $requestData)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, null);
    }

    public function update(array $requestData, Shop $shop)
    {
        $requestData['status'] = $requestData['status'] ?? config('constants.INACTIVE');

        return $this->repository->updateOrCreate($requestData, $shop);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDelete($requestData);
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
