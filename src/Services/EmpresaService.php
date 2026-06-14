<?php

// ============================================
// SERVICE: EmpresaService
// Responsável pelas chamadas à API relacionadas
// às empresas parceiras. Usa o ApiClient para
// fazer as requisições e converte os dados
// retornados em objetos Empresa.
// ============================================

require_once BASE_PATH . '/src/Services/ApiClient.php';
require_once BASE_PATH . '/src/Models/Empresa.php';

class EmpresaService {

    // ── Dependência ──
    // Usa o ApiClient para fazer as requisições HTTP
    private ApiClient $client;

    public function __construct() {
        $this->client = new ApiClient();
    }

    // ── Buscar empresa pelo ID ──
    // Faz GET /empresas/{id} na API
    // Retorna objeto Empresa ou null se não encontrar
    public function buscarEmpresa(int $id): ?Empresa {
        $data = $this->client->get("/empresas/{$id}");

        if (isset($data['erro']) || !isset($data['id'])) return null;

        return new Empresa($data);
    }

    // ── Cadastrar nova empresa ──
    // Faz POST /empresas na API com os dados do formulário
    // A empresa fica com status 'pendente' até ser aprovada
    // pelo Back Office Java da UniALFA
    public function cadastrarEmpresa(array $dados): array {
        return $this->client->post('/empresas', $dados);
    }

    // ── Login da empresa ──
    // Faz POST /auth/login na API com email e senha
    // Se autenticada, a API retorna os dados da empresa + token
    public function login(string $email, string $senha): array {
        return $this->client->post('/auth/login', [
            'email' => $email,
            'senha' => $senha,
            'tipo'  => 'empresa',
        ]);
    }

    // ── Atualizar perfil da empresa ──
    // Faz PUT /empresas/{id} na API com os novos dados
    // Usado na aba "Perfil da empresa" do painel
    public function atualizarEmpresa(int $id, array $dados): array {
        return $this->client->put("/empresas/{$id}", $dados);
    }
}
