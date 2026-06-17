<?php
// ============================================
// Representa um aluno cadastrado no portal.
// Campos do banco (portal_estagios.alunos):
// ra       → varchar(8)   — Registro Acadêmico (chave primária)
// nome     → varchar(255)
// curso    → varchar(100)
// apto     → tinyint(1)   — 1 = apto a estagiar, 0 = não apto
// ============================================

class Aluno {

    // ── Atributos privados (encapsulamento) ──
    private string $ra;    // chave primária — não é int, é string!
    private string $nome;
    private string $curso;
    private bool   $apto;  // tinyint vira bool no PHP

    // ── Construtor ──
    // Recebe array com dados vindos da API
    // Ex: ['ra' => '2024001', 'nome' => 'João', 'curso' => 'TSI', 'apto' => 1]
    public function __construct(array $data) {
        $this->ra    = $data['ra']    ?? '';
        $this->nome  = $data['nome']  ?? '';
        $this->curso = $data['curso'] ?? '';
        // tinyint(1) vem como 0 ou 1 da API — converte para bool
        $this->apto  = (bool)($data['apto'] ?? false);
    }

    // ── Getters ──
    public function getRa(): string    { return $this->ra; }
    public function getNome(): string  { return $this->nome; }
    public function getCurso(): string { return $this->curso; }
    public function isApto(): bool     { return $this->apto; }

    // ── Método auxiliar ──
    // Retorna label amigável do status
    public function getStatusLabel(): string {
        return $this->apto ? 'Apto' : 'Não apto';
    }
}
