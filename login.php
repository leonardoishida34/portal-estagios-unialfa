<?php
$tituloPagina = 'Login — Portal UniALFA';
$paginaAtiva  = '';
$raiz         = '';
 require_once __DIR__ . '/config.php';
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

  .lh-logo   { font-size: 20px; font-weight: 800; }
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

  .btn-google {
    width: 100%; display: flex; align-items: center; justify-content: center;
    gap: 10px; padding: 11px 22px; border: 1px solid var(--borda);
    border-radius: 50px; font-size: 14px; font-weight: 500;
    background: var(--branco); color: var(--texto);
    cursor: pointer; font-family: inherit; transition: background 0.15s;
  }

  .btn-google:hover { background: var(--fundo); }

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

    <div class="login-form-area">
      <h1>Entrar na plataforma</h1>
      <p class="login-sub">Acesse sua conta para continuar</p>

      <div class="tabs">
        <button class="tab-btn ativo" onclick="trocarAba(this,'aluno')">Sou Aluno</button>
        <button class="tab-btn"       onclick="trocarAba(this,'empresa')">Sou Empresa</button>
      </div>

      <div class="form-group">
        <label id="label-email">E-mail </label>
        <input type="email" id="email" placeholder="Digite seu e-mail" autocomplete="email" />
      </div>

      <div class="form-group">
        <label>Senha</label>
        <input type="password" id="senha" placeholder="••••••••" autocomplete="current-password" />
      </div>

      <a class="esqueci" href="#">Esqueci minha senha</a>

      <button class="btn btn-primary btn-lg btn-block" onclick="fazerLogin()">Entrar</button>

      <div class="divider">ou</div>

      

    <p class="form-rodape">Não tem conta? <a id="link-cadastro" href="<?= BASE_URL ?>/aluno/cadastro.php">Criar conta gratuita</a></p>
    </div>

  </div>
</div>

<script>
function trocarAba(btn, tipo) {
  // Remove a classe 'ativo' de todos os botões e adiciona no botão clicado
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('ativo'));
  btn.classList.add('ativo');
  
  // Mapeia os elementos que vão mudar
  const label = document.getElementById('label-email');
  const input = document.getElementById('email');
  const linkCadastro = document.getElementById('link-cadastro'); // <-- Pegamos o link aqui

  if (tipo === 'empresa') {
    label.textContent = 'E-mail corporativo';
    input.placeholder = 'contato@suaempresa.com.br';
    
    // Muda o link para a página de cadastro da empresa
    linkCadastro.href = '<?= BASE_URL ?>/empresa/cadastro.php'; 
  } else {
    label.textContent = 'E-mail';
    input.placeholder = 'Digite seu e-mail';
    
    // Muda o link para a página de cadastro do currículo do aluno
    linkCadastro.href = '<?= BASE_URL ?>/aluno/cadastro.php';
  }
}
function fazerLogin() {
  const email = document.getElementById('email').value.trim();
  const senha = document.getElementById('senha').value.trim();
  if (!email || !senha) { alert('Preencha e-mail e senha.'); return; }
  alert('Login OK! Aqui vai conectar com a API.');
}
</script>

<?php
include BASE_PATH . '/includes/footer.php';
?>
