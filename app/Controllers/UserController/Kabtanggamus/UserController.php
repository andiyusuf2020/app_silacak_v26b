<?php

namespace App\Controllers\UserController\Kabtanggamus;

use App\Controllers\BaseController;
use App\Libraries\CryptoUrl;
use \Myth\Auth\Authorization\GroupModel;
use App\Models\UserModel;
use \Myth\Auth\Password;
use App\Models\UserModel\TaUserModel;
use App\Models\UserModel\GroupUserModel;
// use App\Models\adbangmodel\ProgKerjaModel as programkeramodel;
// use App\Models\LrfkProvModel\SubKegModel; // as lrfkModel;
use App\Models\KabtanggamusModel\TglApbdModel as TglApbdModelTanggamus;
use App\Models\KabtanggamusModel\RealApbdModel as RealApbdModelTanggamus;

use App\Models\KablampuraModel\TglApbdModel as TglApbdModelLampura;
use App\Models\KablampuraModel\RealApbdModel as RealApbdModelLampuran;

use App\Models\KablamselModel\TglApbdModel as TglApbdModelLamsel;
use App\Models\KablamselModel\RealApbdModel as RealApbdModelLamsel;

use App\Models\KabtbbModel\TglApbdModel as TglApbdModelTbb;
use App\Models\KabtbbModel\RealApbdModel as RealApbdModelTbb;


class UserController extends BaseController
{

    protected $tausermodel;
    // protected $Programkerjamodel;
    protected $keyword;
    protected $tglapbd;
    protected $groupuser;
    protected $realapbdmodel;

    public function __construct()
    {
        helper(['form']);
        $this->tausermodel = new TaUserModel();
        // $this->Programkerjamodel = new programkeramodel();

        $this->groupuser = new GroupUserModel();
        $wilayah = session()->get('wilayah');

        if ($wilayah == 'tanggamus') {
            $this->tglapbd = new TglApbdModelTanggamus();
            $this->realapbdmodel = new RealApbdModelTanggamus();
        }
        if ($wilayah == 'lampungutara') {
            $this->tglapbd = new TglApbdModelLampura();
            $this->realapbdmodel = new RealApbdModelLampuran();
        }
        if ($wilayah == 'lampungselatan') {
            $this->tglapbd = new TglApbdModelLamsel();
            $this->realapbdmodel = new RealApbdModelLamsel();
        }
        if ($wilayah == 'tulangbawangbarat') {
            $this->tglapbd = new TglApbdModelTbb();
            $this->realapbdmodel = new RealApbdModelTbb();
        }
    }

