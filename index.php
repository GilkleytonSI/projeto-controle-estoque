<?php
session_start();

// Redireciona para o login se o usuário não estiver autenticado
if (!isset($_SESSION['usuario'])) {
    header('Location: pages/login.php');
    exit;
}

// Redireciona para o dashboard se autenticado
header('Location: pages/dashboard.php');
exit;
?>
