<?php

require_once dirname(__DIR__) . '/config.php';
?>
<footer class="footer">
  <div class="footer-grid">
    <div class="footer-brand">
      <span class="logo-texto">Uni<span>ALFA</span> Estágios</span>
      <p>Conectando talentos às oportunidades da região de Umuarama e região.</p>
    </div>
    <div>
      <h4>Plataforma</h4>
      <div class="footer-links">
        <!-- Usa a constante BASE_URL para resolver o caminho absoluto do servidor -->
        <a href="<?= BASE_URL ?>/empresa/empresa.php">Painel da empresa</a>
        <a href="<?= BASE_URL ?>/aluno/vagas.php">Ver vagas</a>
        <a href="<?= BASE_URL ?>/aluno/cadastro.php">Cadastrar currículo</a>
        <a href="<?= BASE_URL ?>/index.php">Entrar</a>
      </div>
    </div>
    <div>
      <h4>UniALFA</h4>
      <div class="footer-links">
        <a href="#">Sobre a faculdade</a>
        <a href="#">Av. Paraná, 7327 — Umuarama/PR</a>
        <a href="tel:4436222500">(44) 3622-2500</a>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    © <?= date('Y') ?> Faculdade UniALFA · CNPJ 10.718.171/0001-04 · Todos os direitos reservados
  </div>
</footer>

</body>

</html>