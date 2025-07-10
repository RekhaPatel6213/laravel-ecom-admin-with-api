<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\MeetingType;
use App\Models\State;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function country_list(Request $request)
    {
        try {
            $countries = Country::select('id', 'name')->where('status', config('constants.ACTIVE'))->orderBy('name', 'ASC')->get();

            return $this->sendResponse($countries, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    public function state_list(Request $request)
    {
        try {
            $states = State::select('id', 'name')->where('country_id', $request->country_id)->where('status', config('constants.ACTIVE'))->orderBy('name', 'ASC')->get();

            return $this->sendResponse($states, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    public function city_list(Request $request)
    {
        try {
            $cities = City::select('id', 'name')->where('state_id', $request->state_id)->where('status', config('constants.ACTIVE'))->orderBy('name', 'ASC')->get();

            return $this->sendResponse($cities, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    public function meeting_type_list(Request $request)
    {
        try {
            $meetingType = MeetingType::select('id', 'name')->where('status', config('constants.ACTIVE'))->orderBy('name', 'ASC')->get();

            return $this->sendResponse($meetingType, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }
}
