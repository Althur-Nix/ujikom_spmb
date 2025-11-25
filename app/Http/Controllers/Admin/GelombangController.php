<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GelombangRequest;
use App\Models\Gelombang;
use Illuminate\Http\Request;

class GelombangController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $gelombang = Gelombang::orderBy('tahun', 'desc')
                            ->orderBy('tgl_mulai', 'desc')
                            ->get();
        return view('admin.gelombang.index', compact('gelombang'));
    }

    public function create()
    {
        return view('admin.gelombang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tahun' => 'required|integer',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
            'biaya_daftar' => 'required|integer|min:0'
        ]);
        
        Gelombang::create($request->only(['nama', 'tahun', 'tgl_mulai', 'tgl_selesai', 'biaya_daftar']));

        return redirect()->route('gelombang.index')->with('success', 'Gelombang berhasil ditambahkan.');
    }

    public function edit(Gelombang $gelombang)
    {
        return view('admin.gelombang.edit', compact('gelombang'));
    }

    public function update(Request $request, Gelombang $gelombang)
    {
        $request->validate([
            'nama' => 'required',
            'tahun' => 'required|integer',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
            'biaya_daftar' => 'required|integer|min:0'
        ]);
        
        $gelombang->update($request->only(['nama', 'tahun', 'tgl_mulai', 'tgl_selesai', 'biaya_daftar']));

        return redirect()->route('gelombang.index')->with('success', 'Gelombang berhasil diperbarui.');
    }

    public function destroy(Gelombang $gelombang)
    {
        if ($gelombang->pendaftar()->exists()) {
            return redirect()
                ->route('gelombang.index')
                ->with('error', 'Tidak dapat menghapus gelombang yang memiliki pendaftar');
        }

        $gelombang->delete();
        return redirect()
            ->route('gelombang.index')
            ->with('success', 'Gelombang pendaftaran berhasil dihapus');
    }
}