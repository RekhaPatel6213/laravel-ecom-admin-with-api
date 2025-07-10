<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Traits\FileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    use FileTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $stateList = getStates('name');

        return view('setting.index', compact('stateList'));
    }

    public function setting_submit(Request $request)
    {
        $request->merge(['company_logo' => $this->getFileImage($request->company_logo, $request->edit_company_logo ?? null)]);
        $request->merge(['company_fav_logo' => $this->getFileImage($request->company_fav_logo, $request->edit_company_fav_logo ?? null)]);

        $result = Setting::where('master_key', '=', 'config')->get();
        $request = $request->all();

        if (($request) != 'null' && ! empty($request)) {
            foreach ($request as $key => $value) {
                if ($key != '_token') {
                    if (! empty($result)) {
                        foreach ($result as $k => $val) {
                            if ($val['config_key'] == $key) {

                                if (is_array($value) && $key == 'quality') {
                                    foreach ($value as $key => $arrayValues) {
                                        $icon = explode('storage/', $arrayValues['icon']);
                                        $value[$key]['icon'] = $icon[1] ?? $icon[0];
                                    }
                                    $value = json_encode($value);
                                    // dd( $value );
                                }

                                Setting::where('id', $val['id'])->update(['config_value' => $value]);
                            }
                        }
                    }
                }
            }
        }

        $noKeys = array_diff(array_keys($request), data_get($result, '*.config_key'));
        if ($noKeys) {
            foreach ($noKeys as $key => $value) {
                if (! str_contains($value, 'edit_') && $value !== '_token') {

                    if (is_array($request[$value]) && $value == 'quality') {
                        foreach ($request[$value] as $key => $arrayValues) {
                            $icon = explode('storage/', $arrayValues['icon']);
                            $request[$value][$key]['icon'] = $icon[1];
                        }
                        $request[$value] = json_encode($request[$value]);
                    }

                    Setting::create([
                        'master_key' => 'config',
                        'config_key' => $value,
                        'config_value' => $request[$value],
                    ]);
                }
            }
        }
        Cache::forget('configSetting');
        $setting = Setting::select('config_key', 'config_value')->pluck('config_value', 'config_key')->toArray();
        Cache::put('configSetting', $setting);

        return redirect()->route('setting.index')->with('success_message', 'Data Successfully Submitted');
    }
}
