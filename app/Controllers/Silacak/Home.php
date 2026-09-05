<?php

namespace App\Controllers\Silacak;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url', 'filesystem']);
    }

    public function index()
    {
        return view('silacak/home');
    }
    public function dashboard()
    {
        return view('silacak/dashboard');
    }
}
