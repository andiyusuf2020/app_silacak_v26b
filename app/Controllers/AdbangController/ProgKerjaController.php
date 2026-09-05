<?php

namespace App\Controllers\AdbangController;

use CodeIgniter\Exceptions\PageNotFoundException;
use App\Models\adbangmodel\Misimodel as misiModel;
use App\Models\adbangmodel\ProgKerjaModel as programkeramodel;
use App\Models\adbangmodel\KebijakanModel as arahkebijakan;

use CodeIgniter\Controller;
use App\Controllers\BaseController;
use App\Controllers\HashCek; // as CekHash;

class ProgKerjaController extends BaseController
{
    protected $misimodel;
    protected $Programkerjamodel;
    protected $Arahkebijakan;
    public function __construct()
    {
        helper(['form']);
        $this->misimodel = new misiModel();
        $this->Programkerjamodel = new programkeramodel();
        $this->Arahkebijakan   = new arahkebijakan();
    }
    function saveCurrentUrl()
    {
        // Jangan simpan URL jika berasal dari form submit
        if ($this->request->getMethod() === 'get') {
            session()->set('previous_url', current_url());
        }
    }

    public function index()
    {
        $data['titlepage'] = 'Misi dan Program Kerja Gubernur dan Wakil Gubernur Lampung 2026-2030';
        $data['datamisi'] = $this->misimodel->lisMisi();
        $data['groupuser'] = 'adminprogkerja';
        $data['groupmenu'] = 'adminprogkerja';

        return view('appprov/v_misi', $data);
    }
    public function opdprov()
    {
        $data['titlepage'] = 'Daftar Perangkat Daerah dilinkungan Provinsi Lampung';

        echo "OPD";
    }
    public function arahgkebijakan()
    {
        $this->saveCurrentUrl();
        $req = $this->request;
        $this->ValidasiHash($req);
        // Contoh penggunaan parameter
        $receivedParams = $req->getGet();
        $misiid = $receivedParams['misiid'] ?? null;
        $idpk = $receivedParams['idpk'] ?? null;
        $id = $receivedParams['id'] ?? null;
        $action = $receivedParams['action'] ?? null;

        $data['titlepage'] = 'Arah Kebijakan Program Kerja Gubernur dan Wakil Gubernur Lampung 2026-2030 per-Program Kerja';
        $data['groupuser'] = 'adminprogkerja';

        if ($action == 'list') {

            $data['datamisi'] = $this->misimodel->lisMisi($misiid);
            $data['dataprogkerja'] = $this->Programkerjamodel->ProgKerja($idpk);
            $data['dataarahkeb'] = $this->Arahkebijakan->listArahKeb($idpk) ?? null;
            $data['groupmenu'] = 'adminprogkerja';

            //simpan idmisi ke session
            // session()->set('idmisises', $id);
            return view('appprov/v_arahkebijakan', $data);
            //echo dd($data['titlepage']);
        }
        if ($action == 'tambah') {
            $data['halaman'] = 'tambah';
            $data['datamisi'] = $this->misimodel->lisMisi($misiid);
            $data['dataprogkerja'] = $this->Programkerjamodel->ProgKerja($idpk);
            $data['dataarahkeb'] = $this->Arahkebijakan->listArahKeb($idpk) ?? null;
            return view('appprov/form_arahkebijakan', $data);
        }
        if ($action == 'edit') {
            //   $sesidmisi = session()->get('idmisises');

            $data['halaman'] = 'edit';
            $data['datamisi'] = $this->misimodel->lisMisi($misiid);
            $data['dataprogkerja'] = $this->Programkerjamodel->ProgKerja($idpk);
            $data['dataarahkeb'] = $this->Arahkebijakan->find($id) ?? null;
            if (!$data['dataarahkeb']) {
                throw new PageNotFoundException('Item Program Kerja not found');
            }
            // echo dd($data['dataarahkeb']);
            return view('appprov/form_arahkebijakan', $data);
        }
        if ($action == 'hapus') {
            $item = $this->Arahkebijakan->find($id);

            if ($item) {
                // Hapus gambar dari server
                if ($item['gambar'] && file_exists(FCPATH . 'uploads/' . $item['gambar'])) {
                    unlink(FCPATH . 'uploads/' . $item['gambar']);
                }
                $this->Arahkebijakan->delete($id);
            }

            return redirect()->back()->withInput()->with('message', 'hapus data berhasil');
            //  return request()->
        }
    }

