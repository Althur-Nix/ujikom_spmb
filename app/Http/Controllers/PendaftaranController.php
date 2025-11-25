<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pendaftaran;

class PendaftaranController extends Controller
{
    public function create()
    {
        $existing = Pendaftaran::where('user_id', auth()->id())->first();
        if ($existing) {
            return redirect()->route('pendaftaran.edit');
        }
        
        return view('pendaftaran.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'jurusan' => 'required|in:PPLG,AKT,ANI,DKV,PEM',
            'asal_sekolah' => 'required|string|max:255',
        ]);
        
        $pendaftaran = Pendaftaran::create([
            'user_id' => auth()->id(),
            'nomor_pendaftaran' => 'SPMB' . date('Y') . str_pad(Pendaftaran::count() + 1, 4, '0', STR_PAD_LEFT),
            'nama_lengkap' => $request->nama_lengkap,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'nama_ayah' => $request->nama_ayah,
            'nama_ibu' => $request->nama_ibu,
            'jurusan' => $request->jurusan,
            'asal_sekolah' => $request->asal_sekolah,
            'status' => 'draft'
        ]);
        
        return redirect()->route('dashboard.siswa')->with('success', 'Pendaftaran berhasil dibuat!');
    }
    
    public function edit()
    {
        $pendaftaran = Pendaftaran::where('user_id', auth()->id())->firstOrFail();
        return view('pendaftaran.edit', compact('pendaftaran'));
    }
    
    public function update(Request $request)
    {
        $pendaftaran = Pendaftaran::where('user_id', auth()->id())->firstOrFail();
        
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'jurusan' => 'required|in:PPLG,AKT,ANI,DKV,PEM',
            'asal_sekolah' => 'required|string|max:255',
        ]);
        
        $pendaftaran->update($request->all());
        
        return redirect()->route('dashboard.siswa')->with('success', 'Data pendaftaran berhasil diperbarui!');
    }
}