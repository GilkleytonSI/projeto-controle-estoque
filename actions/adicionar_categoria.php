<?php
require '../config/db.php'; // Conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome_categoria']);
    
    if (!empty($nome)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO categorias (nome) VALUES (:nome)");
            $stmt->bindParam(':nome', $nome);
            $stmt->execute();
            
            header("Location: ../pages/produtos.php?success=Categoria adicionada com sucesso");
            exit();
        } catch (PDOException $e) {
            die("Erro ao adicionar categoria: " . $e->getMessage());
        }
    } else {
        header("Location: ../pages/produtos.php?error=Nome da categoria não pode estar vazio");
        exit();
    }
} else {
    header("Location: ../pages/produtos.php");
    exit();
}