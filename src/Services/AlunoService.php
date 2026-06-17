<?php

// ============================================
// SERVICE: AlunoService
// Consome a API Node.js — rotas de alunos:
//
// GET    /alunos          → listar todos
// GET    /alunos/:id      → buscar um
// POST   /alunos          → criar
// PUT    /alunos/:id      → atualizar
// DELETE /alunos/:id      → remover
// ============================================

require_once BASE_PATH . '/src/Services/ApiCliente.php';
require_once BASE_PATH . '/src/Models/Aluno.php';

class AlunoService {

    private ApiCliente $client;

    public function __construct() {
        $this->client = new ApiCliente();
    }

    // GET /alunos/:id
    // Retorna objeto Aluno ou null
    public function buscarAluno(int $id): ?Aluno {
        $data = $this->client->get("/alunos/{$id}");

        if (!isset($data['id'])) return null;

        return new Aluno($data);
    }

    // POST /alunos
    // Cria novo aluno — senha já vem criptografada do controller
    public function cadastrarAluno(array $dados): array {
        return $this->client->post('/alunos', $dados);
    }

    // PUT /alunos/:id
    // Atualiza dados do aluno
    public function atualizarAluno(int $id, array $dados): array {
        return $this->client->put("/alunos/{$id}", $dados);
    }

    // POST /alunos + busca por email
    // Como a API não tem rota de login ainda,
    // busca o aluno pelo email e verifica a senha no PHP
    // com password_verify()
    public function login(string $email, string $senha): array {
        // Busca todos os alunos e filtra pelo email
        $data = $this->client->get("/alunos?email={$email}");

        // Se não encontrou nenhum aluno com esse email
        if (empty($data) || !isset($data[0])) {
            return ['erro' => 'E-mail ou senha incorretos.'];
        }

        $aluno = $data[0];

        // Verifica a senha com password_verify()
        // Compara a senha digitada com o hash bcrypt salvo no banco
        if (!password_verify($senha, $aluno['senha'])) {
            return ['erro' => 'E-mail ou senha incorretos.'];
        }

        // Remove a senha antes de retornar
        unset($aluno['senha']);

        return $aluno;
    }
}
