<?php

namespace App\Controllers;

use App\Models\UserXModel;

class Usersx extends BaseController
{
    protected $userModel;
    protected $pager;

    public function __construct()
    {
        $this->userModel = new UserXModel();
        $this->pager = \Config\Services::pager();
    }

    public function index()
    {
        // Get current page from URL segment
        $pager = $this->request->getVar('pager') ?? 1;

        // Set items per page
        $perPage = 3;

        // Calculate offset
        $offset = ($pager - 1) * $perPage;

        // Get data for current page
        $data['users'] = $this->userModel->getUsers($perPage, $offset);

        // Get total records
        $totalRecords = $this->userModel->countAllUsers();

        // Initialize pager
        // $data['pager'] = $this->userModel->pager = $this->pager->makeLinks($page, $perPage, $totalRecords);
        $data['pager'] = $this->userModel->pager = $this->pager->makeLinks($pager, $perPage, $totalRecords, 'bootstrap_pagination');

        // Pass data to view
        $data['title'] = 'Data Pengguna';
        $data['currentPage'] = $pager;
        $data['perPage'] = $perPage;
        $data['totalRecords'] = $totalRecords;

        return view('usersx_view', $data);
    }
}
