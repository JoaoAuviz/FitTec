<?php
session_start();
if (empty($_SESSION['usuario_id'])) { header('Location: index.php'); exit; }
require_once __DIR__ . '/../config/db_connect.php';
$uid = intval($_SESSION['usuario_id']);

// resumo rápido
$q = $conn->prepare("SELECT COUNT(*) AS total FROM historico WHERE usuario_id = ?");
$q->bind_param("i",$uid); $q->execute(); $total = $q->get_result()->fetch_assoc()['total'] ?? 0;

// top exercícios (mais registrados)
$q2 = $conn->prepare("SELECT exercicio, COUNT(*) AS c FROM historico WHERE usuario_id = ? GROUP BY exercicio ORDER BY c DESC LIMIT 5");
$q2->bind_param("i",$uid); $q2->execute(); $top = $q2->get_result();

?>
<!doctype html>
<html lang="pt-BR">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard — FitTec</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="layout">
<?php include __DIR__.'/partials/header.php'; ?>
<main class="content">
  <div class="content-inner">
    <h2>Dashboard</h2>
    <div class="cards-grid">
      <div class="card">
        <h3>Total de registros</h3>
        <div class="stat-number"><?= $total ?></div>
      </div>
      <div class="card">
        <h3>Top exercícios</h3>
        <ul>
          <?php while($t = $top->fetch_assoc()): ?>
            <li><?= htmlspecialchars($t['exercicio']) ?> <small>(<?= $t['c'] ?>x)</small></li>
          <?php endwhile; ?>
        </ul>
      </div>
    </div>

    <div class="card">
      <h3>Gráficos rápidos</h3>
      <p class="muted">Acesse Histórico → para ver gráficos com mais detalhes.</p>
    </div>
  </div>
</main>
<?php include __DIR__.'/partials/footer.php'; ?>
</body>
</html>
