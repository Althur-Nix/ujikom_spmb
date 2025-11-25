@extends('kepala-sekolah.layout')

@section('title', 'Data Asal Sekolah')

@section('content')
<div class="content-card">
    <div class="card-header">
        <i class="fas fa-school me-2"></i>Data Asal Sekolah
    </div>
    <div class="card-body">
        <div class="alert border-0" style="background: #f3e5f5; color: #9b59b6;">
            <i class="fas fa-info-circle me-2"></i>Data asal sekolah calon siswa berdasarkan jumlah pendaftar
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="80">No</th>
                        <th>Nama Sekolah</th>
                        <th width="150">Jumlah Pendaftar</th>
                        <th width="200">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $no = ($asalSekolah->currentPage() - 1) * $asalSekolah->perPage() + 1;
                        $totalSemua = \App\Models\PendaftarAsalSekolah::count();
                    @endphp
                    @forelse($asalSekolah as $sekolah)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td><strong>{{ $sekolah->nama_sekolah ?: 'Tidak Diketahui' }}</strong></td>
                        <td>
                            <span class="badge" style="background: #3498db; padding: 8px 16px; font-size: 14px;">{{ $sekolah->jumlah ?? 0 }}</span>
                        </td>
                        <td>
                            @php 
                                $jml = $sekolah->jumlah ?? 0;
                                $persentase = $totalSemua > 0 ? round(($jml / $totalSemua) * 100, 1) : 0;
                            @endphp
                            <div class="progress" style="height: 24px;">
                                <div class="progress-bar" style="background: #3498db;" role="progressbar" 
                                     style="width: {{ $persentase }}%;" 
                                     aria-valuenow="{{ $persentase }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ $persentase }}%
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada data asal sekolah</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $asalSekolah->links() }}
        </div>
    </div>
</div>
@endsection
