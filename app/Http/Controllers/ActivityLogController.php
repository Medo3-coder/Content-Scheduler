<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::where('user_id', $request->user()->id)
            ->latest()
            ->take(100)
            ->get();

        return response()->json($logs);
    }
}
