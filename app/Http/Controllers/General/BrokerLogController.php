<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\General\BrokerLog;

class BrokerLogController extends Controller
{

    public function index()
    {
        $broker = BrokerLog::all();
        return view('pages.broker.index',compact('broker'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'broker_ip'        => 'required|string',
            'broker_port'      => 'required|integer',
            'status'           => 'required|in:connected,disconnected',
            'connected_at'     => 'nullable|date',
            'disconnected_at'  => 'nullable|date',
        ]);

        $log = BrokerLog::create($validated);

        return response()->json([
            'message' => 'Broker log saved successfully',
            'data'    => $log
        ]);
    }
}
