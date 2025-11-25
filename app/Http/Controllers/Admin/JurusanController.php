<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jurusan;

class JurusanController extends Controller  
{
    public function index()
    {
        $jurusan = Jurusan::withCount('pendaftar')->orderBy('nama')->get();
        return view('admin.jurusan.index', compact('jurusan'));
    }

    public function create()
    {
        return view('admin.jurusan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:jurusan,kode',
            'nama' => 'required',
            'kuota' => 'required|integer|min:1'
        ]);
        
        Jurusan::create($request->only(['kode', 'nama', 'kuota']));

        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function show(Jurusan $jurusan)
    {
        return view('admin.jurusan.show', compact('jurusan'));
    }

    public function edit(Jurusan $jurusan)
    {
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'kode' => 'required|unique:jurusan,kode,' . $jurusan->id,
            'nama' => 'required',
            'kuota' => 'required|integer|min:1'
        ]);
        
        $jurusan->update($request->only(['kode', 'nama', 'kuota']));

        return redirect()->route('jurusan.index')->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Jurusan $jurusan)
    {
        if ($jurusan->pendaftar()->exists()) {
            return redirect()
                ->route('jurusan.index')
                ->with('error', 'Tidak dapat menghapus jurusan yang memiliki pendaftar');
        }

        $jurusan->delete();
        return redirect()
            ->route('jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus');
    }
}
