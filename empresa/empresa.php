<?php
session_start();
$tituloPagina = 'Painel da Empresa — Portal UniALFA';
$paginaAtiva  = 'empresa';
$raiz         = '../';
require_once __DIR__ . '/../config.php';
include BASE_PATH . '/includes/header.php';
// ── Proteção da rota ──
// Verifica se tem alguém logado E se é empresa
// Se não, redireciona para o login
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'empresa') {
    header('Location: ' . BASE_URL . '/login.php?erro=acesso-restrito');
    exit;
    }

// Mock — depois vem da API
$vagas = [
  ['id'=>1, 'titulo'=>'Desenvolvedor(a) Web', 'area'=>'Tecnologia', 'tipo'=>'Presencial', 'carga'=>'30h/sem', 'candidatos'=>7, 'status'=>'aberta', 'criada'=>'01/06/2025'],
  ['id'=>2, 'titulo'=>'Suporte de TI', 'area'=>'Tecnologia', 'tipo'=>'Presencial', 'carga'=>'30h/sem', 'candidatos'=>3, 'status'=>'aberta', 'criada'=>'28/05/2025'],
  ['id'=>3, 'titulo'=>'Designer Gráfico Jr.', 'area'=>'Design', 'tipo'=>'Remoto', 'carga'=>'20h/sem', 'candidatos'=>12, 'status'=>'encerrada', 'criada'=>'10/05/2025'],
];

$aba = $_GET['aba'] ?? 'vagas';
$modalAberto = isset($_GET['nova']);
?>

<style>
  body { background: var(--fundo); }

  .empresa-layout {
    display: flex;
    max-width: 1100px;
    margin: 2rem auto;
    padding: 0 5% 4rem;
    gap: 2rem;
    align-items: flex-start;
  }

  /* Sidebar */
  .sidebar {
    width: 220px;
    flex-shrink: 0;
    position: sticky;
    top: 90px;
  }

  .empresa-perfil {
    background: var(--branco);
    border: 1px solid var(--borda);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    text-align: center;
    box-shadow: var(--sombra);
    margin-bottom: 1rem;
  }

  .emp-avatar {
    width: 60px; height: 60px; border-radius: 50%;
    background: var(--azul); color: #fff;
    font-size: 22px; font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 10px;
  }

  .emp-nome   { font-size: 15px; font-weight: 700; }
  .emp-cidade { font-size: 12px; color: var(--muted); margin-top: 3px; }
  .emp-status { margin-top: 8px; }

  .sidebar-nav {
    background: var(--branco);
    border: 1px solid var(--borda);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--sombra);
  }

  .side-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 16px;
    font-size: 14px;
    color: var(--muted);
    font-weight: 500;
    border-bottom: 1px solid var(--borda);
    transition: background 0.15s, color 0.15s;
    text-decoration: none;
  }

  .side-link:last-child { border-bottom: none; }
  .side-link:hover { background: var(--fundo); color: var(--texto); }
  .side-link.ativo { color: var(--azul); background: var(--azul-claro); font-weight: 600; }

  /* Main */
  .empresa-main { flex: 1; }

  .main-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    gap: 10px;
  }

  .main-header h2 { font-size: 20px; font-weight: 800; }

  /* Stats */
  .stats-mini {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
  }

  .stat-mini-card {
    background: var(--branco);
    border: 1px solid var(--borda);
    border-radius: var(--radius-lg);
    padding: 1rem;
    text-align: center;
    box-shadow: var(--sombra);
  }

  .sm-num   { font-size: 26px; font-weight: 800; color: var(--azul); }
  .sm-label { font-size: 12px; color: var(--muted); margin-top: 2px; }

  /* Tabela vagas */
  .vagas-table {
    background: var(--branco);
    border: 1px solid var(--borda);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--sombra);
  }

  .table-header {
    display: grid;
    grid-template-columns: 2fr 1fr 95px 130px 80px 120px;
    padding: 12px 14px;
    background: var(--fundo);
    border-bottom: 1px solid var(--borda);
    font-size: 12px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
  }

  .table-row {
    display: grid;
    /* Aumentamos de 80px para 140px (Candidatos) e de 90px para 120px (Status) */
    grid-template-columns: 2fr 1fr 1fr 120px 80px 120px;
    padding: 14px 16px;
    border-bottom: 1px solid var(--borda);
    align-items: center;
    transition: background 0.15s;
    gap: 10px; /* Adiciona um respiro extra entre as colunas, caso precisem */
}

  .table-row:last-child { border-bottom: none; }
  .table-row:hover { background: var(--fundo); }

  .table-row .vaga-nome-t { font-size: 14px; font-weight: 600; }
  .table-row .vaga-area-t { font-size: 13px; color: var(--muted); }
  .table-row span { font-size: 13px; }

  .acoes-btn {
    display: flex;
    gap: 6px;
    align-items: center;
  }

  .btn-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    border: 1px solid var(--borda);
    background: var(--branco);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.15s;
  }

  .btn-icon:hover { background: var(--fundo); }

  /* Modal nova vaga */
  .modal-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 200;
    align-items: center;
    justify-content: center;
    padding: 1rem;
  }

  .modal-overlay.aberto { display: flex; }

  .modal {
    background: var(--branco);
    border-radius: var(--radius-lg);
    padding: 2rem;
    width: 100%;
    max-width: 560px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
  }

  .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
  }

  .modal-header h3 { font-size: 18px; font-weight: 700; }

  .btn-fechar {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: 1px solid var(--borda);
    background: var(--branco);
    font-size: 18px;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: var(--muted);
    transition: background 0.15s;
  }

  .btn-fechar:hover { background: var(--fundo); }

  .row-2 { display: flex; gap: 1rem; }
  .row-2 .form-group { flex: 1; }

  @media (max-width: 900px) {
    .empresa-layout { flex-direction: column; }
    .sidebar { width: 100%; position: static; }
    .table-header, .table-row { grid-template-columns: 2fr 1fr 80px 120px; }
    .table-header > *:nth-child(3),
    .table-row    > *:nth-child(3) { display: none; }
    .stats-mini { grid-template-columns: 1fr; }
  }
