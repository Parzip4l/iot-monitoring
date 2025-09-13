<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Catat hanya request yang mengubah data
        if ($request->isMethod('post') || $request->isMethod('put') || $request->isMethod('delete')) {
            $user = Auth::user() ? Auth::user()->name : 'Guest';
            $action = strtoupper($request->method()) . " " . $request->path();
            
            LogActivity::addToLog("{$user} melakukan {$action}");
        }

        return $response;
    }
}
