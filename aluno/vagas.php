<?php
session_start();

$tituloPagina = 'Vagas — Portal UniALFA';
$paginaAtiva  = 'vagas';
$raiz         = '../';
require_once __DIR__ . '/../config.php';
include BASE_PATH . '/includes/header.php';
// Verifica se tem alguém logado E se é aluno
// Se não, redireciona para o login
//if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'aluno') {
  //  header('Location: ' . BASE_URL . '/portal-estagios-unialfa/login.php?erro=acesso-restrito');
  //  exit;
  //  }

// Dados mockados — depois virão da API
  $vagas = [
['id' => 1, 'titulo' => 'Estágio PHP', 'empresa' => 'Alfa', 'area' => 'TI', 'tipo' => 'Remoto', 'carga' => '30h', 'descricao' => 'Teste']
  ];
?>

<style>
  .vagas-layout {
    max-width: 1100px;
    margin: 2.5rem auto;
    padding: 0 5% 4rem;
    display: flex;
    gap: 2rem;
    align-items: flex-start;
  }

  /* ── Filtros ── */
  .filtros {
    width: 240px;
    flex-shrink: 0;
    position: sticky;
    top: 90px;
  }

  .filtro-card {
    background: var(--branco);
    border: 1px solid var(--borda);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    box-shadow: var(--sombra);
  }

  .filtro-titulo {
    font-size: 13px;
    font-weight: 600;
    color: var(--texto);
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--borda);
  }

  .filtro-grupo { margin-bottom: 1.25rem; }
  .filtro-grupo label { font-size: 12px; font-weight: 500; color: var(--muted); margin-bottom: 6px; display: block; }

  .filtro-grupo select,
  .filtro-grupo input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid var(--borda);
    border-radius: var(--radius);
    font-size: 13px;
    color: var(--texto);
    font-family: inherit;
    outline: none;
    background: var(--branco);
  }

  .filtro-grupo select:focus,
  .filtro-grupo input:focus { border-color: var(--azul); }

  /* ── Lista de vagas ── */
  .vagas-main { flex: 1; }

  .vagas-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
    gap: 8px;
  }

  .vagas-header h2 { font-size: 18px; font-weight: 700; }
  .vagas-header span { font-size: 14px; color: var(--muted); }

  .busca-input {
    width: 100%;
    padding: 11px 14px 11px 38px;
    border: 1px solid var(--borda);
    border-radius: var(--radius);
    font-size: 14px;
    font-family: inherit;
    outline: none;
    margin-bottom: 1.25rem;
    background: var(--branco) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' viewBox='0 0 24 24' stroke='%236b7280' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='M21 21l-4.35-4.35'/%3E%3C/svg%3E") no-repeat 12px center;
    transition: border-color 0.15s, box-shadow 0.15s;
  }

  .busca-input:focus { border-color: var(--azul); box-shadow: 0 0 0 3px rgba(26,86,219,0.1); }

  .vagas-grid { display: flex; flex-direction: column; gap: 1rem; }

  .vaga-card {
    background: var(--branco);
    border: 1px solid var(--borda);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    box-shadow: var(--sombra);
    transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
    display: flex;
    gap: 1rem;
    align-items: flex-start;
  }

  .vaga-card:hover {
    box-shadow: 0 8px 32px rgba(26,86,219,0.1);
    border-color: var(--azul-claro);
    transform: translateY(-2px);
  }

  .vaga-logo {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--azul-claro);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
  }

  .vaga-info { flex: 1; }

  .vaga-titulo {
    font-size: 16px;
    font-weight: 700;
    color: var(--texto);
    margin-bottom: 3px;
  }

  .vaga-empresa {
    font-size: 13px;
    color: var(--muted);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .vaga-badges { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 10px; }

  .vaga-desc {
    font-size: 13px;
    color: var(--muted);
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .vaga-acoes {
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex-shrink: 0;
    align-items: flex-end;
  }

  .vaga-acoes .btn { padding: 9px 20px; font-size: 13px; white-space: nowrap; }

  @media (max-width: 768px) {
    .vagas-layout { flex-direction: column; }
    .filtros { width: 100%; position: static; }
    .vaga-card { flex-direction: column; }
    .vaga-acoes { flex-direction: row; align-items: center; }
  }
</style>

<div class="page-hero">
  <h1>Vagas de Estágio</h1>
  <p>Encontre a oportunidade ideal para dar o primeiro passo na sua carreira</p>
</div>

<div class="vagas-layout">

  <!-- Filtros -->
  <aside class="filtros">
    <div class="filtro-card">
      <p class="filtro-titulo">🔍 Filtrar vagas</p>

      <div class="filtro-grupo">
        <label>Área</label>
        <select>
          <option>Todas as áreas</option>
          <option>Tecnologia</option>
          <option>Administração</option>
          <option>Contabilidade</option>
          <option>Design</option>
          <option>Recursos Humanos</option>
        </select>
      </div>

      <div class="filtro-grupo">
        <label>Tipo</label>
        <select>
          <option>Qualquer tipo</option>
          <option>Presencial</option>
          <option>Remoto</option>
          <option>Híbrido</option>
        </select>
      </div>

      <div class="filtro-grupo">
        <label>Carga horária</label>
        <select>
          <option>Qualquer</option>
          <option>20h/sem</option>
          <option>30h/sem</option>
        </select>
      </div>

      <button class="btn btn-primary btn-block" style="font-size:13px;padding:10px">
        Aplicar filtros
      </button>
    </div>
  </aside>

  <!-- Lista -->
  <main class="vagas-main">
    <div class="vagas-header">
      <h2>Vagas disponíveis</h2>
      <span><?= count($vagas) ?> encontradas</span>
    </div>

    <input class="busca-input" type="text" placeholder="Buscar por cargo, empresa ou área..." />

    <div class="vagas-grid">
      <?php foreach ($vagas as $v): ?>
        <div class="vaga-card">
          <div class="vaga-logo">🏢</div>

          <div class="vaga-info">
            <p class="vaga-titulo"><?= htmlspecialchars($v['titulo']) ?></p>
            <p class="vaga-empresa">
              <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 21h18M9 21V7l6-4v18M9 10h6M9 14h6"/></svg>
              <?= htmlspecialchars($v['empresa']) ?>
            </p>
            <div class="vaga-badges">
              <span class="badge badge-blue"><?= htmlspecialchars($v['area']) ?></span>
              <span class="badge badge-gray"><?= htmlspecialchars($v['tipo']) ?></span>
              <span class="badge badge-green"><?= htmlspecialchars($v['carga']) ?></span>
            </div>
            <p class="vaga-desc"><?= htmlspecialchars($v['descricao']) ?></p>
          </div>

          <div class="vaga-acoes">
            <a href="<?= BASE_URL ?>/portal-estagios-unialfa/aluno/candidatar.php?vaga_id=<?= $v['id'] ?>" class="btn btn-primary">Candidatar-se</a>
            <button class="btn btn-outline" style="padding:9px 20px;font-size:13px" onclick="alert('Ver detalhes — em breve!')">Ver detalhes</button>
          </div>
        </div>
      <?php endforeach; ?>''
    </div>
  </main>

</div>

<?php
include BASE_PATH . '/includes/footer.php';
?>