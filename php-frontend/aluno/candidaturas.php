<?php
session_start();
$tituloPagina = 'Minhas Candidaturas — Portal UniALFA';
$paginaAtiva  = 'candidaturas';
$raiz         = '../';
require_once __DIR__ . '/../config.php';
include BASE_PATH . '/includes/header.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'aluno') {
    header('Location: ' . BASE_URL . '/portal-estagios-unialfa/login.php?erro=acesso-restrito');
    exit;
}

// Mock — depois vem da API
$candidaturas = [
  ['id'=>1, 'vaga'=>'Desenvolvedor(a) Web', 'empresa'=>'TechSul Sistemas', 'area'=>'Tecnologia', 'data'=>'05/06/2025', 'status'=>'em_analise'],
  ['id'=>2, 'vaga'=>'Designer Gráfico Jr.', 'empresa'=>'Agência Pixel', 'area'=>'Design', 'data'=>'02/06/2025', 'status'=>'aprovado'],
  ['id'=>3, 'vaga'=>'Assistente Administrativo', 'empresa'=>'Grupo RB Comércio', 'area'=>'Administração', 'data'=>'28/05/2025', 'status'=>'recusado'],
  ['id'=>4, 'vaga'=>'Suporte de TI', 'empresa'=>'Hospital Regional', 'area'=>'Tecnologia', 'data'=>'20/05/2025', 'status'=>'em_analise'],
];

$statusMap = [
  'em_analise' => ['label' => 'Em análise',  'classe' => 'badge-blue'],
  'aprovado'   => ['label' => 'Aprovado ✓',  'classe' => 'badge-green'],
  'recusado'   => ['label' => 'Recusado',    'classe' => 'badge-red'],
];
?>

<style>
  body { background: var(--fundo); }

  .cand-wrapper {
    max-width: 860px;
    margin: 2.5rem auto;
    padding: 0 5% 4rem;
  }

  .cand-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 10px;
  }

  .cand-header h1 { font-size: 22px; font-weight: 800; }
  .cand-header p  { font-size: 14px; color: var(--muted); margin-top: 2px; }

  /* Resumo */
  .resumo-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
  }

  .resumo-card {
    background: var(--branco);
    border: 1px solid var(--borda);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    text-align: center;
    box-shadow: var(--sombra);
  }

  .resumo-num   { font-size: 28px; font-weight: 800; color: var(--azul); }
  .resumo-label { font-size: 13px; color: var(--muted); margin-top: 4px; }

  /* Lista */
  .cand-lista { display: flex; flex-direction: column; gap: 1rem; }

  .cand-item {
    background: var(--branco);
    border: 1px solid var(--borda);
    border-radius: var(--radius-lg);
    padding: 1.25rem 1.5rem;
    box-shadow: var(--sombra);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: border-color 0.15s;
  }

  .cand-item:hover { border-color: var(--azul-claro); }

  .cand-icone {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: var(--azul-claro);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
  }

  .cand-info   { flex: 1; }
  .cand-titulo { font-size: 15px; font-weight: 700; color: var(--texto); margin-bottom: 2px; }
  .cand-emp    { font-size: 13px; color: var(--muted); margin-bottom: 6px; }
  .cand-meta   { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
  .cand-data   { font-size: 12px; color: var(--muted); }

  .cand-status { flex-shrink: 0; }

  .vazio {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--muted);
  }

  .vazio div  { font-size: 48px; margin-bottom: 1rem; }
  .vazio h3   { font-size: 18px; font-weight: 700; color: var(--texto); margin-bottom: 8px; }
  .vazio p    { font-size: 14px; margin-bottom: 1.5rem; }

  @media (max-width: 560px) {
    .resumo-grid { grid-template-columns: 1fr; }
    .cand-item   { flex-direction: column; align-items: flex-start; }
  }
</style>

<div class="cand-wrapper">
  <div class="cand-header">
    <div>
      <h1>Minhas Candidaturas</h1>
      <p>Acompanhe o status de todas as suas candidaturas</p>
    </div>
    <a href="vagas.php" class="btn btn-primary">+ Ver mais vagas</a>
  </div>

  <!-- Resumo -->
  <?php
    $total     = count($candidaturas);
    $analise   = count(array_filter($candidaturas, fn($c) => $c['status'] === 'em_analise'));
    $aprovados = count(array_filter($candidaturas, fn($c) => $c['status'] === 'aprovado'));
  ?>
  <div class="resumo-grid">
    <div class="resumo-card">
      <div class="resumo-num"><?= $total ?></div>
      <div class="resumo-label">Total de candidaturas</div>
    </div>
    <div class="resumo-card">
      <div class="resumo-num" style="color:var(--azul)"><?= $analise ?></div>
      <div class="resumo-label">Em análise</div>
    </div>
    <div class="resumo-card">
      <div class="resumo-num" style="color:var(--verde)"><?= $aprovados ?></div>
      <div class="resumo-label">Aprovadas</div>
    </div>
  </div>

  <!-- Lista -->
  <?php if (empty($candidaturas)): ?>
    <div class="card vazio">
      <div>📋</div>
      <h3>Nenhuma candidatura ainda</h3>
      <p>Você ainda não se candidatou a nenhuma vaga. Que tal começar agora?</p>
      <a href="vagas.php" class="btn btn-primary">Ver vagas disponíveis</a>
    </div>
  <?php else: ?>
    <div class="cand-lista">
      <?php foreach ($candidaturas as $c):
        $st = $statusMap[$c['status']];
      ?>
        <div class="cand-item">
          <div class="cand-icone">🏢</div>
          <div class="cand-info">
            <p class="cand-titulo"><?= htmlspecialchars($c['vaga']) ?></p>
            <p class="cand-emp"><?= htmlspecialchars($c['empresa']) ?></p>
            <div class="cand-meta">
              <span class="badge badge-gray"><?= htmlspecialchars($c['area']) ?></span>
              <span class="cand-data">Enviada em <?= htmlspecialchars($c['data']) ?></span>
            </div>
          </div>
          <div class="cand-status">
            <span class="badge <?= $st['classe'] ?>"><?= $st['label'] ?></span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php
include BASE_PATH . '/includes/footer.php';
?>

