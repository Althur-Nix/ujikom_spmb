@extends('kepala-sekolah.layout')

@section('title', 'Data Asal Wilayah')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 500px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
</style>
@endpush

@section('content')
<div class="content-card mb-4">
    <div class="card-header">
        <i class="fas fa-map-marked-alt me-2"></i>Peta Sebaran Asal Wilayah
    </div>
    <div class="card-body">
        <div id="map"></div>
    </div>
</div>

<div class="content-card">
    <div class="card-header">
        <i class="fas fa-map-marker-alt me-2"></i>Data Asal Wilayah
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="80">No</th>
                        <th>Kabupaten/Kota</th>
                        <th width="150">Jumlah Pendaftar</th>
                        <th width="200">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $no = ($asalWilayah->currentPage() - 1) * $asalWilayah->perPage() + 1;
                        $totalSemua = \App\Models\PendaftarDataSiswa::count();
                    @endphp
                    @forelse($asalWilayah as $wilayah)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>
                            <strong>{{ $wilayah->regency->name ?? 'Tidak Diketahui' }}</strong><br>
                            <small style="color: #95a5a6;">{{ $wilayah->regency->province->name ?? '' }}</small>
                        </td>
                        <td>
                            <span class="badge" style="background: #3498db; padding: 8px 16px; font-size: 14px;">{{ $wilayah->total }}</span>
                        </td>
                        <td>
                            @php 
                                $persentase = $totalSemua > 0 ? round(($wilayah->total / $totalSemua) * 100, 1) : 0;
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
                        <td colspan="4" class="text-center text-muted py-4">Belum ada data asal wilayah</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $asalWilayah->links() }}
        </div>
    </div>
</div>

