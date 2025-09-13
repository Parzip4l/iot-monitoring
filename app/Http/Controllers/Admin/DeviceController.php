<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Models\General\Device;

use App\Models\TrainManagement\TrainConfig;
use App\Models\TrainManagement\TrainCars;
use App\Models\General\MqttSetting;

class DeviceController extends Controller
{
    /**
     * List roles
     */
    public function index()
    {
        $devices = Device::all();
        return view('pages.devices.index', compact('devices'));
    }

    /**
     * Form create
     */
    public function create()
    {
        $train = TrainConfig::all();
        $cars = TrainCars::all();
        return view('pages.devices.create', compact('train','cars'));
    }

    /**
     * Store new role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:devices,name',
            'serial_number' => 'required|string|unique:devices,serial_number',
            'topic' => 'required|string',
            'car_id' => 'required|string',
            'train_id' => 'required|string',
            'broker_ip' => 'required|string',
            'broker_port' => 'required|string',
            'type' => 'required|string',
            'interval' => 'required|integer',
        ]);

        try {
            $device = Device::create([
                'name'          => strtolower($request->name),
                'serial_number' => $request->serial_number,
                'status'        => 'active',
                'topic'         => $request->topic,
                'user_id'       => 1,
                'car_id'        => $request->car_id,
                'train_id'      => $request->train_id,
                'broker_ip'     => $request->broker_ip,
                'broker_port'   => $request->broker_port,
                'type'          => $request->type,
            ]);

            MqttSetting::create([
                'device_id' => $device->id,
                'topic' => $request->topic,
                'interval' => $request->interval,
            ]);

            return redirect()
                ->route('device.index')
                ->with('success', 'Device berhasil ditambahkan');
        } catch (\Exception $e) {
            // Bisa juga ditambahkan log error
            \Log::error('Error saat menambahkan device: '.$e->getMessage());

            return redirect()
                ->route('device.index')
                ->with('error', 'Terjadi kesalahan saat menambahkan device.');
        }
    }

    /**
     * Form edit role
     */
    public function edit(Device $device)
    {
        $train = TrainConfig::all();
        $cars = TrainCars::all();
        return view('pages.devices.edit', compact('train','device','cars'));
    }

    /**
     * Update role
     */
    public function update(Request $request, Device $device)
    {
        $request->validate([
            'name' => 'required|string|unique:devices,name,' . $device->id,
            'serial_number' => [
                'required',
                'string',
                Rule::unique('devices', 'serial_number')->ignore($device->id),
            ],
            'topic' => 'required|string',
            'car_id' => 'required|string',
            'train_id' => 'required|string',
            'broker_ip' => 'required|string',
            'broker_port' => 'required|string',
            'type' => 'required|string',
        ]);

        try {
            $device->update([
                'name' => strtolower($request->name),
                'serial_number' => $request->serial_number,
                'topic' => $request->topic,
                'car_id' => $request->car_id,
                'train_id' => $request->train_id,
                'broker_ip' => $request->broker_ip,
                'broker_port' => $request->broker_port,
                'type' => $request->type,
            ]);

            return redirect()
                ->route('device.index')
                ->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            \Log::error('Error saat update data '.$device->id.': '.$e->getMessage());

            return redirect()
                ->route('device.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    /**
     * Hapus role
     */
    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->route('device.index')->with('success', 'Data berhasil dihapus');
    }

    
}
