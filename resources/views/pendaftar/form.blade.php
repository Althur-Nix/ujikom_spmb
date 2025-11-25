@extends('layouts.pendaftar')

@section('title', 'Form Pendaftaran')

@section('content')
<div class="content-card">
    <div class="card-header">
        <i class="fas fa-edit me-2"></i>Form Pendaftaran
    </div>
    <div class="card-body">
        @if($pendaftar && !in_array($pendaftar->status, ['SUBMIT', 'ADM_REJECT']))
        <div class="alert border-0" style="background: #e3f2fd; color: #3498db;">
            <i class="fas fa-info-circle me-2"></i>Form tidak dapat diubah karena sudah dalam proses verifikasi atau sudah diverifikasi.
        </div>
        @endif
        
        <form method="POST" action="{{ route('pendaftar.form.store') }}">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Jurusan Pilihan <span class="text-danger">*</span></label>
                    <select name="jurusan_id" class="form-select @error('jurusan_id') is-invalid @enderror" required 
                            {{ $pendaftar && !in_array($pendaftar->status, ['SUBMIT', 'ADM_REJECT']) ? 'disabled' : '' }}>
                        <option value="">Pilih Jurusan</option>
                        @foreach($jurusan as $j)
                            <option value="{{ $j->id }}" {{ (old('jurusan_id') ?? $pendaftar?->jurusan_id) == $j->id ? 'selected' : '' }}>
                                {{ $j->kode }} - {{ $j->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('jurusan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gelombang <span class="text-danger">*</span></label>
                    <select name="gelombang_id" class="form-select @error('gelombang_id') is-invalid @enderror" required>
                        <option value="">Pilih Gelombang</option>
                        @php
                            $gelombangList = \App\Models\Gelombang::where('tgl_mulai', '<=', now())
                                ->where('tgl_selesai', '>=', now())
                                ->get();
                        @endphp
                        @foreach($gelombangList as $g)
                            <option value="{{ $g->id }}" {{ (old('gelombang_id') ?? $pendaftar?->gelombang_id ?? $gelombang?->id) == $g->id ? 'selected' : '' }}>
                                {{ $g->nama }} ({{ $g->tgl_mulai->format('d/m/Y') }} - {{ $g->tgl_selesai->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('gelombang_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>
            <h6 style="color: #2c3e50;"><i class="fas fa-user me-2"></i>Data Pribadi</h6>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                           value="{{ old('nama') ?? $pendaftar?->dataSiswa?->nama }}" required
                           {{ $pendaftar && !in_array($pendaftar->status, ['SUBMIT', 'ADM_REJECT']) ? 'readonly' : '' }}>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">NISN <span class="text-danger">*</span></label>
                    <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror" 
                           value="{{ old('nisn') ?? $pendaftar?->dataSiswa?->nisn }}" maxlength="10" required>
                    @error('nisn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                        <option value="">Pilih</option>
                        <option value="L" {{ (old('jenis_kelamin') ?? $pendaftar?->dataSiswa?->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ (old('jenis_kelamin') ?? $pendaftar?->dataSiswa?->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                    <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                           value="{{ old('tempat_lahir') ?? $pendaftar?->dataSiswa?->tempat_lahir }}" required>
                    @error('tempat_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" name="tgl_lahir" class="form-control @error('tgl_lahir') is-invalid @enderror" 
                           value="{{ old('tgl_lahir') ?? $pendaftar?->dataSiswa?->tgl_lahir?->format('Y-m-d') }}" required>
                    @error('tgl_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <hr>
            <h6 style="color: #2c3e50;"><i class="fas fa-school me-2"></i>Data Asal Sekolah</h6>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">NPSN <small class="text-muted">(opsional)</small></label>
                    <input type="text" name="npsn" class="form-control @error('npsn') is-invalid @enderror" 
                           value="{{ old('npsn') ?? $pendaftar?->asalSekolah?->npsn }}" maxlength="20" placeholder="Nomor Pokok Sekolah Nasional">
                    @error('npsn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nama Sekolah <span class="text-danger">*</span></label>
                    <input type="text" name="nama_sekolah" class="form-control @error('nama_sekolah') is-invalid @enderror" 
                           value="{{ old('nama_sekolah') ?? $pendaftar?->asalSekolah?->nama_sekolah }}" placeholder="Nama SMP/MTs asal" required>
                    @error('nama_sekolah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Kabupaten Asal Sekolah</label>
                    <input type="text" name="kabupaten_sekolah" class="form-control @error('kabupaten_sekolah') is-invalid @enderror" 
                           value="{{ old('kabupaten_sekolah') ?? $pendaftar?->asalSekolah?->kabupaten }}" placeholder="Kabupaten/Kota asal sekolah">
                    @error('kabupaten_sekolah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nilai Rata-rata <small class="text-muted">(opsional)</small></label>
                    <input type="number" name="nilai_rata" class="form-control @error('nilai_rata') is-invalid @enderror" 
                           value="{{ old('nilai_rata') ?? $pendaftar?->asalSekolah?->nilai_rata }}" step="0.01" min="0" max="100" placeholder="Contoh: 85.50">
                    @error('nilai_rata')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>
            <h6 style="color: #2c3e50;"><i class="fas fa-map-marker-alt me-2"></i>Alamat Domisili</h6>

            <div class="mb-3">
                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                          rows="3" required>{{ old('alamat') ?? $pendaftar?->dataSiswa?->alamat }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                    <select name="province_id" id="province_id" class="form-select @error('province_id') is-invalid @enderror" required>
                        <option value="">Pilih Provinsi</option>
                    </select>
                    <input type="hidden" id="selected_province" value="{{ old('province_id') ?? $pendaftar?->dataSiswa?->province_id }}">
                    @error('province_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
                    <select name="regency_id" id="regency_id" class="form-select @error('regency_id') is-invalid @enderror" required disabled>
                        <option value="">Pilih Kabupaten/Kota</option>
                    </select>
                    <input type="hidden" id="selected_regency" value="{{ old('regency_id') ?? $pendaftar?->dataSiswa?->regency_id }}">
                    @error('regency_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                    <select name="district_id" id="district_id" class="form-select @error('district_id') is-invalid @enderror" required disabled>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                    <input type="hidden" id="selected_district" value="{{ old('district_id') ?? $pendaftar?->dataSiswa?->district_id }}">
                    @error('district_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kelurahan/Desa <span class="text-danger">*</span></label>
                    <select name="village_id" id="village_id" class="form-select @error('village_id') is-invalid @enderror" required disabled>
                        <option value="">Pilih Kelurahan/Desa</option>
                    </select>
                    <input type="hidden" id="selected_village" value="{{ old('village_id') ?? $pendaftar?->dataSiswa?->village_id }}">
                    @error('village_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <hr>
            <h6 style="color: #2c3e50;"><i class="fas fa-users me-2"></i>Data Orang Tua/Wali</h6>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Ayah</label>
                    <input type="text" name="nama_ayah" class="form-control @error('nama_ayah') is-invalid @enderror" 
                           value="{{ old('nama_ayah') ?? $pendaftar?->dataOrtu?->nama_ayah }}">
                    @error('nama_ayah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pekerjaan Ayah</label>
                    <input type="text" name="pekerjaan_ayah" class="form-control @error('pekerjaan_ayah') is-invalid @enderror" 
                           value="{{ old('pekerjaan_ayah') ?? $pendaftar?->dataOrtu?->pekerjaan_ayah }}">
                    @error('pekerjaan_ayah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Ibu</label>
                    <input type="text" name="nama_ibu" class="form-control @error('nama_ibu') is-invalid @enderror" 
                           value="{{ old('nama_ibu') ?? $pendaftar?->dataOrtu?->nama_ibu }}">
                    @error('nama_ibu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pekerjaan Ibu</label>
                    <input type="text" name="pekerjaan_ibu" class="form-control @error('pekerjaan_ibu') is-invalid @enderror" 
                           value="{{ old('pekerjaan_ibu') ?? $pendaftar?->dataOrtu?->pekerjaan_ibu }}">
                    @error('pekerjaan_ibu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">No. HP Ayah</label>
                    <input type="text" name="hp_ayah" class="form-control @error('hp_ayah') is-invalid @enderror" 
                           value="{{ old('hp_ayah') ?? $pendaftar?->dataOrtu?->hp_ayah }}" minlength="10" maxlength="13" 
                           pattern="[0-9]{10,13}" placeholder="08xxxxxxxxxx">
                    @error('hp_ayah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">10-13 digit angka</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. HP Ibu</label>
                    <input type="text" name="hp_ibu" class="form-control @error('hp_ibu') is-invalid @enderror" 
                           value="{{ old('hp_ibu') ?? $pendaftar?->dataOrtu?->hp_ibu }}" minlength="10" maxlength="13" 
                           pattern="[0-9]{10,13}" placeholder="08xxxxxxxxxx">
                    @error('hp_ibu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">10-13 digit angka</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">No. HP Wali <small class="text-muted">(opsional)</small></label>
                    <input type="text" name="wali_hp" class="form-control @error('wali_hp') is-invalid @enderror" 
                           value="{{ old('wali_hp') ?? $pendaftar?->dataOrtu?->wali_hp }}" minlength="10" maxlength="13" 
                           pattern="[0-9]{10,13}" placeholder="08xxxxxxxxxx">
                    @error('wali_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">10-13 digit angka</small>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Wali <small class="text-muted">(jika ada)</small></label>
                <input type="text" name="wali_nama" class="form-control @error('wali_nama') is-invalid @enderror" 
                       value="{{ old('wali_nama') ?? $pendaftar?->dataOrtu?->wali_nama }}" placeholder="Kosongkan jika tidak ada wali">
                @error('wali_nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between">
                <a href="{{ route('pendaftar.dashboard') }}" class="btn btn-secondary">Kembali</a>
                <button type="button" class="btn btn-primary" onclick="confirmSimpanData()"
                        {{ $pendaftar && !in_array($pendaftar->status, ['SUBMIT', 'ADM_REJECT']) ? 'disabled' : '' }}>
                    <i class="fas fa-save me-1"></i>Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($pendaftar && !in_array($pendaftar->status, ['SUBMIT', 'ADM_REJECT']))
    const formInputs = document.querySelectorAll('input, select, textarea, button[type="submit"]');
    formInputs.forEach(input => {
        if (input.type !== 'button' && !input.classList.contains('btn-secondary')) {
            input.disabled = true;
            input.style.cursor = 'not-allowed';
            input.style.opacity = '0.6';
        }
    });
    @endif
    
    const provinceSelect = document.getElementById('province_id');
    const regencySelect = document.getElementById('regency_id');
    const districtSelect = document.getElementById('district_id');
    const villageSelect = document.getElementById('village_id');

    if (!provinceSelect) return;

    fetch('/api/provinces')
        .then(response => response.json())
        .then(data => {
            provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
            data.forEach(province => {
                const option = document.createElement('option');
                option.value = province.id;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading provinces:', error));

    provinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        regencySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        regencySelect.disabled = !provinceId;
        districtSelect.disabled = true;
        villageSelect.disabled = true;
        
        if (provinceId) {
            fetch(`/api/regencies/${provinceId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(regency => {
                        const option = document.createElement('option');
                        option.value = regency.id;
                        option.textContent = regency.name;
                        regencySelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading regencies:', error));
        }
    });

    regencySelect.addEventListener('change', function() {
        const regencyId = this.value;
        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        districtSelect.disabled = !regencyId;
        villageSelect.disabled = true;
        
        if (regencyId) {
            fetch(`/api/districts/${regencyId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading districts:', error));
        }
    });

    districtSelect.addEventListener('change', function() {
        const districtId = this.value;
        villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
        villageSelect.disabled = !districtId;
        
        if (districtId) {
            fetch(`/api/villages/${districtId}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach(village => {
                        const option = document.createElement('option');
                        option.value = village.id;
                        option.textContent = village.name;
                        villageSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error loading villages:', error));
        }
    });
});

function confirmSimpanData() {
    const form = document.querySelector('form');
    const nama = form.querySelector('[name="nama"]').value;
    const jurusan = form.querySelector('[name="jurusan_id"] option:checked').text;
    
    if (!nama || !form.querySelector('[name="jurusan_id"]').value) {
        Swal.fire({
            icon: 'warning',
            title: 'Data Belum Lengkap',
            text: 'Mohon lengkapi minimal nama dan jurusan pilihan!',
            confirmButtonColor: '#28a745'
        });
        return;
    }
    
    Swal.fire({
        title: 'Simpan Data Pendaftaran?',
        html: `Pastikan data sudah benar:<br><br><strong>Nama:</strong> ${nama}<br><strong>Jurusan:</strong> ${jurusan}<br><br>Data yang sudah disimpan dapat diubah kembali sebelum disubmit.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>
@endpush
