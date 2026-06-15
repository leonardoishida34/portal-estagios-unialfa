<?php

// ============================================
// CONTROLLER: EmpresaController
// Faz a ponte entre a VIEW e o SERVICE.
// Gerencia as ações da empresa: cadastro,
// login e gerenciamento de vagas.
// ============================================

require_once __DIR__ . '/../../config.php';
require_once BASE_PATH . '/src/Services/EmpresaService.php';
require_once BASE_PATH . '/src/Models/Empresa.php';

class EmpresaController {

    // ── Dependência ──
    // O controller não fala com a API diretamente,
    // ele delega isso para o EmpresaService
    private EmpresaService $empresaService;

    public function __construct() {
        $this->empresaService = new EmpresaService();
    }

    // ── Buscar empresa pelo ID ──
    // Chamado após login para carregar os dados da empresa logada
    public function buscar(int $id): ?Empresa {
        return $this->empresaService->buscarEmpresa($id);
    }

    // ── Cadastrar nova empresa ──
    // Chamado pela view: empresa/cadastro.php
    // Pega os dados do $_POST, valida, criptografa a senha
    // e envia para a API. Status inicial: 'pendente'
    public function cadastrar(): array {
        $nome      = trim($_POST['nome']      ?? '');
        $cnpj      = trim($_POST['cnpj']      ?? '');
        $email     = trim($_POST['email']     ?? '');
        $telefone  = trim($_POST['telefone']  ?? '');
        $cidade    = trim($_POST['cidade']    ?? '');
        $estado    = trim($_POST['estado']    ?? '');
        $area      = trim($_POST['area']      ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $senha     = trim($_POST['senha']     ?? '');

        // ── Validações ──
        if (empty($nome)) {
            return ['erro' => 'O nome da empresa é obrigatório.'];
        }

        if (empty($cnpj)) {
            return ['erro' => 'O CNPJ é obrigatório.'];
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['erro' => 'Informe um e-mail válido.'];
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
            'nome'      => $nome,
            'cnpj'      => $cnpj,
            'email'     => $email,
            'telefone'  => $telefone,
            'cidade'    => $cidade,
            'estado'    => $estado,
            'area'      => $area,
            'descricao' => $descricao,
            'senha'     => $senhaCriptografada, // ← hash bcrypt, não texto puro
        ];

        // Manda para o Service que chama a API
        // A API salva com status 'pendente' até aprovação da UniALFA
        return $this->empresaService->cadastrarEmpresa($dados);
    }

    // ── Login da empresa ──
    // Chamado pela view: index.php (aba Sou Empresa)
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
        $resultado = $this->empresaService->login($email, $senha);

        // Se autenticou, salva os dados na sessão PHP
        if (!isset($resultado['erro'])) {
            if (session_status() === PHP_SESSION_NONE) session_start();

            $_SESSION['usuario'] = [
                'id'    => $resultado['id'],
                'nome'  => $resultado['nome'],
                'email' => $resultado['email'],
                'tipo'  => 'empresa',
                'token' => $resultado['token'] ?? '',
            ];
        }

        return $resultado;
    }

    // ── Atualizar perfil da empresa ──
    // Chamado pela view: empresa/empresa.php (aba Perfil)
    public function atualizarPerfil(int $id): array {
        $dados = [
            'nome'      => trim($_POST['nome']      ?? ''),
            'email'     => trim($_POST['email']     ?? ''),
            'cidade'    => trim($_POST['cidade']    ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'telefone'  => trim($_POST['telefone']  ?? ''),
        ];

        // Se a empresa quer trocar a senha também
        if (!empty($_POST['senha'])) {
            if (strlen($_POST['senha']) < 8) {
                return ['erro' => 'A nova senha deve ter no mínimo 8 caracteres.'];
            }
            // Criptografa a nova senha antes de enviar
            $dados['senha'] = password_hash(trim($_POST['senha']), PASSWORD_BCRYPT);
        }

        return $this->empresaService->atualizarEmpresa($id, $dados);
    }
}
