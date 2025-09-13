<?php

namespace App\Http\Controllers\TrainManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrainManagement\TrainConfig;
use App\Models\TrainManagement\TrainCars;

use Illuminate\Validation\Rule;

class CarsController extends Controller
{
    /**
     * List roles
     */
    public function index()
    {
        $cars = TrainCars::all();
        return view('pages.admin.trainmanagement.gerbong.index', compact('cars'));
    }

    /**
     * Form create
     */
    public function create()
    {
        $train = TrainConfig::all();
        return view('pages.admin.trainmanagement.gerbong.create', compact('train'));
    }

    /**
     * Store new role
     */
    public function store(Request $request)
    {
        $request->validate([
            'train_id' => 'required|exists:train_config,id',
            'car_number' => [
                'required',
                'integer',
                'min:1',
                'max:99',
                Rule::unique('train_cars')->where(function ($query) use ($request) {
                    return $query->where('train_id', $request->train_id);
                }),
            ],
            'car_type' => 'required|string',
        ], [
            'car_number.unique' => 'Nomor gerbong ini sudah ada untuk train yang dipilih.',
        ]);

        TrainCars::create([
            'train_id' => $request->train_id,
            'car_number' => $request->car_number,
            'car_type' => $request->car_type,
        ]);

        return redirect()->route('cars.config.index')->with('success', 'Gerbong berhasil ditambahkan.');
    }

    /**
     * Form edit role
     */
    public function edit(TrainCars $cars)
    {
        $train = TrainConfig::all();
        return view('pages.admin.trainmanagement.gerbong.edit', compact('cars','train'));
    }

    /**
     * Update role
     */
    public function update(Request $request, TrainCars $cars)
    {
        $request->validate([
            'train_id' => 'required|exists:train_config,id',
            'car_number' => [
                'required',
                'integer',
                'min:1',
                'max:99',
                Rule::unique('train_cars')->where(function ($query) use ($request) {
                    return $query->where('train_id', $request->train_id);
                })->ignore($cars->id),
            ],
            'car_type' => 'required|string',
        ], [
            'car_number.unique' => 'Nomor gerbong ini sudah ada untuk train yang dipilih.',
        ]);

        $cars->update([
            'train_id' => $request->train_id,
            'car_number' => $request->car_number,
            'car_type' => $request->car_type,
        ]);

        return redirect()->route('cars.config.index')->with('success', 'Data berhasil diperbarui');
    }


    /**
     * Hapus role
     */
    public function destroy(TrainCars $cars)
    {
        $cars->delete();
        return redirect()->route('cars.config.index')->with('success', 'Data berhasil dihapus');
    }

    
}
