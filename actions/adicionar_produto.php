<?php
include '../config/db.php'; // Inclui a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe e sanitiza os dados do formulário
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_STRING);
    $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);
    $preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);

    // Validação dos campos
    if (!$nome || !$categoria || !$quantidade || !$preco) {
        header("Location: ../pages/produtos.php?error=Dados inválidos");
        exit;
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
