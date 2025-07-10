<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Distributor;
use App\Models\User;
use App\Repositories\AddressRepository;

class AddressService
{
    protected $repository;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->repository = $addressRepository;
    }

    public function list($requestData, $userType, $userId)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'first_name') ?? 'first_name';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $addresses = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)
            ->when(($userType === 'distributor'), function ($query) {
                $query->where('addressable_type', Distributor::class);
            }, function ($query) {
                $query->where('addressable_type', User::class);
            })
            ->where('addressable_id', $userId)
            ->select('id', 'first_name', 'last_name', 'flat', 'area', 'pincode', 'country_id', 'state_id', 'city_id', 'default_address')
            ->with(['country:id,name', 'state:id,name', 'city:id,name'])
            ->get();
        $addressArray = [];

        if ($addresses) {
            foreach ($addresses as $key => $address) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$address->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['name'] = ($address->first_name ?? '').' '.($address->last_name ?? '');
                $data['address1'] = $address->flat;
                $data['address2'] = $address->area;
                $data['pincode'] = $address->pincode;
                $data['country'] = $address->country->name;
                $data['state'] = $address->state->name;
                $data['city'] = $address->city->name;
                $data['default_address'] = $address->default_address === 1 ? 'Yes' : 'No';
                $data['action'] = "<a href='".route('address.edit', [$userType, $userId, $address->id])."' title='Edit'><i class='fa fa-edit'></i></a>";
                $addressArray[] = $data;
            }
        }

        return $addressArray;
    }

    public function create(array $requestData, $userType, $userId)
    {

        $requestData['default_address'] = $requestData['default_address'] ?? 0;
        $requestData['addressable_type'] = $userType === 'distributor' ? Distributor::class : User::class;
        $requestData['addressable_id'] = $userId;

        // dd($requestData);
        return $this->repository->updateOrCreate($requestData, null);
    }

    public function update(array $requestData, Address $address)
    {
        $requestData['default_address'] = $requestData['default_address'] ?? 0;

        return $this->repository->updateOrCreate($requestData, $address);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDelete($requestData);
    }
}
