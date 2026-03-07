<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityController extends Controller
{
    public function markAllRead(Request $request)
    {
        ActivityLog::where('is_read', false)->update(['is_read' => true]);
        return back();
    }
}
