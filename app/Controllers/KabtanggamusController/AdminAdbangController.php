<?php

namespace App\Controllers\KabtanggamusController;

use App\Controllers\Sipkabkota\SuperadminController;

use \Myth\Auth\Authorization\GroupModel;
use App\Models\KabtanggamusModel\RealApbdModel;
use App\Models\KabtanggamusModel\JadwalModel;
use App\Models\KabtanggamusModel\AnggaranKasModel;
use App\Models\KabtanggamusModel\RealisasiPendapatanModel;
use App\Models\UserModel\TaUserModel;
use App\Models\KabtanggamusModel\DataDashboardModel;
use App\Models\KabtanggamusModel\TglApbdModel;
use App\Models\KabtanggamusModel\ExcelApbdModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Controllers\BaseController;
use App\Libraries\CryptoUrl;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use Dompdf\Dompdf;
use Dompdf\Options;

class AdminAdbangController extends BaseController
{
    protected $realapbdmodel;
    protected $tglapbdmodel;
    protected $excelmodel;
    protected $helpers = ['form', 'url'];
    protected $security;
    protected $jadwalmodel;
    protected $SuperadminController;
    protected $tausermodel;
    protected $anggaranKasModel;
    protected $realisasiPendapatanModel;
    protected $dataDashboardModel;
    protected $encrypter;
    public function __construct()
    {
        helper(['form', 'url', 'filesystem']);
        // Load library security untuk sanitasi
        $this->security = \Config\Services::security();
        $this->SuperadminController = new SuperadminController();
        $this->jadwalmodel = new JadwalModel();
        $this->tausermodel = new TaUserModel();
        $this->anggaranKasModel = new AnggaranKasModel();
        $this->realapbdmodel = new RealApbdModel();
        $this->tglapbdmodel = new TglApbdModel();
        $this->excelmodel = new ExcelApbdModel();
        $this->realisasiPendapatanModel = new RealisasiPendapatanModel();
        $this->dataDashboardModel = new DataDashboardModel();
        $this->encrypter      = \Config\Services::encrypter();
    }
    public function getDataJson()
    {
        // $bulan = $this->request->getGet('bulan') ?? date('m');
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();

        $dataRekap = $this->realapbdmodel->getRekapPerSKPD($jadwalaktif['bulan']);

        return $this->response->setJSON($dataRekap);
    }
    public function exportXlsx()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $dataRekap = $this->realapbdmodel->getRekapPerSKPD($bulan);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Judul Header Spreadsheet
        $sheet->setCellValue('A1', 'REKAPITULASI CAPAIAN DAN EFISIENSI PER SKPD');
        $sheet->setCellValue('A2', 'BULAN: ' . $bulan);
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(12);

        // Header Tabel
        $headers = [
            'NO',
            'KODE SKPD',
            'NAMA SKPD',
            'ANGGARAN (Rp)',
            'REALISASI (Rp)',
            'TOTAL SRO',
            'SRO NOL',
            'CAPAIAN REALISASI (%)',
            'CAPAIAN SRO (%)',
            'STATUS EFISIENSI'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $col++;
        }

        // Style Header Tabel
        $sheet->getStyle('A4:J4')->getFont()->setBold(true);
        $sheet->getStyle('A4:J4')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9E1F2');
        $sheet->getStyle('A4:J4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Isi Data
        $rowNum = 5;
        $no = 1;
        foreach ($dataRekap as $row) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, $row['kode']);
            $sheet->setCellValue('C' . $rowNum, $row['nama']);
            $sheet->setCellValue('D' . $rowNum, $row['anggaran']);
            $sheet->setCellValue('E' . $rowNum, $row['realisasi']);
            $sheet->setCellValue('F' . $rowNum, $row['sro']);
            $sheet->setCellValue('G' . $rowNum, $row['sro_nol']);
            $sheet->setCellValue('H' . $rowNum, $row['capaian_realisasi'] / 100);
            $sheet->setCellValue('I' . $rowNum, $row['capaian_sro'] / 100);
            $sheet->setCellValue('J' . $rowNum, $row['status_efisiensi']);

