<?php
include '../config/db.php'; // Inclui a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe e valida o ID do produto
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if (!$id) {
        header("Location: ../pages/produtos.php?error=ID do produto inválido");
        exit;
    }

    try {
        // Prepara a exclusão do produto
        $sql = "DELETE FROM produtos WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        // Redireciona com mensagem de sucesso
        header("Location: ../pages/produtos.php?success=Produto excluído com sucesso");
        exit;

    } catch (PDOException $e) {
        // Redireciona com mensagem de erro em caso de falha
        header("Location: ../pages/produtos.php?error=Erro ao excluir o produto: " . $e->getMessage());
        exit;
    }
} else {
    // Redireciona se o método não for POST
    header("Location: ../pages/produtos.php");
    exit;
}
?>
