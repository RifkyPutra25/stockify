<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $logs = $this->filteredQuery($request)->paginate(20)->withQueryString();

        return view('activity-logs.index', compact('logs', 'users'));
    }

    public function export(Request $request): StreamedResponse
    {
        $logs = $this->filteredQuery($request)->get();
        $filename = 'aktivitas-pengguna-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Waktu', 'Pengguna', 'Aksi', 'Deskripsi'], ';');

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user->name ?? '-',
                    $log->action,
                    $log->description,
                ], ';');
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function filteredQuery(Request $request)
    {
        return ActivityLog::with('user')
            ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->latest();
    }
}