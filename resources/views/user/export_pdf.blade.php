<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            page-break-inside: auto; /* Prevent table from being cut off */
        }
        td, th {
            padding: 4px 3px;
            font-size: 10pt;
        }
        th {
            text-align: left;
        }
        .d-block {
            display: block;
        }
        img.image {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-10 {
            font-size: 10pt;
        }
        .font-11 {
            font-size: 11pt;
        }
        .font-12 {
            font-size: 12pt;
        }
        .font-13 {
            font-size: 13pt;
        }
        .border-bottom-header {
            border-bottom: 1px solid;
        }
        .border-all, .border-all th, .border-all td {
            border: 1px solid;
        }
        .no-wrap {
            white-space: nowrap;
        }
        .img-thumbnail {
            width: 50px;
            height: 50px;
        }
    </style>
</head>
<body>
    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ asset('polinema-bw.png') }}" alt="Logo Polinema" width="80" height="auto">
            </td>            
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold mb-1">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <h3 class="text-center">LAPORAN DATA USER</h3>

    <table class="border-all">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="no-wrap">NIP</th>
                <th class="no-wrap">Username</th>
                <th class="no-wrap">Nama</th>
                <th class="no-wrap">Email</th>
                <th class="no-wrap">No Telepon</th>
                <th class="no-wrap">Alamat</th>
                <th class="no-wrap">Level</th>
                <th class="no-wrap">Foto Profil</th>
            </tr>
        </thead>
        <tbody>
            @foreach($user as $user)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $user->nip }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->nama }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->no_telp }}</td>
                <td>{{ $user->alamat }}</td>
                <td>{{ $user->level->level_nama }}</td>
                <td class="text-center">
                    @if($user->foto)
                        <img src="{{ public_path('uploads/'.$user->foto) }}" alt="Foto Profil" class="img-thumbnail">
                    @else
                        Tidak ada foto
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>