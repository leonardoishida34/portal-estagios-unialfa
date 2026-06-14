<?php
// ============================================
// empresa/cadastro.php
// Tela de cadastro da empresa no portal.
// Após o cadastro, fica com status 'pendente'
// até ser aprovada pelo Back Office Java da UniALFA.
// ============================================

require_once __DIR__ . '/../config.php';
require_once BASE_PATH . '/src/Controllers/EmpresaController.php';

$erro    = '';
$sucesso = false;

// Processa o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Valida se as senhas coincidem antes de chamar o controller
    $senha  = trim($_POST['senha']  ?? '');
    $senha2 = trim($_POST['senha2'] ?? '');

    if ($senha !== $senha2) {
        $erro = 'As senhas não coincidem.';
    } elseif (strlen($senha) < 8) {
        $erro = 'A senha deve ter no mínimo 8 caracteres.';
    } else {
        // Chama o controller que chama o service que chama a API
        $controller = new EmpresaController();
        $resultado  = $controller->cadastrar();

        if (isset($resultado['erro'])) {
            $erro = $resultado['erro'];
        } else {
            // Cadastro feito! Mostra tela de sucesso
            $sucesso = true;
        }
    }
}

$tituloPagina = 'Cadastro de Empresa — Portal UniALFA';
$paginaAtiva  = '';
include BASE_PATH . '/includes/header.php';
?>

<style>
  body { background: var(--fundo); }

  /* ── Hero topo ── */
  .cad-hero {
    background: linear-gradient(135deg, var(--azul) 0%, var(--verde) 100%);
    padding: 2.5rem 5%;
    text-align: center;
    color: #fff;
  }

  .cad-hero h1 { font-size: 28px; font-weight: 800; margin-bottom: 6px; }
  .cad-hero p  { font-size: 15px; opacity: 0.85; }

  /* ── Wrapper ── */
  .cad-wrapper {
    max-width: 680px;
    margin: -1.5rem auto 4rem;
    padding: 0 1.5rem;
  }

  /* ── Card principal ── */
  .cad-card {
    background: var(--branco);
    border: 1px solid var(--borda);
    border-radius: var(--radius-lg);
    padding: 2rem;
    box-shadow: 0 8px 32px rgba(0,0,0,0.08);
  }

  /* ── Título de seção ── */
  .secao-titulo {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: var(--muted);
    margin-bottom: 1rem;
    padding-bottom: 8px;
    border-bottom: 1px solid var(--borda);
    margin-top: 1.5rem;
  }

  .secao-titulo:first-child { margin-top: 0; }

  /* ── Linha com 2 campos ── */
  .row-2 { display: flex; gap: 1rem; }
  .row-2 .form-group { flex: 1; }

  /* ── Aviso de aprovação ── */
  .aviso-aprovacao {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    background: #fefce8;
    border: 1px solid #fde047;
    border-radius: var(--radius);
    padding: 14px;
    margin-bottom: 1.5rem;
    font-size: 13px;
    color: #713f12;
    line-height: 1.6;
  }

  .aviso-aprovacao .aviso-icon { font-size: 18px; flex-shrink: 0; }

  /* ── Força da senha ── */
  .senha-forca {
    height: 4px;
    border-radius: 2px;
    background: var(--borda);
    margin-top: 8px;
    overflow: hidden;
  }

  .senha-forca-bar {
    height: 100%;
    border-radius: 2px;
    width: 0%;
    transition: width 0.3s, background 0.3s;
  }

  .senha-hint {
    font-size: 11px;
    color: var(--muted);
    margin-top: 4px;
  }

  /* ── Termos ── */
  .termos-check {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 1.25rem;
    font-size: 13px;
    color: var(--muted);
    line-height: 1.5;
  }

  .termos-check input { margin-top: 2px; flex-shrink: 0; cursor: pointer; }
  .termos-check a { color: var(--azul); }

  /* ── Rodapé do form ── */
  .form-rodape {
    text-align: center;
    font-size: 13px;
    color: var(--muted);
    margin-top: 1.25rem;
  }

  .form-rodape a { color: var(--azul); font-weight: 500; }

  /* ── Tela de sucesso ── */
  .sucesso-box {
    text-align: center;
    padding: 1rem 0;
  }

  .sucesso-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #d1fae5;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 36px;
  }

  .sucesso-box h2 { font-size: 22px; font-weight: 800; margin-bottom: 10px; }
  .sucesso-box p  { font-size: 15px; color: var(--muted); line-height: 1.65; margin-bottom: 1.5rem; max-width: 420px; margin-left: auto; margin-right: auto; }

  .sucesso-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fefce8;
    color: #713f12;
    border: 1px solid #fde047;
    padding: 8px 16px;
    border-radius: 50px;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 1.5rem;
  }

  .sucesso-acoes { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }

  @media (max-width: 560px) { .row-2 { flex-direction: column; } }
