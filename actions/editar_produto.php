<?php
include '../config/db.php'; // Inclui a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe e sanitiza os dados do formulário
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $categoria = filter_input(INPUT_POST, 'categoria', FILTER_SANITIZE_STRING);
    $quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_VALIDATE_INT);
    $preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT, FILTER_FLAG_ALLOW_THOUSAND);

    // Validação dos campos
    if (!$id || !$nome || !$categoria || !$quantidade || !$preco) {
        header("Location: ../pages/produtos.php?error=Dados inválidos ao editar o produto");
        exit;
    }

    try {
        // Atualiza o produto no banco de dados
        $sql = "UPDATE produtos 
                SET nome = :nome, categoria = :categoria, quantidade = :quantidade, preco = :preco 
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':categoria' => $categoria,
            ':quantidade' => $quantidade,
            ':preco' => $preco,
            ':id' => $id
        ]);

        header("Location: ../pages/produtos.php?success=Produto atualizado com sucesso");
        exit;

    } catch (PDOException $e) {
        header("Location: ../pages/produtos.php?error=Erro ao atualizar o produto: " . $e->getMessage());
        exit;
    }
} else {
    header("Location: ../pages/produtos.php");
    exit;
}
?>
