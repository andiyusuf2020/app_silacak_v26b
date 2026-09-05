<?php

namespace App\Controllers\Silacak;

use App\Controllers\BaseController;

class SuperadminController extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url', 'filesystem']);
    }

    public function index()
    {
        echo "Masuk halaman superadmin";
        // return view('silacak/home');
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
