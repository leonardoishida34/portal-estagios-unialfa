<?php
// Representa a candidatura de um aluno a uma vaga.
// Campos do banco (portal_estagios.candidaturas):
// id        → bigint(20)
// aluno_ra  → varchar(8)  — FK para alunos (usa RA, não ID!)
// vaga_id   → bigint(20)  — FK para vagas
// status    → varchar(50) — 'pendente' | 'aprovado' | 'reprovado'
//
// ATENÇÃO: não tem campo 'carta' no banco!
// A chave do aluno é o RA (string), não um ID (int)

class Candidatura {

    // ── Atributos privados (encapsulamento) ──
    private int    $id;
    private string $alunoRa;  // campo no banco: aluno_ra — é string!
    private int    $vagaId;   // campo no banco: vaga_id
    private string $status;   // 'pendente' | 'aprovado' | 'reprovado'

    // ── Construtor ──
    // Recebe array com dados vindos da API
    // Ex: ['id' => 1, 'aluno_ra' => '2024001', 'vaga_id' => 3, 'status' => 'pendente']
    public function __construct(array $data) {
        $this->id      = $data['id']       ?? 0;
        // banco usa aluno_ra com underscore
        $this->alunoRa = $data['aluno_ra'] ?? '';
        // banco usa vaga_id com underscore
        $this->vagaId  = (int)($data['vaga_id'] ?? 0);
        $this->status  = $data['status']   ?? 'pendente';
    }

    // ── Getters ──
    public function getId(): int       { return $this->id; }
    public function getAlunoRa(): string { return $this->alunoRa; }
    public function getVagaId(): int   { return $this->vagaId; }
    public function getStatus(): string { return $this->status; }

    // ── Métodos auxiliares ──
    public function isPendente(): bool  { return $this->status === 'pendente'; }
    public function isAprovado(): bool  { return $this->status === 'aprovado'; }
    public function isReprovado(): bool { return $this->status === 'reprovado'; }

    // Retorna label amigável para mostrar na view
    public function getStatusLabel(): string {
        return match($this->status) {
            'pendente'  => 'Em análise',
            'aprovado'  => 'Aprovado ✓',
            'reprovado' => 'Reprovado',
            default     => 'Desconhecido'
        };
    }

    // Retorna classe CSS do badge conforme status
    public function getStatusBadge(): string {
        return match($this->status) {
            'aprovado'  => 'badge-green',
            'reprovado' => 'badge-red',
            default     => 'badge-blue'
        };
    }
}
