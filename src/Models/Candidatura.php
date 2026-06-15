<?php

// ============================================
// MODEL: Candidatura
// Representa a candidatura de um aluno
// a uma vaga de estágio.
// Recebe os dados da API Node.js e encapsula
// as informações em um objeto PHP.
// ============================================

class Candidatura {

    // ── Atributos privados (encapsulamento) ──
    // Ninguém acessa direto — só pelos getters abaixo
    private int    $id;
    private int    $alunoId;   // ID do aluno que se candidatou
    private int    $vagaId;    // ID da vaga que foi candidatada
    private string $carta;     // Carta de apresentação
    private string $status;    // 'em_analise' | 'aprovado' | 'recusado'
    private string $dataCriacao;

    // ── Construtor ──
    // Recebe um array com os dados vindos da API
    // Ex: ['id' => 1, 'aluno_id' => 3, 'vaga_id' => 5, 'status' => 'em_analise', ...]
    // O ?? define um valor padrão caso o campo não venha na resposta
    public function __construct(array $data) {
        $this->id          = $data['id']           ?? 0;
        $this->alunoId     = $data['aluno_id']     ?? 0;
        $this->vagaId      = $data['vaga_id']      ?? 0;
        $this->carta       = $data['carta']        ?? '';
        $this->status      = $data['status']       ?? 'em_analise';
        $this->dataCriacao = $data['created_at']   ?? date('Y-m-d');
    }

    // ── Getters ──
    // Métodos públicos para acessar os atributos privados
    // A view chama $candidatura->getStatus() em vez de $candidatura['status']
    public function getId(): int            { return $this->id; }
    public function getAlunoId(): int       { return $this->alunoId; }
    public function getVagaId(): int        { return $this->vagaId; }
    public function getCarta(): string      { return $this->carta; }
    public function getStatus(): string     { return $this->status; }
    public function getDataCriacao(): string { return $this->dataCriacao; }

    // ── Métodos auxiliares ──
    // Facilitam verificações de status na view
    // Ex: if ($candidatura->isAprovada()) { mostrar parabéns }
    public function isEmAnalise(): bool { return $this->status === 'em_analise'; }
    public function isAprovada(): bool  { return $this->status === 'aprovado'; }
    public function isRecusada(): bool  { return $this->status === 'recusado'; }

    // ── Método para exibição ──
    // Retorna o label amigável do status para mostrar na view
    public function getStatusLabel(): string {
        return match($this->status) {
            'em_analise' => 'Em análise',
            'aprovado'   => 'Aprovado ✓',
            'recusado'   => 'Recusado',
            default      => 'Desconhecido'
        };
    }
}
