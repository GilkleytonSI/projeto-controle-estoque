<?php
require_once '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $categoria = $_POST['nome_categoria'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];

    if (empty($nome) || empty($categoria) || empty($quantidade) || empty($preco)) {
        header("Location: ../pages/produtos.php?erro=1");
        exit();
    }

    try {
        // Inicia a transação
        $pdo->beginTransaction();

        // Insere o produto sem o código
        $sql = "INSERT INTO produtos (nome, categoria, quantidade, preco) VALUES (:nome, :categoria, :quantidade, :preco)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':categoria' => $categoria,
            ':quantidade' => $quantidade,
            ':preco' => $preco
        ]);

        // Obtém o ID do último produto inserido
        $lastId = $pdo->lastInsertId();

        // Gera o código único e sequencial
        $codigo = 'PROD-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

        // Atualiza o produto com o código gerado
        $sqlUpdate = "UPDATE produtos SET codigo = :codigo WHERE id = :id";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':codigo' => $codigo,
            ':id' => $lastId
        ]);

        // Confirma a transação
        $pdo->commit();

        header("Location: ../pages/produtos.php?success=Produto adicionado com sucesso");
        exit;

    } catch (PDOException $e) {
        // Em caso de erro, desfaz a transação
        $pdo->rollBack();
        header("Location: ../pages/produtos.php?error=Erro ao salvar no banco: " . $e->getMessage());
        exit;
    }
} else {
    header("Location: ../pages/produtos.php");
    exit;
}
?>