<div class="content-card mt-4">
    <div class="card-header">
        <i class="fas fa-map-pin me-2"></i>Data Detail Per Kecamatan
        @php
            echo ' (Count: ' . (isset($detailWilayah) ? count($detailWilayah) : 'NOT SET') . ')';
        @endphp
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Kecamatan</th>
                        <th>Kabupaten/Kota</th>
                        <th>Provinsi</th>
                        <th width="120" class="text-center">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $detailWilayah = $detailWilayah ?? [];
                        $no = 1;
                    @endphp
                    @forelse($detailWilayah as $detail)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td><strong>{{ $detail->district->name ?? '-' }}</strong></td>
                        <td>{{ $detail->district->regency->name ?? '-' }}</td>
                        <td><small class="text-muted">{{ $detail->district->regency->province->name ?? '-' }}</small></td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $detail->total }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada data detail kecamatan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const map = L.map('map').setView([-2.5, 118], 5);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    const wilayahData = {!! json_encode($asalWilayah->map(function($w) {
        return [
            'regency' => $w->regency->name ?? 'Unknown',
            'province' => $w->regency->province->name ?? '',
            'total' => $w->total
        ];
    })) !!};

    const indonesiaCoords = {
        // Jawa Barat
        'KABUPATEN TASIKMALAYA': [-7.3506, 108.2170],
        'KABUPATEN BADUNG': [-8.5069, 115.1775],
        'KOTA BANDUNG': [-6.9175, 107.6191],
        'KABUPATEN BOGOR': [-6.5950, 106.7969],
        'KOTA BOGOR': [-6.5950, 106.7969],
        'KABUPATEN BEKASI': [-6.2349, 107.1537],
        'KOTA BEKASI': [-6.2349, 107.1537],
        'KABUPATEN DEPOK': [-6.4025, 106.7942],
        'KOTA DEPOK': [-6.4025, 106.7942],
        'KABUPATEN CIREBON': [-6.7063, 108.5571],
        'KOTA CIREBON': [-6.7063, 108.5571],
        'KABUPATEN GARUT': [-7.2253, 107.8986],
        'KABUPATEN CIANJUR': [-6.8200, 107.1425],
        'KABUPATEN SUKABUMI': [-6.9278, 106.9571],
        'KOTA SUKABUMI': [-6.9278, 106.9571],
        'KABUPATEN PURWAKARTA': [-6.5569, 107.4431],
        'KABUPATEN KARAWANG': [-6.3015, 107.3374],
        'KABUPATEN SUBANG': [-6.5697, 107.7607],
        'KABUPATEN INDRAMAYU': [-6.3274, 108.3199],
        'KABUPATEN MAJALENGKA': [-6.8364, 108.2274],
        'KABUPATEN KUNINGAN': [-6.9759, 108.4836],
        'KABUPATEN BANJAR': [-7.3553, 108.5492],
        'KOTA BANJAR': [-7.3553, 108.5492],
        'KABUPATEN CIAMIS': [-7.3253, 108.3534],
        'KABUPATEN PANGANDARAN': [-7.6840, 108.6500],
        
        // DKI Jakarta
        'KOTA JAKARTA': [-6.2088, 106.8456],
        'KOTA JAKARTA PUSAT': [-6.1805, 106.8284],
        'KOTA JAKARTA UTARA': [-6.1384, 106.8631],
        'KOTA JAKARTA BARAT': [-6.1352, 106.7674],
        'KOTA JAKARTA SELATAN': [-6.2615, 106.8106],
        'KOTA JAKARTA TIMUR': [-6.2250, 106.9004],
        'KABUPATEN KEPULAUAN SERIBU': [-5.6108, 106.5276],
        
        // Jawa Tengah
        'KOTA SEMARANG': [-6.9667, 110.4167],
        'KOTA SURAKARTA': [-7.5755, 110.8243],
        'KOTA SOLO': [-7.5755, 110.8243],
        'KABUPATEN SEMARANG': [-7.3167, 110.5000],
        'KABUPATEN BOYOLALI': [-7.5322, 110.5953],
        'KABUPATEN KLATEN': [-7.7058, 110.6061],
        'KABUPATEN SUKOHARJO': [-7.6794, 110.8372],
        'KABUPATEN WONOGIRI': [-7.8145, 110.9260],
        'KABUPATEN KARANGANYAR': [-7.6022, 111.0378],
        'KABUPATEN SRAGEN': [-7.4186, 111.0217],
        'KABUPATEN GROBOGAN': [-7.0544, 110.6958],
        'KABUPATEN BLORA': [-6.9698, 111.4187],
        'KABUPATEN REMBANG': [-6.7089, 111.3426],
        'KABUPATEN PATI': [-6.7518, 111.0380],
        'KABUPATEN KUDUS': [-6.8048, 110.8405],
        'KABUPATEN JEPARA': [-6.5890, 110.6687],
        'KABUPATEN DEMAK': [-6.8901, 110.6399],
        'KABUPATEN KENDAL': [-6.9264, 110.2034],
        'KABUPATEN TEMANGGUNG': [-7.3167, 110.1667],
        'KABUPATEN WONOSOBO': [-7.3608, 109.9024],
        'KABUPATEN PURWOREJO': [-7.7181, 110.0078],
        'KABUPATEN MAGELANG': [-7.4698, 110.2177],
        'KOTA MAGELANG': [-7.4698, 110.2177],
        'KABUPATEN KEBUMEN': [-7.6707, 109.6544],
        'KABUPATEN PURBALINGGA': [-7.3881, 109.3668],
        'KABUPATEN BANJARNEGARA': [-7.3050, 109.6854],
        'KABUPATEN BATANG': [-6.9115, 109.7319],
        'KABUPATEN PEKALONGAN': [-6.8886, 109.6753],
        'KOTA PEKALONGAN': [-6.8886, 109.6753],
        'KABUPATEN PEMALANG': [-6.8982, 109.3776],
        'KABUPATEN TEGAL': [-6.8694, 109.1402],
        'KOTA TEGAL': [-6.8694, 109.1402],
        'KABUPATEN BREBES': [-6.8731, 108.8456],
        'KABUPATEN CILACAP': [-7.726, 109.0154],
        'KABUPATEN BANYUMAS': [-7.5149, 109.2921],
        
        // DI Yogyakarta
        'KOTA YOGYAKARTA': [-7.7956, 110.3695],
        'KABUPATEN SLEMAN': [-7.7326, 110.3553],
        'KABUPATEN BANTUL': [-7.8753, 110.3261],
        'KABUPATEN KULON PROGO': [-7.8266, 110.1614],
        'KABUPATEN GUNUNG KIDUL': [-7.9344, 110.5907],
        
        // Jawa Timur
        'KOTA SURABAYA': [-7.2575, 112.7521],
        'KOTA MALANG': [-7.9797, 112.6304],
        'KABUPATEN MALANG': [-8.1335, 112.7006],
        'KOTA BATU': [-7.8700, 112.5284],
        'KABUPATEN SIDOARJO': [-7.4474, 112.7183],
        'KABUPATEN GRESIK': [-7.1554, 112.6536],
        'KABUPATEN MOJOKERTO': [-7.4664, 112.4336],
        'KOTA MOJOKERTO': [-7.4664, 112.4336],
        'KABUPATEN JOMBANG': [-7.5460, 112.2326],
        'KABUPATEN NGANJUK': [-7.6051, 111.9046],
        'KABUPATEN MADIUN': [-7.6298, 111.5239],
        'KOTA MADIUN': [-7.6298, 111.5239],
        'KABUPATEN MAGETAN': [-7.6471, 111.3500],
        'KABUPATEN NGAWI': [-7.4040, 111.4462],
        'KABUPATEN BOJONEGORO': [-7.1502, 111.8817],
        'KABUPATEN TUBAN': [-6.8978, 111.9628],
        'KABUPATEN LAMONGAN': [-7.1196, 112.4133],
        'KABUPATEN BANGKALAN': [-7.0455, 112.7351],
        'KABUPATEN SAMPANG': [-7.1845, 113.2394],
        'KABUPATEN PAMEKASAN': [-7.1568, 113.4746],
        'KABUPATEN SUMENEP': [-7.0167, 113.8667],
        'KABUPATEN KEDIRI': [-7.8486, 112.0178],
        'KOTA KEDIRI': [-7.8486, 112.0178],
        'KABUPATEN BLITAR': [-8.0983, 112.1681],
        'KOTA BLITAR': [-8.0983, 112.1681],
        'KABUPATEN TULUNGAGUNG': [-8.0644, 111.9036],
        'KABUPATEN TRENGGALEK': [-8.0500, 111.7167],
        'KABUPATEN PONOROGO': [-7.8697, 111.4619],
        'KABUPATEN PACITAN': [-8.2069, 111.0919],
        'KABUPATEN LUMAJANG': [-8.1335, 113.2254],
        'KABUPATEN JEMBER': [-8.1844, 113.7068],
        'KABUPATEN BANYUWANGI': [-8.2192, 114.3691],
        'KABUPATEN BONDOWOSO': [-7.9138, 113.8213],
        'KABUPATEN SITUBONDO': [-7.7063, 114.0094],
        'KABUPATEN PROBOLINGGO': [-7.7543, 113.2159],
        'KOTA PROBOLINGGO': [-7.7543, 113.2159],
        'KABUPATEN PASURUAN': [-7.6453, 112.9075],
        'KOTA PASURUAN': [-7.6453, 112.9075],
        
        // Sumatera Utara
        'KOTA MEDAN': [3.5952, 98.6722],
        'KABUPATEN DELI SERDANG': [3.4309, 98.6718],
        'KABUPATEN LANGKAT': [3.7833, 98.4167],
        'KABUPATEN KARO': [3.1667, 98.5000],
        'KABUPATEN SIMALUNGUN': [2.9667, 99.0167],
        'KABUPATEN ASAHAN': [2.9667, 99.6167],
        'KABUPATEN LABUHANBATU': [2.1500, 100.1167],
        'KABUPATEN TOBA SAMOSIR': [2.6167, 98.8667],
        'KABUPATEN TAPANULI UTARA': [2.0167, 98.8167],
        'KABUPATEN TAPANULI TENGAH': [1.5500, 98.6833],
        'KABUPATEN TAPANULI SELATAN': [1.2833, 99.1500],
        'KABUPATEN NIAS': [1.0833, 97.5833],
        'KABUPATEN MANDAILING NATAL': [0.7833, 99.2500],
        'KABUPATEN DAIRI': [2.8333, 98.2167],
        'KABUPATEN PAKPAK BHARAT': [2.6167, 98.2500],
        'KABUPATEN HUMBANG HASUNDUTAN': [2.2833, 98.5000],
        'KABUPATEN SAMOSIR': [2.5833, 98.7167],
        'KABUPATEN SERDANG BEDAGAI': [3.3667, 99.1167],
        'KABUPATEN BATU BARA': [3.2167, 99.4500],
        'KABUPATEN PADANG LAWAS UTARA': [1.3833, 99.8333],
        'KABUPATEN PADANG LAWAS': [1.1833, 99.8167],
        'KABUPATEN LABUHANBATU SELATAN': [1.8500, 100.1833],
        'KABUPATEN LABUHANBATU UTARA': [2.3833, 100.0833],
        'KABUPATEN NIAS UTARA': [1.4167, 97.1333],
        'KABUPATEN NIAS BARAT': [1.0167, 97.4833],
        'KABUPATEN NIAS SELATAN': [0.6167, 97.7833],
        'KOTA BINJAI': [3.6000, 98.4833],
        'KOTA TEBING TINGGI': [3.3167, 99.1667],
        'KOTA PEMATANGSIANTAR': [2.9667, 99.0667],
        'KOTA TANJUNGBALAI': [2.9667, 99.8000],
        'KOTA SIBOLGA': [1.7333, 98.7833],
        'KOTA PADANG SIDEMPUAN': [1.3833, 99.2667],
        'KOTA GUNUNGSITOLI': [1.2833, 97.6167],
        
        // Bali
        'KOTA DENPASAR': [-8.6705, 115.2126],
        'KABUPATEN BADUNG': [-8.5069, 115.1775],
        'KABUPATEN GIANYAR': [-8.5391, 115.3275],
        'KABUPATEN TABANAN': [-8.5391, 115.1275],
        'KABUPATEN KLUNGKUNG': [-8.5391, 115.4275],
        'KABUPATEN BANGLI': [-8.2969, 115.3525],
        'KABUPATEN KARANGASEM': [-8.3469, 115.6275],
        'KABUPATEN BULELENG': [-8.1169, 115.0925],
        'KABUPATEN JEMBRANA': [-8.3469, 114.6275],
        
        // Sulawesi Selatan
        'KOTA MAKASSAR': [-5.1477, 119.4327],
        'KABUPATEN GOWA': [-5.3114, 119.4327],
        'KABUPATEN TAKALAR': [-5.4114, 119.4827],
        'KABUPATEN JENEPONTO': [-5.6414, 119.7427],
        'KABUPATEN BANTAENG': [-5.5514, 120.0227],
        'KABUPATEN BULUKUMBA': [-5.5414, 120.1927],
        'KABUPATEN SELAYAR': [-6.1214, 120.4627],
        'KABUPATEN MAROS': [-4.9914, 119.5727],
        'KABUPATEN PANGKEP': [-4.7714, 119.5327],
        'KABUPATEN BARRU': [-4.4214, 119.6327],
        'KABUPATEN BONE': [-4.7314, 120.0827],
        'KABUPATEN SOPPENG': [-4.3514, 119.8827],
        'KABUPATEN WAJO': [-4.0014, 120.0327],
        'KABUPATEN SIDENRENG RAPPANG': [-3.8914, 120.0727],
        'KABUPATEN PINRANG': [-3.6414, 119.6327],
        'KABUPATEN ENREKANG': [-3.5514, 119.7827],
        'KABUPATEN LUWU': [-2.5514, 120.1827],
        'KABUPATEN TANA TORAJA': [-3.0814, 119.8327],
        'KOTA PAREPARE': [-4.0114, 119.6227],
        'KOTA PALOPO': [-2.9914, 120.1927],
        
        // Sumatera Barat
        'KOTA PADANG': [-0.9471, 100.4172],
        'KABUPATEN PADANG PARIAMAN': [-0.6171, 100.1172],
        'KABUPATEN AGAM': [-0.2471, 100.1472],
        'KABUPATEN LIMA PULUH KOTA': [-0.0171, 100.4172],
        'KABUPATEN TANAH DATAR': [-0.4771, 100.6172],
        'KABUPATEN SOLOK': [-1.0171, 100.6572],
        'KABUPATEN SIJUNJUNG': [-0.6871, 101.0172],
        'KABUPATEN DHARMASRAYA': [-1.0871, 101.3572],
        'KABUPATEN SAWAHLUNTO SIJUNJUNG': [-0.6871, 101.0172],
        'KABUPATEN PESISIR SELATAN': [-1.7971, 100.7972],
        'KABUPATEN SOLOK SELATAN': [-1.4271, 101.4172],
        'KABUPATEN KEPULAUAN MENTAWAI': [-2.0871, 99.6472],
        'KABUPATEN PASAMAN': [0.2529, 99.9472],
        'KABUPATEN PASAMAN BARAT': [0.0829, 99.6172],
        'KOTA BUKITTINGGI': [-0.3071, 100.3672],
        'KOTA PADANGPANJANG': [-0.4671, 100.4072],
        'KOTA PAYAKUMBUH': [-0.2271, 100.6372],
        'KOTA SAWAHLUNTO': [-0.6771, 100.7772],
        'KOTA SOLOK': [-0.7971, 100.6572],
        'KOTA PARIAMAN': [-0.6171, 100.1172],
        
        // Riau
        'KOTA PEKANBARU': [0.5071, 101.4478],
        'KABUPATEN KAMPAR': [0.3271, 101.1478],
        'KABUPATEN ROKAN HULU': [0.8571, 100.4778],
        'KABUPATEN ROKAN HILIR': [2.0871, 100.5678],
        'KABUPATEN SIAK': [1.1371, 101.4278],
        'KABUPATEN KUANTAN SINGINGI': [-0.4829, 101.4778],
        'KABUPATEN INDRAGIRI HULU': [-0.5529, 102.1178],
        'KABUPATEN INDRAGIRI HILIR': [-0.3229, 103.2578],
        'KABUPATEN PELALAWAN': [0.3071, 102.1478],
        'KABUPATEN BENGKALIS': [1.4671, 101.4678],
        'KABUPATEN KEPULAUAN MERANTI': [1.2071, 103.3978],
        'KOTA DUMAI': [1.6871, 101.4478],
        
        // Kepulauan Riau
        'KOTA BATAM': [1.0456, 104.0305],
        'KOTA TANJUNGPINANG': [0.9171, 104.4478],
        'KABUPATEN BINTAN': [1.1371, 104.6178],
        'KABUPATEN KARIMUN': [0.9271, 103.4678],
        'KABUPATEN NATUNA': [4.0171, 108.2678],
        'KABUPATEN LINGGA': [-0.1829, 104.6178],
        'KABUPATEN KEPULAUAN ANAMBAS': [3.0171, 106.2678],
        
        // Kalimantan Timur
        'KOTA BALIKPAPAN': [-1.2379, 116.8529],
        'KOTA SAMARINDA': [-0.5022, 117.1536],
        'KABUPATEN KUTAI KARTANEGARA': [-0.7822, 117.4236],
        'KABUPATEN KUTAI BARAT': [-0.0322, 115.8836],
        'KABUPATEN KUTAI TIMUR': [0.1678, 117.4836],
        'KABUPATEN BERAU': [2.1678, 117.3636],
        'KABUPATEN PASER': [-1.7422, 116.2236],
        'KABUPATEN PENAJAM PASER UTARA': [-1.3422, 116.5036],
        'KABUPATEN MAHAKAM ULU': [0.7678, 115.0236],
        'KOTA BONTANG': [0.1378, 117.4836],
        
        // Sulawesi Utara
        'KOTA MANADO': [1.4748, 124.8421],
        'KABUPATEN MINAHASA': [1.3248, 124.9121],
        'KABUPATEN MINAHASA SELATAN': [1.2048, 124.4521],
        'KABUPATEN MINAHASA UTARA': [1.5748, 125.0621],
        'KABUPATEN MINAHASA TENGGARA': [0.9548, 124.3921],
        'KABUPATEN BOLAANG MONGONDOW': [0.7148, 124.2721],
        'KABUPATEN BOLAANG MONGONDOW UTARA': [0.8948, 124.0921],
        'KABUPATEN BOLAANG MONGONDOW SELATAN': [0.4248, 124.1821],
        'KABUPATEN BOLAANG MONGONDOW TIMUR': [0.8748, 124.5321],
        'KABUPATEN KEPULAUAN SANGIHE': [3.5748, 125.5021],
        'KABUPATEN KEPULAUAN TALAUD': [4.2748, 126.7821],
        'KABUPATEN KEPULAUAN SIAU TAGULANDANG BIARO': [2.7748, 125.4021],
        'KOTA TOMOHON': [1.3348, 124.8321],
        'KOTA KOTAMOBAGU': [0.7248, 124.3221],
        'KOTA BITUNG': [1.4548, 125.1821],
        
        // Sumatera Selatan
        'KOTA PALEMBANG': [-2.9761, 104.7754],
        'KABUPATEN OGAN KOMERING ULU': [-3.3061, 104.1454],
        'KABUPATEN OGAN KOMERING ILIR': [-3.2161, 105.1854],
        'KABUPATEN MUARA ENIM': [-3.6061, 103.9354],
        'KABUPATEN LAHAT': [-3.7961, 103.5454],
        'KABUPATEN MUSI RAWAS': [-2.9261, 103.0654],
        'KABUPATEN MUSI BANYUASIN': [-2.4461, 104.7754],
        'KABUPATEN BANYUASIN': [-2.1761, 104.8654],
        'KABUPATEN OGAN KOMERING ULU SELATAN': [-4.2361, 103.9654],
        'KABUPATEN OGAN KOMERING ULU TIMUR': [-3.5561, 104.6254],
        'KABUPATEN OGAN ILIR': [-3.4461, 104.7254],
        'KABUPATEN EMPAT LAWANG': [-3.8361, 103.3654],
        'KABUPATEN PENUKAL ABAB LEMATANG ILIR': [-3.1261, 104.0854],
        'KABUPATEN MUSI RAWAS UTARA': [-2.5261, 102.8654],
        'KOTA PRABUMULIH': [-3.4361, 104.2354],
        'KOTA PAGAR ALAM': [-4.0061, 103.2254],
        'KOTA LUBUKLINGGAU': [-3.2961, 102.8654],
        
        // Lampung
        'KOTA BANDAR LAMPUNG': [-5.4292, 105.2619],
        'KABUPATEN LAMPUNG SELATAN': [-5.6592, 105.4919],
        'KABUPATEN LAMPUNG TENGAH': [-4.8592, 105.2919],
        'KABUPATEN LAMPUNG UTARA': [-4.1892, 104.9419],
        'KABUPATEN LAMPUNG BARAT': [-5.0892, 104.2319],
        'KABUPATEN TULANG BAWANG': [-4.0592, 105.6319],
        'KABUPATEN TANGGAMUS': [-5.3692, 104.6319],
        'KABUPATEN LAMPUNG TIMUR': [-4.8592, 105.6919],
        'KABUPATEN WAY KANAN': [-4.2392, 104.5819],
        'KABUPATEN PESAWARAN': [-5.3992, 105.0619],
        'KABUPATEN PRINGSEWU': [-5.3592, 104.9719],
        'KABUPATEN MESUJI': [-3.4392, 105.8319],
        'KABUPATEN TULANG BAWANG BARAT': [-4.4692, 105.1919],
        'KABUPATEN PESISIR BARAT': [-5.0892, 103.9319],
        'KOTA METRO': [-5.1132, 105.3069]
    };

    let markersAdded = 0;
    let bounds = [];

    wilayahData.forEach(w => {
        let coords = null;
        const regencyUpper = w.regency.toUpperCase();
        
        // Cari koordinat exact match
        coords = indonesiaCoords[regencyUpper];
        
        // Jika tidak ada, coba dengan menambahkan prefix KABUPATEN/KOTA
        if (!coords) {
            coords = indonesiaCoords['KABUPATEN ' + regencyUpper] || indonesiaCoords['KOTA ' + regencyUpper];
        }
        
        // Jika masih tidak ada, coba pencarian partial
        if (!coords) {
            for (let city in indonesiaCoords) {
                const cityName = city.replace('KOTA ', '').replace('KABUPATEN ', '');
                if (regencyUpper.includes(cityName) || cityName.includes(regencyUpper)) {
                    coords = indonesiaCoords[city];
                    break;
                }
            }
        }
        
        // Jika masih tidak ada, gunakan koordinat default berdasarkan nama yang mirip
        if (!coords) {
            console.log('Koordinat tidak ditemukan untuk:', w.regency);
            // Set koordinat default ke tengah Indonesia
            coords = [-2.5, 118];
        }
        
        if (coords) {
            const [lat, lng] = coords;
            bounds.push([lat, lng]);
            
            const marker = L.circleMarker([lat, lng], {
                radius: Math.max(8, Math.min(w.total * 5, 25)),
                fillColor: '#3498db',
                color: '#fff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.7
            }).addTo(map);
            
            marker.bindPopup(`
                <div style="text-align: center; min-width: 150px;">
                    <strong style="font-size: 14px;">${w.regency}</strong><br>
                    <small style="color: #95a5a6;">${w.province}</small><br>
                    <span class="badge" style="background: #3498db; padding: 6px 12px; margin-top: 5px; display: inline-block;">${w.total} pendaftar</span>
                </div>
            `);
            
            markersAdded++;
        }
    });

    if (bounds.length > 0) {
        map.fitBounds(bounds, { padding: [50, 50] });
    }

    console.log(`Added ${markersAdded} markers to map`);
</script>
@endpush
