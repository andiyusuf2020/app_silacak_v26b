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
            return view('maintenis/soon');
            // session()->set('wilayah', 'lambar');
            // session()->set('namawilayah', 'Kabuapaten Lampung Barat');
            // return redirect()->to(base_url('lambar'));
        } elseif ($wilayah === 'lampungselatan') {
            return view('maintenis/soon');

            // session()->set('wilayah', 'lampungselatan');
            // session()->set('namawilayah', 'Kabupaten Lampung Selatan');
            // return redirect()->to(base_url('lampungselatan'));
        } elseif ($wilayah === 'lampungtimur') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'lampungtimur');
            // session()->set('namawilayah', 'Kabupaten Lampung Timur');
            // return redirect()->to(base_url('lampungtimur'));
        } elseif ($wilayah === 'lampungtengah') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'lampungtengah');
            // session()->set('namawilayah', 'Kabupaten Lampung Tengah');
            // return redirect()->to(base_url('lampungtengah'));
        } elseif ($wilayah === 'lampungutara') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'lampungutara');
            // session()->set('namawilayah', 'Kabupaten Lampung Utara');
            // return redirect()->to(base_url('lampungutara'));
        } elseif ($wilayah === 'mesuji') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'mesuji');
            // session()->set('namawilayah', 'Kabupaten Mesuji');
            // return redirect()->to(base_url('mesuji'));
        } elseif ($wilayah === 'pesawaran') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'pesawaran');
            // session()->set('namawilayah', 'Kabupaten Pesawaran');
            // return redirect()->to(base_url('pesawaran'));
        } elseif ($wilayah === 'pringsewu') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'pringsewu');
            // session()->set('namawilayah', 'Kabupaten Pringsewu');
            // return redirect()->to(base_url('pringsewu'));
        } elseif ($wilayah === 'tanggamus') {
            session()->set('wilayah', 'tanggamus');
            session()->set('namawilayah', 'Kabupaten Tanggamus');
            return redirect()->to(base_url('tanggamus'));
        } elseif ($wilayah === 'tulangbawang') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'tulangbawang');
            // session()->set('namawilayah', 'Kabupaten Tulang Bawang');
            // return redirect()->to(base_url('tulangbawang'));

        } elseif ($wilayah === 'tulangbawangbarat') {
            return view('maintenis/soon');
            // session()->set('wilayah', 'tulangbawangbarat');
            // session()->set('namawilayah', 'Kabupaten Tulang Bawang Barat');
            // return redirect()->to(base_url('tulangbawangbarat'));
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
    public function dilarang()
    {
        $data = [
            'title1' => 'Anda tidak memiliki hak akses untuk halaman ini',
            // 'title2' => 
        ];

        return view('hakakses', $data);
    }
}
