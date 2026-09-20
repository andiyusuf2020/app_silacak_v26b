<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class HomeKabController extends BaseController
{
    public function index()
    {
        $req = $this->request;
        $receivedParams = $req->getGet();
        $wilayah = $receivedParams['wilayah'] ?? null;
        if (!$receivedParams) {
            return view('index');
        }
        $this->ValidasiHash($req);

        if ($wilayah == 'lambar') {
            session()->set('wilayah', 'lambar');
            return redirect()->to(base_url('lambar'));
        } elseif ($wilayah === 'lampungselatan') {
            session()->set('wilayah', 'lampungselatan');
            return redirect()->to(base_url('lampungselatan'));
        } elseif ($wilayah === 'lampungtimur') {
            session()->set('wilayah', 'lampungtimur');
            return redirect()->to(base_url('lampungtimur'));
        } elseif ($wilayah === 'lampungtengah') {
            session()->set('wilayah', 'lampungtengah');
            return redirect()->to(base_url('lampungtengah'));
        } elseif ($wilayah === 'lampungutara') {
            session()->set('wilayah', 'lampungutara');
            return redirect()->to(base_url('lampungutara'));
        } elseif ($wilayah === 'mesuji') {
            session()->set('wilayah', 'mesuji');
            return redirect()->to(base_url('mesuji'));
        } elseif ($wilayah === 'pesawaran') {
            session()->set('wilayah', 'pesawaran');
            return redirect()->to(base_url('pesawaran'));
        } elseif ($wilayah === 'pringsewu') {
            session()->set('wilayah', 'pringsewu');
            return redirect()->to(base_url('pringsewu'));
        } elseif ($wilayah === 'tanggamus') {
            session()->set('wilayah', 'tanggamus');
            return redirect()->to(base_url('tanggamus'));
        } elseif ($wilayah === 'tulangbawang') {
            session()->set('wilayah', 'tulangbawang');
            return redirect()->to(base_url('tulangbawang'));
        } elseif ($wilayah === 'tulangbawangbarat') {
            session()->set('wilayah', 'tulangbawangbarat');
            return redirect()->to(base_url('tulangbawangbarat'));
        } else {
            // Handle the case when the wilayah parameter is not recognized
            // You can choose to show an error page or redirect to a default page
            return view('index'); // Redirect to the index page as a fallback
        }
    }
    public function pilihakses()
    {
        $wilayah = session()->get('wilayah');
        if (!$wilayah) {
            return redirect()->to(base_url());
        }
        $data =
            [
                'wilayah' => $wilayah
            ];
        // echo dd($data);
        return view('Sipkabkota/pilihakses', $data);
    }
    public function simpantahun()
    {
        $tahun = $this->request->getPost('tahun');

        // simpan tahun di session
        session()->set('tahun', $tahun);
        return redirect()->back();
    }
}
