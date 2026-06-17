<?php

// ============================================
// CONTROLLER: AlunoController
// Faz a ponte entre a VIEW e o SERVICE.
// Gerencia as ações do aluno: cadastro,
// login e visualização de candidaturas.
// ============================================

require_once __DIR__ . '/../../config.php';
require_once BASE_PATH . '/src/Services/AlunoService.php';
require_once BASE_PATH . '/src/Models/Aluno.php';

class AlunoController {

    // ── Dependência ──
    // O controller não fala com a API diretamente,
    // ele delega isso para o AlunoService
    private AlunoService $alunoService;

    public function __construct() {
        $this->alunoService = new AlunoService();
    }

    // ── Buscar aluno pelo ID ──
    // Chamado após login para carregar os dados do aluno logado
    public function buscar(int $id): ?Aluno {
        return $this->alunoService->buscarAluno($id);
    }

    // ── Cadastrar novo aluno ──
    // Chamado pela view: aluno/cadastro.php
    // Pega os dados do $_POST, valida, criptografa a senha
    // e envia para a API
    public function cadastrar(): array {
        $nome    = trim($_POST['nome']    ?? '');
        $email   = trim($_POST['email']   ?? '');
        $ra      = trim($_POST['ra']      ?? '');
        $curso   = trim($_POST['curso']   ?? '');
        $periodo = (int)($_POST['periodo']?? 1);
        $senha   = trim($_POST['senha']   ?? '');

        // ── Validações ──
        if (empty($nome)) {
            return ['erro' => 'O nome é obrigatório.'];
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['erro' => 'Informe um e-mail válido.'];
        }

        if (empty($ra)) {
            return ['erro' => 'O RA é obrigatório.'];
        }

        if (strlen($senha) < 8) {
            return ['erro' => 'A senha deve ter no mínimo 8 caracteres.'];
        }

        // ── Criptografia da senha ──
        // password_hash() usa bcrypt por padrão (PASSWORD_BCRYPT)
        // O hash gerado é compatível com bcrypt.compare() do Node.js
        // NUNCA salva a senha em texto puro!
        $senhaCriptografada = password_hash($senha, PASSWORD_BCRYPT);

        $dados = [
            'nome'    => $nome,
            'email'   => $email,
            'ra'      => $ra,
            'curso'   => $curso,
            'periodo' => $periodo,
            'senha'   => $senhaCriptografada, // ← hash bcrypt, não texto puro
        ];

        // Manda para o Service que chama a API
        return $this->alunoService->cadastrarAluno($dados);
    }

    // ── Login do aluno ──
    // Chamado pela view: index.php (formulário de login)
    // A verificação da senha é feita pela API Node.js com bcrypt.compare()
    // Aqui só enviamos o email e senha — a API compara com o hash do banco
    public function login(): array {
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        // Validação básica
        if (empty($email) || empty($senha)) {
            return ['erro' => 'Preencha e-mail e senha.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['erro' => 'Informe um e-mail válido.'];
        }

        // Envia email + senha para a API
        // A API usa bcrypt.compare(senhaDigitada, hashDoBanco) para verificar
        $resultado = $this->alunoService->login($email, $senha);

        // Se autenticou, salva os dados na sessão PHP
        if (!isset($resultado['erro'])) {
            if (session_status() === PHP_SESSION_NONE) session_start();

            $_SESSION['usuario'] = [
                'id'    => $resultado['id'],
                'nome'  => $resultado['nome'],
                'email' => $resultado['email'],
                'tipo'  => 'aluno',
                'token' => $resultado['token'] ?? '',
            ];
        }

        return $resultado;
    }

    // ── Atualizar dados do aluno ──
    // Chamado pela view: aluno/perfil.php
    public function atualizar(int $id): array {
        $dados = [
            'nome'    => trim($_POST['nome']    ?? ''),
            'email'   => trim($_POST['email']   ?? ''),
            'curso'   => trim($_POST['curso']   ?? ''),
            'periodo' => (int)($_POST['periodo']?? 1),
        ];

        // Se o aluno quer trocar a senha também
        if (!empty($_POST['senha'])) {
            if (strlen($_POST['senha']) < 8) {
                return ['erro' => 'A nova senha deve ter no mínimo 8 caracteres.'];
            }
            // Criptografa a nova senha antes de enviar
            $dados['senha'] = password_hash(trim($_POST['senha']), PASSWORD_BCRYPT);
        }

        return $this->alunoService->atualizarAluno($id, $dados);
    }
}
