<?php
// Representa uma empresa parceira no portal.
// Campos do banco (portal_estagios.empresas):
// id          → bigint(20)
// razao_social→ varchar(255)
// cnpj        → varchar(18)
// email       → varchar(255)
// telefone    → varchar(20)
// aprovada    → tinyint(1) — 1 = aprovada, 0 = pendente

class Empresa {

    // ── Atributos privados (encapsulamento) ──
    private int    $id;
    private string $razaoSocial; // campo no banco: razao_social
    private string $cnpj;
    private string $email;
    private string $telefone;
    private bool   $aprovada;   // tinyint vira bool no PHP

    // ── Construtor ──
    // Recebe array com dados vindos da API
    // Ex: ['id' => 1, 'razao_social' => 'TechSul', 'aprovada' => 0]
    public function __construct(array $data) {
        $this->id          = $data['id']           ?? 0;
        // banco usa razao_social com underscore
        $this->razaoSocial = $data['razao_social'] ?? '';
        $this->cnpj        = $data['cnpj']         ?? '';
        $this->email       = $data['email']        ?? '';
        $this->telefone    = $data['telefone']     ?? '';
        // tinyint(1) vem como 0 ou 1 da API — converte para bool
        $this->aprovada    = (bool)($data['aprovada'] ?? false);
    }

    // ── Getters ──
    public function getId(): int           { return $this->id; }
    public function getRazaoSocial(): string { return $this->razaoSocial; }
    public function getCnpj(): string      { return $this->cnpj; }
    public function getEmail(): string     { return $this->email; }
    public function getTelefone(): string  { return $this->telefone; }
    public function isAprovada(): bool     { return $this->aprovada; }

    // ── Métodos auxiliares ──
    public function getStatusLabel(): string {
        return $this->aprovada ? 'Aprovada' : 'Pendente';
    }

    public function getStatusBadge(): string {
        return $this->aprovada ? 'badge-green' : 'badge-gray';
    }
}
