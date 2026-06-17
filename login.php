<?php
// ============================================
// login.php
// Tela de login do portal.
// Autentica aluno e empresa via API Node.js.
// ============================================

require_once __DIR__ . '/config.php';
require_once BASE_PATH . '/src/Controllers/AlunoController.php';
require_once BASE_PATH . '/src/Controllers/EmpresaController.php';

session_start();

// Se já está logado redireciona para o painel correto
if (isset($_SESSION['usuario'])) {
    $perfil = $_SESSION['usuario']['perfil'] ?? 'aluno';
    header('Location: ' . ($perfil === 'empresa'
        ? BASE_URL . '/empresa/empresa.php'
        : BASE_URL . '/aluno/vagas.php'));
    exit;
}

$erro = '';

// ── Processa o login ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo  = $_POST['tipo']  ?? 'aluno';
    $login = trim($_POST['login'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (empty($login) || empty($senha)) {
        $erro = 'Preencha login e senha para continuar.';
    } else {
        if ($tipo === 'empresa') {
            $controller = new EmpresaController();
        } else {
            $controller = new AlunoController();
        }

        $resultado = $controller->login();

        if (isset($resultado['erro'])) {
            $erro = $resultado['erro'];
        } else {
            // ── Salva na sessão ──
            // perfil vem da tabela usuarios ('aluno' ou 'empresa')
            $_SESSION['usuario'] = [
                'id'     => $resultado['id']     ?? 0,
                'nome'   => $resultado['nome']   ?? '',
                'login'  => $resultado['login']  ?? $login,
                'ra'     => $resultado['ra']     ?? '',   // só aluno tem RA
                'curso'  => $resultado['curso']  ?? '',   // só aluno tem curso
                'perfil' => $resultado['perfil'] ?? $tipo,
            ];

            // Redireciona para o painel correto
            $destino = ($tipo === 'empresa')
                ? BASE_URL . '/empresa/empresa.php'
                : BASE_URL . '/aluno/vagas.php';

            header("Location: $destino");
            exit;
        }
    }
}

$tituloPagina = 'Login — Portal UniALFA';
$paginaAtiva  = '';
include BASE_PATH . '/includes/header.php';
?>

<style>
  body { background: var(--fundo); }

  .login-wrapper {
    min-height: calc(100vh - 70px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
  }

  .login-box {
    display: flex;
    width: 100%;
    max-width: 940px;
    background: var(--branco);
    border-radius: var(--radius-lg);
    border: 1px solid var(--borda);
    box-shadow: 0 8px 40px rgba(0,0,0,0.09);
    overflow: hidden;
  }

  .login-hero {
    flex: 1;
    background: linear-gradient(145deg, var(--azul) 0%, var(--verde) 100%);
    padding: 3rem 2.5rem;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    color: #fff;
  }

  .lh-logo { font-size: 20px; font-weight: 800; }
  .lh-logo span { opacity: 0.7; }

  .lh-body h2 { font-size: 26px; font-weight: 800; line-height: 1.3; margin-bottom: 10px; }
  .lh-body p  { font-size: 14px; opacity: 0.8; line-height: 1.65; }

  .lh-stats { display: flex; gap: 1.5rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.2); }
  .ls-num   { font-size: 22px; font-weight: 800; }
  .ls-label { font-size: 12px; opacity: 0.7; margin-top: 2px; }

  .login-form-area {
    flex: 1.1;
    padding: 2.75rem 2.5rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .login-form-area h1 { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
  .login-sub { font-size: 14px; color: var(--muted); margin-bottom: 2rem; }

  .tabs {
    display: flex;
    background: var(--fundo);
    border-radius: var(--radius);
    padding: 4px;
    margin-bottom: 1.75rem;
    border: 1px solid var(--borda);
  }

  .tab-btn {
    flex: 1; padding: 8px; border: none; background: transparent;
    border-radius: 7px; font-size: 13px; font-weight: 500;
    color: var(--muted); cursor: pointer; font-family: inherit;
    transition: background 0.15s, color 0.15s;
  }

  .tab-btn.ativo {
    background: var(--branco); color: var(--azul);
    font-weight: 600; border: 1px solid var(--borda);
  }

  .esqueci {
    display: block; text-align: right;
    font-size: 12px; color: var(--azul);
    margin-top: -4px; margin-bottom: 1.25rem;
  }

  .form-rodape { text-align: center; font-size: 13px; color: var(--muted); margin-top: 1.25rem; }
  .form-rodape a { color: var(--azul); font-weight: 500; }

  @media (max-width: 660px) {
    .login-hero { display: none; }
    .login-form-area { padding: 2rem 1.5rem; }
    .login-box { border-radius: 0; border: none; box-shadow: none; }
    .login-wrapper { padding: 0; }
  }
</style>

<div class="login-wrapper">
  <div class="login-box">

    <!-- Hero -->
    <div class="login-hero">
      <div class="lh-logo">Uni<span>ALFA</span> Estágios</div>
      <div class="lh-body">
        <h2>Conectando talentos às oportunidades da região</h2>
        <p>Encontre vagas de estágio alinhadas ao seu curso e dê o primeiro passo na sua carreira.</p>
      </div>
      <div class="lh-stats">
        <div><div class="ls-num">120+</div><div class="ls-label">Vagas abertas</div></div>
        <div><div class="ls-num">48</div><div class="ls-label">Empresas</div></div>
        <div><div class="ls-num">300+</div><div class="ls-label">Contratados</div></div>
      </div>
    </div>

    <!-- Formulário -->
    <div class="login-form-area">
      <h1>Entrar na plataforma</h1>
      <p class="login-sub">Acesse sua conta para continuar</p>

      <!-- Abas -->
      <div class="tabs">
        <button type="button" class="tab-btn ativo" onclick="trocarAba(this,'aluno')">Sou Aluno</button>
        <button type="button" class="tab-btn"       onclick="trocarAba(this,'empresa')">Sou Empresa</button>
      </div>

      <!-- Erro -->
      <?php if ($erro): ?>
        <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <!-- Form com POST real -->
      <form method="POST" action="" novalidate>
        <!-- Campo hidden que muda conforme a aba selecionada -->
        <input type="hidden" name="tipo" id="input-tipo" value="aluno" />

        <div class="form-group">
          <label id="label-login">Login</label>
          <input type="text"
                 name="login"
                 id="login"
                 placeholder="Digite seu RA"
                 value="<?= htmlspecialchars($_POST['login'] ?? '') ?>"
                 autocomplete="username"
                 required />
        </div>

        <div class="form-group">
          <label>Senha</label>
          <input type="password"
                 name="senha"
                 id="senha"
                 placeholder="••••••••"
                 autocomplete="current-password"
                 required />
        </div>

        <a class="esqueci" href="#">Esqueci minha senha</a>

        <button type="submit" class="btn btn-primary btn-lg btn-block">
          Entrar
        </button>
      </form>

      <p class="form-rodape">
        Não tem conta?
        <a id="link-cadastro" href="<?= BASE_URL ?>/aluno/cadastro.php">
          Criar conta gratuita
        </a>
      </p>
    </div>

  </div>
</div>

<script>
function trocarAba(btn, tipo) {
  // Troca aba ativa
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('ativo'));
  btn.classList.add('ativo');

  // Atualiza o campo hidden para o PHP saber qual tipo está logando
  document.getElementById('input-tipo').value = tipo;

  const label       = document.getElementById('label-login');
  const input       = document.getElementById('login');
  const linkCadastro = document.getElementById('link-cadastro');

  if (tipo === 'empresa') {
    label.textContent  = 'E-mail corporativo';
    input.placeholder  = 'contato@suaempresa.com.br';
    input.type         = 'email';
    linkCadastro.href  = '<?= BASE_URL ?>/empresa/cadastro.php';
    linkCadastro.textContent = 'Cadastrar minha empresa';
  } else {
    label.textContent  = 'Login (e-mail ou RA)';
    input.placeholder  = 'Digite seu e-mail ou RA';
    input.type         = 'text';
    linkCadastro.href  = '<?= BASE_URL ?>/aluno/cadastro.php';
    linkCadastro.textContent = 'Criar conta gratuita';
  }
}
</script>

<?php include BASE_PATH . '/includes/footer.php'; ?>
