<?php

namespace App\Http\Controllers\TrainManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrainManagement\TrainConfig;
use App\Models\TrainManagement\TrainCars;

class TrainController extends Controller
{
    /**
     * List roles
     */
    public function index()
    {
        $train = TrainConfig::all();
        return view('pages.admin.trainmanagement.kereta.index', compact('train'));
    }

    /**
     * Form create
     */
    public function create()
    {
        return view('pages.admin.trainmanagement.kereta.create');
    }

    /**
     * Store new role
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:train_config,name',
            'description' => 'required|string',
            'total_gerbong' => 'required|integer',
        ]);

        try {
            TrainConfig::create([
                'name' => strtolower($request->name),
                'description' => $request->description,
                'total_gerbong' => $request->total_gerbong,

            ]);

            return redirect()
                ->route('train.config.index')
                ->with('success', 'Data berhasil ditambahkan');
        } catch (\Exception $e) {
            // Bisa juga ditambahkan log error
            \Log::error('Error saat menambahkan data: '.$e->getMessage());

            return redirect()
                ->route('train.config.index')
                ->with('error', 'Terjadi kesalahan saat menambahkan data.');
        }
    }

    /**
     * Form edit role
     */
    public function edit(TrainConfig $train)
    {
        return view('pages.admin.trainmanagement.kereta.edit', compact('train'));
    }

    /**
     * Update role
     */
    public function update(Request $request, TrainConfig $train)
    {
        $request->validate([
            'name' => 'required|string|unique:train_config,name,' . $train->id,
            'description' => 'required|string',
            'total_gerbong' => 'required|integer',
        ]);

        try {
            $train->update([
                'name' => strtolower($request->name),
                'description' => $request->description,
                'total_gerbong' => $request->total_gerbong,
            ]);

            return redirect()
                ->route('train.config.index')
                ->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            \Log::error('Error saat update Data '.$train->id.': '.$e->getMessage());

            return redirect()
                ->route('role.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }

    /**
     * Hapus role
     */
    public function destroy(TrainConfig $train)
    {
        $train->delete();
        return redirect()->route('train.config.index')->with('success', 'Data berhasil dihapus');
    }
    
    public function getCars($trainId)
    {
        $cars = TrainCars::where('train_id', $trainId)->get();
        return response()->json($cars);
    }

    
}
