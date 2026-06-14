<?php

// ============================================
// MODEL: Empresa
// Representa uma empresa parceira cadastrada
// no portal. Recebe os dados da API Node.js
// e encapsula as informações em um objeto PHP.
// ============================================

class Empresa {

    // ── Atributos privados (encapsulamento) ──
    // Ninguém acessa direto — só pelos getters abaixo
    private int    $id;
    private string $nome;
    private string $cnpj;
    private string $email;
    private string $cidade;
    private string $descricao;
    private string $status;  // 'pendente' | 'aprovada' | 'bloqueada'

    // ── Construtor ──
    // Recebe um array com os dados vindos da API
    // Ex: ['id' => 1, 'nome' => 'TechSul', 'status' => 'aprovada', ...]
    // O ?? define um valor padrão caso o campo não venha na resposta
    public function __construct(array $data) {
        $this->id        = $data['id']        ?? 0;
        $this->nome      = $data['nome']      ?? '';
        $this->cnpj      = $data['cnpj']      ?? '';
        $this->email     = $data['email']     ?? '';
        $this->cidade    = $data['cidade']    ?? '';
        $this->descricao = $data['descricao'] ?? '';
        $this->status    = $data['status']    ?? 'pendente';
    }

    // ── Getters ──
    // Métodos públicos para acessar os atributos privados
    // A view chama $empresa->getNome() em vez de $empresa['nome']
    public function getId(): int           { return $this->id; }
    public function getNome(): string      { return $this->nome; }
    public function getCnpj(): string      { return $this->cnpj; }
    public function getEmail(): string     { return $this->email; }
    public function getCidade(): string    { return $this->cidade; }
    public function getDescricao(): string { return $this->descricao; }
    public function getStatus(): string    { return $this->status; }

    // ── Métodos auxiliares ──
    // Facilitam verificações de status na view
    // Ex: if ($empresa->isAprovada()) { mostrar painel }
    public function isAprovada(): bool  { return $this->status === 'aprovada'; }
    public function isPendente(): bool  { return $this->status === 'pendente'; }
    public function isBloqueada(): bool { return $this->status === 'bloqueada'; }
}
