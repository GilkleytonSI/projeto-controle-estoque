<?php
$host = 'localhost'; // Servidor
$dbname = 'estoque'; // Nome do banco
$username = 'root'; // Usuário do banco
$password = ''; // Senha do banco

// Criar conexão
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se existe algum usuário na tabela
    $sqlCheck = "SELECT COUNT(*) FROM usuarios";
    $stmt = $pdo->query($sqlCheck);
    $userCount = $stmt->fetchColumn();

    // Cria o usuário padrão se não houver usuários na tabela
    if ($userCount == 0) {
        $senhaHash = password_hash('admin123', PASSWORD_BCRYPT);
        $sqlInsert = "INSERT INTO usuarios (nome, email, senha) VALUES ('Administrador', 'admin@sistema.com', :senha)";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([':senha' => $senhaHash]);
    }
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
