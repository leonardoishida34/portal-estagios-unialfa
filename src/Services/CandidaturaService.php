<?php

// ============================================
// SERVICE: CandidaturaService
// Responsável pelas chamadas à API relacionadas
// às candidaturas. Usa o ApiClient para fazer
// as requisições e converte os dados retornados
// em objetos Candidatura.
// ============================================

require_once BASE_PATH . '/src/Services/ApiClient.php';
require_once BASE_PATH . '/src/Models/Candidatura.php';

class CandidaturaService {

    // ── Dependência ──
    // Usa o ApiClient para fazer as requisições HTTP
    private ApiClient $client;

    public function __construct() {
        $this->client = new ApiClient();
    }

    // ── Listar candidaturas de um aluno ──
    // Faz GET /candidaturas?aluno_id={id} na API
    // Usado na tela "Minhas Candidaturas" do aluno
    // Retorna array de objetos Candidatura
    public function listarPorAluno(int $alunoId): array {
        $data = $this->client->get("/candidaturas?aluno_id={$alunoId}");

        if (isset($data['erro'])) return [];

        // Converte cada item em objeto Candidatura
        return array_map(fn($item) => new Candidatura($item), $data);
    }

    // ── Listar candidatos de uma vaga ──
    // Faz GET /candidaturas?vaga_id={id} na API
    // Usado no painel da empresa para ver quem se candidatou
    // Retorna array de objetos Candidatura
    public function listarPorVaga(int $vagaId): array {
        $data = $this->client->get("/candidaturas?vaga_id={$vagaId}");

        if (isset($data['erro'])) return [];

        return array_map(fn($item) => new Candidatura($item), $data);
    }

    // ── Enviar candidatura ──
    // Faz POST /candidaturas na API com os dados
    // Chamado quando o aluno clica em "Enviar candidatura"
    // Retorna a resposta da API (sucesso ou erro)
    public function candidatar(int $alunoId, int $vagaId, string $carta): array {
        return $this->client->post('/candidaturas', [
            'aluno_id' => $alunoId,
            'vaga_id'  => $vagaId,
            'carta'    => $carta,
        ]);
    }

    // ── Atualizar status da candidatura ──
    // Faz PUT /candidaturas/{id} na API com o novo status
    // Chamado pela empresa quando aprova ou recusa um candidato
    // Status possíveis: 'em_analise', 'aprovado', 'recusado'
    public function atualizarStatus(int $id, string $status): array {
        return $this->client->put("/candidaturas/{$id}", [
            'status' => $status,
        ]);
    }
}
