<?php

// ============================================
// SERVICE: VagaService
// Consome a API Node.js — rotas de vagas:
//
// GET    /vagas          → listar todas
// GET    /vagas/:id      → buscar uma
// POST   /vagas          → criar
// PUT    /vagas/:id      → atualizar
// DELETE /vagas/:id      → excluir
// ============================================

require_once BASE_PATH . '/src/Services/ApiCliente.php';
require_once BASE_PATH . '/src/Models/Vaga.php';

class VagaService {

    private ApiCliente $client;

    public function __construct() {
        $this->client = new ApiCliente();
    }

    // GET /vagas
    // Retorna array de objetos Vaga
    public function listarVagas(): array {
        $data = $this->client->get('/vagas');

        if (!is_array($data) || isset($data['erro'])) return [];

        return array_map(fn($item) => new Vaga($item), $data);
    }

    // GET /vagas/:id
    // Retorna objeto Vaga ou null
    public function buscarVaga(int $id): ?Vaga {
        $data = $this->client->get("/vagas/{$id}");

        if (!isset($data['id'])) return null;

        return new Vaga($data);
    }

    // POST /vagas
    public function criarVaga(array $dados): array {
        return $this->client->post('/vagas', $dados);
    }

    // PUT /vagas/:id
    public function atualizarVaga(int $id, array $dados): array {
        return $this->client->put("/vagas/{$id}", $dados);
    }

    // DELETE /vagas/:id
    public function excluirVaga(int $id): array {
        return $this->client->delete("/vagas/{$id}");
    }

    // GET /vagas?empresa_id=X
    // Filtra vagas por empresa
    public function listarPorEmpresa(int $empresaId): array {
        $data = $this->client->get("/vagas?empresa_id={$empresaId}");

        if (!is_array($data) || isset($data['erro'])) return [];

        return array_map(fn($item) => new Vaga($item), $data);
    }
}
