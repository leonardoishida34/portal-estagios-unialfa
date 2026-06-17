<?php
// Representa uma vaga de estágio no portal.
// Campos do banco (portal_estagios.vagas):
// id          → bigint(20)
// titulo      → varchar(255)
// descricao   → text
// bolsa       → decimal(10,2) — valor da bolsa em R$
// empresa_id  → bigint(20)    — FK para empresas
// ativa       → tinyint(1)    — 1 = aberta, 0 = encerrada

class Vaga {

    // ── Atributos privados (encapsulamento) ──
    private int    $id;
    private string $titulo;
    private string $descricao;
    private float  $bolsa;      // decimal vira float no PHP
    private int    $empresaId;  // campo no banco: empresa_id
    private bool   $ativa;      // tinyint vira bool no PHP

    // ── Construtor ──
    // Recebe array com dados vindos da API
    // Ex: ['id' => 1, 'titulo' => 'Dev Web', 'bolsa' => 800.00, 'ativa' => 1]
    public function __construct(array $data) {
        $this->id        = $data['id']         ?? 0;
        $this->titulo    = $data['titulo']     ?? '';
        $this->descricao = $data['descricao']  ?? '';
        // decimal(10,2) vem como string da API — converte para float
        $this->bolsa     = (float)($data['bolsa']      ?? 0);
        // banco usa empresa_id com underscore
        $this->empresaId = (int)($data['empresa_id']   ?? 0);
        // tinyint(1) vem como 0 ou 1 da API — converte para bool
        $this->ativa     = (bool)($data['ativa']       ?? true);
    }

    // ── Getters ──
    public function getId(): int        { return $this->id; }
    public function getTitulo(): string { return $this->titulo; }
    public function getDescricao(): string { return $this->descricao; }
    public function getBolsa(): float   { return $this->bolsa; }
    public function getEmpresaId(): int { return $this->empresaId; }
    public function isAtiva(): bool     { return $this->ativa; }

    // ── Métodos auxiliares ──
    // Formata a bolsa em Real brasileiro
    // Ex: 800.00 → R$ 800,00
    public function getBolsaFormatada(): string {
        return 'R$ ' . number_format($this->bolsa, 2, ',', '.');
    }

    public function getStatusLabel(): string {
        return $this->ativa ? 'Aberta' : 'Encerrada';
    }

    public function getStatusBadge(): string {
        return $this->ativa ? 'badge-green' : 'badge-gray';
    }
}
