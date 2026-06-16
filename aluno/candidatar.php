<?php
// ============================================
// aluno/candidatar.php
// Formulário de candidatura a uma vaga.
// Consome a API via CandidaturaController.
// ============================================

require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/src/Controllers/CandidaturaController.php';
require_once BASE_PATH . '/src/Controllers/VagaController.php';

// ── Protege a rota ──
// Só aluno logado acessa
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['perfil'] !== 'aluno') {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

$vagaId  = (int)($_GET['vaga_id'] ?? 0);
$sucesso = false;
$erro    = '';

// ── Busca a vaga real da API ──
// Em vez do mock, usa o VagaController
$vagaController = new VagaController();
$vaga = $vagaController->buscar($vagaId);

// Se vaga não existe, volta para lista
if (!$vaga) {
    header('Location: ' . BASE_URL . '/aluno/vagas.php');
    exit;
}

// ── Processa a candidatura ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CandidaturaController();
    $resultado  = $controller->candidatar();

    if (isset($resultado['erro'])) {
        $erro = $resultado['erro'];
    } else {
        $sucesso = true;
    }
}

$tituloPagina = 'Candidatura — Portal UniALFA';
$paginaAtiva  = 'vagas';
include BASE_PATH . '/includes/header.php';
?>

<style>
  body { background: var(--fundo); }

  .breadcrumb {
    background: var(--branco);
    border-bottom: 1px solid var(--borda);
    padding: 12px 5%;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--muted);
  }

  .breadcrumb a { color: var(--muted); transition: color 0.15s; }
  .breadcrumb a:hover { color: var(--texto); }

  /* Steps */
  .steps-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem 0;
    max-width: 380px;
    margin: 0 auto;
  }

  .step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    position: relative;
    flex: 1;
  }

  .step-item:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 13px;
    left: 58%;
    width: 84%;
    height: 2px;
    background: var(--borda);
    z-index: 0;
  }

  .step-item.done:not(:last-child)::after { background: var(--azul); }

  .step-circulo {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 2px solid var(--borda);
    background: var(--branco);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: 700;
    color: var(--muted);
    z-index: 1;
  }

  .step-item.done .step-circulo  { background: var(--azul); border-color: var(--azul); color: #fff; }
  .step-item.ativo .step-circulo { border-color: var(--azul); color: var(--azul); }
  .step-label { font-size: 11px; color: var(--muted); white-space: nowrap; }
  .step-item.ativo .step-label { color: var(--azul); font-weight: 600; }

  /* Layout */
  .cand-layout {
    display: flex;
    gap: 1.5rem;
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 5% 4rem;
    align-items: flex-start;
  }

  .col-form { flex: 1.4; }
  .col-vaga { flex: 1; }

  .card-titulo {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--muted);
    margin-bottom: 1rem;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--borda);
  }

  .vaga-sticky { position: sticky; top: 90px; }
  .vaga-nome   { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
  .vaga-emp    { font-size: 13px; color: var(--muted); margin-bottom: 12px; }
  .vaga-badges { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 14px; }

  .vaga-desc-txt {
    font-size: 13px;
    color: var(--muted);
    line-height: 1.65;
    border-top: 1px solid var(--borda);
    padding-top: 12px;
  }

  .info-box {
    display: flex;
    gap: 8px;
    background: var(--azul-claro);
    border-radius: var(--radius);
    padding: 12px;
    margin-top: 14px;
    font-size: 13px;
    color: var(--azul-escuro);
    line-height: 1.55;
  }

  /* Campo desabilitado */
  input:disabled {
    background: var(--fundo);
    color: var(--muted);
    cursor: not-allowed;
  }

  .termos {
    text-align: center;
    font-size: 12px;
    color: var(--muted);
    margin-top: 10px;
    line-height: 1.5;
  }

  .termos a { color: var(--azul); }

  /* Sucesso */
  .sucesso-box {
    max-width: 480px;
    margin: 4rem auto;
    text-align: center;
    padding: 2rem;
  }

  .check-circle {
    width: 72px; height: 72px; border-radius: 50%;
    background: #d1fae5;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 1.5rem; font-size: 32px;
  }

  .sucesso-box h2 { font-size: 22px; font-weight: 800; margin-bottom: 8px; }
  .sucesso-box p  { font-size: 15px; color: var(--muted); margin-bottom: 2rem; line-height: 1.6; }
  .sucesso-acoes  { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }

  @media (max-width: 700px) {
    .cand-layout { flex-direction: column-reverse; }
    .col-vaga { position: static; }
  }
</style>

<!-- Breadcrumb -->
<div class="breadcrumb">
  <a href="<?= BASE_URL ?>/aluno/vagas.php">← Vagas</a>
  <span>/</span>
  <span>Candidatura</span>
</div>

<?php if ($sucesso): ?>

  <!-- ── Tela de sucesso ── -->
  <div class="sucesso-box">
    <div class="check-circle">✓</div>
    <h2>Candidatura enviada!</h2>
    <p>
      Sua candidatura para <strong><?= htmlspecialchars($vaga->getTitulo()) ?></strong>
      foi recebida. Você será notificado quando houver uma atualização.
    </p>
    <div class="sucesso-acoes">
      <a href="<?= BASE_URL ?>/aluno/vagas.php" class="btn btn-primary">Ver mais vagas</a>
    </div>
  </div>

<?php else: ?>

  <!-- ── Steps ── -->
  <div class="steps-bar">
    <div class="step-item done">
      <div class="step-circulo">✓</div>
      <span class="step-label">Dados</span>
    </div>
    <div class="step-item ativo">
      <div class="step-circulo">2</div>
      <span class="step-label">Candidatura</span>
    </div>
    <div class="step-item">
      <div class="step-circulo">3</div>
      <span class="step-label">Confirmação</span>
    </div>
  </div>

  <!-- ── Erro ── -->
  <?php if ($erro): ?>
    <div class="alert alert-error" style="max-width:1000px;margin:0 auto 1rem;padding:0 5%">
      <?= htmlspecialchars($erro) ?>
    </div>
  <?php endif; ?>

  <div class="cand-layout">

    <!-- ── Formulário ── -->
    <div class="col-form">

      <!-- Dados do candidato — pré-preenchidos da sessão -->
      <div class="card" style="margin-bottom:1.25rem">
        <p class="card-titulo">Dados do candidato</p>

        <!-- Campo hidden com vaga_id para o controller -->
        <form method="POST" action="">
          <input type="hidden" name="vaga_id" value="<?= $vagaId ?>" />

          <div class="form-group">
            <label>Nome completo</label>
            <!-- Pré-preenchido da sessão e desabilitado — aluno não edita aqui -->
            <input type="text"
                   value="<?= htmlspecialchars($_SESSION['usuario']['nome']) ?>"
                   disabled />
          </div>

          <div class="form-group">
            <label>RA (Registro Acadêmico)</label>
            <!-- RA da sessão — vai para o controller via $_SESSION -->
            <input type="text"
                   value="<?= htmlspecialchars($_SESSION['usuario']['ra']) ?>"
                   disabled />
          </div>

          <div class="form-group">
            <label>Curso</label>
            <input type="text"
                   value="<?= htmlspecialchars($_SESSION['usuario']['curso'] ?? '') ?>"
                   disabled />
          </div>

          <!-- Botão de envio -->
          <button type="submit" class="btn btn-primary btn-lg btn-block"
                  style="margin-top:0.5rem">
            Confirmar candidatura
          </button>

        </form>

        <p class="termos">
          Ao enviar, você concorda com os
          <a href="#">termos de uso</a> do Portal UniALFA.
        </p>
      </div>
    </div>

    <!-- ── Card da vaga ── -->
    <div class="col-vaga">
      <div class="card vaga-sticky">
        <p class="card-titulo">Vaga selecionada</p>

        <!-- Usa getters do objeto Vaga — não mais array -->
        <p class="vaga-nome"><?= htmlspecialchars($vaga->getTitulo()) ?></p>
        <p class="vaga-emp">🏢 Empresa #<?= $vaga->getEmpresaId() ?></p>

        <div class="vaga-badges">
          <!-- bolsa e status são os únicos campos do banco -->
          <span class="badge badge-green"><?= $vaga->getBolsaFormatada() ?></span>
          <span class="badge <?= $vaga->getStatusBadge() ?>">
            <?= $vaga->getStatusLabel() ?>
          </span>
        </div>

        <p class="vaga-desc-txt">
          <?= htmlspecialchars($vaga->getDescricao()) ?>
        </p>

        <div class="info-box">
          ℹ️ <span>Após enviar, a empresa analisará seu perfil e você será notificado pelo portal.</span>
        </div>
      </div>
    </div>

  </div>

<?php endif; ?>

<?php include BASE_PATH . '/includes/footer.php'; ?>