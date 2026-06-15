<?php

// ============================================
// SERVICE: AlunoService
// Responsável pelas chamadas à API relacionadas
// aos alunos. Usa o ApiClient para fazer as
// requisições e converte os dados retornados
// em objetos Aluno.
// ============================================

require_once BASE_PATH . '/src/Services/ApiClient.php';
require_once BASE_PATH . '/src/Models/Aluno.php';
class AlunoService {

    // ── Dependência ──
    // Usa o ApiClient para fazer as requisições HTTP
    private ApiClient $client;

    public function __construct() {
        $this->client = new ApiClient();
    }

    // ── Buscar aluno pelo ID ──
    // Faz GET /alunos/{id} na API
    // Retorna objeto Aluno ou null se não encontrar
    public function buscarAluno(int $id): ?Aluno {
        $data = $this->client->get("/alunos/{$id}");

        if (isset($data['erro']) || !isset($data['id'])) return null;

        return new Aluno($data);
    }

    // ── Cadastrar novo aluno ──
    // Faz POST /alunos na API com os dados do formulário
    // Retorna a resposta da API (sucesso ou erro)
    public function cadastrarAluno(array $dados): array {
        return $this->client->post('/alunos', $dados);
    }

    // ── Login do aluno ──
    // Faz POST /auth/login na API com email e senha
    // Se autenticado, a API retorna os dados do aluno + token
    public function login(string $email, string $senha): array {
        return $this->client->post('/auth/login', [
            'email' => $email,
            'senha' => $senha,
            'tipo'  => 'aluno',
        ]);
    }

    // ── Atualizar dados do aluno ──
    // Faz PUT /alunos/{id} na API com os novos dados
    // Retorna a resposta da API (sucesso ou erro)
    public function atualizarAluno(int $id, array $dados): array {
        return $this->client->put("/alunos/{$id}", $dados);
    }
}
