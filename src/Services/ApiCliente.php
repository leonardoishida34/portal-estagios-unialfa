<?php

// ============================================
// SERVICE: ApiCliente
// Responsável por TODA comunicação HTTP com
// a API Node.js. Nenhum outro arquivo faz
// requisições diretas — só este aqui.
//
// Métodos disponíveis:
// get()   → GET
// post()  → POST
// put()   → PUT
// patch() → PATCH  ← usado em candidaturas/status
// delete()→ DELETE
// ============================================

require_once BASE_PATH . '/config.php';

class ApiCliente {

    private string $baseUrl;

    public function __construct() {
        // URL base da API Node.js definida no config.php
        // Ex: 'http://localhost:3000/api'
        $this->baseUrl = API_URL;
    }

    // ── Método principal (privado) ──
    // Todos os outros métodos chamam este aqui
    private function request(string $method, string $endpoint, array $body = []): array {
        $url = $this->baseUrl . $endpoint;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
        ]);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        // Envia body em POST, PUT e PATCH
        if (!empty($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Erro de conexão — API offline ou URL errada
        if ($response === false) {
            return ['erro' => 'Não foi possível conectar com a API. Verifique se o servidor Node.js está rodando.'];
        }

        $decoded = json_decode($response, true);

        if ($decoded === null) {
            return ['erro' => 'Resposta inválida da API.'];
        }

        return $decoded;
    }

    // GET — buscar dados
    public function get(string $endpoint): array {
        return $this->request('GET', $endpoint);
    }

    // POST — criar dados
    public function post(string $endpoint, array $body): array {
        return $this->request('POST', $endpoint, $body);
    }

    // PUT — atualizar dados completos
    public function put(string $endpoint, array $body): array {
        return $this->request('PUT', $endpoint, $body);
    }

    // PATCH — atualizar dados parciais
    // Usado em: PATCH /candidaturas/:id/status
    public function patch(string $endpoint, array $body): array {
        return $this->request('PATCH', $endpoint, $body);
    }

    // DELETE — excluir dados
    public function delete(string $endpoint): array {
        return $this->request('DELETE', $endpoint);
    }
}
