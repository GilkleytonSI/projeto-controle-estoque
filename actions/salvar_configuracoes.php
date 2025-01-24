<?php
include '../config/db.php'; // Inclui a conexão com o banco de dados
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

    if (!$username || !$email) {
        header("Location: ../pages/configuracoes.php?error=Campos obrigatórios não preenchidos");
        exit;
    }

    try {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email";
        $params = [
            ':nome' => $username,
            ':email' => $email,
        ];

        // Atualiza a senha somente se foi informada
        if (!empty($senha)) {
            $senhaHash = password_hash($senha, PASSWORD_BCRYPT);
            $sql .= ", senha = :senha";
            $params[':senha'] = $senhaHash;
        }

        $sql .= " WHERE id = :id";
        $params[':id'] = $_SESSION['usuario']['id'];

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Atualiza a sessão com os novos dados
        $_SESSION['usuario']['nome'] = $username;
        $_SESSION['usuario']['email'] = $email;

        header("Location: ../pages/configuracoes.php?success=Configurações atualizadas com sucesso");
        exit;

    } catch (PDOException $e) {
        header("Location: ../pages/configuracoes.php?error=Erro ao salvar as configurações: " . $e->getMessage());
        exit;
    }
} else {
    header("Location: ../pages/configuracoes.php");
    exit;
}
?>
