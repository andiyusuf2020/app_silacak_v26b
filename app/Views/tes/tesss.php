<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RekapModel; // Sesuaikan dengan nama model Anda
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RekapController extends BaseController
{
    protected $rekapModel;

    public function __construct()
    {
        $this->rekapModel = new RekapModel();
    }

    /**
     * Halaman View Utama Rekap
     */
    public function index()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $kdSU  = $this->request->getGet('kd_su') ?? '';

        $data['rekap'] = $this->rekapModel->getRekapLengkapPerTingkatAman($bulan, $kdSU);
        $data['bulan'] = $bulan;
        $data['kdSU']  = $kdSU;

        return view('rekap_lengkap', $data);
    }

    /**
     * Export Data Rekap ke Excel (.xlsx) dari Backend
     */
    public function exportExcel()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $kdSU  = $this->request->getGet('kd_su') ?? '';

        // Fetch Data menggunakan Model yang sama
        $rekap = $this->rekapModel->getRekapLengkapPerTingkatAman($bulan, $kdSU);

        if (empty($rekap)) {
            return redirect()->back()->with('error', 'Tidak ada data untuk diexport.');
        }

        // Inisialisasi Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap Anggaran & SRO');

        // --- 1. HEADER JUDUL ---
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'REKAPITULASI EFISIENSI ANGGARAN DAN CAPAIAN SRO');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'Bulan: ' . $bulan . ' | Filter SKPD: ' . ($kdSU ?: 'Semua SKPD'));
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // --- 2. HEADER TABEL ---
        $headers = [
            'A4' => 'Kode & Nama Hierarki',
            'B4' => 'Anggaran (Rp)',
            'C4' => 'Realisasi (Rp)',
            'D4' => 'Capaian Realisasi (%)',
            'E4' => 'Capaian SRO (%)',
            'F4' => 'Efisiensi (%)',
            'G4' => 'Status'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Styling Header Tabel
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
                'wrapText'   => true
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '212529'] // Dark Background
            ]
        ];
        $sheet->getStyle('A4:G4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(28);

        // --- 3. POPULASI DATA HIERARKI ---
        $row = 5;

        foreach ($rekap as $skpd) {
            // Level 1: SKPD
            $row = $this->writeExcelRow($sheet, $row, '[SKPD] [' . $skpd['kode'] . '] ' . $skpd['nama'], $skpd, 'D9E2EC', true);

            foreach ($skpd['program'] as $prog) {
                // Level 2: Program
                $row = $this->writeExcelRow($sheet, $row, '  [PROGRAM] [' . $prog['kode'] . '] ' . $prog['nama'], $prog, 'E2E8F0', true);

                foreach ($prog['kegiatan'] as $giat) {
                    // Level 3: Kegiatan
                    $row = $this->writeExcelRow($sheet, $row, '    [GIAT] [' . $giat['kode'] . '] ' . $giat['nama'], $giat, 'F1F5F9', false);

                    foreach ($giat['sub_kegiatan'] as $subGiat) {
                        // Level 4: Sub-Kegiatan
                        $row = $this->writeExcelRow($sheet, $row, '      [SUB GIAT] [' . $subGiat['kode'] . '] ' . $subGiat['nama'], $subGiat, 'FEF3C7', false);

                        foreach ($subGiat['list_sro'] as $sro) {
                            // Level 5: SRO
                            $sheet->setCellValue('A' . $row, '        [SRO] [' . $sro['kode'] . '] ' . $sro['nama']);
                            $sheet->setCellValue('B' . $row, $sro['anggaran']);
                            $sheet->setCellValue('C' . $row, $sro['realisasi']);
                            $sheet->setCellValue('D' . $row, '-');
                            $sheet->setCellValue('E' . $row, '-');
                            $sheet->setCellValue('F' . $row, '-');
                            $sheet->setCellValue('G' . $row, 'Detail SRO');

                            // Styling khusus level SRO
                            $sheet->getStyle('A' . $row)->getFont()->setItalic(true);
                            $sheet->getStyle('D' . $row . ':G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                            $row++;
                        }
                    }
                }
            }
        }

        // --- 4. FORMATTING KOLOM (Number Format, Borders, Alignment) ---
        $lastRow = $row - 1;

        // Number Format Rupiah/Angka
        $sheet->getStyle('B5:C' . $lastRow)->getNumberFormat()->setFormatCode('#,##0');
        $sheet->getStyle('D5:F' . $lastRow)->getNumberFormat()->setFormatCode('0.00');

        // Alignment Kolom
        $sheet->getStyle('B5:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('D5:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Border Seluruh Tabel
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['rgb' => 'D3D3D3'],
                ],
            ],
        ];
        $sheet->getStyle('A4:G' . $lastRow)->applyFromArray($borderStyle);

        // Auto-size Lebar Kolom
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // --- 5. OUTPUT RESPONSE DOWNLOAD ---
        $filename = 'Rekap_Anggaran_SRO_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    /**
     * Helper privat untuk penulisan baris dan warna hirarki Excel
     */
    private function writeExcelRow($sheet, $row, $label, $data, $hexColor, $isBold)
    {
        $sheet->setCellValue('A' . $row, $label);
        $sheet->setCellValue('B' . $row, $data['anggaran']);
        $sheet->setCellValue('C' . $row, $data['realisasi']);
        $sheet->setCellValue('D' . $row, $data['capaian_realisasi']);
        $sheet->setCellValue('E' . $row, $data['capaian_sro']);
        $sheet->setCellValue('F' . $row, $data['efisiensi']);
        $sheet->setCellValue('G' . $row, $data['status_efisiensi']);

        // Color Fill & Font Weight
        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
            'font' => ['bold' => $isBold],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $hexColor]
            ]
        ]);

        return $row + 1;
    }
}
