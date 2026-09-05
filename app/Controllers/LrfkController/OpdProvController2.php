<?php

namespace App\Controllers\LrfkController;

use App\Models\LrfkProvModel\JadwalModel;
use App\Models\UserModel\TaUserModel;
use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
use \Myth\Auth\Authorization\GroupModel;
use App\Models\LrfkProvModel\RealisasiSubKegModel;
use App\Models\LrfkProvModel\TotalRealisasiModel;
use App\Models\LrfkProvModel\TaIndikatorProgModal;
use App\Models\LrfkProvModel\TaIndikatorSubKegModal;
use App\Models\LrfkProvModel\TaRealisasiRinciModel;

use App\Libraries\PdfLibrary;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use FontLib\Table\Type\post;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;
use TCPDF;

class OpdProvController extends BaseController
{

    protected $jadwalmodel;
    protected $subkegmodel;
    protected $tausermodel;
    protected $realisasilrfk;
    protected $realisasilrfkrinci;

    protected $totalrealisasiM;
    protected $tcpdfConfig;
    protected $taindikator;
    protected $taindisubkeg;
    protected $db;
    public function __construct()
    {
        // helper(['form']);
        $this->tausermodel = new TaUserModel();
        $this->jadwalmodel = new JadwalModel();
        $this->subkegmodel = new SubKegModel();
        $this->realisasilrfk = new RealisasiSubKegModel();
        $this->totalrealisasiM = new TotalRealisasiModel();
        $this->taindikator = new TaIndikatorProgModal();
        $this->taindisubkeg = new TaIndikatorSubKegModal();
        $this->realisasilrfkrinci = new TaRealisasiRinciModel();
        $this->tcpdfConfig = new \Config\Tcpdf();
        helper(['form', 'url', 'filesystem']);
        $this->db = db_connect();
    }
    function bulan($bulan)
    {
        if ($bulan == '01') {
            $bln = 'Januari';
        }
        if ($bulan == '02') {
            $bln = 'Februari';
        }
        if ($bulan == '03') {
            $bln = 'Maret';
        }
        if ($bulan == '04') {
            $bln = 'April';
        }
        if ($bulan == '05') {
            $bln = 'Mei';
        }
        if ($bulan == '06') {
            $bln = 'Juni';
        }
        if ($bulan == '07') {
            $bln = 'Juli';
        }
        if ($bulan == '08') {
            $bln = 'Agustus';
        }
        if ($bulan == '09') {
            $bln = 'September';
        }
        if ($bulan == '10') {
            $bln = 'Oktober';
        }
        if ($bulan == '11') {
            $bln = 'November';
        }
        if ($bulan == '12') {
            $bln = 'Desember';
        }
        return $bln;
    }