    public function index()
    {
        if (logged_in()) {
            $user = user();
            $useraktif =  $user->email;
            $groupModel = new GroupModel();
            $groupuser = $groupModel->getGroupsForUser($user->id);
        }
        $idUser = $user->id;

        if (session()->get('groupuser') == 'Adminadbang') {
            $datauser = $this->tausermodel->listuser2(session()->get('wilayah'), null);
            $tgl = $this->tglapbd->tgldataaktif();
        }
        if (session()->get('groupuser') == 'skpdkab') {
            $datauser = $this->tausermodel->listuser2(session()->get('wilayah'), $idUser);
            $tgl = $this->tglapbd->tgldataaktif();
        }
        if ($groupuser[0]['name'] == 'superadmin') {

            $datauser = $this->tausermodel->listuser2(null, null);
            session()->set('wilayah', 'provinsi');
            session()->set('groupmenu', 'menusuperadmin');
            session()->set('groupuser', 'superadmin');
            $tgl = date('d-m-Y');
        }
        // echo dd($datauser);
        $data = [
            'titlepage' => 'Selamat datang ' . $useraktif . ' di Halaman Manajemen User Sistem Pelaporan SiTAPIS',
            'wilayah' =>  session()->get('wilayah'),
            'groupmenu' => session()->get('groupmenu'),
            'groupuser' => session()->get('groupuser'),
            'listuser2' => $datauser, //$this->tausermodel->listuser2(session()->get('wilayah'), null),
            'tglapbdaktif' => $tgl
        ];
        // echo dd($data);
        // Hanya enkripsi 'id' dan 'name'
        $users = array_map(function ($user) {
            $user['update_token_groupuser'] = CryptoUrl::encrypt([
                'id'   => $user['id'],
                'name' => $user['username'],

            ]);
            $user['update_token_aktif'] = CryptoUrl::encrypt([
                'id'   => $user['id'],
                'active' => $user['active']
            ]);
            $user['update_token_hapus'] = CryptoUrl::encrypt([
                'idhapus'   => $user['id'],
            ]);

            $user['update_token_opduser'] = CryptoUrl::encrypt([
                'id'   => $user['id'],
                'hal' => 'ubahopd',
            ]);
            $user['update_token_passuser'] = CryptoUrl::encrypt([
                'id'   => $user['id'],
                'hal' => 'ubahpass',
            ]);
            $user['groupuser'] =  session()->get('groupuser');
            return $user;
        }, $data['listuser2']);
        // echo dd($users);
        $datauserx = json_encode($users);
        $data['datajson'] = preg_replace('/"([^"]+)"\s*:/', '$1:', $datauserx);
        // echo dd($data['groupuser'] . '//' . $data['wilayah'] . '//' . $data['groupmenu']);
        // return view('user/listusersuperadmin', $data);
        // echo dd($data['datajson']);
        // if ($data['groupuser'] == 'superadmin') {
        //     // echo dd('masuk');
        //     return redirect()->to(base_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/daftaruser'));
        // } else {
        //     return view('KabtanggamusViews/Adminadbang/listusersuperadmin', $data);
        // }
        return view('KabtanggamusViews/Adminadbang/listusersuperadmin', $data);
    }
    public function updateuser()
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
        $userId   = $payload['id'] ?? null;
        $active = $payload['active'] ?? null;
        $idHapus = $payload['idhapus'] ?? null;
        $sub_unit = $payload['sub_unit'] ?? null;
        $hal = $payload['hal'] ?? null;
        $groupModel = new GroupModel();
        //        $data['groups'] = $groupModel->findAll();
        if (session()->get('groupuser') == 'Adminadbang') {
            $tgl = $this->tglapbd->tgldataaktif();
        }
        if (session()->get('groupuser') == 'skpdkab') {
            $tgl = $this->tglapbd->tgldataaktif();
        }
        if (session()->get('groupuser') == 'superadmin') {

            // $datauser = $this->tausermodel->listuser2(null, null);
            // session()->set('wilayah', 'provinsi');
            // session()->set('groupmenu', 'menusuperadmin');
            // session()->set('groupuser', 'superadmin');
            $tgl = date('d-m-Y');
        }
        $data = [
            'groups' => $this->groupuser->listgroupkabkota(), //$groupModel->findAll(), //whereNotIn('name', 'superadmin')->get()->getResultArray(), //findAll(),
            'groupuser' => session()->get('groupuser'),
            'groupmenu' => session()->get('groupuser'),
            'wilayah' =>  session()->get('wilayah'),
            'titlepage' => 'Halaman UBAH RULE GROUP User SiTAPIS Perangkat Daerah',
            'listuser' => $this->tausermodel->listuser($userId),
            'tglapbdaktif' => $tgl, //$this->tglapbd->tgldataaktif() ?? date('d-m-Y'),
        ];
        if ($hal == 'ubahopd') {
            $data['titlepage'] = 'Halaman Ubah Perangkat Daerah User SiTAPIS-KAB<br>' . session()->get('namawilayah');

            return view('user/set_opd', $data);
        }
        if ($hal == 'ubahpass') {
            $data['titlepage'] = 'Halaman Ubah Password User SiTAPIS-KAB<br>' . session()->get('namawilayah');

            return view('user/set_pasword', $data);
        }
        // echo dd($data['groups']);
        if ($idHapus) {
            $this->tausermodel->delete($idHapus);
            if (session()->get('groupuser') == 'superadmin') {
                return redirect()->to(base_url('' . $data['wilayah'] . '/daftaruser'));
            } else {
                return redirect()->to(base_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/daftaruser'));
            }
        }
        if ($active == null) {
            // echo dd($data['groupuser']);
            return view('user/set_group', $data);
        } else {
            if ($active == 0) {
                $aktif = 1;
            }
            if ($active == 1) {
                $aktif = 0;
            }
            $dataX = [
                'id' => $userId,
                'activate_hash' => null,
                'active' => $aktif,
            ];
            // echo dd($dataX);
            $this->tausermodel->update($userId, $dataX);
            if (session()->get('groupuser') == 'superadmin') {
                return redirect()->to(base_url('' . $data['wilayah'] . '/daftaruser'));
            } else {
                return redirect()->to(base_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/daftaruser'));
            }
        }
    }
    public function simpanubahopd()
    {
        $id = $this->request->getPost('id');
        $subunit = $this->request->getPost('sub_unit');
        // $listopd = $this->subkegmodel->listopd($this->request->getPost('nm_opd'));
        $listopd = $this->realapbdmodel->dataopd($this->request->getPost('sub_unit'));
        $data = [
            // 'groups' => $this->groupuser->listgroupkabkota(), //$groupModel->findAll(), //whereNotIn('name', 'superadmin')->get()->getResultArray(), //findAll(),
            'groupuser' => session()->get('groupuser'),
            'groupmenu' => session()->get('groupuser'),
            'wilayah' =>  session()->get('wilayah'),
            'titlepage' => 'Halaman Ubah OPD User SiTAPIS-KAB<br>' . session()->get('namawilayah'),
        ];
        // echo dd($subunit . '/' . $id);
        if ($listopd == null) {
            if (session()->get('groupuser') == 'superadmin') {
                return redirect()->to(base_url('' . $data['wilayah'] . '/daftaruser'));
            } else {
                return redirect()->to(base_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/daftaruser'));
            }
        } else {
            $dataupdate = [
                'kd_skpd' => $listopd['KODE_SKPD'],
                'kd_sub_unit' => $listopd['KODE_UNIT_SKPD'],
                'sub_unit' => $this->request->getPost('sub_unit'),
            ];
            // $data['kd_skpd'] = $listopd['KODE_SKPD'];
            // $data['kd_sub_unit'] = $listopd['KODE_UNIT_SKPD '];
            // $data['sub_unit'] = $this->request->getPost('sub_unit');

            $this->tausermodel->update($id, $dataupdate);
            if (session()->get('groupuser') == 'superadmin') {
                return redirect()->to(base_url('' . $data['wilayah'] . '/daftaruser'));
            } else {
                return redirect()->to(base_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/daftaruser'));
            }
        }
    }
    public function changeGroup()
    {
        $userId = $this->request->getPost('id');
        $groupId = $this->request->getPost('nm_group');

        $groupModel = new GroupModel();
        $groupModel->removeUserFromAllGroups(intval($userId));

        $groupModel->addUserToGroup(intval($userId), intval($groupId));

        $data = [
            'groups' => $groupModel->findAll(),
            'groupuser' => session()->get('groupuser'),
            'groupmenu' => session()->get('groupuser'),
            'wilayah' =>  session()->get('wilayah'),
            'titlepage' => 'Halaman UBAH RULE GROUP User SiTAPIS Perangkat Daerah',
            'listuser' => $this->tausermodel->listuser($userId),
        ];
        echo dd($data['groupuser']);
        if (session()->get('groupuser') == 'superadmin') {
            return redirect()->to(base_url('' . $data['wilayah'] . '/daftaruser'));
        } else {
            return redirect()->to(base_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/daftaruser'));
        }
    }
    public function setPassword()
    {
        $id = $this->request->getVar('id');
        $rules = [
            'password'     => 'required|strong_password',
            'pass_confirm' => 'required|matches[password]',
        ];
        if (!$this->validate($rules)) {
            $data = [
                'groupuser' => session()->get('groupuser'),
                'groupmenu' => session()->get('groupuser'),
                'wilayah' =>  session()->get('wilayah'),
                'titlepage' => 'Halaman Ubah Password User SiTAPIS-KAB<br>' . session()->get('namawilayah'),
                'id' => $id,
                'listuser' => $this->tausermodel->listuser($id),

                'validation' => $this->validator,
                'tglapbdaktif' => $this->tglapbd->tgldataaktif(),
            ];
            return view('user/set_pasword', $data);
        } else {
            if (logged_in()) {
                $user = user();
                $useraktif =  $user->email;
            }

            $data = [
                'titlepage' => 'Selamat datang ' . $useraktif . ' di Halaman Manajemen User Sistem Pelaporan SiTAPIS',
                'wilayah' =>  session()->get('wilayah'),
                'groupmenu' => session()->get('groupmenu'),
                'groupuser' => session()->get('groupuser'),
                'listuser2' => $this->tausermodel->listuser2(session()->get('wilayah'), null),
            ];
            $userModel = new UserModel();
            $dataupdate = [
                'password_hash' => Password::hash($this->request->getVar('password')),
                'reset_hash' => null,
                'reset_at' => null,
                'reset_expires' => null,
            ];
            $userModel->update($this->request->getVar('id'), $dataupdate);
            if (session()->get('groupuser') == 'superadmin') {
                return redirect()->to(base_url('' . $data['wilayah'] . '/daftaruser'));
            } else {
                return redirect()->to(base_url('' . $data['wilayah'] . '/' . $data['groupuser'] . '/daftaruser'));
            }
        }
    }
}
