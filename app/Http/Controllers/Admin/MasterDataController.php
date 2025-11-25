<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Gelombang;
use App\Models\User;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    // Dashboard Admin
    public function dashboard()
    {
        $stats = [
            'jurusan' => Jurusan::count(),
            'gelombang' => Gelombang::count(),
            'users' => User::count(),
        ];

        return view('admin.master.dashboard', compact('stats'));
    }

    // Jurusan Management
    public function jurusan()
    {
        $jurusan = Jurusan::latest()->get();
        return view('admin.master.jurusan', compact('jurusan'));
    }

    public function storeJurusan(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:jurusan,kode',
            'nama' => 'required',
            'kuota' => 'required|integer|min:1'
        ]);

        Jurusan::create($request->all());
        return back()->with('success', 'Jurusan berhasil ditambahkan');
    }

    public function updateJurusan(Request $request, Jurusan $jurusan)
    {
        $request->validate([
            'kode' => 'required|unique:jurusan,kode,' . $jurusan->id,
            'nama' => 'required',
            'kuota' => 'required|integer|min:1'
        ]);

        $jurusan->update($request->all());
        return back()->with('success', 'Jurusan berhasil diupdate');
    }

    public function deleteJurusan(Jurusan $jurusan)
    {
        $jurusan->delete();
        return back()->with('success', 'Jurusan berhasil dihapus');
    }

    // Gelombang Management
    public function gelombang()
    {
        $gelombang = Gelombang::latest()->get();
        return view('admin.master.gelombang', compact('gelombang'));
    }

    public function storeGelombang(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tahun' => 'required|integer',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
            'biaya_daftar' => 'required|numeric|min:0'
        ]);

        Gelombang::create($request->all());
        return back()->with('success', 'Gelombang berhasil ditambahkan');
    }

    public function updateGelombang(Request $request, Gelombang $gelombang)
    {
        $request->validate([
            'nama' => 'required',
            'tahun' => 'required|integer',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
            'biaya_daftar' => 'required|numeric|min:0'
        ]);

        $gelombang->update($request->all());
        return back()->with('success', 'Gelombang berhasil diupdate');
    }

    public function deleteGelombang(Gelombang $gelombang)
    {
        $gelombang->delete();
        return back()->with('success', 'Gelombang berhasil dihapus');
    }

    // User Management
    public function users()
    {
        $users = User::latest()->get();
        return view('admin.master.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,verifikator_adm,keuangan,kepsek'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,verifikator_adm,keuangan,kepsek'
        ]);

        $data = $request->only(['name', 'email', 'role']);
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);
        return back()->with('success', 'User berhasil diupdate');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus');
    }
}