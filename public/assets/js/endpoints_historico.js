<?php
// public/assets/js/endpoint_historico.php
require_once __DIR__ . '/../../config/db_connect.php';
session_start();
header('Content-Type: application/json');

$usuario = $_SESSION['usuario_id'] ?? 0;
$treino = intval($_GET['treino'] ?? 0);
$exercicio = trim($_GET['exercicio'] ?? '');

if (!$usuario) { echo json_encode(['dates'=>[], 'pesoAvg'=>[]]); exit; }

// agrupamos por dia (YYYY-MM-DD)
$params = [$usuario];
$types = "i";
$sql = "SELECT DATE(data_registro) as dia, AVG(peso) as peso_avg FROM historico WHERE usuario_id = ?";

if ($treino > 0) { $sql .= " AND treino_id = ?"; $params[] = $treino; $types .= "i"; }
if ($exercicio !== '') { $sql .= " AND exercicio LIKE ?"; $params[] = '%'.$exercicio.'%'; $types .= "s"; }

$sql .= " GROUP BY dia ORDER BY dia ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$dates = []; $peso = [];
while ($r = $res->fetch_assoc()) {
    $dates[] = date('d/m/Y', strtotime($r['dia']));
    $peso[] = floatval($r['peso_avg']);
}
echo json_encode(['dates'=>$dates, 'pesoAvg'=>$peso]);
