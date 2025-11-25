<?php
namespace App\Services;

use App\Models\Pendaftar;
use App\Models\User;
use Illuminate\Support\Str;
use App\Enums\PendaftarStatus;

class PendaftaranService
{
    protected AuditLogService $audit;
    protected NotifikasiService $notifikasi;

    public function __construct(AuditLogService $audit, NotifikasiService $notifikasi)
    {
        $this->audit = $audit;
        $this->notifikasi = $notifikasi;
    }

    /**
     * Generate nomor pendaftaran unik (simple scheme: YYYYmmdd-XXXX).
     */
    public function generateNoPendaftaran(): string
    {
        do {
            $no = now()->format('Ymd') . '-' . strtoupper(Str::random(4));
        } while (Pendaftar::where('no_pendaftaran', $no)->exists());

        return $no;
    }

    /**
     * Buat pendaftar baru (minimal) — data harus sudah tervalidasi.
     * Mengembalikan instance Pendaftar.
     */
    public function createPendaftar(User $user, array $data): Pendaftar
    {
        $payload = $data;
        $payload['user_id'] = $user->id;
        $payload['no_pendaftaran'] = $this->generateNoPendaftaran();
        $payload['status'] = PendaftarStatus::SUBMIT->value;
        $payload['tanggal_daftar'] = $payload['tanggal_daftar'] ?? now();

        $pendaftar = Pendaftar::create($payload);

        // audit
        $this->audit->log('pendaftaran.create', 'pendaftar', ['objek_id' => $pendaftar->id, 'payload' => $payload], $user->id);

        // send verification code OR welcome notification (optional)
        try {
            $this->notifikasi->kirimNotifStatus($user, 'SUBMIT');
        } catch (\Throwable $e) {
            // swallow - notification failures shouldn't break creation
        }

        return $pendaftar;
    }

    /**
     * Update status pendaftar (oleh admin/verifikator).
     */
    public function updateStatus(Pendaftar $pendaftar, string $newStatus, User $adminUser): Pendaftar
    {
        $old = $pendaftar->status?->value ?? (string)$pendaftar->status;
        $pendaftar->status = $newStatus;
        $pendaftar->user_verifikasi_adm = $adminUser->id;
        $pendaftar->tgl_verifikasi_adm = now();
        $pendaftar->save();

        // audit log
        $this->audit->log('pendaftaran.status_change', 'pendaftar', [
            'objek_id' => $pendaftar->id,
            'old_status' => $old,
            'new_status' => $newStatus
        ], $adminUser->id);

        // notif ke user (best-effort)
        if ($pendaftar->user) {
            $this->notifikasi->kirimNotifStatus($pendaftar->user, $newStatus);
        }

        return $pendaftar;
    }

    public function submitForm(User $user, array $formData): Pendaftar
    {
        $pendaftar = $this->createPendaftar($user, [
            'gelombang_id' => $formData['gelombang_id'],
            'jurusan_id' => $formData['jurusan_id']
        ]);

        if (isset($formData['data_siswa'])) {
            $pendaftar->dataSiswa()->create($formData['data_siswa']);
        }

        if (isset($formData['data_ortu'])) {
            $pendaftar->dataOrtu()->create($formData['data_ortu']);
        }

        if (isset($formData['asal_sekolah'])) {
            $pendaftar->asalSekolah()->create($formData['asal_sekolah']);
        }

        return $pendaftar;
    }
}