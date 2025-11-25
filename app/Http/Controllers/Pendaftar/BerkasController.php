<?php

namespace App\Http\Controllers\Pendaftar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pendaftar;
use App\Models\PendaftarBerkas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BerkasController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->session()->has('user')) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $user = $request->session()->get('user');
        
        if ($user['role'] !== 'pendaftar') {
            return redirect('/')->with('error', 'Akses ditolak.');
        }
        
        $pendaftar = Pendaftar::where('user_id', $user['id'])->with(['berkas' => function($query) {
            $query->orderBy('is_draft', 'desc')->orderBy('created_at', 'desc');
        }])->first();
        
        if (!$pendaftar) {
            return redirect()->route('pendaftar.form')->with('info', 'Silakan lengkapi data pendaftaran terlebih dahulu.');
        }
        
        // Get berkas dengan query fresh dari database
        $draftBerkas = PendaftarBerkas::where('pendaftar_id', $pendaftar->id)
            ->where('is_draft', true)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $uploadedBerkas = PendaftarBerkas::where('pendaftar_id', $pendaftar->id)
            ->where('is_draft', false)
            ->orderBy('uploaded_at', 'desc')
            ->get();
            
        // Refresh pendaftar berkas relation
        $pendaftar->load('berkas');
        

        $draftTypes = $draftBerkas->pluck('jenis')->map(function($jenis) {
            return strtolower($jenis);
        })->toArray();
        
        return view('pendaftar.berkas', compact('pendaftar', 'draftBerkas', 'uploadedBerkas', 'draftTypes'));
    }
    
    public function store(Request $request)
    {
        if (!$request->session()->has('user')) {
            return redirect('/')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $user = $request->session()->get('user');
        $pendaftar = Pendaftar::where('user_id', $user['id'])->first();
        
        if (!$pendaftar) {
            return redirect()->route('pendaftar.form')->with('error', 'Data pendaftaran tidak ditemukan.');
        }
        
        // VALIDASI KETAT: Hanya proses jika ada explicit request untuk finalisasi
        if (!$request->has('finalize_submission') || $request->finalize_submission !== 'true') {
            return redirect()->route('pendaftar.berkas')->with('error', 'Aksi tidak valid. Gunakan tombol "Finalisasi & Serahkan Berkas".');
        }
        
        $uploadedCount = 0;
        $uploadedFiles = [];
        
        // HANYA proses jika ada draft berkas yang dipilih DAN ada konfirmasi finalisasi
        if ($request->has('draft_berkas') && is_array($request->draft_berkas) && $request->finalize_submission === 'true') {
            $draftIds = $request->draft_berkas;
            
            // VALIDASI TAMBAHAN: Pastikan semua ID adalah draft yang valid
            $validDrafts = PendaftarBerkas::whereIn('id', $draftIds)
                ->where('pendaftar_id', $pendaftar->id)
                ->where('is_draft', true)
                ->pluck('id')
                ->toArray();
            
            if (count($validDrafts) !== count($draftIds)) {
                return redirect()->route('pendaftar.berkas')->with('error', 'Terdapat berkas yang tidak valid atau sudah dikirim.');
            }
            
            // LOG SEBELUM UPDATE untuk audit trail
            \Log::info('FINALISASI BERKAS: User ' . $user['id'] . ' mengirim berkas IDs: ' . implode(',', $draftIds));
            
            // Update berkas draft menjadi uploaded HANYA setelah validasi ketat
            $updated = PendaftarBerkas::whereIn('id', $validDrafts)
                ->where('pendaftar_id', $pendaftar->id)
                ->where('is_draft', true)
                ->update([
                    'is_draft' => false,
                    'uploaded_at' => now()
                ]);
            
            // Get berkas yang baru diupdate untuk feedback
            $uploadedBerkas = PendaftarBerkas::whereIn('id', $validDrafts)
                ->where('pendaftar_id', $pendaftar->id)
                ->where('is_draft', false)
                ->get();
                
            foreach ($uploadedBerkas as $berkas) {
                $uploadedFiles[] = strtoupper($berkas->jenis);
            }
            
            $uploadedCount = $updated;
            
            // LOG SETELAH UPDATE untuk konfirmasi
            \Log::info('BERKAS BERHASIL DIKIRIM: ' . $uploadedCount . ' berkas untuk pendaftar ' . $pendaftar->no_pendaftaran);
        }
        
        if ($uploadedCount > 0) {
            $fileList = implode(', ', $uploadedFiles);
            return redirect()->route('pendaftar.berkas')->with('success', "$uploadedCount berkas ($fileList) berhasil dikirim ke panitia untuk verifikasi!");
        } else {
            return redirect()->route('pendaftar.berkas')->with('error', 'Silakan pilih minimal 1 berkas draft untuk dikirim ke panitia!');
        }
    }
    
    public function autoSave(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'type' => 'required|string|in:ijazah,rapor,akta,kk,kip,kks'
        ]);
        
        if (!$request->session()->has('user')) {
            return response()->json(['message' => 'User tidak ditemukan.'], 401);
        }
        
        $user = $request->session()->get('user');
        $pendaftar = Pendaftar::where('user_id', $user['id'])->first();
        
        if (!$pendaftar) {
            return response()->json(['message' => 'Data pendaftaran tidak ditemukan.'], 404);
        }
        
        // Cek apakah sudah ada berkas yang dikirim untuk jenis ini
        $existingBerkas = PendaftarBerkas::where('pendaftar_id', $pendaftar->id)
            ->where('jenis', strtoupper($request->type))
            ->where('is_draft', false)
            ->first();
            
        if ($existingBerkas) {
            return response()->json(['message' => 'Berkas ' . strtoupper($request->type) . ' sudah dikirim ke panitia. Tidak bisa membuat draft baru.'], 400);
        }
        
        $file = $request->file('file');
        $type = $request->type;
        
        // Hapus draft lama jika ada
        $oldDraft = PendaftarBerkas::where('pendaftar_id', $pendaftar->id)
            ->where('jenis', strtoupper($type))
            ->where('is_draft', true)
            ->first();
            
        if ($oldDraft) {
            if (\Storage::disk('public')->exists($oldDraft->url)) {
                \Storage::disk('public')->delete($oldDraft->url);
            }
            $oldDraft->delete();
        }
        
        $filename = $pendaftar->no_pendaftaran . '_draft_' . $type . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('berkas', $filename, 'public');
        
        // PAKSA BERKAS TERSIMPAN SEBAGAI DRAFT DENGAN VALIDASI BERLAPIS
        $newBerkas = new PendaftarBerkas();
        $newBerkas->pendaftar_id = $pendaftar->id;
        $newBerkas->jenis = strtoupper($type);
        $newBerkas->nama_file = $file->getClientOriginalName();
        $newBerkas->url = $path;
        $newBerkas->ukuran_kb = round($file->getSize() / 1024);
        $newBerkas->valid = false;
        $newBerkas->is_draft = true;  // PAKSA TRUE
        $newBerkas->uploaded_at = null;  // PASTIKAN NULL untuk draft
        
        // VALIDASI SEBELUM SAVE
        if (!$newBerkas->is_draft) {
            \Log::error('CRITICAL: Berkas akan tersimpan bukan sebagai draft! Pendaftar: ' . $pendaftar->id);
            return response()->json(['message' => 'Error: Sistem gagal menyimpan sebagai draft'], 500);
        }
        
        $newBerkas->save();
        
        // VERIFIKASI BERLAPIS SETELAH SAVE
        $verify = PendaftarBerkas::find($newBerkas->id);
        if (!$verify || !$verify->is_draft || $verify->uploaded_at !== null) {
            \Log::error('CRITICAL: Berkas tidak tersimpan sebagai draft! ID: ' . $newBerkas->id . ', is_draft: ' . ($verify ? $verify->is_draft : 'null') . ', uploaded_at: ' . ($verify ? $verify->uploaded_at : 'null'));
            
            // HAPUS BERKAS YANG SALAH
            if ($verify) {
                $verify->delete();
            }
            
            // HAPUS FILE DARI STORAGE
            if (\Storage::disk('public')->exists($path)) {
                \Storage::disk('public')->delete($path);
            }
            
            return response()->json(['message' => 'Error: Berkas tidak tersimpan sebagai draft, sistem telah membatalkan penyimpanan'], 500);
        }
        
        // LOG SUKSES UNTUK AUDIT
        \Log::info('DRAFT BERKAS TERSIMPAN: ID ' . $newBerkas->id . ' untuk pendaftar ' . $pendaftar->no_pendaftaran . ' jenis ' . $type);
        
        return response()->json(['message' => 'Berkas berhasil disimpan sebagai draft.', 'draft_id' => $newBerkas->id, 'is_draft' => $verify->is_draft]);
    }
    
    public function deleteDraft(Request $request, $id)
    {
        if (!$request->session()->has('user')) {
            return response()->json(['message' => 'User tidak ditemukan.'], 401);
        }
        
        $user = $request->session()->get('user');
        $pendaftar = Pendaftar::where('user_id', $user['id'])->first();
        
        if (!$pendaftar) {
            return response()->json(['message' => 'Data pendaftaran tidak ditemukan.'], 404);
        }
        
        $berkas = PendaftarBerkas::where('id', $id)
            ->where('pendaftar_id', $pendaftar->id)
            ->where('is_draft', true)
            ->first();
        
        if (!$berkas) {
            return response()->json(['message' => 'Draft berkas tidak ditemukan.'], 404);
        }
        
        // Hapus file dari storage
        if (\Storage::disk('public')->exists($berkas->url)) {
            \Storage::disk('public')->delete($berkas->url);
        }
        
        // Hapus dari database
        $berkas->delete();
        
        return response()->json(['message' => 'Draft berkas berhasil dihapus.']);
    }
    
    public function uploadUlang(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
                'berkas_id' => 'required|exists:pendaftar_berkas,id'
            ]);
            
            if (!$request->session()->has('user')) {
                return response()->json(['message' => 'User tidak ditemukan.'], 401);
            }
            
            $user = $request->session()->get('user');
            $pendaftar = Pendaftar::where('user_id', $user['id'])->first();
            
            if (!$pendaftar) {
                return response()->json(['message' => 'Data pendaftaran tidak ditemukan.'], 404);
            }
            
            // Cari berkas yang ditolak
            $berkas = PendaftarBerkas::where('id', $request->berkas_id)
                ->where('pendaftar_id', $pendaftar->id)
                ->where('valid', false)
                ->whereNotNull('catatan')
                ->first();
                
            if (!$berkas) {
                return response()->json(['message' => 'Berkas tidak ditemukan atau tidak dapat diupload ulang.'], 404);
            }
            
            $file = $request->file('file');
            
            // Hapus file lama
            if (\Storage::disk('public')->exists($berkas->url)) {
                \Storage::disk('public')->delete($berkas->url);
            }
            
            // Upload file baru
            $filename = $pendaftar->no_pendaftaran . '_' . strtolower($berkas->jenis) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('berkas', $filename, 'public');
            
            // Update berkas
            $berkas->update([
                'nama_file' => $file->getClientOriginalName(),
                'url' => $path,
                'ukuran_kb' => round($file->getSize() / 1024),
                'valid' => false,
                'catatan' => '',
                'uploaded_at' => now()
            ]);
            
            \Log::info('Upload ulang berhasil: ' . $berkas->jenis . ' untuk pendaftar ' . $pendaftar->no_pendaftaran);
            
            return response()->json(['success' => true, 'message' => 'Berkas berhasil diupload ulang.']);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error upload ulang: ' . json_encode($e->errors()));
            return response()->json(['message' => 'File tidak valid: ' . implode(', ', array_flatten($e->errors()))], 422);
        } catch (\Exception $e) {
            \Log::error('Error upload ulang: ' . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}