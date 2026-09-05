<!DOCTYPE html>
<html>

<head>
    <title>Cetak PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .img-container {
            text-align: center;
            margin: 10px 0;
        }

        .img-container img {
            max-width: 200px;
            max-height: 200px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Laporan Data</h1>
        <p>Tanggal: <?= date('d F Y'); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>ANDI</td>
                <td>TARGET </td>
                <td><?php FCPATH . '/uploads/1742796096_0b8c07928478302736cc.png'; ?></td>
                <td>
                    <div class="img-container">
                        <img src="uploads/lrfkopd/Biro Administrasi Pembangunan/1748172812_2c9cb400eda93f698c17.png" alt="Foto">
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>