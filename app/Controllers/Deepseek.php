<?php

namespace App\Controllers;

use App\Models\DeepseekModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Deepseek extends BaseController
{
    protected $model;
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->model = new DeepseekModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();

        helper(['form', 'text', 'url']);
    }

    /**
     * Halaman utama - form pertanyaan
     */
    public function index()
    {
        // Ambil riwayat dari session jika ada
        $data['history'] = $this->session->get('deepseek_history') ?? [];

        return view('deepseek/form', $data);
    }

    /**
     * Proses pertanyaan ke API DeepSeek
     */
    public function ask()
    {
        // Validasi input
        $rules = [
            'question' => [
                'rules' => 'required|min_length[3]|max_length[2000]',
                'errors' => [
                    'required' => 'Pertanyaan harus diisi',
                    'min_length' => 'Pertanyaan minimal {param} karakter',
                    'max_length' => 'Pertanyaan maksimal {param} karakter'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validation->getErrors());
        }

        $question = $this->request->getPost('question', FILTER_SANITIZE_STRING);

        try {
            // Dapatkan riwayat dari session
            $history = $this->session->get('deepseek_history') ?? [];

            // Format messages - bisa dengan atau tanpa history
            $messages = empty($history)
                ? $this->model->formatSimpleMessage($question)
                : $this->model->formatMessageWithHistory($question, $history);

            // Kirim ke API
            $response = $this->model->chatCompletion($messages);

            // Proses response
            if (!isset($response['choices'][0]['message']['content'])) {
                throw new \RuntimeException('Format response tidak valid');
            }

            $answer = $response['choices'][0]['message']['content'];

            // Simpan ke riwayat session (maksimal 5 item)
            $newHistoryItem = [
                'question' => $question,
                'answer' => $answer,
                'timestamp' => time()
            ];

            array_unshift($history, $newHistoryItem);
            $history = array_slice($history, 0, 5);
            $this->session->set('deepseek_history', $history);

            // Tampilkan hasil
            return view('deepseek/result', [
                'question' => $question,
                'answer' => $answer,
                'history' => $history
            ]);
        } catch (\Exception $e) {
            log_message('error', 'DeepSeek API Error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus riwayat percakapan
     */
    public function clearHistory()
    {
        $this->session->remove('deepseek_history');
        return redirect()->to('/deepseek')->with('message', 'Riwayat telah dihapus');
    }

    /**
     * API Endpoint untuk integrasi AJAX
     */
    public function apiAsk()
    {
        // Hanya terima request AJAX
        if (!$this->request->isAJAX()) {
            throw PageNotFoundException::forPageNotFound();
        }

        $question = $this->request->getPost('question', FILTER_SANITIZE_STRING);

        if (empty($question)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Pertanyaan tidak boleh kosong'
            ])->setStatusCode(400);
        }

        try {
            $messages = $this->model->formatSimpleMessage($question);
            $response = $this->model->chatCompletion($messages);

            $answer = $response['choices'][0]['message']['content'] ?? 'Tidak dapat memproses jawaban';

            return $this->response->setJSON([
                'status' => 'success',
                'answer' => $answer
            ]);
        } catch (\Exception $e) {
            log_message('error', 'DeepSeek API Error: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ])->setStatusCode(500);
        }
    }
}
