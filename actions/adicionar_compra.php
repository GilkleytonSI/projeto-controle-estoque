<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fornecedor = trim($_POST['fornecedor']);
    $produto_id = intval($_POST['produto_id']);
    $quantidade = intval($_POST['quantidade']);
    $preco = floatval(str_replace(',', '.', $_POST['preco'])); // Converte para formato decimal

    if (!empty($fornecedor) && $produto_id > 0 && $quantidade > 0 && $preco > 0) {
        try {
            $pdo->beginTransaction();

            // Inserir compra na tabela 'compras'
            $sql = "INSERT INTO compras (produto_id, fornecedor, quantidade, preco_unitario, data_compra) 
                    VALUES (:produto_id, :fornecedor, :quantidade, :preco_unitario, NOW())";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':produto_id' => $produto_id,
                ':fornecedor' => $fornecedor,
                ':quantidade' => $quantidade,
                ':preco_unitario' => $preco
            ]);

            // Atualizar a quantidade do produto no estoque
            $sqlUpdate = "UPDATE produtos SET quantidade = quantidade + :quantidade WHERE id = :produto_id";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([
                ':quantidade' => $quantidade,
                ':produto_id' => $produto_id
            ]);

            $pdo->commit();

            // Redirecionar para a página de compras com mensagem de sucesso
            header("Location: ../pages/compras.php?success=1");
            exit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            die("Erro ao registrar a compra: " . $e->getMessage());
        }
    } else {
        // Redirecionar para a página de compras com erro
        header("Location: ../pages/compras.php?error=1");
        exit();
    }
} else {
    header("Location: ../pages/compras.php");
    exit();
}