    public function subkegiatan()
    {
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        ///  $data['subkeg'] = $this->subkegmodel->subkeg($kd);
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = $jadwalaktif['tahun'];
        $bulanaktif = $jadwalaktif['bulan'];
        $data = [
            'groupuser' => $namagroup,
            'titlepage' => 'Selamat Data di e-TAPIS Laporan Realisasi Fisik Anggaran Program Kegiatan Perangkat Daerah Provinsi Lampung',
            'datauser' => $this->tausermodel->listuser($user->id),
            'jadwalaktif' => $jadwalaktif,
        ];
        $data['listopd'] = $this->subkegmodel->listopd();
        $datauser = $this->tausermodel->listuser($user->id);
        $dataopd =  $this->subkegmodel->listopd($datauser['sub_unit']);
        // echo dd($dataopd); //"masuk update0";

        //  echo $page;
        //  $data['listapbd'] = $this->subkegmodel->listapbd($datauser['sub_unit']);
        $data['listprogram'] = $this->subkegmodel->listprogram($datauser['sub_unit'], $dataopd['kd_sub_unit']);
        // echo dd($data['listprogram']); //"masuk update0";

        return view('lrfk/opd/subkegiatan', $data);
    }
    public function index()
    {
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = $jadwalaktif['tahun'];
        $bulanaktif = $jadwalaktif['bulan'];
        $req = $this->request;

        $this->ValidasiHash($req);
        // Contoh penggunaan parameter
        $receivedParams = $req->getGet();
        $page = $receivedParams['page'] ?? null;
        $action = $receivedParams['action'] ?? null;
        $kd = $receivedParams['kd'] ?? null;
        $kdR = $receivedParams['kdR'] ?? null;
        $kdSK = $receivedParams['kdSK'] ?? null;
        $kdSU = $receivedParams['kdSU'] ?? null;
        $kdU = $receivedParams['kdU'] ?? null;


        $bulan = $receivedParams['bulan'] ?? null;

        $user = user();
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        //$datauser = $this->tausermodel->listuser($user->id);
        $data = [
            'groupuser' => $namagroup,
            'titlepage' => 'Selamat Data di e-TAPIS Laporan Realisasi Fisik Anggaran Program Kegiatan Perangkat Daerah Provinsi Lampung',
            'datauser' => $this->tausermodel->listuser($user->id),
            'jadwalaktif' => $jadwalaktif,
        ];

        $data['listopd'] = $this->subkegmodel->listopd();

        $data['subkeg'] = $this->subkegmodel->subkeg($kdSK, $kdSU);
        $datauser = $this->tausermodel->listuser($user->id);

        $dataopd =  $this->subkegmodel->listopd($datauser['sub_unit']);

        $data['blnskrg'] = $this->bulan(date('m'));

        if ($page == 'profile') {
            //  echo $page;
            return view('lrfk/opd/profile', $data);
        }
        if ($page == 'subkegiatan') {
            if ($action == 'list') {
                //  echo $page;
                //  $data['listapbd'] = $this->subkegmodel->listapbd($datauser['sub_unit']);
                $data['listprogram'] = $this->subkegmodel->listprogram($datauser['sub_unit'], $dataopd['kd_sub_unit']);
                //echo dd($data['subke']); //"masuk update0";
                return view('lrfk/opd/subkegiatan', $data);
            }
        }
        if ($page == 'progresrealisasi') {
            $jadwalall = $this->jadwalmodel->findAll();
            $data['datajadwal'] =  $jadwalall;
            if ($action == 'listall') {
                if (!$bulan) {
                    //  $jadwalbulan = $this->jadwalmodel->where('bulan', $bulan)->find();
                    $data['bulanpilih'] = '';
                    //echo "masuk koson bln";
                    // echo dd($jadwalbulan);
                } else {
                    $data['bulanpilih'] = $bulan;
                }
                $datauser = $this->tausermodel->listuser($user->id);
                $data['listapbdopd'] = $this->subkegmodel->paguperurusan($datauser['sub_unit'], $dataopd['kd_sub_unit']);
                $data['RperProgram'] = $this->totalrealisasiM->RperProgramOpd($tahunaktif, $dataopd['kd_sub_unit'], $datauser['sub_unit']);
                // $RProgram = $this->totalrealisasiM->RUrusanOpd($data['tahun'], $datauser['sub_unit'], $value['kd_urusan']);
                // echo dd($data['listapbdopd']);
                // return view('lrfk/opd/subkegiatanall', $data);
                return view('lrfk/opd/subkegallrinci2', $data);
            }
        }

        if ($page == 'progres') {
            if ($action == 'lapor') {
                $data['subkeg'] = $this->subkegmodel->subkeg($kdSK, $kdSU);
                $data['subkegbelanja'] = $this->subkegmodel->subkegbelanja($kdSK, $kdSU);
                $pagusk = $this->subkegmodel->pagusubkeg($kdSK, $kdSU);
                $data['totalpagusubkeg'] = $pagusk['pagu_rincian'];
                $datauser = $this->tausermodel->listuser($user->id);

                //$data['datarealisasi'] = $this->realisasilrfkrinci->RperSubKegOpd($kdSK, $tahunaktif, $datauser['sub_unit']);
                //$data['datarealisasirinci'] = $this->realisasilrfkrinci->RperSubKegOpd($kdSK, $tahunaktif, $datauser['sub_unit']);

                // echo dd($data['subkegbelanja']);

                return view('lrfk/opd/form_realisasi_rinci', $data);
                //echo dd($data['subkeg']); //"masuk update0";
                // echo "masuk lapor";
            }
            if ($action == 'lihat') {
                $data['subkegbelanja'] = $this->subkegmodel->subkegbelanja($kdSK, $kdSU);

                // $data['datarealisasi'] = $this->realisasilrfkrinci->realperSKpeBln(
                //     $kdSU,
                //     $kdSK,
                //     $tahunaktif,
                //     $bulanaktif
                // );
                $data['datarealisasirinci'] = $this->realisasilrfkrinci->realperSKpeBlnRinci2(
                    $kdSU,
                    $kdSK,
                    $tahunaktif,
                    $bulanaktif
                );

                if (!$data['datarealisasirinci']) {
                    echo dd("data kosong");
                }
                return view('lrfk/opd/v_realisasirinci', $data);
                // echo dd($data['datarealisasirinci']);
                /*
                echo dd($kdSU . '/' .
                    $kdSK . '/' .
                    $tahunaktif . '/' .
                    $bulanaktif);
            */
            }
            if ($action == 'hapus') {
                //  $dataR = $this->realisasilrfk->dataperId($kdR);

                $this->realisasilrfkrinci->delete($kdR);

                // $d = [
                //     'tahun' => $dataR['tahun'],
                //     'bulan' => $dataR['bulan'],
                //     'kd_sub_unit' => $dataR['kd_sub_unit'],
                //     'kd_subkegiatan' => $dataR['kd_subkegiatan']
                // ];
                // $this->realisasilrfk->delete($kdR);
                // echo dd($d);
                return redirect()->to('lrfkopd/subkegiatan')->withInput()->with('message', 'Hapus data berhasil');
            }
            if ($action == 'lihatpersubkeg') {
                $data['subkeg'] = $this->subkegmodel->subkeg($kdSK, $kdSU);
                $datauser = $this->tausermodel->listuser($user->id);
                $data['datarealisasi'] = $this->realisasilrfk->RperSubKegOpd($kdSK, $tahunaktif, $kdSU);
                $data['jadwal'] = $this->jadwalmodel->bulan($tahunaktif);
                return view('lrfk/opd/v_realisasipersubkeg', $data);
                //echo dd($data['datarealisasi']);
            }
        }
        if ($page == 'cetakopd') {
            if ($action == 'lrfkperurusan') {
                $jadwalall = $this->jadwalmodel->findAll();
                $data['datajadwal'] =  $jadwalall;
                //  $data['datarealisasi'] = $this->realisasilrfk->RperSubKegOpd($kdSK, $tahunaktif, $datauser['sub_unit']);
                // echo dd($data['datarealisasi']);

                if (!$bulan) {
                    $jadwalbulan = $this->jadwalmodel->where('bulan', $bulan)->find();
                    $data['bulanpilih'] = '';
                    //echo "masuk koson bln";
                    // echo dd($jadwalbulan);
                } else {
                    $data['bulanpilih'] = $bulan;
                }
                $data['listprogram'] = $this->subkegmodel->listprogram($datauser['sub_unit'], $dataopd['kd_sub_unit']);
                $data['urusanopd'] = $this->subkegmodel->urusanopd($dataopd['kd_sub_unit'], $kdU);

                $data['RperProgram'] = $this->totalrealisasiM->RperProgramOpd($tahunaktif, $dataopd['kd_sub_unit'], $datauser['sub_unit']);
                // echo dd($data['RperProgram']);
                //    echo dd($data['bulanpilih']);

                $html = view('/lrfk/opd/CetakApbdOpd', $data);
                $pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Biro Administrasi Pembangunan');
                $pdf->SetTitle('Laporan RFK Perangkat Daerah');
                $pdf->SetSubject('Laporan');
                $pdf->SetHeaderData(
                    PDF_HEADER_LOGO,
                    PDF_HEADER_LOGO_WIDTH,
                    PDF_HEADER_TITLE . 'PEMERINTAH PROVINSI LAMPUNG',
                    PDF_HEADER_STRING .
                        'Sistem Data Pengendalian dan Informasi (SiTAPIS)',
                    array(1, 64, 255),
                    array(1, 64, 100)
                );
                $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
                $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
                $pdf->setPrintHeader(true);
                $pdf->setPrintFooter(true);
                $pdf->addPage();
                // output the HTML content
                $pdf->writeHTML($html, true, true, true, false, '');
                //line ini penting
                $this->response->setContentType('application/pdf');
                //Close and output PDF document
                $pdf->Output('Rekap_LRFK_' . $tahunaktif . '_' . $datauser['sub_unit'], 'I');
            }
        }
    }
    public function saveprogres()
    {
        $kdSU = $this->request->getPost('kd_sub_unit');
        $kdSK = $this->request->getPost('kd_subkegiatan');
        $idrealisasi = $this->request->getPost('id');
        $jadwalaktif = $this->jadwalmodel->jadwalaktifskrg();
        $tahunaktif = $jadwalaktif['tahun'];
        $bulanaktif = $jadwalaktif['bulan'];
        // $dokumentasi = $this->request->getPost('dokumentasi');
        // $link_dokumentasi = $this->request->getPost('link_dokumentasi');

        $datareal = $this->request->getPost('datareal');
        $jumlah = count($datareal); // Mengetahui jumlah elemen
        for ($i = 0; $i < $jumlah; $i++) {
            if ($datareal[$i]['realisasi'] > $datareal[$i]['pagu_rincian']) {
                return redirect()->back()->withInput()->with('message', 'Realisasi  ' . $datareal[$i]['nm_rekening'] . '  melebihi pagu belanja');
            }
        }

        // $datarealisasisk = $this->realisasilrfk->realperSKpeBln(
        //     $kdSU,
        //     $kdSK,
        //     $tahunaktif,
        //     $bulanaktif
        // );
        // $datarealisasirinci = $this->realisasilrfkrinci->realperSKpeBlnRinci2(
        //     $kdSU,
        //     $kdSK,
        //     $tahunaktif,
        //     $bulanaktif
        // );

        //$datasubkeg = $this->subkegmodel->subkeg($kdSK, $kdSU);
        // $datasubkeg = $this->subkegmodel->subkeg($this->request->getPost('kd_subkegiatan'), $this->request->getPost('kd_sub_unit'));

        /*
        echo dd($datareal);
        echo dd(
            $kdSU . '/' .
                $kdSK . '/'
            //  $tahunaktif . '/' .
            //    $bulanaktif
        );
        */


        // // Validasi Input
        // $validation = \Config\Services::validation();
        // if ($link_dokumentasi) {
        //     $validation->setRules(
        //         [
        //             'link_dokumentasi' => 'valid_url_strict|regex_match[/drive\.google\.com/i]',
        //         ],
        //         ['link_dokumentasi' => [
        //             'valid_url_strict' => 'Format URL tidak valid',
        //             'regex_match' => 'URL harus dari Google Drive dan harus dapat dibuka'
        //         ]],
        //     );
        //     if (!$validation->withRequest($this->request)->run()) {
        //         return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        //     }
        //     $link = $link_dokumentasi;
        // } else {
        //     if ($datarealisasisk) {
        //         $datarealisasi = $this->realisasilrfk->listrealisasi($idrealisasi);
        //         //echo dd($idrealisasi);
        //         if ($datarealisasi) {
        //             $link = $datarealisasi['link_dokumentasi'];
        //         } else {
        //             $link = "";
        //         }
        //     } else {
        //         $link = "";
        //     }
        // }
        // $vargambar = $this->request->getFile('dokumentasi');
        // if ($datarealisasisk) {
        //     // Handle File Upload
        //     if ($vargambar == "") {
        //         $datarealisasi = $this->realisasilrfk->find($idrealisasi);
        //         $imageName = $datarealisasi['dokumentasi'];
        //         // echo dd('masuk gambar kosong');
        //     } else {
        //         //$imageName = "";
        //         //echo dd('masuk gambar isi');
        //         $validation->setRules(
        //             [
        //                 'dokumentasi' =>
        //                 'uploaded[dokumentasi]'
        //                     . '|max_size[dokumentasi,1024]'
        //                     . '|mime_in[dokumentasi,image/jpg,image/jpeg,image/png]'
        //                     . '|is_image[dokumentasi]', //  'link_dokumentasi' => 'valid_url_strict|regex_match[/drive\.google\.com/share/i]',
        //             ],
        //             ['dokumentasi' => [
        //                 // 'mime_in' => 'Dokumentasi harus foto atau gambar  .jpg/.jpeg/.png',
        //                 //'max_size' => 'Dokumentasi harus foto atau gambar maksimal 2Mb',
        //                 'uploaded' => 'Harus memilih file gambar',
        //                 'max_size' => 'Ukuran gambar maksimal 1MB',
        //                 'mime_in' => 'Format file harus JPG/JPEG/PNG',
        //                 'is_image' => 'File harus berupa gambar',
        //             ]],
        //         );
        //         if (!$validation->withRequest($this->request)->run()) {
        //             return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        //         }
        //         //  echo dd($datasubkeg);
        //         $link = $link_dokumentasi;
        //         $imageName = $vargambar->getRandomName(); // Sanitasi nama file
        //         // $vargambar->move(FCPATH . 'uploads/lrfkopd/' . $subunit . '/', $imageName); // Simpan di public/  
        //         $vargambar->move(FCPATH . 'uploads/lrfkopd/' . $this->request->getPost('sub_unit') . '/', $imageName); // Simpan di public/  

        //     }
        // } else {
        //     $validation->setRules(
        //         [
        //             'dokumentasi' =>
        //             'uploaded[dokumentasi]'
        //                 . '|max_size[dokumentasi,1024]'
        //                 . '|mime_in[dokumentasi,image/jpg,image/jpeg,image/png]'
        //                 . '|is_image[dokumentasi]', //  'link_dokumentasi' => 'valid_url_strict|regex_match[/drive\.google\.com/share/i]',
        //         ],
        //         ['dokumentasi' => [
        //             // 'mime_in' => 'Dokumentasi harus foto atau gambar  .jpg/.jpeg/.png',
        //             //'max_size' => 'Dokumentasi harus foto atau gambar maksimal 2Mb',
        //             'uploaded' => 'Harus memilih file gambar',
        //             'max_size' => 'Ukuran gambar maksimal 1MB',
        //             'mime_in' => 'Format file harus JPG/JPEG/PNG',
        //             'is_image' => 'File harus berupa gambar',
        //         ]],
        //     );
        //     if (!$validation->withRequest($this->request)->run()) {
        //         return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        //     }
        //     //  echo dd($datasubkeg);
        //     $link = $link_dokumentasi;
        //     $imageName = $vargambar->getRandomName(); // Sanitasi nama file
        //     // $vargambar->move(FCPATH . 'uploads/lrfkopd/' . $subunit . '/', $imageName); // Simpan di public/  
        //     $vargambar->move(FCPATH . 'uploads/lrfkopd/' . $this->request->getPost('sub_unit') . '/', $imageName); // Simpan di public/  

        // }
        // // Validation

        // $validation->setRules(
        //     [
        //         'uraian_realisasi' => 'required|regex_match[/target/i]',
        //     ],
        //     ['uraian_realisasi' => [
        //         'required' => 'Uraian Realisasi/Pelasksanaan Subkegiatan  wajib diisi.',
        //         'regex_match' => 'Uraian Realisasi/Pelasksanaan Subkegiatan harus mengandung kata target dan realisasi',
        //     ]],

        // );

        // if (!$validation->withRequest($this->request)->run()) {
        //     return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        // }
        // echo dd($datareal[0]['id']);
        if ($datareal[0]['id']) {
            /*
            $datasubkeg = $this->subkegmodel->subkeg($this->request->getPost('kd_subkegiatan'), $this->request->getPost('kd_sub_unit'));
            $datarealisasi = $this->realisasilrfk->find($idrealisasi);
            //  $paguR = $this->request->getPost('pagu_realisasi');

            if ($this->request->getPost('pagu_realisasi') == '0') {
                //$datarealisasi = $this->realisasilrfk->find($idrealisasi);
                $paguR = $datarealisasi['pagu_realisasi'];
            } else {
                $paguR = $this->request->getPost('pagu_realisasi');
            }
            */
            echo dd($datareal);

            // $pagusk = $this->subkegmodel->pagusubkeg($kdSK, $kdSU);
            // // $totalpagusk = $this->request->getPost('totalpagusubkeg');
            // $totalrealsk = $this->realisasilrfkrinci->totalrealperSKpeBln(
            //     $this->request->getPost('kd_sub_unit'),
            //     $this->request->getPost('kd_subkegiatan'),
            //     $tahunaktif,
            //     $bulanaktif
            // );
            //echo dd($datasubkeg);

            // $dataupdate1 = [
            //     'id' => $idrealisasi,
            //     'tahun' => $tahunaktif,
            //     'bulan' => $bulanaktif,
            //     'kd_sub_unit' => $this->request->getPost('kd_sub_unit'),
            //     'sub_unit' => $this->request->getPost('sub_unit'),
            //     'id_subkegiatan' => $this->request->getPost('id_subkegiatan'),
            //     'kd_subkegiatan' => $this->request->getPost('kd_subkegiatan'),
            //     'nm_subkegiatan' => $this->request->getPost('nm_subkegiatan'),
            //     'pagu_subkeg' => $pagusk, //$datasubkeg['pagu_subkeg'], //$totalpagusk,
            //     'pagu_realisasi' => $totalrealsk['realisasi'],
            //     'uraian_realisasi' => '', //$this->request->getPost('uraian_realisasi'),
            //     'dokumentasi' => '', // $imageName,
            //     'link_dokumentasi' => '' //$link,
            // ];
            // echo dd('ubah data masuk');

            // $this->realisasilrfk->save($dataupdate1);
            //   return view('lrfk/opd/subkegiatan', $data);

            //  return redirect()->back()->withInput()->with('message', 'penyimpanan data berhasil');
            // $this->realisasilrfkrinci->updateBatch($datareal, 'id');
            return redirect()->to(base_url('lrfkopd/subkegiatan'))->withInput()->with('message', 'penyimpanan data berhasil');
            //   echo dd($paguR);
        } else {


            // $totalpagusk = $this->request->getPost('totalpagusubkeg');
            // $totalrealsk = $this->realisasilrfkrinci->totalrealperSKpeBln(
            //     $this->request->getPost('kd_sub_unit'),
            //     $this->request->getPost('kd_subkegiatan'),
            //     $tahunaktif,
            //     $bulanaktif
            // );
            //  echo dd($totalrealsk['realisasi']);
            // $datarealisasitotal = [
            //     'tahun' => $tahunaktif,
            //     'bulan' => $bulanaktif,
            //     'kd_sub_unit' => $this->request->getPost('kd_sub_unit'),
            //     'sub_unit' => $this->request->getPost('sub_unit'),
            //     'id_subkegiatan' => $this->request->getPost('id_subkegiatan'),
            //     'kd_subkegiatan' => $this->request->getPost('kd_subkegiatan'),
            //     'nm_subkegiatan' => $this->request->getPost('nm_subkegiatan'),
            //     'pagu_subkeg' => $totalpagusk,
            //     'pagu_realisasi' => $totalrealsk['realisasi'],
            //     'uraian_realisasi' => '', //$this->request->getPost('uraian_realisasi'),
            //     'dokumentasi' => '', // $imageName,
            //     'link_dokumentasi' => '' //$link,
            // ];
            // // echo dd($datarealisasitotal);
            echo dd($datareal);

            // $this->realisasilrfkrinci->insertBatch($datareal);
            return redirect()->to(base_url('lrfkopd/subkegiatan'))->withInput()->with('message', 'penyimpanan data berhasil');
        }
    }

