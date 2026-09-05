<?php

namespace App\Controllers;

use App\Models\LokasiModel;

class Lokasi extends BaseController
{
    protected $lokasiModel;
    protected $helpers = ['form'];

    public function __construct()
    {
        $this->lokasiModel = new LokasiModel();
    }
    public function index()
    {
        $keyword = $this->request->getVar('keyword');
        $kategori = $this->request->getVar('kategori');
        // $lokasi = $lokasiModel->findAll();
        $data = [
            'title' => 'Daftar Lokasi',
            'kategoriList' => $this->lokasiModel->getKategori(),
            'groupuser' => '',
            'titlepage' => 'Data Lokasi'
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
        // echo dd($data);
        // return view('lokasi/index2', $data);
        return view('lokasi/index', $data);
    }

    public function detail($id)
    {
        $data = [
            'title' => 'Detail Lokasi',
            'lokasi' => $this->lokasiModel->getLokasi($id)
        ];

        if (empty($data['lokasi'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Lokasi dengan ID ' . $id . ' tidak ditemukan');
        }

        return view('lokasi/detail', $data);
    }

    public function tambah()
    {
        $data = [
            'title' => 'Tambah Lokasi Baru',
            'kategoriList' => $this->lokasiModel->getKategori()
        ];

        return view('lokasi/tambah', $data);
    }

    public function simpan()
    {
        $rules = [
            'nama' => 'required|max_length[100]',
            'latitude' => 'required|decimal',
            'longitude' => 'required|decimal',
            'alamat' => 'permit_empty|max_length[255]',
            'deskripsi' => 'permit_empty',
            'gambar' => 'uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
            'kategori' => 'permit_empty|max_length[50]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $fileGambar = $this->request->getFile('gambar');
        $namaGambar = $fileGambar->getRandomName();
        $fileGambar->move(FCPATH . 'uploads/lokasi/', $namaGambar); // Simpan di public/  

        // $fileGambar->move('uploads/lokasi', $namaGambar);
        $datakategori = $this->request->getVar('kategori');
        if (!$datakategori) {
            $kategori = $this->request->getVar('kategori_baru');
        } else {
            $kategori = $this->request->getVar('kategori');
        }

        $data = [
            'nama' => $this->request->getVar('nama'),
            'latitude' => $this->request->getVar('latitude'),
            'longitude' => $this->request->getVar('longitude'),
            'alamat' => $this->request->getVar('alamat'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'gambar' => $namaGambar,
            'kategori' => $kategori //$this->request->getVar('kategori')
        ];


        // echo dd($data);

        $this->lokasiModel->save($data);
        return redirect()->to('/lokasi')->with('message', 'Lokasi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $lokasi = $this->lokasiModel->find($id);

        if (!$lokasi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Lokasi tidak ditemukan');
        }

        // Pastikan koordinat valid
        if (!is_numeric($lokasi['latitude']) || !is_numeric($lokasi['longitude'])) {
            $lokasi['latitude'] = -6.175392; // Default latitude
            $lokasi['longitude'] = 106.827153; // Default longitude
        }
        $data = [
            'title' => 'Edit Lokasi',
            'lokasi' => $this->lokasiModel->getLokasi($id),
            'kategoriList' => $this->lokasiModel->getKategori()
        ];

        if (empty($data['lokasi'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Lokasi dengan ID ' . $id . ' tidak ditemukan');
        }

        return view('lokasi/edit', $data);
    }

    public function update($id)
    {

        $dataedit = [
            'id' => $id,
            'nama' => $this->request->getVar('nama'),
            'latitude' => $this->request->getVar('latitude'),
            'longitude' => $this->request->getVar('longitude'),
            'alamat' => $this->request->getVar('alamat'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'dokumentasi' => $this->request->getFile('dokumentasi'), //$namaGambar,
            'kategori' => $this->request->getVar('kategori')
        ];

        // echo dd($dataedit);
        $rules = [
            'nama' => 'required|max_length[100]',
            'latitude' => 'required|decimal',
            'longitude' => 'required|decimal',
            'alamat' => 'permit_empty|max_length[255]',
            'deskripsi' => 'permit_empty',
            // 'gambar' =>
            // 'mime_in[gambar,image/jpg,image/jpeg,image/png]'
            //     . '|is_image[gambar]', //  'link_dokumentasi' => 'valid_url_strict|regex_match[/drive\.google\.com/share/i]',
            'gambar' => 'is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
            'kategori' => 'permit_empty|max_length[50]'
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        $fileGambar = $this->request->getFile('gambar');
        if ($fileGambar->isValid() && !$fileGambar->hasMoved()) {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/lokasi', $namaGambar);

            // Hapus gambar lama jika ada
            $lokasi = $this->lokasiModel->find($id);
            if ($lokasi['gambar']) {
                unlink('uploads/lokasi/' . $lokasi['gambar']);
            }

            $data['gambar'] = $namaGambar;
        }

        $this->lokasiModel->save([
            'id' => $id,
            'nama' => $this->request->getVar('nama'),
            'latitude' => $this->request->getVar('latitude'),
            'longitude' => $this->request->getVar('longitude'),
            'alamat' => $this->request->getVar('alamat'),
            'deskripsi' => $this->request->getVar('deskripsi'),
            'gambar' => $namaGambar,
            'kategori' => $this->request->getVar('kategori')
        ]);

        return redirect()->to('/lokasi/detail/' . $id)->with('message', 'Lokasi berhasil diperbarui');
    }

    public function hapus($id)
    {
        $lokasi = $this->lokasiModel->find($id);

        if (!$lokasi) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Lokasi dengan ID ' . $id . ' tidak ditemukan');
        }

        $this->lokasiModel->delete($id);
        return redirect()->to('/lokasi')->with('message', 'Lokasi berhasil dihapus');
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
