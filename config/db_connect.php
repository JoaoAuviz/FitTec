<?php
// config/db_connect.php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'fittec_db';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("Erro na conexão com o banco: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
