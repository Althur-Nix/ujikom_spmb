<?php

namespace App\Http\Controllers\Pendaftar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftar;
use App\Models\Jurusan;
use App\Models\Gelombang;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();
        
        if (!$pendaftar) {
            return redirect()->route('pendaftar.form')->with('info', 'Silakan lengkapi data pendaftaran Anda terlebih dahulu.');
        }
        
        $pendaftar->load(['jurusan', 'gelombang', 'dataSiswa', 'dataOrtu', 'asalSekolah', 'berkas']);
        
        return view('pendaftar.dashboard', compact('pendaftar'));
    }
    
    public function form()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->with('dataSiswa')->first();
        $jurusan = Jurusan::all();
        $gelombang = Gelombang::first();
        
        return view('pendaftar.form', compact('jurusan', 'gelombang', 'pendaftar'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'jurusan_id' => 'required|exists:jurusan,id',
            'nama' => 'required|string|max:120',
            'nisn' => 'required|string|max:10',
            'jenis_kelamin' => 'required|in:L,P',
            'asal_sekolah' => 'required|string|max:100',
            'tempat_lahir' => 'required|string|max:60',
            'tgl_lahir' => 'required|date',
            'alamat' => 'required|string',
            'wilayah' => 'required|string|max:100',
        ]);
        
        $user = Auth::user();
        $gelombang = Gelombang::first();
        
        // Debug: Log user info
        \Log::info('Form submission debug', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'gelombang_exists' => $gelombang ? 'yes' : 'no'
        ]);
        
        // Check if pendaftar already exists
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();
        
        if ($pendaftar) {
            // Update existing pendaftar
            $pendaftar->update([
                'jurusan_id' => $request->jurusan_id,
            ]);
            
            // Update or create dataSiswa
            $pendaftar->dataSiswa()->updateOrCreate(
                ['pendaftar_id' => $pendaftar->id],
                [
                    'nama' => $request->nama,
                    'nisn' => $request->nisn,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tgl_lahir' => $request->tgl_lahir,
                    'alamat' => $request->alamat,
                    'asal_sekolah' => $request->asal_sekolah,
                ]
            );
        } else {
            // Create new pendaftar
            $noPendaftaran = $this->generateNoPendaftaran($gelombang->tahun ?? null);
            
            $pendaftar = Pendaftar::create([
                'user_id' => $user->id,
                'no_pendaftaran' => $noPendaftaran,
                'gelombang_id' => $gelombang->id,
                'jurusan_id' => $request->jurusan_id,
                'status' => 'PENDING_UPLOAD',
            ]);
            
            $pendaftar->dataSiswa()->create([
                'nama' => $request->nama,
                'nisn' => $request->nisn,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'alamat' => $request->alamat,
                'asal_sekolah' => $request->asal_sekolah,
            ]);
        }
        
        return redirect()->route('pendaftar.dashboard')->with('success', 'Data pendaftaran berhasil disimpan.');
    }
    
    public function status()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)
            ->with(['dataSiswa', 'jurusan', 'gelombang'])
            ->first();
        
        return view('pendaftar.status', compact('pendaftar'));
    }
    
    public function pembayaran()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)
            ->with(['dataSiswa', 'jurusan', 'gelombang'])
            ->first();
        

        
        return view('pendaftar.pembayaran', compact('pendaftar'));
    }
    
    public function storePembayaran(Request $request)
    {
        $request->validate([
            'bank_tujuan' => 'required|string',
            'nominal' => 'required|numeric',
            'tanggal_transfer' => 'required|date',
            'nama_pengirim' => 'required|string|max:100',
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)->first();
        
        if (!$pendaftar || $pendaftar->status != 'ADM_PASS') {
            return redirect()->route('pendaftar.pembayaran')->with('error', 'Tidak dapat melakukan pembayaran.');
        }
        
        $file = $request->file('bukti_transfer');
        $filename = $pendaftar->no_pendaftaran . '_bukti_transfer.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('pembayaran', $filename, 'public');
        
        // Update status to PAID after payment proof uploaded
        $pendaftar->update(['status' => 'PAID']);
        
        return redirect()->route('pendaftar.pembayaran')->with('success', 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.');
    }
    
    public function cetak()
    {
        $user = Auth::user();
        $pendaftar = Pendaftar::where('user_id', $user->id)
            ->with(['dataSiswa', 'jurusan', 'gelombang'])
            ->first();
        
        return view('pendaftar.cetak', compact('pendaftar'));
    }
    
    private function generateNoPendaftaran($tahun = null)
    {
        $tahun = $tahun ?? date('Y');
        $prefix = $tahun . '001';
        $lastNumber = Pendaftar::where('no_pendaftaran', 'like', $prefix . '%')
            ->orderBy('no_pendaftaran', 'desc')
            ->first();
            
        if ($lastNumber) {
            $number = intval(substr($lastNumber->no_pendaftaran, -4)) + 1;
        } else {
            $number = 1;
        }
        
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}