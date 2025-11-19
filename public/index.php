<?php
// public/index.php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

if (!empty($_SESSION['usuario_id'])) {
    header('Location: home.php'); exit;
}

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($email === '' || $senha === '') {
        $erro = 'Preencha e-mail e senha.';
    } else {
        $stmt = $conn->prepare("SELECT id,nome,senha FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($row = $res->fetch_assoc()) {
            if (password_verify($senha, $row['senha'])) {
                $_SESSION['usuario_id'] = $row['id'];
                $_SESSION['usuario_nome'] = $row['nome'];
                header('Location: home.php'); exit;
            } else {
                $erro = 'Email ou senha inválidos.';
            }
        } else {
            $erro = 'Email ou senha inválidos.';
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>FitTec — Entrar</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="layout">
  <main class="auth-page">
    <div class="card auth-card">
      <h2>Entrar</h2>
      <p class="muted">Acesse sua conta e comece a registrar seus treinos.</p>

      <?php if ($erro): ?><div class="alert alert-error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

      <form method="post" class="form">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Senha</label>
        <input type="password" name="senha" required>

        <button class="btn btn-primary" type="submit">Entrar</button>
      </form>

      <div class="auth-foot">
        <span>Não tem conta?</span> <a href="register.php">Criar conta</a>
      </div>
    </div>
  </main>
</body>
</html>
