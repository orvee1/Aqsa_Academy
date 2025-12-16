<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FooterSettingRequest;
use App\Models\FooterSetting;

class FooterSettingController extends Controller
{
    public function edit()
    {
        $setting = FooterSetting::query()->firstOrCreate([]);
        return view('admin.footer.settings', compact('setting'));
    }

    public function update(FooterSettingRequest $request)
    {
        $setting = FooterSetting::query()->firstOrCreate([]);
        $setting->update($request->validated());

        return back()->with('success', 'Footer settings updated.');
    }
}