</style>

<!-- Hero -->
<div class="cad-hero">
  <h1>Cadastrar minha empresa</h1>
  <p>Publique vagas e encontre os melhores talentos da UniALFA</p>
</div>

<div class="cad-wrapper">
  <div class="cad-card">

    <?php if ($sucesso): ?>

      <!-- ── Tela de sucesso ── -->
      <div class="sucesso-box">
        <div class="sucesso-circle">🏢</div>
        <h2>Cadastro enviado!</h2>
        <p>Recebemos os dados da sua empresa. Nossa equipe irá analisar e aprovar o cadastro em até <strong>2 dias úteis</strong>.</p>
        <div class="sucesso-badge">
          ⏳ Aguardando aprovação da UniALFA
        </div>
        <div class="sucesso-acoes">
          <a href="<?= BASE_URL ?>/index.php" class="btn btn-primary">Ir para o login</a>
          <a href="<?= BASE_URL ?>/aluno/vagas.php" class="btn btn-outline">Ver vagas disponíveis</a>
        </div>
      </div>

    <?php else: ?>

      <!-- ── Aviso de aprovação ── -->
      <div class="aviso-aprovacao">
        <span class="aviso-icon">⚠️</span>
        <span>Após o cadastro, sua empresa ficará <strong>pendente de aprovação</strong> pela equipe da UniALFA. Você receberá um e-mail quando for aprovada e poderá publicar vagas.</span>
      </div>

      <!-- ── Mensagem de erro ── -->
      <?php if ($erro): ?>
        <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
      <?php endif; ?>

      <form method="POST" action="" novalidate>

        <!-- Dados da empresa -->
        <p class="secao-titulo">Dados da empresa</p>

        <div class="form-group">
          <label>Nome da empresa *</label>
          <input type="text" name="nome"
                 placeholder="Ex: TechSul Sistemas Ltda"
                 value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
                 required />
        </div>

        <div class="row-2">
          <div class="form-group">
            <label>CNPJ *</label>
            <input type="text" name="cnpj"
                   placeholder="00.000.000/0001-00"
                   value="<?= htmlspecialchars($_POST['cnpj'] ?? '') ?>"
                   maxlength="18"
                   required />
          </div>
          <div class="form-group">
            <label>Telefone *</label>
            <input type="tel" name="telefone"
                   placeholder="(44) 99999-0000"
                   value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>"
                   required />
          </div>
        </div>

        <div class="row-2">
          <div class="form-group">
            <label>Cidade *</label>
            <input type="text" name="cidade"
                   placeholder="Ex: Umuarama"
                   value="<?= htmlspecialchars($_POST['cidade'] ?? '') ?>"
                   required />
          </div>
          <div class="form-group">
            <label>Estado *</label>
            <select name="estado">
              <option value="">Selecione...</option>
              <option value="PR" <?= ($_POST['estado']??'')==='PR'?'selected':'' ?>>Paraná</option>
              <option value="SP" <?= ($_POST['estado']??'')==='SP'?'selected':'' ?>>São Paulo</option>
              <option value="SC" <?= ($_POST['estado']??'')==='SC'?'selected':'' ?>>Santa Catarina</option>
              <option value="MS" <?= ($_POST['estado']??'')==='MS'?'selected':'' ?>>Mato Grosso do Sul</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label>Área de atuação *</label>
          <select name="area">
            <option value="">Selecione...</option>
            <option value="Tecnologia">Tecnologia</option>
            <option value="Administração">Administração</option>
            <option value="Contabilidade">Contabilidade</option>
            <option value="Saúde">Saúde</option>
            <option value="Educação">Educação</option>
            <option value="Comércio">Comércio</option>
            <option value="Indústria">Indústria</option>
            <option value="Outro">Outro</option>
          </select>
        </div>

        <div class="form-group">
          <label>Descrição da empresa</label>
          <textarea name="descricao" rows="3"
                    placeholder="Conte um pouco sobre sua empresa, missão e cultura..."><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>
        </div>

        <!-- Dados de acesso -->
        <p class="secao-titulo">Dados de acesso</p>

        <div class="form-group">
          <label>E-mail corporativo *</label>
          <input type="email" name="email"
                 placeholder="contato@suaempresa.com.br"
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                 required />
        </div>

        <div class="row-2">
          <div class="form-group">
            <label>Senha *</label>
            <input type="password" name="senha" id="senha"
                   placeholder="Mínimo 8 caracteres"
                   oninput="verificarSenha(this.value)"
                   required />
            <div class="senha-forca">
              <div class="senha-forca-bar" id="forca-bar"></div>
            </div>
            <p class="senha-hint" id="forca-texto">Digite uma senha</p>
          </div>
          <div class="form-group">
            <label>Confirmar senha *</label>
            <input type="password" name="senha2" id="senha2"
                   placeholder="Repita a senha"
                   required />
          </div>
        </div>

        <!-- Termos -->
        <div class="termos-check">
          <input type="checkbox" id="termos" required />
          <label for="termos">
            Li e concordo com os <a href="#">termos de uso</a> e a
            <a href="#">política de privacidade</a> do Portal de Estágios UniALFA.
          </label>
        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block">
          Cadastrar empresa
        </button>

      </form>

      <p class="form-rodape">
        Já tem cadastro? <a href="<?= BASE_URL ?>/index.php">Fazer login</a>
      </p>

    <?php endif; ?>

  </div>
