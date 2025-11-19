<?php
session_start();
include("../config/db_connect.php");
if (!isset($_SESSION["user_id"])) header("Location: index.php");

$treinos = $conn->query("SELECT * FROM treinos WHERE usuario_id=" . $_SESSION["user_id"]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome_exercicio"];
    $musculo = $_POST["musculo"];
    $carga = $_POST["carga"];
    $reps = $_POST["repeticoes"];
    $treino_id = $_POST["treino_id"];
    $conn->query("INSERT INTO exercicios (nome_exercicio, musculo, carga, repeticoes, treino_id) VALUES ('$nome', '$musculo', '$carga', '$reps', '$treino_id')");
}

$exercicios = $conn->query("SELECT e.*, t.nome_treino FROM exercicios e JOIN treinos t ON e.treino_id=t.id");
?>
<?php include("partials/header.php"); ?>
<div class="container">
    <h2>🔥 Exercícios</h2>
    <form method="POST" class="form-inline">
        <input type="text" name="nome_exercicio" placeholder="Nome do exercício" required>
        <input type="text" name="musculo" placeholder="Músculo alvo">
        <input type="number" name="carga" placeholder="Carga (kg)">
        <input type="number" name="repeticoes" placeholder="Repetições">
        <select name="treino_id">
            <?php while ($t = $treinos->fetch_assoc()): ?>
                <option value="<?= $t['id']; ?>"><?= $t['nome_treino']; ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit" class="btn-primary">Adicionar</button>
    </form>
    <ul class="list">
        <?php while ($e = $exercicios->fetch_assoc()): ?>
            <li><?= $e['nome_exercicio']; ?> (<?= $e['musculo']; ?>) — <?= $e['carga']; ?>kg × <?= $e['repeticoes']; ?> reps [<?= $e['nome_treino']; ?>]</li>
        <?php endwhile; ?>
    </ul>
</div>
<?php include("partials/footer.php"); ?>
