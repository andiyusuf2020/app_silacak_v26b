<?php

namespace App\Controllers\DekonTPController;

use CodeIgniter\Controller;
use App\Models\ApbnModel\danaapbnModel;


class Home extends Controller
{
    protected $danaapbnmodel;

    protected $helpers = ['form'];

    public function __construct()
    {
        $this->danaapbnmodel = new danaapbnModel();
    }
    public function index()
    {
        $data['danaapbn'] = $this->danaapbnmodel->DataAll(date('Y'));
        // echo dd($data['danaapbn']);
        return view('dekontp/dashboard', $data);
    }
}
