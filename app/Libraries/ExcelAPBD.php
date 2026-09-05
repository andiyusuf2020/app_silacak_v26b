<?php

namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelReader
{
    public function read(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Validasi header
        $header = array_map('strtolower', array_shift($rows));
        $expectedHeader = ['name', 'email', 'phone', 'address'];

        if ($header !== $expectedHeader) {
            throw new \RuntimeException('Format header tidak valid. Harus: ' . implode(', ', $expectedHeader));
        }

        $data = [];

        foreach ($rows as $rowIndex => $row) {
            // Skip baris kosong
            if (empty(array_filter($row))) {
                continue;
            }

            // Pastikan jumlah kolom sesuai
            if (count($header) !== count($row)) {
                throw new \RuntimeException("Jumlah kolom tidak valid pada baris " . ($rowIndex + 2));
            }

            $combined = array_combine($header, $row);

            // Konversi format tanggal jika diperlukan
            foreach ($combined as $key => $value) {
                if (is_numeric($value) && (Date::isDateTime($sheet->getCellByColumnAndRow(array_search($key, $header) + 1, $rowIndex + 2)))) {
                    $combined[$key] = Date::excelToDateTimeObject($value)->format('Y-m-d H:i:s');
                }
            }

            $data[] = $combined;
        }

        return $data;
    }
}