    public function updateprofile()
    {
        $data['titlepage'] = 'Selamat Data di e-TAPIS Laporan Realisasi Fisik Anggaran Program Kegiatan Perangkat Daerah Provinsi Lampung';
        $data['groupuser'] = 'forbiddenopd';
        $data['listopd'] = $this->subkegmodel->listopd();

        // Validasi Input
        $validation = \Config\Services::validation();
        // Validation
        $validation->setRules(
            [
                'nip' => 'required|numeric|min_length[18]|max_length[18]',
                'nama' => 'required|min_length[4]|max_length[100]',
                'jabatan' => 'required|min_length[5]|max_length[255]',
            ],
            ['nip' => [
                'required' => 'NIP  wajib diisi.',
                'numeric' => 'NIP Wajib Angka',
                'min_length' => 'NIP minimal 4 karakter.',
                'max_length' => 'NIP maksimal 100 karakter.',
            ]],
            ['nama' => [
                'required' => 'Nama  wajib diisi.',
                'min_length' => 'Nama minimal 4 karakter.',
                'max_length' => 'Nama maksimal 100 karakter.',
            ]],
            ['jabatan' => [
                'required' => 'Jabatan  wajib diisi.',
                'min_length' => 'Jabatan minimal 5 karakter.',
                'max_length' => 'Jabatan maksimal 100 karakter.',
            ]],
        );
        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
        $user = user();
        $nmopd =  $this->request->getPost('sub_unit');

        if ($nmopd == null) {
            $dataupdate = [
                'id' => $user->id, //$this->request->getPost('id'),
                'nama' => $this->request->getPost('nama'),
                'nip' => $this->request->getPost('nip'),
                'jabatan' => $this->request->getPost('jabatan'),
            ];
            $this->tausermodel->save($dataupdate);
            return redirect()->back()->withInput()->with('message', 'penyimpanan data berhasil');

            // return redirect()->to(base_url('lrfkopd/profile'));
        }
        if ($nmopd) {
            $dataupdate = [
                'id' => $user->id, //$this->request->getPost('id'),
                'sub_unit' => $this->request->getPost('sub_unit'),
                'nama' => $this->request->getPost('nama'),
                'nip' => $this->request->getPost('nip'),
                'jabatan' => $this->request->getPost('jabatan'),
            ];
            $this->tausermodel->save($dataupdate);

            return view('lrfkopd', $data);
        }

        // echo dd($dataupdate); //"masuk update0";
    }
}
