<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md">

        <!-- Header & Toolbar -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Rekapitulasi Per SKPD</h1>
                <p class="text-sm text-gray-600">Diurutkan berdasarkan Capaian SRO Terendah</p>
            </div>

            <!-- Form Filter & Tombol Cetak -->
            <form method="get" action="<?= base_url('rekap') ?>" class="flex flex-wrap items-center gap-3">
                <div>
                    <select name="bulan" onchange="this.form.submit()" class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <?php $b = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
                            <option value="<?= $b ?>" <?= $b == $bulan ? 'selected' : '' ?>>
                                Bulan <?= $b ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>

                <a href="<?= base_url('rekap/exportXlsx?bulan=' . $bulan) ?>"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-4 py-2 rounded text-sm transition">
                    Cetak XLSX
                </a>

                <a href="<?= base_url('rekap/exportPdf?bulan=' . $bulan) ?>"
                    class="bg-rose-600 hover:bg-rose-700 text-white font-medium px-4 py-2 rounded text-sm transition">
                    Cetak PDF
                </a>
            </form>
        </div>

        <!-- Tabel Data -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 uppercase text-xs">
                        <th class="border border-gray-300 px-3 py-2 text-center">No</th>
                        <th class="border border-gray-300 px-3 py-2 text-center">Kode</th>
                        <th class="border border-gray-300 px-3 py-2 text-left">Nama SKPD</th>
                        <th class="border border-gray-300 px-3 py-2 text-right">Anggaran</th>
                        <th class="border border-gray-300 px-3 py-2 text-right">Realisasi</th>
                        <th class="border border-gray-300 px-3 py-2 text-center">SRO</th>
                        <th class="border border-gray-300 px-3 py-2 text-center">SRO Nol</th>
                        <th class="border border-gray-300 px-3 py-2 text-center">Capaian Realisasi</th>
                        <th class="border border-gray-300 px-3 py-2 text-center bg-yellow-100">Capaian SRO</th>
                        <th class="border border-gray-300 px-3 py-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($dataRekap)): ?>
                        <tr>
                            <td colspan="10" class="text-center py-4 text-gray-500">Data tidak ditemukan.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1;
                        foreach ($dataRekap as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="border border-gray-300 px-3 py-2 text-center"><?= $no++ ?></td>
                                <td class="border border-gray-300 px-3 py-2 text-center font-mono"><?= esc($row['kode']) ?></td>
                                <td class="border border-gray-300 px-3 py-2"><?= esc($row['nama']) ?></td>
                                <td class="border border-gray-300 px-3 py-2 text-right">Rp <?= number_format($row['anggaran'], 2, ',', '.') ?></td>
                                <td class="border border-gray-300 px-3 py-2 text-right">Rp <?= number_format($row['realisasi'], 2, ',', '.') ?></td>
                                <td class="border border-gray-300 px-3 py-2 text-center"><?= number_format($row['sro']) ?></td>
                                <td class="border border-gray-300 px-3 py-2 text-center"><?= number_format($row['sro_nol']) ?></td>
                                <td class="border border-gray-300 px-3 py-2 text-center"><?= number_format($row['capaian_realisasi'], 2, ',', '.') ?>%</td>
                                <td class="border border-gray-300 px-3 py-2 text-center font-semibold bg-yellow-50"><?= number_format($row['capaian_sro'], 2, ',', '.') ?>%</td>
                                <td class="border border-gray-300 px-3 py-2 text-center">
                                    <span class="px-2 py-1 text-xs rounded font-semibold <?= $row['status_efisiensi'] == 'Inefisien' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>">
                                        <?= esc($row['status_efisiensi']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>

</html>