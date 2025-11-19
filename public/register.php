<?php
// public/register.php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if ($nome === '' || $email === '' || $senha === '') {
        $erro = 'Preencha todos os campos.';
    } else {
        // checar email existente
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            $erro = 'Este e-mail já está em uso.';
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (nome,email,senha) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nome, $email, $hash);
            if ($stmt->execute()) {
                header('Location: index.php'); exit;
            } else {
                $erro = 'Erro ao cadastrar. Tente novamente.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>FitTec — Cadastro</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="layout">
  <main class="auth-page">
    <div class="card auth-card">
      <h2>Criar conta</h2>
      <p class="muted">Rápido e fácil. Seus dados ficarão seguros.</p>

      <?php if ($erro): ?><div class="alert alert-error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>

      <form method="post" class="form">
        <label>Nome completo</label>
        <input type="text" name="nome" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Senha</label>
        <input type="password" name="senha" required>

        <button class="btn btn-primary" type="submit">Criar conta</button>
      </form>

      <div class="auth-foot">
        <span>Já tem conta?</span> <a href="index.php">Entrar</a>
      </div>
    </div>
  </main>
</body>
</html>
