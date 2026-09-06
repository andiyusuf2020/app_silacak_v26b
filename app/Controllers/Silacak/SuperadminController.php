<?php

namespace App\Controllers\Silacak;

use App\Controllers\BaseController;
use \Myth\Auth\Authorization\GroupModel;

class SuperadminController extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url', 'filesystem']);
    }

    public function index()
    {
        // if (logged_in()) {
        $user = user();
        $groupModel = new GroupModel();
        $groupuser = $groupModel->getGroupsForUser($user->id);
        foreach ($groupuser as $row) {
            $namagroup = $row['name'];
        }
        // }
        $data['titlepage'] = 'Selamat Data di halaman Superadmin - SILACAK Kabupaten Tulang Bawang Barat';
        $data['groupuser'] = $namagroup;
        $data['groupmenu'] = $namagroup;
        $tahun = session()->get('tahun');
        $tgldataaktif = session()->get('tglaktif');
        // if ($data['groupuser'] == 'user') {
        //     return redirect()->to(base_url('user'));
        // }

        if (!$tahun) {
            session()->set('groupuser', $namagroup);
            session()->set('groupmenu', $namagroup);
            return view('pilihtahun', $data);
        }
        // echo dd($data['groupmenu']);
        // echo dd($tahun . '-'  . $tgldataaktif);
        return view('silacak/superadmin/apexchart', $data);
    }
    public function dashboard()
    {
        return view('silacak/dashboard');
    }
    public function pilihakses()
    {
        return view('silacak/pilihakses');
    }
}
