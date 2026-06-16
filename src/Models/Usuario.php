<?php
// Representa um usuário do sistema (login).
// Campos do banco (portal_estagios.usuarios):
// id     → bigint(20)
// nome   → varchar(100)
// login  → varchar(50)  — e-mail ou username
// senha  → varchar(255) — hash bcrypt
// perfil → varchar(20)  — 'aluno' | 'empresa' | 'admin'
// Esta tabela centraliza a autenticação —
// tanto aluno quanto empresa fazem login aqui!

class Usuario {

    // ── Atributos privados (encapsulamento) ──
    private int    $id;
    private string $nome;
    private string $login;  // e-mail ou username
    private string $perfil; // 'aluno' | 'empresa' | 'admin'
    // senha não fica no objeto por segurança!

    // ── Construtor ──
    // Recebe array com dados vindos da API
    // Ex: ['id' => 1, 'nome' => 'João', 'login' => 'joao@aluno.com', 'perfil' => 'aluno']
    public function __construct(array $data) {
        $this->id     = $data['id']     ?? 0;
        $this->nome   = $data['nome']   ?? '';
        $this->login  = $data['login']  ?? '';
        $this->perfil = $data['perfil'] ?? 'aluno';
        // senha NÃO é salva no objeto por segurança!
    }

    // ── Getters ──
    public function getId(): int       { return $this->id; }
    public function getNome(): string  { return $this->nome; }
    public function getLogin(): string { return $this->login; }
    public function getPerfil(): string { return $this->perfil; }

    // ── Métodos auxiliares ──
    public function isAluno(): bool   { return $this->perfil === 'aluno'; }
    public function isEmpresa(): bool { return $this->perfil === 'empresa'; }
    public function isAdmin(): bool   { return $this->perfil === 'admin'; }

    // Redireciona para o painel correto após login
    public function getPainelUrl(): string {
        return match($this->perfil) {
            'empresa' => BASE_URL . '/empresa/empresa.php',
            'admin'   => BASE_URL . '/admin/dashboard.php',
            default   => BASE_URL . '/aluno/vagas.php',
        };
    }
}
