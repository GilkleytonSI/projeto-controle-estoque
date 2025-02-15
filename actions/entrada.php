<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];
    $motivo = $_POST['motivo'];

    // Atualizar a quantidade do produto no banco
    $sql = "UPDATE produtos SET quantidade = quantidade + ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$quantidade, $produto_id]);

    // Registrar no histórico
    $sql = "INSERT INTO historico_movimentacao (produto_id, tipo, quantidade, motivo, data) VALUES (?, 'entrada', ?, ?, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$produto_id, $quantidade, $motivo]);

    header("Location: ../pages/produtos.php?success=entrada");
    exit();
}
?>