</style>

<div class="empresa-layout">

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="empresa-perfil">
      <div class="emp-avatar">TS</div>
      <p class="emp-nome">TechSul Sistemas</p>
      <p class="emp-cidade">Umuarama, PR</p>
      <div class="emp-status">
        <span class="badge badge-green"></span>
      </div>
    </div>

    <nav class="sidebar-nav">
      <a href="?aba=vagas"       class="side-link <?= $aba==='vagas'      ?'ativo':'' ?>">📋 Minhas Vagas</a>
      <a href="?aba=candidatos"  class="side-link <?= $aba==='candidatos' ?'ativo':'' ?>">👥 Candidatos</a>
      <a href="?aba=perfil"      class="side-link <?= $aba==='perfil'     ?'ativo':'' ?>">⚙️ Perfil da empresa</a>
      <a href="../logout.php"        class="side-link">🚪 Sair</a>
    </nav>
  </aside>

  <!-- Main -->
  <main class="empresa-main">

    <div class="main-header">
      <h2>
        <?php
          echo match($aba) {
            'candidatos' => 'Candidatos recebidos',
            'perfil'     => 'Perfil da empresa',
            default      => 'Minhas Vagas',
          };
        ?>
      </h2>
      <?php if ($aba === 'vagas'): ?>
        <button class="btn btn-primary" onclick="abrirModal()">+ Nova vaga</button>
      <?php endif; ?>
    </div>

    <?php if ($aba === 'vagas'): ?>

      <!-- Stats -->
      <div class="stats-mini">
        <div class="stat-mini-card">
          <div class="sm-num"><?= count($vagas) ?></div>
          <div class="sm-label">Total de vagas</div>
        </div>
        <div class="stat-mini-card">
          <div class="sm-num"><?= array_sum(array_column($vagas, 'candidatos')) ?></div>
          <div class="sm-label">Candidaturas recebidas</div>
        </div>
        <div class="stat-mini-card">
          <div class="sm-num"><?= count(array_filter($vagas, fn($v)=>$v['status']==='aberta')) ?></div>
          <div class="sm-label">Vagas abertas</div>
        </div>
      </div>

      <!-- Tabela -->
      <div class="vagas-table">
        <div class="table-header">
          <span>Vaga</span>
          <span>Tipo</span>
          <span>Criada em</span>
          <span>Candidatos</span>
          <span>Status</span>
          <span>Ações</span>
        </div>

        <?php foreach ($vagas as $v): ?>
          <div class="table-row">
            <div>
              <p class="vaga-nome-t"><?= htmlspecialchars($v['titulo']) ?></p>
              <p class="vaga-area-t"><?= htmlspecialchars($v['area']) ?></p>
            </div>
            <span><?= htmlspecialchars($v['tipo']) ?></span>
            <span><?= htmlspecialchars($v['criada']) ?></span>
            <span>
              <span class="badge badge-blue"><?= $v['candidatos'] ?> candidatos</span>
            </span>
            <span>
              <span class="badge <?= $v['status']==='aberta' ? 'badge-green' : 'badge-gray' ?>">
                <?= $v['status'] === 'aberta' ? 'Aberta' : 'Encerrada' ?>
              </span>
            </span>
            <div class="acoes-btn">
              <button class="btn-icon" title="Ver candidatos" onclick="alert('Ver candidatos da vaga <?= $v['id'] ?>')">👥</button>
              <button class="btn-icon" title="Editar vaga" onclick="alert('Editar vaga <?= $v['id'] ?>')">✏️</button>
              <button class="btn-icon" title="Excluir vaga" onclick="confirmarExclusao(<?= $v['id'] ?>)">🗑️</button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

    <?php elseif ($aba === 'candidatos'): ?>

      <div class="card">
        <p style="color:var(--muted);font-size:14px">
          Selecione uma vaga na aba <strong>Minhas Vagas</strong> e clique no botão 👥 para ver os candidatos daquela vaga.
        </p>
      </div>

    <?php elseif ($aba === 'perfil'): ?>

      <div class="card">
        <p class="card-titulo" style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:var(--muted);margin-bottom:1rem;padding-bottom:8px;border-bottom:1px solid var(--borda)">
          Dados da empresa
        </p>
        <div class="form-group"><label>Nome da empresa</label><input type="text" value="TechSul Sistemas" /></div>
        <div class="form-group"><label>CNPJ</label><input type="text" value="12.345.678/0001-99" /></div>
        <div class="form-group"><label>Cidade</label><input type="text" value="Umuarama, PR" /></div>
        <div class="form-group"><label>E-mail de contato</label><input type="email" value="contato@techsul.com.br" /></div>
        <div class="form-group"><label>Telefone</label><input type="tel" value="(44) 99999-0000" /></div>
        <div class="form-group"><label>Descrição da empresa</label><textarea rows="4">Empresa de desenvolvimento de software localizada em Umuarama-PR.</textarea></div>
        <button class="btn btn-primary" onclick="alert('Perfil salvo!')">Salvar alterações</button>
      </div>

    <?php endif; ?>

  </main>
