<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rekapitulasi Presensi PKBM</title>
    <style>
        @page {
            margin: 20px 25px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header h2 {
            font-size: 12px;
            font-weight: normal;
            margin: 0 0 4px 0;
            color: #475569;
        }
        .meta-info {
            margin-bottom: 15px;
            font-size: 10px;
        }
        .meta-info table {
            width: 100%;
        }
        .meta-info td {
            padding: 2px 0;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #1e3a8a;
            border-left: 3px solid #1e3a8a;
            padding-left: 6px;
            margin: 15px 0 8px 0;
            text-transform: uppercase;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9.5px;
        }
        table.data-table th {
            background-color: #1e293b;
            color: #ffffff;
            font-weight: 600;
            text-align: left;
            padding: 6px 5px;
            border: 1px solid #334155;
        }
        table.data-table td {
            padding: 5px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8.5px;
            text-align: center;
        }
        .badge-hadir { background: #dcfce7; color: #15803d; }
        .badge-terlambat { background: #fef9c3; color: #a16207; }
        .badge-izin { background: #e0f2fe; color: #0369a1; }
        .badge-sakit { background: #f3e8ff; color: #7e22ce; }
        .badge-alpa { background: #fee2e2; color: #b91c1c; }
        .footer {
            margin-top: 20px;
            font-size: 9px;
            color: #64748b;
            text-align: right;
            border-top: 1px dashed #cbd5e1;
            padding-top: 5px;
        }
        .page-break {
            page-break-after: always;
        }
        .signatures {
            margin-top: 30px;
            width: 100%;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Pusat Kegiatan Belajar Masyarakat (PKBM)</h1>
        <h2>Laporan Rekapitulasi Presensi Digital & Log Kehadiran Pembelajaran</h2>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td style="width: 15%;"><strong>Pelatihan</strong></td>
                <td style="width: 35%;">: {{ $selectedPelatihan ? $selectedPelatihan->nama : 'Semua Pelatihan' }}</td>
                <td style="width: 15%;"><strong>Dicetak Pada</strong></td>
                <td style="width: 35%;">: {{ $generatedAt }}</td>
            </tr>
            <tr>
                <td><strong>Peserta</strong></td>
                <td>: {{ $selectedPeserta ? $selectedPeserta->nama . ' (' . ($selectedPeserta->nomor_induk ?? '-') . ')' : 'Semua Peserta' }}</td>
                <td><strong>Rentang Tanggal</strong></td>
                <td>: {{ $startDate ?: '-' }} s/d {{ $endDate ?: '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Lembar 1: Ringkasan Rekapitulasi per Peserta</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 12%;">No. Induk</th>
                <th style="width: 20%;">Nama Warga Belajar</th>
                <th style="width: 22%;">Pelatihan</th>
                <th style="width: 6%; text-align: center;">Total</th>
                <th style="width: 6%; text-align: center;">Hadir</th>
                <th style="width: 6%; text-align: center;">Lmbat</th>
                <th style="width: 5%; text-align: center;">Izin</th>
                <th style="width: 5%; text-align: center;">Sakit</th>
                <th style="width: 5%; text-align: center;">Alpa</th>
                <th style="width: 9%; text-align: center;">% Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapPeserta as $row)
            <tr>
                <td style="text-align: center;">{{ $row['no'] }}</td>
                <td>{{ $row['nomor_induk'] }}</td>
                <td><strong>{{ $row['nama'] }}</strong></td>
                <td>{{ $row['pelatihan'] }}</td>
                <td style="text-align: center;">{{ $row['total_sesi'] }}</td>
                <td style="text-align: center; color: #15803d; font-weight: bold;">{{ $row['hadir'] }}</td>
                <td style="text-align: center; color: #a16207;">{{ $row['terlambat'] }}</td>
                <td style="text-align: center;">{{ $row['izin'] }}</td>
                <td style="text-align: center;">{{ $row['sakit'] }}</td>
                <td style="text-align: center; color: #b91c1c; font-weight: bold;">{{ $row['alpa'] }}</td>
                <td style="text-align: center; font-weight: bold;">{{ $row['persentase'] }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" style="text-align: center; color: #64748b;">Tidak ada data rekapitulasi sesuai filter.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="section-title">Lembar 2: Log Detail Kehadiran Sesi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">Tanggal</th>
                <th style="width: 18%;">Judul Sesi</th>
                <th style="width: 10%;">Jam Sesi</th>
                <th style="width: 14%;">Lokasi</th>
                <th style="width: 16%;">Nama Peserta</th>
                <th style="width: 8%; text-align: center;">Status</th>
                <th style="width: 12%;">Waktu Server</th>
                <th style="width: 6%; text-align: center;">Metode</th>
                <th style="width: 18%;">Catatan Koreksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detailSesi as $row)
            <tr>
                <td>{{ $row['tanggal'] }}</td>
                <td><strong>{{ $row['judul_sesi'] }}</strong></td>
                <td>{{ $row['jam_sesi'] }}</td>
                <td>{{ $row['lokasi'] }}</td>
                <td>{{ $row['nama'] }} <br><span style="font-size: 8px; color: #64748b;">({{ $row['nomor_induk'] }})</span></td>
                <td style="text-align: center;">
                    @php
                        $st = strtolower($row['status']);
                        $badgeClass = 'badge-' . $st;
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $row['status'] }}</span>
                </td>
                <td>{{ $row['waktu_server'] }}</td>
                <td style="text-align: center;">{{ $row['metode'] }}</td>
                <td style="font-size: 8.5px; color: #475569;">{{ $row['catatan_koreksi'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center; color: #64748b;">Tidak ada log riwayat presensi sesuai filter.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="signatures">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Kepala PKBM</strong>
                <br><br><br><br>
                ( .................................................... )
            </td>
            <td>
                {{ now()->format('d F Y') }}<br>
                <strong>Tutor / Pengelola Kelas</strong>
                <br><br><br><br>
                ( {{ auth()->user()->nama ?? 'Tutor Kelas' }} )
            </td>
        </tr>
    </table>

    <div class="footer">
        Dicetak secara otomatis melalui Sistem Presensi Digital PKBM &bull; Halaman dicetak pada {{ $generatedAt }}
    </div>

</body>
</html>
