<?php
require_once "db_connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $senha = $_POST["senha"];

    $query = "SELECT * FROM usuarios WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (password_verify($senha, $user["senha"])) {
            session_start();
            $_SESSION["usuario"] = $user["nome"];
            header("Location: ../dashboard.php");
            exit;
        } else {
            echo "<script>alert('Senha incorreta!'); window.location='../login.html';</script>";
        }
    } else {
        echo "<script>alert('Usuário não encontrado!'); window.location='../login.html';</script>";
    }
}
?>
