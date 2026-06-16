<?php

// ============================================
// SERVICE: CandidaturaService
// Consome a API Node.js — rotas de candidaturas:
//
// GET    /candidaturas              → listar todas
// GET    /candidaturas/:id          → buscar uma
// POST   /candidaturas              → criar
// PATCH  /candidaturas/:id/status   → atualizar status
// DELETE /candidaturas/:id          → remover
//
// ATENÇÃO: atualizar status usa PATCH, não PUT!
// E a rota é /:id/status, não só /:id
// ============================================

require_once BASE_PATH . '/src/Services/ApiCliente.php';
require_once BASE_PATH . '/src/Models/Candidatura.php';

class CandidaturaService {

    private ApiCliente $client;

    public function __construct() {
        $this->client = new ApiCliente();
    }

    // GET /candidaturas?aluno_id=X
    // Retorna candidaturas de um aluno específico
    public function listarPorAluno(int $alunoId): array {
        $data = $this->client->get("/candidaturas?aluno_id={$alunoId}");

        if (!is_array($data) || isset($data['erro'])) return [];

        return array_map(fn($item) => new Candidatura($item), $data);
    }

    // GET /candidaturas?vaga_id=X
    // Retorna candidatos de uma vaga específica
    public function listarPorVaga(int $vagaId): array {
        $data = $this->client->get("/candidaturas?vaga_id={$vagaId}");

        if (!is_array($data) || isset($data['erro'])) return [];

        return array_map(fn($item) => new Candidatura($item), $data);
    }

    // GET /candidaturas/:id
    public function buscarCandidatura(int $id): ?Candidatura {
        $data = $this->client->get("/candidaturas/{$id}");

        if (!isset($data['id'])) return null;

        return new Candidatura($data);
    }

    // POST /candidaturas
    // Envia candidatura do aluno para a vaga
    public function candidatar(int $alunoId, int $vagaId, string $carta): array {
        return $this->client->post('/candidaturas', [
            'aluno_id' => $alunoId,
            'vaga_id'  => $vagaId,
            'carta'    => $carta,
        ]);
    }

    // PATCH /candidaturas/:id/status
    // ATENÇÃO: usa PATCH e rota especial /status no final
    // A empresa usa isso para aprovar ou recusar candidatos
    public function atualizarStatus(int $id, string $status): array {
        return $this->client->patch("/candidaturas/{$id}/status", [
            'status' => $status,
        ]);
    }

    // DELETE /candidaturas/:id
    public function remover(int $id): array {
        return $this->client->delete("/candidaturas/{$id}");
    }
}
