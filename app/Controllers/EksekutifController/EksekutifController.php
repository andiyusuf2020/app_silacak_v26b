<?php

namespace App\Controllers\EksekutifController;

use CodeIgniter\Controller;
use App\Models\LokasiModel;
use App\Models\EksekutifModel\TaApbdSkpdModel;
use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;


class EksekutifController extends Controller
{
    protected $lokasiModel;
    protected $apbdopdmodel;
    protected $subkegmodel;

    protected $helpers = ['form'];

    public function __construct()
    {
        $this->lokasiModel = new LokasiModel();
        $this->apbdopdmodel = new TaApbdSkpdModel();
        $this->subkegmodel = new SubKegModel();
    }
    public function index()
    {
        $keyword = $this->request->getVar('keyword');
        $kategori = $this->request->getVar('kategori');
        // $lokasi = $lokasiModel->findAll();
        $tahun = 2025;
        $bulan = "juli";
        $jumlahprog = count($this->subkegmodel->jumlahprogram($tahun));
        $jumlahkeg = count($this->subkegmodel->jumlahkegiatan($tahun));
        $jumlahsubkeg = count($this->subkegmodel->jumlahsubkegiatan($tahun));

        $jumlahapbd = $this->apbdopdmodel->selectSUM('pagu')->selectSUM('realisasi')->where('tahun', $tahun)
            ->where('bulan', $bulan)->get()->getRowArray();
        $data = [
            'title' => 'Daftar Lokasi',
            'kategoriList' => $this->lokasiModel->getKategori(),
            'groupuser' => '',
            'datarealisasi' => $this->apbdopdmodel->DataAll($tahun, $bulan),
            'totprog' => $jumlahprog,
            'totkeg' => $jumlahkeg,
            'totsubkeg' => $jumlahsubkeg,
            'totapbd' => $jumlahapbd,

            // 'lokasi' => $this->lokasiModel->getLokasi()
        ];

        if ($keyword) {
            $data['lokasi'] = $this->lokasiModel->search($keyword);
            $data['keyword'] = $keyword;
        } elseif ($kategori) {
            $data['lokasi'] = $this->lokasiModel->where('kategori', $kategori)->findAll();
            $data['kategori'] = $kategori;
        } else {
            $data['lokasi'] = $this->lokasiModel->getLokasi();
        }

        // echo dd($jumlah);
        return view('eksekutif/dashboard', $data);
    }
    public function getLokasiJson()
    {
        $lokasi = $this->lokasiModel->getLokasi();
        return $this->response->setJSON($lokasi);
    }
    public function getImage($filename)
    {
        $path = WRITEPATH . '../public/uploads/lokasi/' . $filename;

        if (!file_exists($path)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('File tidak ditemukan');
        }

        $mime = mime_content_type($path);
        header('Content-Type: ' . $mime);
        readfile($path);
        exit;
    }
}
