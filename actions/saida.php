<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];
    $motivo = $_POST['motivo'];

    // Verificar se há estoque suficiente
    $stmt = $pdo->prepare("SELECT quantidade FROM produtos WHERE id = ?");
    $stmt->execute([$produto_id]);
    $produto = $stmt->fetch();

    if ($produto && $produto['quantidade'] >= $quantidade) {
        // Atualizar a quantidade do produto no banco
        $sql = "UPDATE produtos SET quantidade = quantidade - ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$quantidade, $produto_id]);

        // Registrar no histórico
        $sql = "INSERT INTO historico_movimentacao (produto_id, tipo, quantidade, motivo, data) VALUES (?, 'saida', ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$produto_id, $quantidade, $motivo]);

        header("Location: ../pages/produtos.php?success=saida");
        exit();
    } else {
        header("Location: ../pages/produtos.php?error=estoque_insuficiente");
        exit();
    }
}
?>
