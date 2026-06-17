<?php
$tituloPagina = 'Cadastro — Portal UniALFA';
$paginaAtiva  = 'cadastro';
require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/src/Controllers/AlunoController.php';

$erro    = '';
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AlunoController();
    $resultado  = $controller->cadastrar();

    if (isset($resultado['erro'])) {
        $erro = $resultado['erro'];
    } else {
        $sucesso = true;
    }
}

include BASE_PATH . '/includes/header.php';
?>
<style>
  body {
    background: var(--fundo);
  }

  .cadastro-wrapper {
    max-width: 620px;
    margin: 3rem auto;
    padding: 0 1.5rem 4rem;
  }

  .cadastro-header {
    text-align: center;
    margin-bottom: 2rem;
  }

  .cadastro-header h1 {
    font-size: 26px;
    font-weight: 800;
    margin-bottom: 6px;
  }

  .cadastro-header p {
    font-size: 15px;
    color: var(--muted);
  }

  .cadastro-card {
    background: var(--branco);
    border: 1px solid var(--borda);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: var(--sombra);
  }

  .secao-titulo {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--muted);
    margin-bottom: 1rem;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--borda);
  }

  .row-2 {
    display: flex;
    gap: 1rem;
  }

  .row-2 .form-group {
    flex: 1;
  }

  .upload-area {
    border: 1.5px dashed var(--borda);
    border-radius: var(--radius);
    padding: 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
  }

  .upload-area:hover {
    background: var(--fundo);
    border-color: var(--azul);
  }

  .upload-area input {
    display: none;
  }

  .upload-area .up-icon {
    font-size: 28px;
  }

  .upload-area p {
    font-size: 13px;
    color: var(--muted);
  }

  .upload-area small {
    font-size: 11px;
    color: var(--muted);
    opacity: 0.7;
  }

  .terms {
    font-size: 12px;
    color: var(--muted);
    text-align: center;
    margin-top: 12px;
    line-height: 1.6;
  }

  .terms a {
    color: var(--azul);
  }

  .ja-tem {
    text-align: center;
    font-size: 14px;
    color: var(--muted);
    margin-top: 1.25rem;
  }

  .ja-tem a {
    color: var(--azul);
    font-weight: 500;
  }

  @media (max-width: 560px) {
    .row-2 {
      flex-direction: column;
    }
  }
</style>

<div class="cadastro-wrapper">
  <div class="cadastro-header">
    <h1>Criar conta de aluno</h1>
    <p>Preencha os dados abaixo para acessar as vagas de estágio</p>
  </div>

  <div class="cadastro-card">
    <form method="POST" action="" novalidate>

      <p class="secao-titulo">Dados pessoais</p>

      <div class="form-group">
        <label>Nome completo *</label>
        <input type="text" name="nome" placeholder="Seu nome completo" />
      </div>
      <p class="secao-titulo" style="margin-top:1.5rem">Dados acadêmicos</p>
      <div class="form-group">
        <label>RA (Registro Acadêmico) *</label>
        <input type="text" name="ra" placeholder="Ex: 258450" maxlength="8" />
      </div>
      <div class="form-group">
        <label>Curso *</label>
        <select name="curso">
          <option value="">Selecione seu curso...</option>
          <option>Tecnologia em Sistemas para Internet</option>
          <option>Administração</option>
          <option>Ciências Contábeis</option>
          <option>Direito</option>
          <option>Enfermagem</option>
          <option>Psicologia</option>
        </select>
      </div>
      <p class="secao-titulo" style="margin-top:1.5rem">Acesso</p>

      <div class="form-group">
        <label>Senha *</label>
        <input type="password" name="senha" id="senha" placeholder="Mínimo 8 caracteres" />
      </div>

      <div class="form-group">
        <label>Confirmar senha *</label>
        <input type="password" name="senha2" id="senha2" placeholder="Repita a senha" />
      </div>
    </form>

    <button class="btn btn-primary btn-lg btn-block" onclick="cadastrar()" style="margin-top:.5rem">
      Criar conta gratuita
    </button>

    <p class="terms">
      Ao criar sua conta, você concorda com os
      <a href="#">termos de uso</a> e a
      <a href="#">política de privacidade</a> do Portal UniALFA.
    </p>
  </div>

  <p class="ja-tem">Já tem conta? <a href="../login.php">Entrar na plataforma</a></p>
</div>

<script>
  function cadastrar() {
    const senha = document.getElementById('senha').value;
    const senha2 = document.getElementById('senha2').value;
    if (senha !== senha2) {
      alert('As senhas não coincidem.');
      return;
    }
    if (senha.length < 8) {
      alert('A senha precisa ter pelo menos 8 caracteres.');
      return;
    }
    alert('Cadastro OK! Aqui vai conectar com a API.');
  }
</script>

<?php
include BASE_PATH . '/includes/footer.php';
?>