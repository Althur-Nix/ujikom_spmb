<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pendaftaran - SPMB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body { 
            background: #f8f9fa;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            min-height: 100vh;
            padding: 20px 0;
        }
        
        .form-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
            border: 1px solid #e9ecef;
        }
        
        .form-header {
            background: #fff;
            color: #2c3e50;
            padding: 30px;
            text-align: center;
            border-bottom: 3px solid #28a745;
        }
        
        .form-header h3 {
            margin: 0;
            font-weight: 700;
            color: #2c3e50;
        }
        
        .form-header p {
            margin: 10px 0 0 0;
            color: #6c757d;
        }
        
        .form-body {
            padding: 30px;
        }
        
        .section-title {
            color: #2c3e50;
            font-weight: 600;
            margin: 25px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #28a745;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            color: #28a745;
        }
        
        .form-label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.15);
        }
        
        .btn-primary {
            background: #28a745;
            border: none;
            padding: 15px 40px;
            font-weight: 600;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: #218838;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        
        .alert-info {
            background: #e8f5e8;
            border: 1px solid #28a745;
            color: #155724;
            border-radius: 8px;
        }
        
        .text-danger {
            color: #dc3545 !important;
        }
        
        .text-muted {
            color: #6c757d !important;
        }
        
        .mb-3 {
            margin-bottom: 1.5rem;
        }
        
        .mb-4 {
            margin-bottom: 2rem;
        }
        
        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
        }
        
        .is-invalid {
            border-color: #dc3545;
        }
        
        .is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
        }
        
        @media (max-width: 768px) {
            .form-container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .form-header {
                padding: 20px;
            }
            
            .form-body {
                padding: 20px;
            }
            
            .section-title {
                font-size: 1rem;
                margin: 20px 0 15px 0;
            }
        }
        
        .form-control::placeholder {
            color: #adb5bd;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <div class="d-flex align-items-center justify-content-center mb-2">
                <i class="fas fa-graduation-cap me-3" style="font-size: 2rem; color: #28a745;"></i>
                <div class="text-start">
                    <h3 class="mb-0">SMK BAKTI NUSANTARA 666</h3>
                    <p class="mb-0">Formulir Pendaftaran Siswa Baru</p>
                </div>
            </div>
        </div>
        
        <div class="form-body">


<div class="alert alert-info border-0 mb-4">
    <div class="d-flex align-items-center">
        <i class="fas fa-info-circle me-3" style="font-size: 1.2rem;"></i>
        <div>
            <h6 class="mb-1 fw-bold">Selamat Datang!</h6>
            <p class="mb-0 small">Silakan lengkapi formulir pendaftaran di bawah ini untuk melanjutkan proses pendaftaran Anda.</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('pendaftar.form.store') }}">
    @csrf
    
    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Jurusan Pilihan <span class="text-danger">*</span></label>
            <select name="jurusan_id" class="form-select @error('jurusan_id') is-invalid @enderror" required>
                <option value="">Pilih Jurusan</option>
                @foreach($jurusan as $j)
                    <option value="{{ $j->id }}" {{ old('jurusan_id') == $j->id ? 'selected' : '' }}>
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
                    <option value="{{ $g->id }}" {{ (old('gelombang_id') ?? $gelombang?->id) == $g->id ? 'selected' : '' }}>
                        {{ $g->nama }} ({{ $g->tgl_mulai->format('d/m/Y') }} - {{ $g->tgl_selesai->format('d/m/Y') }})
                    </option>
                @endforeach
            </select>
            @error('gelombang_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <h6 class="section-title mt-4"><i class="fas fa-user me-2"></i>Data Pribadi</h6>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                   value="{{ old('nama') }}" required>
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">NISN <span class="text-danger">*</span></label>
            <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror" 
                   value="{{ old('nisn') }}" maxlength="10" required>
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
                <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
            @error('jenis_kelamin')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
            <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                   value="{{ old('tempat_lahir') }}" required>
            @error('tempat_lahir')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
            <input type="date" name="tgl_lahir" class="form-control @error('tgl_lahir') is-invalid @enderror" 
                   value="{{ old('tgl_lahir') }}" required>
            @error('tgl_lahir')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    
    <h6 class="section-title mt-4"><i class="fas fa-school me-2"></i>Data Asal Sekolah</h6>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">NPSN <small class="text-muted">(opsional)</small></label>
            <input type="text" name="npsn" class="form-control @error('npsn') is-invalid @enderror" 
                   value="{{ old('npsn') }}" maxlength="20" placeholder="Nomor Pokok Sekolah Nasional">
            @error('npsn')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Nama Sekolah <span class="text-danger">*</span></label>
            <input type="text" name="nama_sekolah" class="form-control @error('nama_sekolah') is-invalid @enderror" 
                   value="{{ old('nama_sekolah') }}" placeholder="Nama SMP/MTs asal" required>
            @error('nama_sekolah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Kabupaten Asal Sekolah</label>
            <input type="text" name="kabupaten_sekolah" class="form-control @error('kabupaten_sekolah') is-invalid @enderror" 
                   value="{{ old('kabupaten_sekolah') }}" placeholder="Kabupaten/Kota asal sekolah">
            @error('kabupaten_sekolah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Nilai Rata-rata <small class="text-muted">(opsional)</small></label>
            <input type="number" name="nilai_rata" class="form-control @error('nilai_rata') is-invalid @enderror" 
                   value="{{ old('nilai_rata') }}" step="0.01" min="0" max="100" placeholder="Contoh: 85.50">
            @error('nilai_rata')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <h6 class="section-title mt-4"><i class="fas fa-map-marker-alt me-2"></i>Alamat Domisili</h6>

    <div class="mb-3">
        <label class="form-label">Alamat <span class="text-danger">*</span></label>
        <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                  rows="3" required>{{ old('alamat') }}</textarea>
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
            @error('province_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
            <select name="regency_id" id="regency_id" class="form-select @error('regency_id') is-invalid @enderror" required disabled>
                <option value="">Pilih Kabupaten/Kota</option>
            </select>
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
            @error('district_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Kelurahan/Desa <span class="text-danger">*</span></label>
            <select name="village_id" id="village_id" class="form-select @error('village_id') is-invalid @enderror" required disabled>
                <option value="">Pilih Kelurahan/Desa</option>
            </select>
            @error('village_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <h6 class="section-title mt-4"><i class="fas fa-users me-2"></i>Data Orang Tua/Wali</h6>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Nama Ayah</label>
            <input type="text" name="nama_ayah" class="form-control @error('nama_ayah') is-invalid @enderror" 
                   value="{{ old('nama_ayah') }}">
            @error('nama_ayah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Pekerjaan Ayah</label>
            <input type="text" name="pekerjaan_ayah" class="form-control @error('pekerjaan_ayah') is-invalid @enderror" 
                   value="{{ old('pekerjaan_ayah') }}">
            @error('pekerjaan_ayah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">Nama Ibu</label>
            <input type="text" name="nama_ibu" class="form-control @error('nama_ibu') is-invalid @enderror" 
                   value="{{ old('nama_ibu') }}">
            @error('nama_ibu')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Pekerjaan Ibu</label>
            <input type="text" name="pekerjaan_ibu" class="form-control @error('pekerjaan_ibu') is-invalid @enderror" 
                   value="{{ old('pekerjaan_ibu') }}">
            @error('pekerjaan_ibu')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <label class="form-label">No. HP Ayah</label>
            <input type="text" name="hp_ayah" class="form-control @error('hp_ayah') is-invalid @enderror" 
                   value="{{ old('hp_ayah') }}" minlength="10" maxlength="13" 
                   pattern="[0-9]{10,13}" placeholder="08xxxxxxxxxx">
            @error('hp_ayah')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">10-13 digit angka</small>
        </div>
        <div class="col-md-4">
            <label class="form-label">No. HP Ibu</label>
            <input type="text" name="hp_ibu" class="form-control @error('hp_ibu') is-invalid @enderror" 
                   value="{{ old('hp_ibu') }}" minlength="10" maxlength="13" 
                   pattern="[0-9]{10,13}" placeholder="08xxxxxxxxxx">
            @error('hp_ibu')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">10-13 digit angka</small>
        </div>
        <div class="col-md-4">
            <label class="form-label">No. HP Wali <small class="text-muted">(opsional)</small></label>
            <input type="text" name="wali_hp" class="form-control @error('wali_hp') is-invalid @enderror" 
                   value="{{ old('wali_hp') }}" minlength="10" maxlength="13" 
                   pattern="[0-9]{10,13}" placeholder="08xxxxxxxxxx">
            @error('wali_hp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">10-13 digit angka</small>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label">Nama Wali <small class="text-muted">(jika ada)</small></label>
        <input type="text" name="wali_nama" class="form-control @error('wali_nama') is-invalid @enderror" 
               value="{{ old('wali_nama') }}" placeholder="Kosongkan jika tidak ada wali">
        @error('wali_nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="text-center mt-5 pt-4 border-top">
        <button type="submit" class="btn btn-primary btn-lg px-5">
            <i class="fas fa-paper-plane me-2"></i>Kirim Pendaftaran
        </button>
        <p class="text-muted mt-3 small">
            <i class="fas fa-shield-alt me-1"></i>
            Data Anda akan dienkripsi dan disimpan dengan aman
        </p>
    </div>
</form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load provinces on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadProvinces();
        });
        
        async function loadProvinces() {
            try {
                const response = await fetch('/api/provinces');
                const provinces = await response.json();
                const select = document.getElementById('province_id');
                
                provinces.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.id;
                    option.textContent = province.name;
                    select.appendChild(option);
                });
            } catch (error) {
                console.error('Error loading provinces:', error);
            }
        }
        
        document.getElementById('province_id').addEventListener('change', async function() {
            const provinceId = this.value;
            const regencySelect = document.getElementById('regency_id');
            const districtSelect = document.getElementById('district_id');
            const villageSelect = document.getElementById('village_id');
            
            // Reset dependent selects
            regencySelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
            districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
            
            if (provinceId) {
                try {
                    const response = await fetch(`/api/regencies/${provinceId}`);
                    const regencies = await response.json();
                    
                    regencies.forEach(regency => {
                        const option = document.createElement('option');
                        option.value = regency.id;
                        option.textContent = regency.name;
                        regencySelect.appendChild(option);
                    });
                    
                    regencySelect.disabled = false;
                } catch (error) {
                    console.error('Error loading regencies:', error);
                }
            } else {
                regencySelect.disabled = true;
                districtSelect.disabled = true;
                villageSelect.disabled = true;
            }
        });
        
        document.getElementById('regency_id').addEventListener('change', async function() {
            const regencyId = this.value;
            const districtSelect = document.getElementById('district_id');
            const villageSelect = document.getElementById('village_id');
            
            // Reset dependent selects
            districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
            
            if (regencyId) {
                try {
                    const response = await fetch(`/api/districts/${regencyId}`);
                    const districts = await response.json();
                    
                    districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.id;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                    
                    districtSelect.disabled = false;
                } catch (error) {
                    console.error('Error loading districts:', error);
                }
            } else {
                districtSelect.disabled = true;
                villageSelect.disabled = true;
            }
        });
        
        document.getElementById('district_id').addEventListener('change', async function() {
            const districtId = this.value;
            const villageSelect = document.getElementById('village_id');
            
            // Reset dependent select
            villageSelect.innerHTML = '<option value="">Pilih Kelurahan/Desa</option>';
            
            if (districtId) {
                try {
                    const response = await fetch(`/api/villages/${districtId}`);
                    const villages = await response.json();
                    
                    villages.forEach(village => {
                        const option = document.createElement('option');
                        option.value = village.id;
                        option.textContent = village.name;
                        villageSelect.appendChild(option);
                    });
                    
                    villageSelect.disabled = false;
                } catch (error) {
                    console.error('Error loading villages:', error);
                }
            } else {
                villageSelect.disabled = true;
            }
        });
    </script>
</body>
</html>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province_id');
    const regencySelect = document.getElementById('regency_id');
    const districtSelect = document.getElementById('district_id');
    const villageSelect = document.getElementById('village_id');

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
</script>
@endpush
