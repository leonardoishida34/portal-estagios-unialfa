<?php
$tituloPagina = 'Candidatura — Portal UniALFA';
$paginaAtiva  = 'vagas';
$raiz         = '../';
require_once __DIR__ . '/../config.php';
include BASE_PATH . '/includes/header.php';

$vagaId = (int)($_GET['vaga_id'] ?? 1);

// Mock da vaga — depois vem da API
$vagas = [
  1 => ['titulo'=>'Desenvolvedor(a) Web', 'empresa'=>'TechSul Sistemas', 'area'=>'Tecnologia', 'tipo'=>'Presencial', 'carga'=>'30h/sem', 'descricao'=>'Vaga voltada para alunos de TI. Conhecimentos em HTML, CSS e lógica de programação são diferenciais.', 'requisitos'=>'Cursando Tecnologia em Sistemas para Internet ou similar. Disponibilidade para trabalho presencial.'],
  2 => ['titulo'=>'Assistente Administrativo', 'empresa'=>'Grupo RB Comércio', 'area'=>'Administração', 'tipo'=>'Híbrido', 'carga'=>'20h/sem', 'descricao'=>'Apoio nas rotinas administrativas.', 'requisitos'=>'Cursando Administração.'],
];

$vaga = $vagas[$vagaId] ?? $vagas[1];
$sucesso = isset($_GET['sucesso']);
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
  .breadcrumb svg { width: 14px; height: 14px; }

  /* Steps */
  .steps-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem 0;
    max-width: 380px;
    margin: 0 auto;
    gap: 0;
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
  .step-item.ativo .step-label   { color: var(--azul); font-weight: 600; }

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

  .vaga-nome    { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
  .vaga-emp     { font-size: 13px; color: var(--muted); margin-bottom: 12px; }
  .vaga-badges  { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 14px; }
  .vaga-desc-txt { font-size: 13px; color: var(--muted); line-height: 1.65; border-top: 1px solid var(--borda); padding-top: 12px; }
  .vaga-req     { margin-top: 10px; font-size: 13px; color: var(--muted); line-height: 1.65; }
  .vaga-req strong { color: var(--texto); display: block; margin-bottom: 4px; }

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

.upload-area {
    border: 1.5px dashed var(--borda);
    border-radius: var(--radius);
    padding: 1.25rem;
    text-align: center;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    width: 100%;
  }

  .upload-area:hover { background: var(--fundo); border-color: var(--azul); }
  .upload-area input { display: none; }
  .upload-area p     { font-size: 13px; color: var(--muted); text-align: center; width: 100%; }
  .upload-area small { font-size: 11px; color: var(--muted); opacity: 0.7; text-align: center; }

  .termos { text-align: center; font-size: 12px; color: var(--muted); margin-top: 10px; line-height: 1.5; }
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
  <a href="vagas.php">← Vagas</a>
  <span>/</span>
  <span>Candidatura</span>
</div>

<?php if ($sucesso): ?>

  <div class="sucesso-box">
    <div class="check-circle">✓</div>
    <h2>Candidatura enviada!</h2>
    <p>Sua candidatura para <strong><?= htmlspecialchars($vaga['titulo']) ?></strong> foi recebida. Você será notificado quando houver uma atualização.</p>
    <div class="sucesso-acoes">
      <a href="candidaturas.php" class="btn btn-primary">Ver minhas candidaturas</a>
      <a href="vagas.php" class="btn btn-outline">Ver mais vagas</a>
    </div>
  </div>

<?php else: ?>

  <!-- Steps -->
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

  <div class="cand-layout">

    <!-- Formulário -->
    <div class="col-form">
      <div class="card" style="margin-bottom:1.25rem">
        <p class="card-titulo">Dados do candidato</p>
        <div class="form-group">
          <label>Nome completo</label>
          <input type="text" placeholder="Seu nome completo" />
        </div>
        <div style="display:flex;gap:1rem">
          <div class="form-group" style="flex:1">
            <label>RA</label>
            <input type="text" placeholder="Ex: 258450" />
          </div>
          <div class="form-group" style="flex:1">
            <label>Período</label>
            <select>
              <?php for ($i = 1; $i <= 8; $i++): ?>
                <option><?= $i ?>º período</option>
              <?php endfor; ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label>Curso</label>
          <select>
            <option>Tecnologia em Sistemas para Internet</option>
            <option>Administração</option>
            <option>Ciências Contábeis</option>
          </select>
        </div>
      </div>

      <div class="card">
        <p class="card-titulo">Carta de apresentação e currículo</p>
        <div class="form-group">
          <label>Carta de apresentação *</label>
          <textarea rows="5" placeholder="Fale sobre seus objetivos, habilidades e por que você tem interesse nesta vaga..."></textarea>
        </div>
        <label class="upload-area" for="curriculo">
          <input type="file" id="curriculo" accept=".pdf,.doc,.docx" onchange="mostrarArquivo(this)" />
          <div style="font-size:24px">📄</div>
          <p id="upload-texto">Clique para anexar seu currículo</p>
          <small>PDF, DOC ou DOCX · Máximo 5 MB</small>
        </label>

        <button class="btn btn-primary btn-lg btn-block" onclick="enviar()" style="margin-top:1rem">
          Enviar candidatura
        </button>
        <p class="termos">Ao enviar, você concorda com os <a href="#">termos de uso</a> do Portal UniALFA.</p>
      </div>
    </div>

    <!-- Card da vaga -->
    <div class="col-vaga">
      <div class="card vaga-sticky">
        <p class="card-titulo">Vaga selecionada</p>
        <p class="vaga-nome"><?= htmlspecialchars($vaga['titulo']) ?></p>
        <p class="vaga-emp">🏢 <?= htmlspecialchars($vaga['empresa']) ?></p>
        <div class="vaga-badges">
          <span class="badge badge-blue"><?= htmlspecialchars($vaga['area']) ?></span>
          <span class="badge badge-gray"><?= htmlspecialchars($vaga['tipo']) ?></span>
          <span class="badge badge-green"><?= htmlspecialchars($vaga['carga']) ?></span>
        </div>
        <p class="vaga-desc-txt"><?= htmlspecialchars($vaga['descricao']) ?></p>
        <div class="vaga-req">
          <strong>Requisitos</strong>
          <?= htmlspecialchars($vaga['requisitos']) ?>
        </div>
        <div class="info-box">
          ℹ️ <span>Após enviar, a empresa analisará seu perfil e você será notificado pelo portal.</span>
        </div>
      </div>
    </div>

  </div>

<?php endif; ?>

<script>
function mostrarArquivo(input) {
  if (input.files.length > 0) {
    document.getElementById('upload-texto').textContent = '📎 ' + input.files[0].name;
  }
}

function enviar() {
  // Aqui vai o POST para a API futuramente
  window.location.href = 'candidatar.php?vaga_id=<?= $vagaId ?>&sucesso=1';
}
</script>

<?php
include BASE_PATH . '/includes/footer.php';
?>