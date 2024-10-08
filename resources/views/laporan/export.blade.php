<!DOCTYPE html>
<html>

<head>
    <title>Laporan Kegiatan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <style>
        @page {
            size: landscape;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
        }

        /* Mengatur lebar kolom tertentu */
        th:nth-child(1),
        td:nth-child(1) {
            width: 10%;
        }

        /* Mengatur ukuran font */
        table {
            font-size: 10px;
        }
    </style>
</head>

<body>
    <h2 class="text-center">Laporan Kegiatan {{ Auth::user()->profil->nama_pkk }}</h2>
    <br>
    <hr>
    <div><small>Waktu Download:</small> {{ now()->locale('id_ID')->translatedFormat('d F Y H:i:s') }}</div>
    <br>
    <table>
        <thead>
            <tr class="text-center">
                <th style="width: 1%">No.</th>
                <th>Judul</th>
                <th style="width: 20%">Bidang</th>
                <th style="width: 20%">Program Kerja</th>
                <th style="width: 10%">Tanggal</th>
                <th style="width: 10%">Foto</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($data as $row)
                <tr>
                    <td style="width: 1%" class="text-center">{{ $no++ }}</td>
                    <td>{{ $row->name }}</td>
                    <td style="width: 20%">{{ $row->bidang->name }}</td>
                    <td style="width: 20%">{{ $row->program->name }}</td>
                    <td style="width: 15%">
                        <center> {{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('l, d F Y') }}<center>
                    </td>
                    <td style="width: 10%">
                        @if ($row->foto)
                            <center><img src="{{ public_path('storage/foto-kegiatan/' . $row->foto) }}" alt="Foto"
                                    width="50"></center>
                        @else
                            <center><img src="{{ public_path('assets/img/banner/images.png') }}" alt="Foto"
                                    width="50"></center>
                        @endif

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
