<?php
// Representa um aluno cadastrado no portal.
// Recebe os dados da API Node.js e encapsula

class Aluno {

    // ── Atributos privados (encapsulamento) ──
    // Ninguém acessa direto — só pelos getters abaixo
    private int    $id;
    private string $nome;
    private string $email;
    private string $ra;       // Registro Acadêmico
    private string $curso;
    private int    $periodo;
    private string $status;   // 'ativo' | 'inativo'

    // ── Construtor ──
    // Recebe um array com os dados vindos da API
    // Ex: ['id' => 1, 'nome' => 'João Silva', 'ra' => '2024001234', ...]
    // O ?? define um valor padrão caso o campo não venha na resposta
    public function __construct(array $data) {
        $this->id      = $data['id']      ?? 0;
        $this->nome    = $data['nome']    ?? '';
        $this->email   = $data['email']   ?? '';
        $this->ra      = $data['ra']      ?? '';
        $this->curso   = $data['curso']   ?? '';
        $this->periodo = $data['periodo'] ?? 1;
        $this->status  = $data['status']  ?? 'ativo';
    }

    // ── Getters ──
    // Métodos públicos para acessar os atributos privados
    // A view chama $aluno->getNome() em vez de $aluno['nome']
    public function getId(): int        { return $this->id; }
    public function getNome(): string   { return $this->nome; }
    public function getEmail(): string  { return $this->email; }
    public function getRa(): string     { return $this->ra; }
    public function getCurso(): string  { return $this->curso; }
    public function getPeriodo(): int   { return $this->periodo; }
    public function getStatus(): string { return $this->status; }

    // ── Método auxiliar ──
    // Retorna se o aluno está apto a se candidatar
    public function isAtivo(): bool {
        return $this->status === 'ativo';
    }
}
