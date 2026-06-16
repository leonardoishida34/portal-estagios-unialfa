<?php
require_once dirname(__DIR__) . '/config.php';
// dirname(__DIR__) = sobe de includes/ para hacktoon/ = raiz ✓
// e chega na raiz do projeto automaticamente
$paginaAtiva = $paginaAtiva ?? '';
$raiz        = $raiz        ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $tituloPagina ?? 'Portal de Estágios UniALFA' ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/portal-estagios-unialfa/assets/css/style.css" />
  <?php if (isset($cssExtra)): ?>
     <link rel="stylesheet" href="<?= $raiz ?>css/<?= $cssExtra ?>" />
  <?php endif; ?>
</head>
<body>

<header class="navbar">
  <a href="<?= BASE_URL ?>/portal-estagios-unialfa/index.php" class="navbar-logo">
    <span class="logo-texto">Uni<span>ALFA</span> Estágios</span>
  </a>

  <nav class="nav-links" id="nav-links">
      <a href="<?= BASE_URL ?>/portal-estagios-unialfa/aluno/vagas.php"        class="<?= $paginaAtiva==='vagas'       ?'ativo':'' ?>">Vagas</a>
      <a href="<?= BASE_URL ?>/portal-estagios-unialfa/aluno/candidaturas.php"  class="<?= $paginaAtiva==='candidaturas'?'ativo':'' ?>">Minhas Candidaturas</a>
      <a href="<?= BASE_URL ?>/portal-estagios-unialfa/aluno/cadastro.php"      class="<?= $paginaAtiva==='cadastro'    ?'ativo':'' ?>">Cadastrar Aluno</a>
      <a href="<?= BASE_URL ?>/portal-estagios-unialfa/empresa/empresa.php"       class="<?= $paginaAtiva==='empresa'     ?'ativo':'' ?>">Sou Empresa &#711;</a>
    </nav>
  <a href="<?= BASE_URL ?>/portal-estagios-unialfa/login.php" class="navbar-btn">
    Login
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
    </svg>
  </a>

  <button class="menu-toggle" onclick="document.getElementById('nav-links').classList.toggle('aberto')" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
</header>
