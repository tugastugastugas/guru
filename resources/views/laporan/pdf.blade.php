<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            margin: 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Laporan Ulasan</h1>

    @foreach($ulasan->groupBy('nama_periode') as $periode => $items)
    <h2>Periode: {{ $periode }}</h2>
    @foreach($items->groupBy('id_guru') as $guruId => $guruItems)
    <h3>Guru: {{ $guruItems->first()->nama_guru }}</h3>
    <p>Mapel: {{ $guruItems->first()->mapel_guru }}</p>
    <table class="table">
        <thead>
            <tr>
                <th>Username</th>
                <th>Kritikan</th>
                <th>Pujian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guruItems as $ulasan)
            <tr>
                <td>{{ $ulasan->username }}</td>
                <td>{{ $ulasan->kritikan }}</td>
                <td>{{ $ulasan->pujian }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach
    @endforeach
</body>

</html>