            // Format Angka & Persentase
            $sheet->getStyle('D' . $rowNum . ':E' . $rowNum)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('H' . $rowNum . ':I' . $rowNum)->getNumberFormat()->setFormatCode('0.00%');
            $sheet->getStyle('A' . $rowNum . ':B' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $rowNum . ':J' . $rowNum)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowNum++;
        }

        // Border Tabel
        $styleBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        $sheet->getStyle('A4:J' . ($rowNum - 1))->applyFromArray($styleBorder);

        // Auto Size Kolom
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Export File
        $filename = "Rekap_SKPD_Bulan_{$bulan}.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $dataRekap = $this->realapbdmodel->getRekapPerSKPD($bulan);

        $data = [
            'title'     => 'Rekapitulasi Per SKPD',
            'bulan'     => $bulan,
            'dataRekap' => $dataRekap,
        ];

        $html = view('KabtanggamusViews/Adminadbang/laporanlrfkopdpdf', $data);
        // return view('KabtanggamusViews/Adminadbang/laporanlrfkopdpdf', $data);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->stream("Rekap_SKPD_Bulan_{$bulan}.pdf", ["Attachment" => true]);
        exit;
    }
    public function updatedatadashboard()
    {
        $rules = [
            'enc_id'                  => 'required',
            'jumlah_perangkat_daerah' => 'required|integer|greater_than_equal_to[0]',
            'jumlah_kecamatan'        => 'required|integer|greater_than_equal_to[0]',
            'jumlah_tiuh_kampung'     => 'required|integer|greater_than_equal_to[0]',
            'total_anggaran_apbd'     => 'required|string|max_length[50]',
            'index_sakip'             => 'required|string|max_length[50]',
            'index_rb'                => 'required|string|max_length[50]',
            'tingkat_kemiskinan'      => 'required|string|max_length[50]',
            'angka_stunting'          => 'required|string|max_length[50]',
        ];

        if (!$this->validate($rules)) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'errors' => $this->validator->getErrors(),
                ]);
            }
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $encId = $this->request->getPost('enc_id');
            $decryptedId = $this->encrypter->decrypt(hex2bin($encId));
        } catch (\Exception $e) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Token/ID tidak valid.']);
            }
            return redirect()->back()->with('error', 'Token/ID tidak valid.');
        }

        $saveData = [
            'jumlah_perangkat_daerah' => $this->request->getPost('jumlah_perangkat_daerah'),
            'jumlah_kecamatan'        => $this->request->getPost('jumlah_kecamatan'),
            'jumlah_tiuh_kampung'     => $this->request->getPost('jumlah_tiuh_kampung'),
            'total_anggaran_apbd'     => $this->request->getPost('total_anggaran_apbd'),
            'index_sakip'             => $this->request->getPost('index_sakip'),
            'index_rb'                => $this->request->getPost('index_rb'),
            'tingkat_kemiskinan'      => $this->request->getPost('tingkat_kemiskinan'),
            'angka_stunting'          => $this->request->getPost('angka_stunting'),
        ];

        $this->dataDashboardModel->update($decryptedId, $saveData);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Data Dashboard Pembangunan berhasil diperbarui!',
            ]);
        }

        return redirect()->to(hash_url('' . session()->get('wilayah') . '/' . session()->get('groupuser'), ['hal' => 'inputdashboardutama']))->with('success', 'Data Dashboard Pembangunan berhasil diperbarui!');
    }
    // 2. Endpoint AJAX - Ambil Detail Single Data untuk Modal Edit
    public function getDetail($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method not allowed']);
        }

        $data = $this->realisasiPendapatanModel->find($id);
        if (!$data) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    // 3. Endpoint AJAX - Update Data Realisasi
    public function update($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method not allowed']);
        }

        $row = $this->realisasiPendapatanModel->find($id);
        if (!$row) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }

        $anggaranRaw  = $this->request->getPost('anggaran');
        $realisasiRaw = $this->request->getPost('realisasi');

        $anggaran  = str_replace(['.', ','], ['', '.'], $anggaranRaw ?? '0');
        $realisasi = str_replace(['.', ','], ['', '.'], $realisasiRaw ?? '0');

        $updateData = [
            'tahun_anggaran' => $this->request->getPost('tahun_anggaran'),
            'bulan'          => $this->request->getPost('bulan'),
            'jenis'          => $this->request->getPost('jenis'),
            'kode_rekening'  => $this->request->getPost('kode_rekening'),
            'uraian'         => $this->request->getPost('uraian'),
            'anggaran'       => floatval($anggaran),
            'realisasi'      => floatval($realisasi),
        ];

        if ($this->realisasiPendapatanModel->update($id, $updateData)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil diperbarui!']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui data.']);
    }

    // 4. Endpoint AJAX - Hapus Data Realisasi
    public function delete($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method not allowed']);
        }

        $row = $this->realisasiPendapatanModel->find($id);
        if (!$row) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }

        if ($this->realisasiPendapatanModel->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil dihapus!']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus data.']);
    }
    // Endpoint AJAX untuk reload tren grafik berdasarkan filter tahun
    public function getTrenBulanan()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error']);
        }

        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $data  = $this->realisasiPendapatanModel->getTrenPersentaseBulanan($tahun);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $data
        ]);
    }
    // METHOD BARU: Mengambil ringkasan data berdasarkan filter AJAX
    public function getSummaryByPeriode()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method Not Allowed']);
        }

        $tahun = $this->request->getGet('tahun');
        $bulan = $this->request->getGet('bulan');

        if (!$tahun || !$bulan) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Parameter tahun dan bulan wajib diisi.']);
        }

        $summary = $this->realisasiPendapatanModel->getSummaryByPeriode($tahun, $bulan);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => $summary
        ]);
    }

    public function storeangkas_ajax()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Method not allowed']);
        }

        $items = $this->request->getPost('items');
        $tahun = $this->request->getPost('tahun_anggaran');
        $bulan = $this->request->getPost('bulan');

        if (empty($items) || !is_array($items)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak boleh kosong.']);
        }

        $batchData = [];
        foreach ($items as $item) {
            $anggaran  = str_replace(['.', ','], ['', '.'], $item['anggaran'] ?? '0');
            $realisasi = str_replace(['.', ','], ['', '.'], $item['realisasi'] ?? '0');

            $batchData[] = [
                'tahun_anggaran' => $tahun,
                'bulan'          => $bulan,
                'jenis'          => $item['jenis'] ?? 'Pendapatan',
                'kode_rekening'  => $item['kode_rekening'] ?? '',
                'uraian'         => $item['uraian'] ?? '',
                'anggaran'       => floatval($anggaran),
                'realisasi'      => floatval($realisasi),
            ];
        }

        if ($this->realisasiPendapatanModel->insertBatch($batchData)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data realisasi berhasil disimpan!']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data.']);
    }

    public function storeangkas()
    {
        // 1. Validasi Input Server-Side
        $rules = [
            'perangkat_daerah_id' => 'required|numeric',
            'tahun_anggaran'      => 'required|numeric|exact_length[4]',
            'sub_kegiatan'        => 'required|min_length[3]',
            'pagu_anggaran'       => 'required',
            'anggaran_kas'        => 'required|is_array',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        /* 
         * [PERBAIKAN BUG]
         * Helper khusus untuk membersihkan string format Rupiah dari View (e.g. "100.000.000")
         * menjadi Float/Double murni yang aman dari glitch floating point.
         */
        $cleanRupiah = function ($val) {
            if (empty($val)) return 0.00;
            // Hapus semua karakter non-angka
            $cleaned = preg_replace('/[^0-9]/', '', $val);
            return round((float) $cleaned, 2);
        };

        // Parse Nilai Pagu Anggaran
        $paguAnggaran = $cleanRupiah($this->request->getPost('pagu_anggaran'));
        $inputBulanan = $this->request->getPost('anggaran_kas');

        $bulanList = [
            'januari',
            'februari',
            'maret',
            'april',
            'mei',
            'juni',
            'juli',
            'agustus',
            'september',
            'oktober',
            'november',
            'desember'
        ];

        $dataToSave = [
            'perangkat_daerah_id' => $this->request->getPost('perangkat_daerah_id'),
            'tahun_anggaran'      => $this->request->getPost('tahun_anggaran'),
            'sub_kegiatan'        => $this->request->getPost('sub_kegiatan'),
            'pagu_anggaran'       => $paguAnggaran,
        ];

        $totalKas = 0.00;

        // 2. Loop dan Ekstrak Data Input per Bulan
        foreach ($bulanList as $bulan) {
            $nilaiBulan = isset($inputBulanan[$bulan]) ? $cleanRupiah($inputBulanan[$bulan]) : 0.00;
            $dataToSave[$bulan] = $nilaiBulan;

            // Akumulasi total dengan pembulatan 2 desimal
            $totalKas = round($totalKas + $nilaiBulan, 2);
        }
        // echo dd($dataToSave);
        $dataToSave['total_anggaran_kas'] = $totalKas;

        /*
         * [PERBAIKAN BUG]
         * Validasi Keseimbangan (Balance): Pagu DPA VS Akumulasi Total Anggaran Kas
         * Menggunakan batas toleransi 0.01 cent untuk menghindari kesalahan pembulatan IEEE-754 float
         */
        if (abs($paguAnggaran - $totalKas) > 0.01) {
            $paguFormatted  = "Rp " . number_format($paguAnggaran, 0, ',', '.');
            $totalFormatted = "Rp " . number_format($totalKas, 0, ',', '.');

            return redirect()->back()->withInput()->with(
                'error',
                "Gagal Simpan! Total Anggaran Kas ({$totalFormatted}) tidak seimbang (unbalance) dengan Pagu DPA ({$paguFormatted}). Silakan periksa kembali."
            );
        }

        // 3. Simpan Ke Database Via Model
        $this->anggaranKasModel->simpan($dataToSave);
        return redirect()->back()->with('success', 'Data Anggaran Kas berhasil disimpan!');
        // return redirect()->to(hash_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/', ['hal' => 'angkasopd']));

        // return redirect()->to('/anggaran-kas')->with('success', 'Data Anggaran Kas berhasil disimpan!');
    }
    public function index()
    {
        $req = $this->request;
        $receivedParams = $req->getGet();
        $wilayah = $receivedParams['wilayah'] ?? null;
        $hal = $receivedParams['hal'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $kdSU = $receivedParams['kdSU'] ?? null;
        $blndata = $receivedParams['blndata'] ?? null;
        $kdSK = $receivedParams['kdSK'] ?? null;
        $tgldataopd = $receivedParams['tgldataopd'] ?? null;
        $tgldata = $receivedParams['tgldata'] ?? null;
        $id = $receivedParams['id'] ?? null;


        $user = user();
        $groupModel = new GroupModel();
        $groupuser = $groupModel->getGroupsForUser($user->id);
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        // $data['jadwalaktif'] = $this->jadwalmodel->jadwalaktifskrg();
        // echo dd($user);
        $data =
            [
                'wilayah' => session()->get('wilayah'),
                'namawilayah' => session()->get('namawilayah'),
                'groupuser' => $groupuser[0]['name'],
                'groupmenu' => $groupuser[0]['name'],
                'tahun' => session()->get('tahun'),
                'titlepage' => 'halaman Admin Adbang ',
                'datauser' => $user->sub_unit,
                'tglaktif' => session()->get('tglaktif'),
                'nama_opd' => $kdSU,
                'blndata' => $blndata,
                'kdSK' => $kdSK,
                'tgldataopd' => $tgldataopd,
                'tglapbd' => $this->realapbdmodel->tgldata(),
                'tglapbdaktif' => $this->tglapbdmodel->tgldataaktif(),
                'status' => 'apbd',
                'title' => 'Upload Realisasi APBD dari Data excel SIPD',
                'bulan' => $jadwalaktif['bulan'],
                'jadwal' => $this->jadwalmodel->jadwal(),
                'validation' => \Config\Services::validation(),
                // 'title' => 'Form Anggaran Kas Perangkat Daerah',
                'list_anggaran' => $this->anggaranKasModel->findAll(),

            ];
        // $data['tglapbd'] = $this->realapbdmodel->tgldata();
        // $data['tglapbdaktif'] = $this->tglapbdmodel->tgldataaktif();


        if (!$receivedParams) {
            // echo dd($data);
            if (!$data['groupuser']) {
                $data = [
                    'title1' => 'Anda belum memilih wilayah kabupaten/kota diawal halaman atau anda belum memilih halaman hak akses'
                ];
                return view('hakakses', $data);
                // return redirect()->to(base_url());
            }
            if (!$data['wilayah']) {
                $data = [
                    'title1' => 'Anda belum memilih wilayah kabupaten/kota diawal halaman atau anda belum memilih halaman hak akses'
                ];
                return view('hakakses', $data);
                // return redirect()->to(base_url());
            }
            if (!$data['tahun']) {
                session()->set('groupuser', $data['groupuser']);
                session()->set('groupmenu', $data['groupuser']);
                return view('pilihtahun', $data);
            }

            $datatglaktif = $this->tglapbdmodel->tgldataaktif();
            session()->set('tglaktif', $datatglaktif['tanggal']);
            $tahun = session()->get('tahun');
            $tglaktif = session()->get('tglaktif');
            $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($tahun, $tglaktif);
            // echo dd($tahun . '-'  . $tglaktif);
            // echo dd($data['dataopdadmin']);
            // echo dd($data);
            // echo dd($data['wilayah'] . '/' . $user->id);
            $urlasli = $req->getPath();
            $url = explode('/', $urlasli)[0];
            if ($url !== $data['wilayah']) {
                return redirect()->to(base_url('user'));
            }
            $tahunSelected = $this->request->getGet('tahun') ?? date('Y');
            $trenBulanan = $this->realisasiPendapatanModel->getTrenPersentaseBulanan($tahunSelected);
            $data['trenBulanan'] = $trenBulanan;
            $data['tahunSelected'] = $tahunSelected;
            // // return view('realisasi/form_input', [
            // //     'title'        => 'Input & Grafik Tren Realisasi APBD',
            // //     'tahunSelected' => $tahunSelected,
            // //     'trenBulanan'  => $trenBulanan
            // // ]);
            // return view('KabtanggamusViews/Adminadbang/form_inputpendapatan', $data);


            $tahunSelected = $this->request->getGet('tahun') ?? date('Y');
            $bulanSelected = $this->request->getGet('bulan') ?? 'all';

            $dataRealisasi = $this->realisasiPendapatanModel->getDataFilter($tahunSelected, $bulanSelected);
            $data['dataRealisasi'] = $dataRealisasi;
            $data['tahunSelected'] = $tahunSelected;
            $data['bulanSelected'] = $bulanSelected;
            // return view('realisasi/data_list', [

            return view('KabtanggamusViews/Adminadbang/index', $data);
        }
        $wilayah = session()->get('wilayah');
        if (!$wilayah) {
            return redirect()->to(base_url());
        }
        $this->ValidasiHash($req);
        if ($hal == 'apbdopd') {
            return view('KabtanggamusViews/Adminadbang/listtgldata', $data);

            // $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($tgldata);
            // return view('KabtanggamusViews/Adminadbang/apbdopd', $data);
        } elseif ($hal == 'uploadapbd') {
            // $data['status'] = 'apbd';
            // $data['title'] = 'Upload Realisasi APBD dari Data excel SIPD';
            // $data['validation'] = \Config\Services::validation();
            return view('KabtanggamusViews/Adminadbang/upload_form', $data);
        } elseif ($hal == 'angkasopd') {
            // $dataa['list_anggaran'] = $this->anggaranKasModel->findAll();
            $data['list_anggaran'] = $this->anggaranKasModel->findAll();
            // $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($data['tahun'], $data['tglaktif']);
            return view('KabtanggamusViews/Adminadbang/forminputangkasopd3lengkap', $data);
            // return view('KabtanggamusViews/Adminadbang/forminputangkasopd2', $data);
        } elseif ($hal == 'datapbj') {
            $data['dataopdadmin'] = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($data['tahun'], $data['tglaktif']);
            return view('KabtanggamusViews/Adminadbang/datapbj', $data);
        } elseif ($hal == 'jadwal') {

            $data['jadwalaktif'] = $this->jadwalmodel->jadwalaktifskrg();
            //tanggal data pada tabel apbd
            $data['tglapbd'] = $this->realapbdmodel->tgldata();
            $data['tglapbdaktif'] = $this->tglapbdmodel->tgldataaktif();
            // echo dd($data['tglapbd']);
            return view('KabtanggamusViews/Adminadbang/v_jadwal', $data);
        } elseif ($hal == 'pendapatan') {
            // $summary = $this->realisasiPendapatanModel->getSummaryBulanTerakhir();
            // $data['summaryStatis'] = $summary;
            $tahunSelected = $this->request->getGet('tahun') ?? date('Y');
            $trenBulanan = $this->realisasiPendapatanModel->getTrenPersentaseBulanan($tahunSelected);
            $data['trenBulanan'] = $trenBulanan;
            $data['tahunSelected'] = $tahunSelected;
            // // return view('realisasi/form_input', [
            // //     'title'        => 'Input & Grafik Tren Realisasi APBD',
            // //     'tahunSelected' => $tahunSelected,
            // //     'trenBulanan'  => $trenBulanan
            // // ]);
            // return view('KabtanggamusViews/Adminadbang/form_inputpendapatan', $data);


            $tahunSelected = $this->request->getGet('tahun') ?? date('Y');
            $bulanSelected = $this->request->getGet('bulan') ?? 'all';

            $dataRealisasi = $this->realisasiPendapatanModel->getDataFilter($tahunSelected, $bulanSelected);
            $data['dataRealisasi'] = $dataRealisasi;
            $data['tahunSelected'] = $tahunSelected;
            $data['bulanSelected'] = $bulanSelected;
            // return view('realisasi/data_list', [
            //     'title'         => 'Data Realisasi APBD',
            //     'tahunSelected' => $tahunSelected,
            //     'bulanSelected' => $bulanSelected,
            //     'dataRealisasi' => $dataRealisasi
            // ]);
            return view('KabtanggamusViews/Adminadbang/data_listpendapatan', $data);
        } elseif ($hal == 'inputbulanan') {
            $summary = $this->realisasiPendapatanModel->getSummaryBulanTerakhir();
            $data['summaryStatis'] = $summary;
            $tahunSelected = $this->request->getGet('tahun') ?? date('Y');
            $trenBulanan = $this->realisasiPendapatanModel->getTrenPersentaseBulanan($tahunSelected);
            $data['trenBulanan'] = $trenBulanan;
            $data['tahunSelected'] = $tahunSelected;
            // return view('realisasi/form_input', [
            //     'title'        => 'Input & Grafik Tren Realisasi APBD',
            //     'tahunSelected' => $tahunSelected,
            //     'trenBulanan'  => $trenBulanan
            // ]);
            return view('KabtanggamusViews/Adminadbang/form_inputpendapatan', $data);
        } elseif ($hal == 'inputdashboardutama') {
            $dataStat = $this->dataDashboardModel->first();
            // echo dd($dataStat);
            // Buat record awal jika database masih kosong
            if (!$dataStat) {
                $this->dataDashboardModel->insert([
                    'jumlah_perangkat_daerah' => 125,
                    'jumlah_kecamatan'        => 12,
                    'jumlah_tiuh_kampung'     => 120,
                    'total_anggaran_apbd'     => '1.5T',
                    'index_sakip'             => '(B) 90.19',
                    'index_rb'                => '7.392',
                    'tingkat_kemiskinan'      => '28.5%',
                    'angka_stunting'          => '99.9%',
                ]);
            }
            $dataStat2 = $this->dataDashboardModel->first();

            // Encrypt ID untuk proteksi parameter URL/Form
            $encryptedId = bin2hex($this->encrypter->encrypt((string)$dataStat2['id']));

            $data['title'] = 'Input Data Dashboard Pembangunan';
            $data['stat'] = $dataStat2;
            $data['encryptedId'] = $encryptedId;
            $data['validation'] = \Config\Services::validation();
            return view('KabtanggamusViews/Adminadbang/form_inputdashboardutama', $data);
        } elseif ($hal == 'laporanrfkopd') {
            // Handle the 'laporanrfkopd' case
            // $bulan = $this->request->getGet('bulan') ?? date('m');
            // $dataRekap = $this->realapbdmodel->getRekapPerSKPD($jadwalaktif['bulan']);

            // $data = [
            //     'title'     => 'Rekap Per SKPD',
            //     'bulan'     => $bulan,
            //     'dataRekap' => $dataRekap,
            // ];
            $data['dataRekap'] = $this->realapbdmodel->getRekapPerSKPD($jadwalaktif['bulan']);
            $data['bulan'] = $jadwalaktif['bulan'];
            $data['title'] = 'Rekap Per SKPD';
            // echo dd($data['dataRekap']);
            return view('KabtanggamusViews/Adminadbang/laporanlrfkopd2', $data);
        }

        if ($hal == 'gantibulanaktif') {

            return view('KabtanggamusViews/Adminadbang/v_listjadwal', $data);
            //echo dd($data['jadwal']);
        }
        if ($hal == 'editbulanaktif') {
            // $idAwal = $data['jadwalaktif']['id'] ?? null;
            // $tahun = $data['jadwalaktif']['tahun'] ?? null;
            // $bulan = $data['jadwalaktif']['bulan'] ?? null;
            $idAwal = $jadwalaktif['id'] ?? null;
            $tahun = $jadwalaktif['tahun'] ?? null;
            $bulan = $jadwalaktif['bulan'] ?? null;
            //   echo $idUbah;
            $nonaktifbln = [
                'id' => $idAwal,
                'tahun' => $tahun,
                'bulan' => $bulan,
                'status' => '0'
            ];
            $dataubah = $this->jadwalmodel->jdwlygdiaktifkan($id);
            $aktifbln = [
                'id' => $id,
                'tahun' => $dataubah['tahun'],
                'bulan' => $dataubah['bulan'],
                'status' => '1'
            ];
            // echo dd($nonaktifbln);
            $aktifasi = $this->jadwalmodel->save($aktifbln);
            $nonaktifasi = $this->jadwalmodel->save($nonaktifbln);
            if (!$aktifasi && !$nonaktifasi) {
                echo "Terjadi kesalahan";
            } else {
                return redirect()->to(hash_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/', ['hal' => 'jadwal']));
            }
        }
        if ($hal == 'gantitglaktif') {
            $updateAktif = $this->tglapbdmodel->updatetgl(1, $data['tahun'], $id);

            if (!$updateAktif) {
                session()->setFlashdata('message', 'Tanggal Data APBD berhasil diaktifkan');
            }
            // return redirect()->to(base_url('lrfkadmin/jadwal')->withInput()->with('message', 'Tanggal Data APBD berhasil diaktifkan'));
            return redirect()->to(hash_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/', ['hal' => 'jadwal']))->withInput()->with('message', 'Tanggal Data APBD berhasil diaktifkan');
        }


        if ($tgldata) {
            $dataopdadmin = $this->realapbdmodel->getCapaianKinerjaDenganGeometri($data['tahun'], $tgldata);
            // $dataapbdopd = $dataopdadmin['data'];
            // $data['dataopdadmin'] = $this->realapbdmodel->getRekapSubSkpdLengkap($tgldata, $data['bulan']);
            $dataopdapbd = $this->realapbdmodel->getRekapSubSkpd($tgldata);
            $data['dataopdadmin'] = $this->realapbdmodel->getRekapSubSkpd($tgldata);

            // Hanya enkripsi 'id' dan 'name'
            $dataopdapbds = array_map(function ($dataopdapbd) {
                $dataopdapbd['update_token'] = CryptoUrl::encrypt([
                    'kdSU'   => $dataopdapbd['NAMA_UNIT_SKPD'],
                    'blndata' => $dataopdapbd['BULAN']
                ]);
                return $dataopdapbd;
            }, $data['dataopdadmin']);
            $datauserx = json_encode($dataopdapbds);
            $data['datajson'] = preg_replace('/"([^"]+)"\s*:/', '$1:', $datauserx);

            // echo dd($data['dataopdadmin']);
            return view('KabtanggamusViews/Adminadbang/listopdapbd2', $data);
        }
        // $data['tgldata'] = $tgldata;
        // $data['dataopdadmin'] = $this->realapbdmodel->listopdadmin($tgldata);


        if ($hal == 'detail') {
            if ($action == 'opd') {
                $data['dataopd'] = $this->realapbdmodel->rpersk($kdSU, $data['tahun'], $blndata, $tgldataopd);
                // echo dd($kdSU . '-' . $tahunaktif . '-' . $bulanaktif . '-' . $tglaktif);
                // echo dd($data['dataopd']);
                return view('KabtanggamusViews/Adminadbang/detailopdapbd', $data);
            }
            if ($action == 'detailopd') {
                $data['dataopd'] = $this->realapbdmodel->rperrso2($kdSU, $kdSK, $data['tahun'], $tgldataopd)->getResultArray();

                // echo dd($kdSU . '-' . $kdSK . '-' . $tahunaktif . '-' . $tglaktif);
                // echo dd($data['dataopd']);
                return view('superadmin/2026/detailopdsro', $data);
            }
        }
    }
    public function dataperopd()
    {
        $token = $this->request->getGet('token');

        if (empty($token)) {
            return redirect()->back()->with('error', 'Token URL tidak ditemukan.');
        }

        // Dekripsi & Validasi Token (Maksimal 15 menit)
        $payload = CryptoUrl::decrypt($token, 900);

        if (!$payload) {
            return redirect()->back()->with('error', 'Akses ditolak: Token tidak valid atau telah kadaluwarsa.');
        }

        // Ekstrak data hasil dekripsi (hanya ID dan Name)
        $kdSU   = $payload['kdSU'];
        $blndata = $payload['blndata'];
        $user = user();
        $groupModel = new GroupModel();
        $groupuser = $groupModel->getGroupsForUser($user->id);
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();


        $data =
            [
                'wilayah' => session()->get('wilayah'),
                'groupuser' => $groupuser[0]['name'],
                'groupmenu' => $groupuser[0]['name'],
                'tahunaktif' => session()->get('tahun'),
                'titlepage' => 'halaman Admin Adbang ',
                'datauser' => $user->sub_unit,
                'tglaktif' => session()->get('tglaktif'),
                'nama_opd' => $kdSU,

                'rekap' => $this->realapbdmodel->getRekapLengkapPerTingkatAmanlengkapperOPDperProgram($blndata),
                // 'rekap' => $this->realapbdmodel->getRekapLengkapPerTingkatAmanlengkap($blndata, $kdSU),
                // 'rekap' => $this->realapbdmodel->getRekapLengkapPerTingkat($blndata, $kdSU),
                'blndata' => $blndata,
                'tglapbd' => $this->realapbdmodel->tgldata(),
                'tglapbdaktif' => $this->tglapbdmodel->tgldataaktif(),
                'bulan' => $jadwalaktif['bulan'],
                'validation' => \Config\Services::validation()

            ];
        // echo dd($blndata . '-' . $kdSU);
        // $data['rekap'] = $this->realapbdmodel->getRekapLengkapPerTingkat($blndata, $kdSU);
        // echo dd($data['rekap']);
        return view('KabtanggamusViews/listsubkeg7', $data);
    }


    public function uploadapbd()
    {
        ini_set('max_execution_time', 0);

        $rules = [
            'excel_file' => [
                'label' => 'Excel File',
                'rules' => 'uploaded[excel_file]|ext_in[excel_file,xls,xlsx]|max_size[excel_file,20480]',
                'errors' => [
                    'uploaded' => 'Harus mengupload file',
                    'ext_in' => 'Hanya file Excel yang diperbolehkan',
                    'max_size' => 'Ukuran file maksimal 2MB'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil file
        $file = $this->request->getFile('excel_file');

        // Generate nama file random untuk keamanan
        $newName = $file->getRandomName();
        $file->move(WRITEPATH . 'uploads', $newName);
        $filePath = WRITEPATH . 'uploads/' . $newName;

        try {
            // Load file Excel
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Siapkan data untuk database
            $dataToInsert = [];
            $headerSkipped = false;

            foreach ($rows as $row) {
                // Skip header
                if (!$headerSkipped) {
                    $headerSkipped = true;
                    continue;
                }
                $dataToInsert[] = [
                    'TAHUN' => $this->security->sanitizeFilename($row[0] ?? ''),
                    'BULAN' => $this->security->sanitizeFilename($row[1] ?? ''),
                    'KODE_SKPD' => $this->security->sanitizeFilename($row[2] ?? ''),
                    'NAMA_SKPD' => $this->security->sanitizeFilename($row[3] ?? ''),
                    'KODE_UNIT_SKPD' => $this->security->sanitizeFilename($row[4] ?? ''),
                    'NAMA_UNIT_SKPD' => $this->security->sanitizeFilename($row[5] ?? ''),
                    'KODE_URUSAN' => $this->security->sanitizeFilename($row[6] ?? ''),
                    'NAMA_URUSAN' => $this->security->sanitizeFilename($row[7] ?? ''),
                    'KODE_BIDANG_URUSAN' => $this->security->sanitizeFilename($row[8] ?? ''),
                    'NAMA_BIDANG_URUSAN' => $this->security->sanitizeFilename($row[9] ?? ''),
                    'KODE_PROGRAM' => $this->security->sanitizeFilename($row[10] ?? ''),
                    'NAMA_PROGRAM' => $this->security->sanitizeFilename($row[11] ?? ''),
                    'KODE_GIAT' => $this->security->sanitizeFilename($row[12] ?? ''),
                    'NAMA_GIAT' => $this->security->sanitizeFilename($row[13] ?? ''),
                    'KODE_SUB_GIAT' => $this->security->sanitizeFilename($row[14] ?? ''),
                    'NAMA_SUB_GIAT' => $this->security->sanitizeFilename($row[15] ?? ''),
                    'KODE_AKUN' => $this->security->sanitizeFilename($row[16] ?? ''),
                    'NAMA_AKUN' => $this->security->sanitizeFilename($row[17] ?? ''),
                    'KODE_KELOMPOK' => $this->security->sanitizeFilename($row[18] ?? ''),
                    'NAMA_KELOMPOK' => $this->security->sanitizeFilename($row[19] ?? ''),
                    'KODE_JENIS' => $this->security->sanitizeFilename($row[20] ?? ''),
                    'NAMA_JENIS' => $this->security->sanitizeFilename($row[21] ?? ''),
                    'KODE_OBJEK' => $this->security->sanitizeFilename($row[22] ?? ''),
                    'NAMA_OBJEK' => $this->security->sanitizeFilename($row[23] ?? ''),
                    'KODE_RINCIAN_OBJEK' => $this->security->sanitizeFilename($row[24] ?? ''),
                    'NAMA_RINCIAN_OBJEK' => $this->security->sanitizeFilename($row[25] ?? ''),
                    'KODE_SRO' => $this->security->sanitizeFilename($row[26] ?? ''),
                    'NAMA_SRO' => $this->security->sanitizeFilename($row[27] ?? ''),
                    'TOTAL_ANGGARAN' => $this->security->sanitizeFilename($row[28] ?? ''),
                    'TOTAL_REALISASI' => $this->security->sanitizeFilename($row[29] ?? ''),
                    'SELISIH' => $this->security->sanitizeFilename($row[30] ?? ''),
                    'KETERANGAN' => $this->security->sanitizeFilename($row[31] ?? ''),
                    // 'CREATE_AT' => $row[32],
                    // 'UPDATE_AT' => $row[33],
                ];
            }

            // Insert ke database dalam batch
            if (!empty($dataToInsert)) {
                $this->excelmodel->insertBatchData($dataToInsert);
            }

            // Hapus file setelah diproses
            unlink($filePath);
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);

            $data =
                [
                    'wilayah' => session()->get('wilayah'),
                    'groupuser' => $groupuser[0]['name'],
                    'groupmenu' => $groupuser[0]['name'],
                    'tahun' => session()->get('tahun'),
                    'titlepage' => 'halaman Admin Adbang ',
                    'datauser' => $user->sub_unit,
                    'tglaktif' => session()->get('tglaktif'),
                    'status' => 'apbd',
                    'title' => 'Upload Realisasi APBD dari Data excel SIPD',
                    'validation' => \Config\Services::validation()

                ];

            return redirect()->to(hash_url(' ' . $data['wilayah'] . '/' . $data['groupuser'] . '', ['hal' => 'uploadapbd']))->with('success', 'Data berhasil diupload: ' . count($dataToInsert) . ' record');
        } catch (\Exception $e) {
            // Hapus file jika terjadi error
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            log_message('error', 'Excel Upload Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    /**
     * Export Data Rekap ke Excel (.xlsx) dari Backend
     */
    public function exportExcel()
    {
        $req = $this->request;
        $receivedParams = $req->getGet();
        $wilayah = $receivedParams['wilayah'] ?? null;
        $hal = $receivedParams['hal'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $kdSU = $receivedParams['kdSU'] ?? null;
        $bulan = $receivedParams['blndata'] ?? null;
        $kdSK = $receivedParams['kdSK'] ?? null;

        // $bulan = $this->request->getGet('bulan') ?? date('m');
        // $kdSU  = $this->request->getGet('kd_su') ?? '';

        // Fetch Data menggunakan Model yang sama
        $rekap = $this->realapbdmodel->getRekapLengkapPerTingkatAmanlengkap($bulan, $kdSU);
        // echo dd($rekap);
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
    public function simpanapbd()
    {
        echo dd($this->request->getPost());
    }
}
