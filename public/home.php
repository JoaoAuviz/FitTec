<?php
// public/home.php
session_start();
if (empty($_SESSION['usuario_id'])) { header('Location: index.php'); exit; }
require_once __DIR__ . '/../config/db_connect.php';
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>FitTec — Dashboard</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="layout">
  <?php include __DIR__.'/partials/header.php'; ?>

  <main class="content">
    <div class="content-inner">
      <section class="dashboard-header">
        <h2>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h2>
        <p class="muted">Registre seus treinos, acompanhe seu histórico e veja seu progresso.</p>
      </section>

      <section class="cards-grid">
        <a class="card action-card" href="treinos.php">💪 Registrar Treino</a>
        <a class="card action-card" href="historico.php">📈 Ver Histórico</a>
        <a class="card action-card" href="perfil.php">👤 Meu Perfil</a>
      </section>

      <section class="quick-stats card">
        <h3>Últimos registros</h3>
        <?php
        $uid = $_SESSION['usuario_id'];
        $stmt = $conn->prepare("SELECT h.*, t.nome_treino FROM historico h JOIN treinos t ON t.id=h.treino_id WHERE h.usuario_id = ? ORDER BY h.data_registro DESC LIMIT 5");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 0) {
            echo "<p class='muted'>Nenhum registro ainda. Vá em Registrar Treino.</p>";
        } else {
            echo "<ul class='mini-list'>";
            while ($r = $res->fetch_assoc()) {
                $colorClass = 'tag-regular';
                if ($r['desempenho']=='Muito Bom') $colorClass='tag-muito-bom';
                if ($r['desempenho']=='Bom') $colorClass='tag-bom';
                if ($r['desempenho']=='Regular') $colorClass='tag-regular';
                if ($r['desempenho']=='Ruim') $colorClass='tag-ruim';
                if ($r['desempenho']=='Muito Ruim') $colorClass='tag-muito-ruim';
                echo "<li><strong>".htmlspecialchars($r['exercicio'])."</strong> — ".htmlspecialchars($r['nome_treino'])." <span class='tag $colorClass'>".htmlspecialchars($r['desempenho'])."</span></li>";
            }
            echo "</ul>";
        }
        ?>
      </section>
    </div>
  </main>

  <?php include __DIR__.'/partials/footer.php'; ?>
  <script src="assets/js/scripts.js"></script>
</body>
</html>
