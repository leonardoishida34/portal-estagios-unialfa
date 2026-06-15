<?php
// Representa uma vaga de estágio no sistema.
// Recebe os dados da API Node.js e encapsula

class Vaga {
    // ── Atributos privados (encapsulamento) ──
    // Ninguém acessa direto — só pelos getters abaixo
    private int $id;
    private string $titulo;
    private string $area;
    private string $descricao;
    private string $status;

    // ── Construtor ──
    // Recebe um array com os dados vindos da API
    // Ex: ['id' => 1, 'titulo' => 'Dev Web', ...]
    // O ?? define um valor padrão caso o campo não venha na resposta
    public function __construct(array $data) {
        $this->id        = $data['id']        ?? 0;
        $this->titulo    = $data['titulo']    ?? '';
        $this->area      = $data['area']      ?? '';
        $this->descricao = $data['descricao'] ?? '';
        $this->status    = $data['status']    ?? 'aberta';
    }

    // ── Getters ──
    // Métodos públicos para acessar os atributos privados
    // A view chama $vaga->getTitulo() em vez de $vaga['titulo']
    public function getId(): int            { return $this->id; }
    public function getTitulo(): string     { return $this->titulo; }
    public function getArea(): string       { return $this->area; }
    public function getDescricao(): string  { return $this->descricao; }
    public function getStatus(): string     { return $this->status; }
}