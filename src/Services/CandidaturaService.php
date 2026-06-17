<?php
// Consome a API Node.js — rotas de candidaturas:
//
// GET    /candidaturas                  → listar
// GET    /candidaturas/:id              → buscar
// POST   /candidaturas                  → criar
// PATCH  /candidaturas/:id/status       → atualizar status
// DELETE /candidaturas/:id              → remover
//
// Alterações em relação à versão anterior:
// - candidatar() usa aluno_ra (string) em vez de aluno_id (int)
// - candidatar() não tem mais o campo 'carta'
// ============================================

require_once BASE_PATH . '/src/Services/ApiCliente.php';
require_once BASE_PATH . '/src/Models/Candidatura.php';

class CandidaturaService {

    private ApiCliente $client;

    public function __construct() {
        $this->client = new ApiCliente();
    }

    // GET /candidaturas?aluno_ra=X
    // Retorna candidaturas de um aluno pelo RA (string!)
    public function listarPorAluno(string $alunoRa): array {
        $data = $this->client->get("/candidaturas?aluno_ra={$alunoRa}");

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
    // aluno_ra é string! — chave primária da tabela alunos
    // sem campo carta — não existe no banco
    public function candidatar(string $alunoRa, int $vagaId): array {
        return $this->client->post('/candidaturas', [
            'aluno_ra' => $alunoRa, // string, não int!
            'vaga_id'  => $vagaId,
        ]);
    }

    // PATCH /candidaturas/:id/status
    // status válidos: 'pendente' | 'aprovado' | 'reprovado'
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
