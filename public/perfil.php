<?php
// public/perfil.php
session_start();
if (empty($_SESSION['usuario_id'])) { header('Location: index.php'); exit; }
require_once __DIR__ . '/../config/db_connect.php';
$uid = $_SESSION['usuario_id'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $nome = trim($_POST['nome'] ?? '');
    if ($nome !== '') {
        $stmt = $conn->prepare("UPDATE usuarios SET nome = ? WHERE id = ?");
        $stmt->bind_param("si", $nome, $uid);
        $stmt->execute();
        $_SESSION['usuario_nome'] = $nome;
        $msg = 'Perfil atualizado.';
    }
}

$stmt = $conn->prepare("SELECT nome, email, criado_em FROM usuarios WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $uid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!doctype html>
<html lang="pt-BR">
<head><meta charset="utf-8"><title>Perfil — FitTec</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="layout">
<?php include __DIR__.'/partials/header.php'; ?>
<main class="content">
  <div class="content-inner">
    <div class="card form-card">
      <h2>Meu perfil</h2>
      <?php if ($msg) echo "<div class='alert alert-success'>$msg</div>"; ?>
      <form method="post" class="form">
        <label>Nome</label>
        <input type="text" name="nome" value="<?= htmlspecialchars($user['nome']) ?>" required>
        <label>Email (não editável)</label>
        <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
        <div class="inline-row">
          <div class="col"><label>Cadastro</label><input value="<?= date('d/m/Y', strtotime($user['criado_em'])) ?>" disabled></div>
          <div class="col"><label></label><button class="btn" name="update">Salvar</button></div>
        </div>
      </form>
    </div>
  </div>
</main>
<?php include __DIR__.'/partials/footer.php'; ?>
</body>
</html>
