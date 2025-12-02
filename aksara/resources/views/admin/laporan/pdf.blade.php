<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Admin - Kegiatan Dosen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-item {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 10px;
            background-color: #e9ecef;
            border: 1px solid #ddd;
        }
        .stat-item h3 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .stat-item p {
            margin: 5px 0 0 0;
            font-size: 10px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #4a5568;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-diusulkan { background-color: #fef3c7; color: #92400e; }
        .badge-tidak_lolos { background-color: #fee2e2; color: #991b1b; }
        .badge-lolos_perlu_revisi { background-color: #dbeafe; color: #1e40af; }
        .badge-lolos { background-color: #d1fae5; color: #065f46; }
        .badge-revisi_pra_final { background-color: #e0e7ff; color: #3730a3; }
        .badge-selesai { background-color: #d1fae5; color: #065f46; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEGIATAN PENELITIAN DAN PENGABDIAN MASYARAKAT</h1>
        <p><strong>Laporan Administrator</strong></p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
        @if($selectedDosen)
            <p>Dosen: {{ $selectedDosen->name }}</p>
        @endif
        @if($year)
            <p>Tahun: {{ $year }}</p>
        @endif
        @if($status)
            <p>Status: {{ ucfirst(str_replace('_', ' ', $status)) }}</p>
        @endif
    </div>

    <div class="stats">
        <div class="stat-item">
            <h3>{{ $stats['total_penelitian'] }}</h3>
            <p>Total Penelitian</p>
        </div>
        <div class="stat-item">
            <h3>{{ $stats['total_pengabdian'] }}</h3>
            <p>Total Pengabdian</p>
        </div>
        <div class="stat-item">
            <h3>{{ $stats['penelitian_lolos'] + $stats['pengabdian_lolos'] }}</h3>
            <p>Total Lolos</p>
        </div>
        <div class="stat-item">
            <h3>{{ $stats['penelitian_selesai'] + $stats['pengabdian_selesai'] }}</h3>
            <p>Total Selesai</p>
        </div>
    </div>

    <div class="section-title">DAFTAR PENELITIAN</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Judul</th>
                <th width="20%">Dosen</th>
                <th width="10%">Tahun</th>
                <th width="15%">Sumber Dana</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penelitian as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->tahun }}</td>
                    <td>{{ $item->sumber_dana }}</td>
                    <td>
                        <span class="badge badge-{{ $item->status }}">
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #999;">Tidak ada data penelitian</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">DAFTAR PENGABDIAN MASYARAKAT</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="30%">Judul</th>
                <th width="20%">Dosen</th>
                <th width="10%">Tahun</th>
                <th width="15%">Lokasi</th>
                <th width="20%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengabdian as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->judul }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->tahun }}</td>
                    <td>{{ $item->lokasi }}</td>
                    <td>
                        <span class="badge badge-{{ $item->status }}">
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #999;">Tidak ada data pengabdian</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh Sistem AKSARA</p>
        <p>Aplikasi Kegiatan Sains, Riset, dan Abdi Masyarakat</p>
    </div>
</body>
</html>
