<?php

namespace App\Http\Controllers\Api\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\Jurusan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * GET api/kepsek/kpi
     * Query params: days (int)
     */
    public function getKpi(Request $request)
    {
        $days = (int) $request->query('days', 30);
        $since = Carbon::now()->subDays(max(1, $days - 1))->startOfDay();

        $total = Pendaftar::count();

        $verifiedCount = Pendaftar::whereIn('status', ['ADM_PASS', 'PAID'])->count();
        $ratioVerified = $total > 0 ? round(($verifiedCount / $total) * 100, 2) : 0.0;

        // gunakan created_at sebagai fallback jika kolom tanggal_daftar tidak tersedia
        $trendRows = Pendaftar::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->where('created_at', '>=', $since)
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date')
            ->map(fn($r) => (int)$r->count);

        $labels = [];
        $data = [];
        for ($i = 0; $i < $days; $i++) {
            $d = $since->copy()->addDays($i)->format('Y-m-d');
            $labels[] = $d;
            $data[] = (int) ($trendRows[$d] ?? 0);
        }

        $composition = Jurusan::leftJoin('pendaftar', 'jurusan.id', '=', 'pendaftar.jurusan_id')
            ->select('jurusan.id', 'jurusan.nama', DB::raw('count(pendaftar.id) as total'))
            ->groupBy('jurusan.id', 'jurusan.nama')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'total_pendaftar' => (int)$total,
            'ratio_verified_percent' => $ratioVerified,
            'trend' => [
                'labels' => $labels,
                'data' => $data,
            ],
            'composition_by_jurusan' => $composition,
        ]);
    }
}