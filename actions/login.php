<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

    try {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Login bem-sucedido
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email']
            ];
            header('Location: ../pages/dashboard.php');
            exit;
        } else {
            // Login falhou
            header('Location: ../index.php?error=E-mail ou senha inválidos');
            exit;
        }
    } catch (PDOException $e) {
        header('Location: ../index.php?error=Erro no sistema: ' . $e->getMessage());
        exit;
    }
} else {
    header('Location: ../index.php');
    exit;
}
?>
