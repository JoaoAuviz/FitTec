<?php
// public/partials/header.php
if (session_status() === PHP_SESSION_NONE) session_start();
$current = basename($_SERVER['PHP_SELF']);
?>
<nav class="sidebar">
  <div class="sidebar-brand">
    <div class="logo">Fit<span>Tec</span></div>
    <div class="subtitle">Treine. Registre. Evolua.</div>
  </div>

  <ul class="sidebar-menu">
    <li><a href="home.php" class="<?= $current=='home.php' ? 'active' : '' ?>">🏠 Início</a></li>
    <li><a href="treinos.php" class="<?= $current=='treinos.php' ? 'active' : '' ?>">💪 Treinos</a></li>
    <li><a href="historico.php" class="<?= $current=='historico.php' ? 'active' : '' ?>">📈 Histórico</a></li>
    <li><a href="perfil.php" class="<?= $current=='perfil.php' ? 'active' : '' ?>">👤 Perfil</a></li>
    <li><a href="configuracoes.php" class="<?= $current=='configuracoes.php' ? 'active' : '' ?>">⚙️ Configurações</a></li>
    <li><a href="logout.php">🚪 Sair</a></li>
  </ul>
</nav>

<header class="topbar">
  <div class="topbar-left">
    <button id="menu-toggle" class="menu-toggle">☰</button>
    <h1 class="app-title">FitTec</h1>
  </div>
  <div class="topbar-right">
    <?php if (!empty($_SESSION['usuario_nome'])): ?>
      <div class="user-info">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></div>
    <?php endif; ?>
  </div>
</header>
