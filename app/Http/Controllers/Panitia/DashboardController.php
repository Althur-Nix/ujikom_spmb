<?php

namespace App\Http\Controllers\Panitia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics
        $totalPendaftar = Pendaftar::count();
        $menungguVerifikasi = Pendaftar::where('status', 'SUBMIT')->count();
        $berkasVerified = Pendaftar::whereIn('status', ['ADM_PASS', 'PAYMENT_PENDING', 'PAID', 'ACCEPTED'])->count();
        $berkasDitolak = Pendaftar::whereIn('status', ['ADM_REJECT', 'REJECTED'])->count();
        
        // Get recent registrations
        $pendaftarTerbaru = Pendaftar::with(['jurusan', 'user', 'dataSiswa'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Get pending verifications
        $pendaftarMenunggu = Pendaftar::with(['jurusan', 'user', 'dataSiswa'])
            ->where('status', 'SUBMIT')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('panitia.dashboard-simple', compact(
            'totalPendaftar',
            'menungguVerifikasi', 
            'berkasVerified',
            'berkasDitolak',
            'pendaftarTerbaru',
            'pendaftarMenunggu'
        ));
    }
    
    public function pendaftar()
    {
        $pendaftar = Pendaftar::with(['jurusan', 'user', 'dataSiswa'])->paginate(15);
        return view('panitia.pendaftar-simple', compact('pendaftar'));
    }
    
    public function verifikasi()
    {
        $pendaftar = Pendaftar::with(['jurusan', 'user', 'dataSiswa'])
            ->where('status', 'SUBMIT')
            ->paginate(15);
        return view('panitia.verifikasi.index', compact('pendaftar'));
    }
    
    public function verifikasiShow($id)
    {
        $pendaftar = Pendaftar::with(['jurusan', 'user', 'dataSiswa', 'berkas'])->findOrFail($id);
        return view('panitia.verifikasi.show', compact('pendaftar'));
    }
    
    public function verifikasiUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:ADM_PASS,ADM_REJECT'
        ]);
        
        $pendaftar = Pendaftar::findOrFail($id);
        $pendaftar->update([
            'status' => $request->status
        ]);
        
        return redirect()->route('panitia.verifikasi')
            ->with('success', 'Status verifikasi berhasil diupdate');
    }
}