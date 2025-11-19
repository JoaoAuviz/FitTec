<?php
// public/historico.php
session_start();
if (empty($_SESSION['usuario_id'])) { header('Location: index.php'); exit; }
require_once __DIR__ . '/../config/db_connect.php';

$usuario_id = $_SESSION['usuario_id'];

// filtros (opcionais)
$filter_treino = intval($_GET['treino'] ?? 0);
$filter_exercicio = trim($_GET['exercicio'] ?? '');

// query base
$sql = "SELECT h.*, t.nome_treino FROM historico h JOIN treinos t ON t.id=h.treino_id WHERE h.usuario_id = ?";
$params = [$usuario_id];
$types = "i";

if ($filter_treino > 0) {
    $sql .= " AND h.treino_id = ?";
    $params[] = $filter_treino; $types .= "i";
}
if ($filter_exercicio !== '') {
    $sql .= " AND h.exercicio LIKE ?";
    $params[] = '%'.$filter_exercicio.'%'; $types .= "s";
}
$sql .= " ORDER BY h.data_registro DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

// para filtros: lista de treinos do sistema
$treinosList = $conn->query("SELECT id,nome_treino FROM treinos ORDER BY nome_treino");

?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>FitTec — Histórico</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="layout">
  <?php include __DIR__.'/partials/header.php'; ?>

  <main class="content">
    <div class="content-inner">
      <h2>Histórico</h2>

      <div class="card">
        <form method="get" class="filter-form">
          <label>Filtrar por treino</label>
          <select name="treino">
            <option value="0">Todos</option>
            <?php while ($t = $treinosList->fetch_assoc()): ?>
              <option value="<?= $t['id'] ?>" <?= ($filter_treino == $t['id']) ? 'selected' : '' ?>><?= htmlspecialchars($t['nome_treino']) ?></option>
            <?php endwhile; ?>
          </select>

          <label>Filtrar por exercício (parte do nome)</label>
          <input type="text" name="exercicio" value="<?= htmlspecialchars($filter_exercicio) ?>">

          <div class="filter-actions">
            <button class="btn" type="submit">Aplicar filtros</button>
            <a class="btn btn-outline" href="historico.php">Limpar</a>
          </div>
        </form>
      </div>

      <div class="card">
        <h3>Gráfico de desempenho (peso médio por data)</h3>
        <canvas id="chartPeso" height="120"></canvas>
      </div>

      <div class="card table-card">
        <h3>Registros</h3>
        <table class="table">
          <thead>
            <tr><th>Data</th><th>Treino</th><th>Exercício</th><th>Peso (kg)</th><th>Reps</th><th>Desempenho</th><th>Observação</th></tr>
          </thead>
          <tbody>
            <?php while($r = $res->fetch_assoc()): ?>
              <?php
                $cls = 'tag-regular';
                if ($r['desempenho']=='Muito Bom') $cls='tag-muito-bom';
                if ($r['desempenho']=='Bom') $cls='tag-bom';
                if ($r['desempenho']=='Regular') $cls='tag-regular';
                if ($r['desempenho']=='Ruim') $cls='tag-ruim';
                if ($r['desempenho']=='Muito Ruim') $cls='tag-muito-ruim';
              ?>
              <tr>
                <td><?= date('d/m/Y H:i', strtotime($r['data_registro'])) ?></td>
                <td><?= htmlspecialchars($r['nome_treino']) ?></td>
                <td><?= htmlspecialchars($r['exercicio']) ?></td>
                <td><?= $r['peso'] ?></td>
                <td><?= $r['repeticoes'] ?></td>
                <td><span class="tag <?= $cls ?>"><?= htmlspecialchars($r['desempenho']) ?></span></td>
                <td><?= htmlspecialchars($r['observacao']) ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>

    </div>
  </main>

  <?php include __DIR__.'/partials/footer.php'; ?>
  <script src="assets/js/scripts.js"></script>
  <script>
  // obter dados via AJAX para gráfico (simplificado: vamos chamar endpoint local fetch)
  (function(){
    async function fetchChartData(){
      // chamamos um endpoint simples que devolve JSON (criaremos em scripts.js)
      const resp = await fetch('assets/js/endpoint_historico.php?treino=<?= $filter_treino ?>&exercicio=<?= urlencode($filter_exercicio) ?>');
      const data = await resp.json();
      const labels = data.dates;
      const pesoAvg = data.pesoAvg;
      const ctx = document.getElementById('chartPeso').getContext('2d');
      if (window.pesoChart) window.pesoChart.destroy();
      window.pesoChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Peso médio (kg)',
            data: pesoAvg,
            borderColor: '#16a34a',
            backgroundColor: 'rgba(22,163,74,0.12)',
            fill: true,
            tension: 0.3,
            pointRadius: 3
          }]
        },
        options: {
          responsive: true,
          scales: {
            x: { display: true },
            y: { display: true }
          }
        }
      });
    }
    fetchChartData();
  })();
  </script>
</body>
</html>
