<?php

namespace App\Controllers\UserController;

use App\Controllers\BaseController;

use \Myth\Auth\Authorization\GroupModel;
use App\Models\UserModel;
use \Myth\Auth\Password;
use App\Models\UserModel\TaUserModel;
// use App\Models\adbangmodel\ProgKerjaModel as programkeramodel;
use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
use App\Models\DataApbdModel\RealApbdModel;

class UserSitapisController extends BaseController
{

    protected $tausermodel;
    // protected $Programkerjamodel;
    protected $keyword;
    protected $subkegmodel;

    protected $realapbdmodel;

    public function __construct()
    {
        helper(['form']);
        $this->tausermodel = new TaUserModel();
        // $this->Programkerjamodel = new programkeramodel();
        $this->subkegmodel = new SubKegModel();
        $this->realapbdmodel = new RealApbdModel();
    }

    public function index()
    {
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }

            $useraktif =  $user->email;
        }
        $keyword = $this->request->getVar('keyword');
        //$keyword = 'and';

        if ($keyword) {
            $datauser = $this->tausermodel->searchRealTime($keyword);
            return $this->response->setJSON($datauser);
            // echo dd($datacari);

            // $datausera = $this->tausermodel->searchRealTime($keyword);
        } else {
            $datausera[] = null;
            $datauser = $this->tausermodel->listuser();
        }
        // $dataprogram = $this->Programkerjamodel->ProgKerjaAll();

        // $userdata = $datauser->paginate(10, 'users');

        $data = [
            'titlepage' => 'Selamat datang ' . $useraktif . ' di Halaman Manajemen User',
            // 'menu' => 'adminlrfk',
            'groupmenu' => $namagroup,
            'listuser' =>  $datauser,
            'usercari' => $datausera,
            'useraktif' => $useraktif,
            'keyword' => $keyword,
            'groupuser' => $namagroup,
        ];
        // echo dd($data['listuser']);
        $groupModel = new GroupModel();

        foreach ($data['listuser'] as $row) {
            $dataRow['group'] = $groupModel->getGroupsForUser($row['id']);
            $dataRow['row'] = $row;
            $data['row' . $row['id']] = view('user/row', $dataRow);
        }

        //     $data['groups'] = $groupModel->findAll();
        //     $data['title'] = 'Users';
        // echo dd($datauser);

        // echo dd($dataRow['group']);
        // echo dd($data['listuser']);
        return view('user/user', $data);
    }
    public function user()
    {
        $req = $this->request;

        $this->ValidasiHash($req);
        // Contoh penggunaan parameter

        // $url = urldecode($req->getGet('url'));
        $receivedParams = $req->getGet();
        $url = base64_decode($receivedParams['url'] ?? '');
        $action = $receivedParams['action'] ?? null;
        $idUser = $receivedParams['idUser'] ?? null;
        $Status = $receivedParams['Status'] ?? null;
        $data['tahunaktif'] = session()->get('tahun');
        $data['bulanaktif'] = session()->get('bulan');
        $user = user();
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }
        }
        if ($action == 'akftivasi') {
            if ($Status == 0) {
                $aktif = 1;
            }
            if ($Status == 1) {
                $aktif = 0;
            }
            $data = [
                'id' => $idUser,
                'activate_hash' => null,
                'active' => $aktif,
            ];

            $this->tausermodel->update($idUser, $data);
            return redirect()->to(base_url('lrfkadmin/usersitapis'));
        }
        if ($action == 'pass') {

            $data = [
                'groupuser' => $namagroup,
                'groupmenu' => $namagroup,
                'datauser' => $this->tausermodel->listuser($user->id),
                'titlepage' => 'Halaman Aktivasi User SiTAPIS Perangkat Daerah',
                'id' => $idUser,
                'title' => 'Update Password',
                'titlepage' => 'Halaman UBAH PASSWORD User SiTAPIS Perangkat Daerah',
            ];
            $data['listuser'] = $this->tausermodel->listuser($idUser);
            // echo dd($data['listuser']);
            return view('user/set_pasword', $data);
        }
        if ($action == 'ubahgroup') {
            $groupModel = new GroupModel();
            $data['groups'] = $groupModel->findAll();
            $data = [
                'groups' => $groupModel->findAll(),
                'groupuser' => $namagroup,
                'groupmenu' => $namagroup,

                'titlepage' => 'Halaman UBAH RULE GROUP User SiTAPIS Perangkat Daerah',
                'menu' => 'adminlrfk',
                'listuser' => $this->tausermodel->listuser($idUser),
            ];
            return view('user/set_group', $data);
        }
        if ($action == 'ubahperangkatdaerah') {
            $tahunaktif = session()->get('tahun');
            $bulanaktif = session()->get('bulan');

            $data = [
                // 'listopd' => $this->subkegmodel->listopd(),
                'listopd' => $this->realapbdmodel->listopd(),
                'groupuser' => $namagroup,
                'groupmenu' => $namagroup,
                'titlepage' => 'Halaman Ubah Perangkat Daerah User SiTAPIS Perangkat Daerah Provinsi Lampung',
                'menu' => 'adminlrfk',
                'listuser' => $this->tausermodel->listuser($idUser),
            ];
            // echo dd($data['listopd']);
            // echo dd($tahunaktif . '//' . $bulanaktif);
            return view('user/set_opdprov2', $data);
        }
    }
    public function simpanubahopd()
    {
        $id = $this->request->getPost('id');
        // $listopd = $this->subkegmodel->listopd($this->request->getPost('nm_opd'));
        $listopd = $this->realapbdmodel->dataopd($this->request->getPost('nm_opd'));
        // echo dd($listopd);
        $data = [
            'kd_skpd' => $listopd['KODE_SKPD'],
            'kd_sub_unit' => $listopd['KODE_UNIT_SKPD'],
            'sub_unit' => $this->request->getPost('nm_opd'),
        ];
        // echo dd($data);
        $this->tausermodel->update($id, $data);
        return redirect()->to(base_url('lrfkadmin/usersitapis'));
    }
    public function changeGroup()
    {
        $userId = $this->request->getPost('id');
        $groupId = $this->request->getPost('nm_group');

        $groupModel = new GroupModel();
        $groupModel->removeUserFromAllGroups(intval($userId));

        $groupModel->addUserToGroup(intval($userId), intval($groupId));

        //echo $userId . ":" . $groupId;

        return redirect()->to(base_url('lrfkadmin/usersitapis'));
    }
    public function changePassword($id = null)
    {
        if ($id == null) {
            return redirect()->to(base_url('kabkotaviews/vdetail/user'));
        } else {
            $data = [
                'id' => $id,
                'title' => 'Update Password',
            ];
            //   return view('users/set_password', $data);
            return view('kabkotaviews/vdetail/set_password', $data);
        }
    }
    public function setPassword()
    {
        if (logged_in()) {
            $user = user();
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
            foreach ($groupuser as $row) {
                $namagroup = $row['name'];
            }

            $useraktif =  $user->email;
        }
        $id = $this->request->getVar('id');
        $rules = [
            'password'     => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];
        if (!$this->validate($rules)) {
            $data = [
                'titlepage' => 'Halaman Aktivasi User SiTAPIS Perangkat Daerah',
                // 'menu' => 'adminlrfk',
                'groupuser' => $namagroup,
                'groupmenu' => $namagroup,
                'datauser' => $this->tausermodel->listuser($user->id),
                'id' => $id,
                'validation' => $this->validator,
            ];
            return view('user/set_pasword', $data);
        } else {
            $data = [];
            $userModel = new UserModel();
            $data = [
                'titlepage' => 'Halaman Aktivasi User SiTAPIS Perangkat Daerah',
                // 'menu' => 'adminlrfk',
                'groupuser' => $namagroup,
                'groupmenu' => $namagroup,
                'datauser' => $this->tausermodel->listuser($user->id),
                'password_hash' => Password::hash($this->request->getVar('password')),
                'reset_hash' => null,
                'reset_at' => null,
                'reset_expires' => null,
            ];
            $userModel->update($this->request->getVar('id'), $data);
            return redirect()->to(base_url('lrfkadmin/usersitapis'));
        }
    }
    public function profile($id = Null)
    {
        $admin = session()->get('user');
        $data['kab'] = session()->get('nmkab');
        $data['user'] = $admin;
        $data['datauser'] = $this->tausermodel->getDataUser($admin)->getResultArray();
        $data['profileuser'] = $this->tausermodel->getDataUser($admin)->getRowArray();
        // return view('kabkotaviews/templatesuperadmin/vdetail/profile_user', $data);
        return view('tepra_view/_content/profile_user', $data);
    }
    public function simpanprofile($id = Null)
    {
        $admin = session()->get('user');
        $data['kab'] = session()->get('nmkab');
        $data['user'] = $admin;
        $data['datauser'] = $this->tausermodel->getDataUser($admin)->getResultArray();
        $validation =  \Config\Services::validation();
        $nama = $this->request->getPost('nama');
        $nip = $this->request->getPost('nip');
        $jabatan = $this->request->getPost('jabatan');
        $dataprofile = array(
            'nama' => $nama,
            'nip' => $nip,
            'jabatan' => $jabatan
        );
        if ($validation->run($dataprofile, 'dataprofile') == FALSE) {
            session()->setFlashdata('inputs', $this->request->getPost());
            session()->setFlashdata('errors', $validation->getErrors());
            $url = session()->get('urla');
            //  return redirect()->to(base_url($url));
            if ($url == "executive-summary-aku") {
                return redirect()->to(base_url($url . '/' . $id));
            } else {
                return redirect()->to(base_url($url . '/profile/' . $id));
            }
        } else {
            $id = $this->request->getPost('id');
            $this->tausermodel->UpdateUserProfile($id, $dataprofile);
            session()->setFlashdata('success', 'Perubahan Data Perangkat Daerah,Berhasil');
            $url = session()->get('urla');
            return redirect()->to(base_url($url));
        }
    }
}
