<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use App\Services\NotifikasiService; // <-- added
use App\Services\AuditLogService;

class VerifikasiController extends Controller
{
    protected $auditLog;
    protected NotifikasiService $notifikasi;

    public function __construct(AuditLogService $auditLog, NotifikasiService $notifikasi)
    {
        $this->middleware(['auth', 'role:admin,verifikator_adm,keuangan']);
        $this->auditLog = $auditLog;
        $this->notifikasi = $notifikasi;
    }

    public function index()
    {
        $pendaftar = Pendaftar::with(['berkas', 'user', 'gelombang'])
            ->where('status', 'SUBMIT')
            ->orderBy('created_at')
            ->paginate(10);
            
        return view('admin.verifikasi.index', compact('pendaftar'));
    }

    public function show(Pendaftar $pendaftar)
    {
        $pendaftar->load(['berkas', 'user', 'gelombang', 'jurusan']);
        return view('admin.verifikasi.show', compact('pendaftar'));
    }

    public function verify(Request $request, Pendaftar $pendaftar)
    {
        $request->validate([
            'status' => 'required|in:ADM_PASS,ADM_REJECT',
            'catatan' => 'required_if:status,ADM_REJECT|string|max:255'
        ]);

        $oldStatus = $pendaftar->status;
        $pendaftar->update([
            'status' => $request->status,
            'catatan_verifikasi' => $request->catatan
        ]);

        // Send notification
        $pendaftar->user->notify(new VerificationStatusNotification($pendaftar));

        // Log activity
        $this->auditLog->log(
            'verifikasi_pendaftar',
            'Pendaftar',
            $pendaftar->id,
            [
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'catatan' => $request->catatan
            ]
        );

        return redirect()
            ->route('verifikasi.index')
            ->with('success', 'Status pendaftar berhasil diperbarui');
    }

    /**
     * Contoh method: ubah status verifikasi admin (approve/reject)
     * Sesuaikan nama/parameter sesuai implementasimu saat ini.
     */
    public function updateStatus(Request $request, Pendaftar $pendaftar)
    {
        // validasi singkat (saran: gunakan FormRequest)
        $request->validate([
            'status' => 'required|in:ADM_PASS,ADM_REJECT,PAID,SUBMIT',
        ]);

        $oldStatus = $pendaftar->status;
        $newStatus = $request->input('status');

        // simpan perubahan status
        $pendaftar->status = $newStatus;
        $pendaftar->user_verifikasi_adm = auth()->id(); // opsional
        $pendaftar->tgl_verifikasi_adm = now(); // opsional
        $pendaftar->save();

        // kirim notifikasi & simpan audit via NotifikasiService
        try {
            $this->notifikasi->kirimNotifStatus($pendaftar, $newStatus);
        } catch (\Throwable $e) {
            \Log::warning('Notifikasi gagal: '.$e->getMessage());
        }

        // kembalikan response sesuai kebutuhan (JSON untuk API atau redirect untuk web)
        if ($request->wantsJson()) {
            return response()->json(['ok' => true, 'status' => $newStatus]);
        }

        return redirect()->back()->with('success', 'Status pendaftar diubah menjadi '.$newStatus);
    }
}