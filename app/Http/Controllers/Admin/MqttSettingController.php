<?php 

// app/Http/Controllers/Admin/MqttSettingController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\General\MqttSetting;
use App\Models\General\Device;
use Exception;

class MqttSettingController extends Controller
{
    public function index()
    {
        try {
            $mqtt = MqttSetting::with('device')->get(); // kalau ada relasi ke device
            return view('pages.admin.mqtt.index', compact('mqtt'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $device = Device::all();
            return view('pages.admin.mqtt.create', compact('device'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuka form: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'topic'     => 'required|string',
                'device_id' => 'nullable|string',
                'interval'  => 'required|integer|min:5',
            ]);

            MqttSetting::create($validated);

            return redirect()
                ->route('mqtt.index')
                ->with('success', 'Setting berhasil ditambahkan');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan setting: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $setting = MqttSetting::findOrFail($id);
            $device  = Device::all();

            return view('pages.admin.mqtt.edit', compact('setting', 'device'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuka form edit: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $setting = MqttSetting::findOrFail($id);

            $validated = $request->validate([
                'interval' => 'required|integer|min:5',
            ]);

            $setting->update($validated);

            return redirect()
                ->route('mqtt.index')
                ->with('success', 'Setting berhasil diupdate');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal update setting: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $setting = MqttSetting::findOrFail($id);
            $setting->delete();

            return redirect()
                ->route('mqtt.index')
                ->with('success', 'Setting berhasil dihapus');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus setting: ' . $e->getMessage());
        }
    }
}