</div>

<script>
// ── Máscara do CNPJ ──
document.querySelector('input[name="cnpj"]').addEventListener('input', function () {
  let v = this.value.replace(/\D/g, '').substring(0, 14);
  v = v.replace(/^(\d{2})(\d)/, '$1.$2');
  v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
  v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
  v = v.replace(/(\d{4})(\d)/, '$1-$2');
  this.value = v;
});

// ── Máscara do telefone ──
document.querySelector('input[name="telefone"]').addEventListener('input', function () {
  let v = this.value.replace(/\D/g, '').substring(0, 11);
  v = v.replace(/^(\d{2})(\d)/, '($1) $2');
  v = v.replace(/(\d{5})(\d)/, '$1-$2');
  this.value = v;
});

// ── Indicador de força da senha ──
function verificarSenha(senha) {
  const bar   = document.getElementById('forca-bar');
  const texto = document.getElementById('forca-texto');

  let forca = 0;
  if (senha.length >= 8)          forca++;
  if (/[A-Z]/.test(senha))        forca++;
  if (/[0-9]/.test(senha))        forca++;
  if (/[^A-Za-z0-9]/.test(senha)) forca++;

  const config = {
    0: { width: '0%',   cor: '',        label: 'Digite uma senha' },
    1: { width: '25%',  cor: '#ef4444', label: 'Senha fraca' },
    2: { width: '50%',  cor: '#f97316', label: 'Senha razoável' },
    3: { width: '75%',  cor: '#eab308', label: 'Senha boa' },
    4: { width: '100%', cor: '#22c55e', label: 'Senha forte ✓' },
  };

  bar.style.width      = config[forca].width;
  bar.style.background = config[forca].cor;
  texto.textContent    = config[forca].label;
}

// ── Validação antes de enviar ──
document.querySelector('form').addEventListener('submit', function (e) {
  const senha  = document.getElementById('senha').value;
  const senha2 = document.getElementById('senha2').value;
  const termos = document.getElementById('termos').checked;

  if (!termos) {
    e.preventDefault();
    alert('Você precisa aceitar os termos de uso para continuar.');
    return;
  }

  if (senha !== senha2) {
    e.preventDefault();
    alert('As senhas não coincidem.');
    return;
  }

  if (senha.length < 8) {
    e.preventDefault();
    alert('A senha deve ter no mínimo 8 caracteres.');
  }
});
</script>

<?php include BASE_PATH . '/includes/footer.php'; ?>
