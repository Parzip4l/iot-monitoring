<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\General\SensorThreshold;
use App\Models\General\Device;
use App\Models\General\Anomaly;
use App\Models\TrainManagement\TrainConfig;
use App\Models\TrainManagement\TrainCars;

class SensorThresholdController extends Controller
{
    public function index()
    {
        $thresholds = SensorThreshold::with('device')->get();
        $devices = Device::all();
        return view('pages.sensorthreshold.index', compact('thresholds', 'devices'));
    }

    public function create()
    {
        $devices = Device::all();
        $sensorTypes = ['temperature', 'humidity', 'noise'];
        return view('pages.sensorthreshold.create', compact('devices', 'sensorTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|string',
            'sensor_type' => 'required|string',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric',
        ]);

        SensorThreshold::updateOrCreate(
            ['device_id' => $validated['device_id'], 'sensor_type' => $validated['sensor_type']],
            ['min_value' => $validated['min_value'], 'max_value' => $validated['max_value']]
        );

        return redirect()->route('sensor-threshold.index')
            ->with('success', 'Threshold berhasil disimpan');
    }

    public function edit(SensorThreshold $sensorThreshold)
    {
        $devices = Device::all();
        $sensorTypes = ['temperature', 'humidity', 'noise'];
        return view('pages.sensorthreshold.edit', compact('sensorThreshold','devices','sensorTypes'));
    }

    public function update(Request $request, SensorThreshold $sensorThreshold)
    {
        $validated = $request->validate([
            'device_id' => 'required|string',
            'sensor_type' => 'required|string',
            'min_value' => 'nullable|numeric',
            'max_value' => 'nullable|numeric',
        ]);

        $sensorThreshold->update($validated);

        return redirect()->route('sensor-threshold.index')
            ->with('success', 'Threshold berhasil diperbarui');
    }

    public function destroy(SensorThreshold $sensorThreshold)
    {
        $sensorThreshold->delete();
        return redirect()->route('sensor-threshold.index')
            ->with('success', 'Threshold berhasil dihapus');
    }

    public function indexAnomaly(Request $request)
    {
        $query = Anomaly::with(['device', 'device.train', 'device.cars']); // eager load

        // Filter by device
        if ($request->filled('device_id')) {
            $query->where('device_id', $request->device_id);
        }

        // Filter by train
        if ($request->filled('train_id')) {
            $query->whereHas('device.train', fn($q) => $q->where('id', $request->train_id));
        }

        // Filter by car
        if ($request->filled('car_id')) {
            $query->whereHas('device.cars', fn($q) => $q->where('id', $request->car_id));
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date.' 00:00:00', $request->end_date.' 23:59:59']);
        }

        // Filter by month/year
        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('created_at', $request->month)
                ->whereYear('created_at', $request->year);
        }

        $anomalies = $query->get();

        // Ambil list device, train, car untuk filter
        $devices = Device::all();
        $trains  = TrainConfig::all();
        $cars    = TrainCars::all();

        return view('pages.alerts.anomali.index', compact('anomalies', 'devices', 'trains', 'cars'));
    }

}