</div>

<!-- Modal nova vaga -->
<div class="modal-overlay" id="modal" onclick="fecharModalFora(event)">
  <div class="modal">
    <div class="modal-header">
      <h3>Nova vaga de estágio</h3>
      <button class="btn-fechar" onclick="fecharModal()">×</button>
    </div>

    <div class="form-group">
      <label>Título da vaga *</label>
      <input type="text" placeholder="Ex: Desenvolvedor(a) Web" />
    </div>

    <div class="row-2">
      <div class="form-group">
        <label>Área *</label>
        <select>
          <option>Tecnologia</option>
          <option>Administração</option>
          <option>Design</option>
          <option>Contabilidade</option>
          <option>Recursos Humanos</option>
        </select>
      </div>
      <div class="form-group">
        <label>Tipo *</label>
        <select>
          <option>Presencial</option>
          <option>Remoto</option>
          <option>Híbrido</option>
        </select>
      </div>
    </div>

    <div class="form-group">
      <label>Carga horária *</label>
      <select>
        <option>20h/semana</option>
        <option>30h/semana</option>
      </select>
    </div>

    <div class="form-group">
      <label>Descrição da vaga *</label>
      <textarea rows="3" placeholder="Descreva as atividades que o estagiário irá desempenhar..."></textarea>
    </div>

    <div class="form-group">
      <label>Requisitos</label>
      <textarea rows="2" placeholder="Ex: Cursando Tecnologia em Sistemas para Internet..."></textarea>
    </div>

    <div style="display:flex;gap:10px;margin-top:.5rem">
      <button class="btn btn-outline" style="flex:1" onclick="fecharModal()">Cancelar</button>
      <button class="btn btn-primary" style="flex:1" onclick="salvarVaga()">Publicar vaga</button>
    </div>
  </div>
</div>

<script>
function abrirModal() {
  const modal = document.getElementById('modal');
  if (modal) modal.classList.add('aberto');
}

function fecharModal() {
  const modal = document.getElementById('modal');
  if (modal) modal.classList.remove('aberto');
}

function fecharModalFora(e) {
  if (e.target === document.getElementById('modal')) fecharModal();
}

function salvarVaga() {
  alert('Vaga publicada! Aqui vai conectar com a API.');
  fecharModal();
}

function confirmarExclusao(id) {
  if (confirm('Tem certeza que deseja excluir esta vaga?')) {
    alert('Vaga ' + id + ' excluída! Aqui vai conectar com a API.');
  }
}

</script>
 
<?php 
include BASE_PATH . '/includes/footer.php';
?>