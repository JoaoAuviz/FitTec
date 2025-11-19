<?php
// public/treinos.php
session_start();
if (empty($_SESSION['usuario_id'])) { header('Location: index.php'); exit; }
require_once __DIR__ . '/../config/db_connect.php';

$msg = '';
// carregar treinos disponíveis
$treinos = $conn->query("SELECT * FROM treinos ORDER BY nome_treino ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $treino_id = intval($_POST['treino_id'] ?? 0);
    $exercicio = trim($_POST['exercicio'] ?? '');
    $peso = floatval($_POST['peso'] ?? 0);
    $repeticoes = intval($_POST['repeticoes'] ?? 0);
    $desempenho = $_POST['desempenho'] ?? 'Regular';
    $observacao = trim($_POST['observacao'] ?? '');

    if ($treino_id <= 0 || $exercicio === '') {
        $msg = 'Preencha treino e exercício.';
    } else {
        $stmt = $conn->prepare("INSERT INTO historico (usuario_id, treino_id, exercicio, peso, repeticoes, desempenho, observacao) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisdiss", $usuario_id, $treino_id, $exercicio, $peso, $repeticoes, $desempenho, $observacao);
        $stmt->execute();
        $msg = 'Registro salvo com sucesso!';
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>FitTec — Registrar Treino</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="layout">
  <?php include __DIR__.'/partials/header.php'; ?>

  <main class="content">
    <div class="content-inner">
      <div class="card form-card">
        <h2>Registrar treino</h2>
        <p class="muted">Escolha o treino, escreva o exercício e registre peso, repetições e como você se sentiu.</p>

        <?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

        <form method="post" class="form">
          <label>Qual treino você realizou?</label>
          <select name="treino_id" required>
            <option value="">-- selecione --</option>
            <?php while ($t = $treinos->fetch_assoc()): ?>
              <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nome_treino']) ?> — <?= htmlspecialchars($t['categoria']) ?></option>
            <?php endwhile; ?>
          </select>

          <label>Exercício (nome)</label>
          <input type="text" name="exercicio" placeholder="Ex: Rosca Direta / Agachamento" required>

          <div class="inline-row">
            <div class="col">
              <label>Peso (kg)</label>
              <input type="number" step="0.1" name="peso" placeholder="Ex: 40">
            </div>
            <div class="col">
              <label>Repetições</label>
              <input type="number" name="repeticoes" placeholder="Ex: 12">
            </div>
          </div>

          <h3>Como foi sua reação?</h3>
          <p class="subtext">Escolha a opção que melhor descreve como você se saiu levantando o peso/realizando a série. Isso nos ajuda a construir seu histórico e ajustar treinos.</p>

          <div class="radio-grid five">
            <label class="option muito-bom"><input type="radio" name="desempenho" value="Muito Bom" required><span>💥 Muito Bom</span></label>
            <label class="option bom"><input type="radio" name="desempenho" value="Bom"><span>💪 Bom</span></label>
            <label class="option regular"><input type="radio" name="desempenho" value="Regular"><span>🙂 Regular</span></label>
            <label class="option ruim"><input type="radio" name="desempenho" value="Ruim"><span>😓 Ruim</span></label>
            <label class="option muito-ruim"><input type="radio" name="desempenho" value="Muito Ruim"><span>💀 Muito Ruim</span></label>
          </div>

          <label>Observação (opcional)</label>
          <textarea name="observacao" placeholder="Ex: senti desconforto no joelho na terceira série..."></textarea>

          <button class="btn btn-primary" type="submit" name="salvar">Salvar registro</button>
        </form>
      </div>
    </div>
  </main>

  <?php include __DIR__.'/partials/footer.php'; ?>
  <script src="assets/js/scripts.js"></script>
</body>
</html>
