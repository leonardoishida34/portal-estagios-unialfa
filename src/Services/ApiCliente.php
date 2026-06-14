<?php

// ============================================
// SERVICE: ApiClient
// Responsável por TODA comunicação HTTP com
// a API Node.js. Nenhum outro arquivo faz
// requisições diretas — só este aqui.
//
// Fluxo:
// View → Controller → Service → ApiClient → API Node.js
// ============================================

require_once __DIR__ . '/../../config.php';

class ApiClient {

    // ── URL base da API Node.js ──
    // Definida no config.php como API_URL
    // Ex: 'http://localhost:3000/api'
    private string $baseUrl;

    public function __construct() {
        $this->baseUrl = API_URL;
    }

    // ── Método principal (privado) ──
    // Todos os outros métodos (get, post, put, delete)
    // chamam este aqui passando o método HTTP correto
    private function request(string $method, string $endpoint, array $body = []): array {
        $url = $this->baseUrl . $endpoint;

        // Inicializa o cURL — biblioteca do PHP para fazer requisições HTTP
        $ch = curl_init($url);

        // Configurações básicas do cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // retorna a resposta em vez de imprimir
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',  // avisa a API que estamos enviando JSON
            'Accept: application/json',        // avisa que queremos receber JSON
        ]);

        // Define o método HTTP (GET, POST, PUT, DELETE)
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        // Se tem dados para enviar (POST/PUT), converte para JSON
        if (!empty($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        // Executa a requisição e pega a resposta
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Se o cURL falhou (sem conexão, API offline etc.)
        if ($response === false) {
            return ['erro' => 'Não foi possível conectar com a API. Verifique se o servidor Node.js está rodando.'];
        }

        // Converte o JSON da resposta para array PHP
        $decoded = json_decode($response, true);

        // Se a resposta não é um JSON válido
        if ($decoded === null) {
            return ['erro' => 'Resposta inválida da API.'];
        }

        return $decoded;
    }

    // ── Métodos públicos ──
    // Atalhos para cada método HTTP

    // GET — buscar dados (listar vagas, buscar aluno, etc.)
    public function get(string $endpoint): array {
        return $this->request('GET', $endpoint);
    }

    // POST — criar dados (nova vaga, nova candidatura, login, etc.)
    public function post(string $endpoint, array $body): array {
        return $this->request('POST', $endpoint, $body);
    }

    // PUT — atualizar dados (editar vaga, atualizar status, etc.)
    public function put(string $endpoint, array $body): array {
        return $this->request('PUT', $endpoint, $body);
    }

    // DELETE — excluir dados (deletar vaga, etc.)
    public function delete(string $endpoint): array {
        return $this->request('DELETE', $endpoint);
    }
}
