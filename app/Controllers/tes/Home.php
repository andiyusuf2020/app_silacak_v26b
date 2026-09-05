<?php

namespace App\Controllers\tes;

use App\Controllers\BaseController;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Welcome Page',
            'showModal' => true
        ];

        return view('tes/homepage', $data);
    }

    public function closeModal()
    {
        // Optional: Handle session or cookie after modal closed
        session()->set('modal_closed', true);
        return $this->response->setJSON(['status' => 'success']);
    }
}
