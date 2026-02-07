<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $divisionId = $user->division_id;

        // Admin hanya melihat aktivitas di divisinya sendiri
        if (!$divisionId) {
            return view('activity_logs.index', [
                'activityLogs' => collect(),
                'users' => collect(),
                'filters' => []
            ]);
        }

        $query = ActivityLog::with(['user.division', 'division'])
            ->where('division_id', $divisionId)
            ->latest();

        // Filter berdasarkan action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter berdasarkan user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activityLogs = $query->paginate(20)->withQueryString();

        // Get users in the same division for filter
        $users = \App\Models\User::where('division_id', $divisionId)
            ->orderBy('name')
            ->get();

        $filters = [
            'action' => $request->action,
            'user_id' => $request->user_id,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ];

        return view('activity_logs.index', compact('activityLogs', 'users', 'filters'));
    }
}
