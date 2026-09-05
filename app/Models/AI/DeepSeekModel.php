<?php

namespace App\Models\AI;

use CodeIgniter\Model;
use Config\Deepseek as DeepseekConfig;
use Config\Services;
use Exception;

class DeepSeekModel extends Model
{
    protected $config;
    protected $client;
    protected $maxRetries = 3;
    protected $retryDelay = 1000; // dalam milidetik
    protected $DeepSeekConfig;
    public function __construct()
    {
        $this->config = config('Deepseek');
        $this->client = Services::curlrequest();
        $this->DeepSeekConfig = new DeepseekConfig();
    }

    /**
     * Mengirim permintaan chat completion ke API DeepSeek
     *
     * @param array $messages Array pesan dalam format [['role' => 'user|assistant', 'content' => '...']]
     * @param string|null $model Model yang digunakan (null untuk default)
     * @param array|null $params Parameter tambahan (null untuk default)
     * @return array Response dari API
     * @throws Exception Jika terjadi error
     */
    public function chatCompletion(array $messages, ?string $model = null, ?array $params = null): array
    {
        // Validasi input
        if (empty($messages)) {
            throw new \InvalidArgumentException('Messages tidak boleh kosong');
        }

        // Siapkan payload
        $payload = [
            'model' => $model ?? $this->DeepSeekConfig->defaultModel,
            'messages' => $messages,
        ] + ($params ?? $this->DeepSeekConfig->defaultParams);

        $attempt = 0;
        $lastError = null;

        // Retry mechanism
        while ($attempt < $this->maxRetries) {
            try {
                $response = $this->client->post(
                    $this->DeepSeekConfig->apiUrl . '/chat/completions',
                    [
                        'headers' => $this->DeepSeekConfig->getHeaders(),
                        'json' => $payload,
                        'timeout' => 30,
                        'http_errors' => false,
                    ]
                );

                $statusCode = $response->getStatusCode();
                $body = json_decode($response->getBody(), true);

                // Handle success
                if ($statusCode >= 200 && $statusCode < 300) {
                    if (isset($body['choices'][0]['message']['content'])) {
                        return $body;
                    }
                    throw new Exception('Format response tidak valid');
                }

                // Handle rate limiting (429) atau server errors (5xx)
                if ($statusCode === 429 || $statusCode >= 500) {
                    $attempt++;
                    if ($attempt < $this->maxRetries) {
                        usleep($this->retryDelay * 1000);
                        continue;
                    }
                }

                // Handle client errors (4xx)
                $errorMsg = $body['error']['message'] ?? 'Unknown error';
                throw new Exception("API Error ($statusCode): $errorMsg");
            } catch (Exception $e) {
                $lastError = $e;
                $attempt++;
                if ($attempt < $this->maxRetries) {
                    usleep($this->retryDelay * 1000);
                    continue;
                }
                throw $e;
            }
        }

        throw $lastError ?? new Exception('Unknown error after retries');
    }

    /**
     * Format pesan sederhana untuk prompt tunggal
     *
     * @param string $prompt Pertanyaan/prompt dari user
     * @return array Array pesan yang diformat
     */
    public function formatSimpleMessage(string $prompt): array
    {
        return [
            ['role' => 'user', 'content' => $prompt]
        ];
    }

    /**
     * Format pesan dengan konteks/riwayat
     *
     * @param string $prompt Pertanyaan baru
     * @param array $history Riwayat percakapan sebelumnya
     * @return array Array pesan yang diformat
     */
    public function formatMessageWithHistory(string $prompt, array $history): array
    {
        $messages = [];

        // Convert history to message format
        foreach ($history as $item) {
            $messages[] = ['role' => 'user', 'content' => $item['question']];
            $messages[] = ['role' => 'assistant', 'content' => $item['answer']];
        }

        // Add new prompt
        $messages[] = ['role' => 'user', 'content' => $prompt];

        return $messages;
    }
}
