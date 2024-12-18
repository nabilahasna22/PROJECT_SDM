<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            padding: 5px;
            border: 1px solid black;
        }

        th {
            text-align: center;
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .title {
            text-align: center;
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .filter-info {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h2 class="title">Laporan Daftar Kegiatan</h2>

    <!-- Informasi Filter -->
    <div class="filter-info">
        <p><strong>Filter Kategori:</strong> {{ $filterKategori ?? 'Semua Kategori' }}</p>
        <p><strong>Filter Wilayah:</strong> {{ $filterWilayah ?? 'Semua Wilayah' }}</p>
    </div>

    <!-- Tabel Data Kegiatan -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kegiatan</th>
                <th>Deskripsi</th>
                <th>Periode</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
                <th>Kategori</th>
                <th>Wilayah</th>
                <th>Surat Tugas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kegiatan as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-left">{{ $item->kegiatan_nama }}</td>
                    <td class="text-left">{{ $item->deskripsi }}</td>
                    <td class="text-center">{{ $item->periode->tahun ?? '-' }}</td>
                    <td class="text-center">{{ $item->tanggal_mulai ? $item->tanggal_mulai->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $item->tanggal_selesai ? $item->tanggal_selesai->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">{{ $item->status }}</td>
                    <td class="text-left">{{ $item->kategori->kategori_nama ?? '-' }}</td>
                    <td class="text-left">{{ $item->wilayah->nama_wilayah ?? '-' }}</td>
                    <td class="text-center">
                        @if ($item->surat_tugas_url)
                            <a href="{{ $item->surat_tugas_url }}" target="_blank">Unduh</a>
                        @else
                            Tidak Ada
                        @endif
                    </td>                    
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>