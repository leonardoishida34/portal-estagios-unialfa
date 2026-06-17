<?php
$tituloPagina = 'Portal de Estágios UniALFA';
$paginaAtiva  = '';
$raiz         = '';
require_once __DIR__ . '/config.php';
include BASE_PATH . '/includes/header.php'; 

?>

<style>
  /* ── Hero ── */
  .hero {
    position: relative;
    padding: 5rem 5% 4rem;
    min-height: 88vh;
    display: flex;
    align-items: center;
    overflow: hidden;
  }

  .hero-inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
    gap: 4rem;
    position: relative;
    z-index: 2;
  }

  .hero-content { flex: 1; max-width: 560px; }

  .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f0fdf4;
    color: #15803d;
    padding: 6px 14px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 1.5rem;
  }

  .hero-titulo {
    font-size: 3.5rem;
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -1.5px;
    margin-bottom: 1.25rem;
    color: var(--texto);
  }

  .hero-sub {
    font-size: 17px;
    color: var(--muted);
    line-height: 1.65;
    margin-bottom: 2rem;
  }

  .hero-acoes { display: flex; gap: 12px; flex-wrap: wrap; }

  /* Lado direito */
  .hero-visual {
    flex: 1;
    position: relative;
    height: 480px;
    max-width: 520px;
  }

  .hero-img-box {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--azul-claro) 0%, #d1fae5 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 80px;
    box-shadow: 0 20px 60px rgba(26,86,219,0.15);
  }

  /* Cards flutuantes */
  .float-card {
    position: absolute;
    background: #fff;
    border-radius: 14px;
    padding: 14px 18px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    font-size: 13px;
    z-index: 3;
    border: 1px solid var(--borda);
  }

  .float-card-1 { top: 30px; left: -40px; min-width: 180px; }
  .float-card-2 { top: 60px; right: -30px; min-width: 160px; }
  .float-card-3 { bottom: 30px; left: 50%; transform: translateX(-50%); min-width: 200px; text-align: center; }

  .float-card strong { display: block; font-size: 15px; font-weight: 700; color: var(--texto); }
  .float-card span   { color: var(--muted); font-size: 12px; }

  /* Blobs de fundo */
  .blob {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    z-index: 1;
    opacity: 0.35;
  }

  .blob-purple { width: 400px; height: 400px; background: #c084fc; bottom: -100px; left: -100px; }
  .blob-green  { width: 500px; height: 500px; background: #86efac; bottom: -200px; right: -100px; }

  /* ── Como funciona ── */
  .section { padding: 5rem 5%; }
  .section-titulo { text-align: center; font-size: 28px; font-weight: 800; margin-bottom: 10px; }
  .section-sub { text-align: center; color: var(--muted); margin-bottom: 3rem; font-size: 15px; }

  .steps-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
    max-width: 900px;
    margin: 0 auto;
  }

  .step-card {
    text-align: center;
    padding: 2rem 1.5rem;
    border-radius: var(--radius-lg);
    border: 1px solid var(--borda);
    background: var(--branco);
    transition: box-shadow 0.2s, transform 0.2s;
  }

  .step-card:hover { box-shadow: var(--sombra); transform: translateY(-4px); }

  .step-num {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: var(--azul);
    color: #fff;
    font-size: 20px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
  }

  .step-card h3 { font-size: 16px; font-weight: 700; margin-bottom: 8px; }
  .step-card p  { font-size: 14px; color: var(--muted); line-height: 1.6; }

  /* ── Stats ── */
  .stats-bar {
    background: linear-gradient(135deg, var(--azul) 0%, var(--verde) 100%);
    padding: 3rem 5%;
  }

  .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
    color: #fff;
  }

  .stat-num-lg { font-size: 36px; font-weight: 800; }
  .stat-desc   { font-size: 13px; opacity: 0.8; margin-top: 4px; }

  /* ── CTA final ── */
  .cta-section {
    padding: 5rem 5%;
    text-align: center;
    background: var(--fundo);
  }

  .cta-section h2 { font-size: 28px; font-weight: 800; margin-bottom: 12px; }
  .cta-section p  { color: var(--muted); font-size: 16px; margin-bottom: 2rem; }
  .cta-acoes { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

  @media (max-width: 900px) {
    .hero-inner    { flex-direction: column; text-align: center; }
    .hero-acoes    { justify-content: center; }
    .hero-visual   { width: 100%; height: 280px; max-width: 100%; }
    .float-card    { display: none; }
    .hero-titulo   { font-size: 2.4rem; }
    .steps-grid    { grid-template-columns: 1fr; }
    .stats-grid    { grid-template-columns: repeat(2, 1fr); }
  }
</style>

<!-- Hero -->
<section class="hero">
  <div class="blob blob-purple"></div>
  <div class="blob blob-green"></div>

  <div class="hero-inner">
    <div class="hero-content">
      <div class="hero-badge">🏆 A plataforma nº1 para estágios</div>
      <h1 class="hero-titulo">A plataforma de nº1 para contratar talentos</h1>
      <p class="hero-sub">Integre toda a jornada dos seus estagiários. Centralize processos, conecte alunos às melhores empresas da região.</p>
      <div class="hero-acoes">
        <a href="aluno/vagas.php" class="btn btn-primary btn-lg">Ver vagas disponíveis</a>
        <a href="aluno/cadastro.php" class="btn btn-outline btn-lg">Cadastrar currículo</a>
      </div>
    </div>

    <div class="hero-visual">
      <div class="hero-img-box"></div>

      <div class="float-card float-card-1">
        <strong>48 empresas</strong>
        <span>parceiras cadastradas</span>
      </div>
      <div class="float-card float-card-2">
        <strong>120+ vagas</strong>
        <span>abertas agora</span>
      </div>
      <div class="float-card float-card-3">
        <strong>300+ alunos</strong>
        <span>já contratados pelo portal</span>
      </div>
    </div>
  </div>
</section>

<!-- Stats -->
<div class="stats-bar">
  <div class="stats-grid">
    <div><div class="stat-num-lg">120+</div><div class="stat-desc">Vagas abertas</div></div>
    <div><div class="stat-num-lg">48</div><div class="stat-desc">Empresas parceiras</div></div>
    <div><div class="stat-num-lg">300+</div><div class="stat-desc">Alunos contratados</div></div>
    <div><div class="stat-num-lg">98%</div><div class="stat-desc">Satisfação dos alunos</div></div>
  </div>
</div>

<!-- Como funciona -->
<section class="section">
  <h2 class="section-titulo">Como funciona?</h2>
  <p class="section-sub">Em 3 passos simples você já está concorrendo a uma vaga</p>
  <div class="steps-grid">
    <div class="step-card">
      <div class="step-num">1</div>
      <h3>Crie sua conta</h3>
      <p>Cadastre-se com seu e-mail institucional e preencha seu perfil acadêmico.</p>
    </div>
    <div class="step-card">
      <div class="step-num">2</div>
      <h3>Explore as vagas</h3>
      <p>Filtre por área, empresa ou curso e encontre a oportunidade ideal para você.</p>
    </div>
    <div class="step-card">
      <div class="step-num">3</div>
      <h3>Candidate-se</h3>
      <p>Envie sua candidatura em segundos e acompanhe o status pelo portal.</p>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <h2>Pronto para dar o próximo passo?</h2>
  <p>Cadastre-se gratuitamente e acesse as melhores oportunidades de estágio da região.</p>
  <div class="cta-acoes">
    <a href="./aluno/cadastro.php" class="btn btn-primary btn-lg">Criar conta gratuita</a>
    <a href="./empresa/empresa.php"  class="btn btn-outline btn-lg">Sou empresa</a>
  </div>
</section>

<?php 
include BASE_PATH . '/includes/footer.php';
?>
