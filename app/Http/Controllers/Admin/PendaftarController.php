<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;

class PendaftarController extends Controller
{
    public function index(Request $request)
    {
        $query = Pendaftar::with(['dataSiswa', 'jurusan', 'gelombang', 'berkas']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->jurusan_id) {
            $query->where('jurusan_id', $request->jurusan_id);
        }
        
        $pendaftar = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.pendaftar.index', compact('pendaftar'));
    }
    
    public function show($id)
    {
        $pendaftar = Pendaftar::with(['dataSiswa', 'jurusan', 'gelombang', 'berkas', 'user'])
            ->findOrFail($id);
        
        return view('admin.pendaftar.show', compact('pendaftar'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:SUBMIT,ADM_PASS,ADM_REJECT,PAID,ACCEPTED,REJECTED',
            'catatan' => 'nullable|string'
        ]);
        
        $pendaftar = Pendaftar::findOrFail($id);
        $pendaftar->update([
            'status' => $request->status
        ]);
        
        return redirect()->back()->with('success', 'Status berhasil diupdate.');
    }
}