    public function simpanarahkeb()
    {
        // Validasi input
        if (!$this->validate($this->Arahkebijakan->validationRules, $this->Arahkebijakan->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $idarahkeb = $this->request->getPost('id');
        //  echo dd($idarahkeb);

        if ($idarahkeb) {
            // Handle File Upload
            $vargambar = $this->request->getFile('gambar');
            if ($vargambar == "") {
                $item = $this->Arahkebijakan->find($idarahkeb);
                $imageName = $item['gambar'];
            } else {
                // Validasi Input
                $validation = \Config\Services::validation();
                $validation->setRules([
                    'gambar' => 'uploaded[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/gif,image/png]|max_size[gambar,2048]|ext_in[gambar,png,jpg,gif,jpeg]',
                ]);
                if (!$validation->withRequest($this->request)->run()) {
                    return redirect()->back()->withInput()->with('errors', $validation->getErrors());
                }
                $imageName = $vargambar->getRandomName(); // Sanitasi nama file
                $vargambar->move(FCPATH . 'uploads', $imageName); // Simpan di public/
                // Hapus gambar lama jika ada
                $item = $this->Arahkebijakan->find($idarahkeb);
                if ($item['gambar'] && file_exists(FCPATH . 'uploads/' . $item['gambar'])) {
                    unlink(FCPATH . 'uploads/' . $item['gambar']);
                }
                $dataupdate = [
                    'id' => $this->request->getPost('id'),
                    'id_progkerja' => $this->request->getPost('id_progkerja'),
                    'id_misi' => $this->request->getPost('id_misi'),
                    'tahun' => date('Y'),
                    'judul_kebijakan' => $this->request->getPost('judul_kebijakan'),
                    'ket' => $this->request->getPost('ket'),
                    'gambar' => $imageName,
                    // 'indikator' => $this->request->getPost('indikator'),
                ];
                $simpan = $this->Arahkebijakan->save($dataupdate);
            }
        } else {
            // Handle File Upload
            $vargambar = $this->request->getFile('gambar');

            if ($vargambar == "") {
                $imageName = "";
            } else {
                // Validasi Input
                $validation = \Config\Services::validation();
                $validation->setRules([
                    'gambar' => 'uploaded[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/gif,image/png]|max_size[gambar,2048]|ext_in[gambar,png,jpg,gif,jpeg]',
                ]);
                if (!$validation->withRequest($this->request)->run()) {
                    return redirect()->back()->withInput()->with('errors', $validation->getErrors());
                }
                $imageName = $vargambar->getRandomName(); // Sanitasi nama file
                $vargambar->move(FCPATH . 'uploads', $imageName); // Simpan di public/  
            }

            $data = [
                'id_progkerja' => $this->request->getPost('id_progkerja'),
                'id_misi' => $this->request->getPost('id_misi'),
                'tahun' => date('Y'),
                'judul_kebijakan' => $this->request->getPost('judul_kebijakan'),
                'ket' => $this->request->getPost('ket'),
                'gambar' => $imageName,

                // 'indikator' => $this->request->getPost('indikator'),
            ];
            $simpan = $this->Arahkebijakan->save($data);
        }
        if ($simpan) {
            return redirect()->back()->withInput()->with('message', 'penyimpanan data berhasil');
        } else {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
    }
    public function programkerja($idmisi = null)
    {
        $data['titlepage'] = 'Program Kerja Gubernur dan Wakil Gubernur Lampung 2026-2030 per-Misi';
        $data['groupuser'] = 'adminprogkerja';

        if ($idmisi == null) {
            $req = $this->request;
            $this->ValidasiHash($req);
            // Contoh penggunaan parameter
            $receivedParams = $req->getGet();
            $id = $receivedParams['id'] ?? null;
            $misiid = $receivedParams['misiid'] ?? null;
            $action = $receivedParams['action'] ?? null;
            if ($action == 'list') {
                $data['datamisi'] = $this->misimodel->lisMisi($id);
                $data['dataprogkerja'] = $this->Programkerjamodel->listProgKerja($id);
                $data['groupmenu'] = 'adminprogkerja';
                // session()->set('idmisises', $id);
                return view('appprov/v_programkerja', $data);
            }
            if ($action == 'tambah') {
                $data['halaman'] = 'tambah';
                $data['datamisi'] = $this->misimodel->lisMisi($id);
                $data['dataprogkerja'] = $this->Programkerjamodel->listProgKerja($id);
                return view('appprov/form_programkerja', $data);
            }
            if ($action == 'edit') {
                //   $sesidmisi = session()->get('idmisises');

                $data['halaman'] = 'edit';
                $data['datamisi'] = $this->misimodel->lisMisi($misiid);
                $data['itemprogkerja'] = $this->Programkerjamodel->find($id);
                if (!$data['itemprogkerja']) {
                    throw new PageNotFoundException('Item Program Kerja not found');
                }
                // $data['dataprogkerja'] = $this->Programkerjamodel->listProgKerja($id);
                // echo $sesidmisi; //dd($data['datamisi']);
                return view('appprov/form_programkerja', $data);
            }

            if ($action == 'hapus') {
                $item = $this->Programkerjamodel->find($id);

                if ($item) {
                    // Hapus gambar dari server
                    //    if ($item['gambar'] && file_exists(FCPATH . 'uploads/' . $item['gambar'])) {
                    //        unlink(FCPATH . 'uploads/' . $item['gambar']);
                    //   }
                    $this->Programkerjamodel->delete($id);
                }

                return redirect()->back()->withInput()->with('message', 'hapus data berhasil');
                //  return request()->
            }
        } else {
            $data['datamisi'] = $this->misimodel->lisMisi($idmisi);
            $data['dataprogkerja'] = $this->Programkerjamodel->listProgKerja($idmisi);
            return view('appprov/v_programkerja', $data);
        }
    }
    public function simpanprogkerja()
    {
        // Validasi input
        if (!$this->validate($this->Programkerjamodel->validationRules, $this->Programkerjamodel->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        // Simpan Data ke Database
        $idprogkeg = $this->request->getPost('id');
        if ($idprogkeg) {
            // Handle File Upload
            $vargambar = $this->request->getFile('gambar');
            if ($vargambar == "") {
                $item = $this->Programkerjamodel->find($idprogkeg);
                $imageName = $item['gambar'];
            } else {
                // Validasi Input
                $validation = \Config\Services::validation();
                $validation->setRules([
                    'gambar' => 'uploaded[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/gif,image/png]|max_size[gambar,2048]|ext_in[gambar,png,jpg,gif,jpeg]',

                ]);
                if (!$validation->withRequest($this->request)->run()) {
                    return redirect()->back()->withInput()->with('errors', $validation->getErrors());
                }
                $imageName = $vargambar->getRandomName(); // Sanitasi nama file
                $vargambar->move(FCPATH . 'uploads', $imageName); // Simpan di public/
                // Hapus gambar lama jika ada
                $item = $this->Programkerjamodel->find($idprogkeg);
                if ($item['gambar'] && file_exists(FCPATH . 'uploads/' . $item['gambar'])) {
                    unlink(FCPATH . 'uploads/' . $item['gambar']);
                }
            }
            $dataupdate = [
                'id' => $this->request->getPost('id'),
                'id_misi' => $this->request->getPost('id_misi'),
                'tahun' => date('Y'),
                'judul_programkerja' => $this->request->getPost('judul_programkerja'),
                'indikator' => $this->request->getPost('indikator'),
                'gambar' => $imageName,
                'ket' => $this->request->getPost('ket'),
            ];

            $simpan = $this->Programkerjamodel->save($dataupdate);
        } else {
            // Handle File Upload
            $vargambar = $this->request->getFile('gambar');

            if ($vargambar == "") {
                $imageName = "";
            } else {
                // Validasi Input
                $validation = \Config\Services::validation();
                $validation->setRules([
                    'gambar' => 'uploaded[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/gif,image/png]|max_size[gambar,2048]|ext_in[gambar,png,jpg,gif,jpeg]',
                ]);
                if (!$validation->withRequest($this->request)->run()) {
                    return redirect()->back()->withInput()->with('errors', $validation->getErrors());
                }
                $imageName = $vargambar->getRandomName(); // Sanitasi nama file
                $vargambar->move(FCPATH . 'uploads', $imageName); // Simpan di public/  
            }
            // Does an insert()
            $data = [
                'id_misi' => $this->request->getPost('id_misi'),
                'tahun' => date('Y'),
                'judul_programkerja' => $this->request->getPost('judul_programkerja'),
                'indikator' => $this->request->getPost('indikator'),
                'gambar' => $imageName,
                'ket' => $this->request->getPost('ket'),
            ];
            $simpan = $this->Programkerjamodel->save($data);
        }
        if ($simpan) {
            return redirect()->back()->withInput()->with('message', 'penyimpanan data berhasil');
            //return redirect()->to('adbang/program_kerja/' . $data['id_misi'])->with('message', 'Item created successfully');
        } else {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
    }
}
