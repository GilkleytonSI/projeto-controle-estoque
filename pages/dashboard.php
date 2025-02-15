<?php
session_start();
if (!isset($_SESSION['usuario'])) {
  header('Location: ../index.php');
  exit;
}

// Incluindo o arquivo de conexão com o banco de dados
require_once '../config/db.php';

// Consultar o total de produtos cadastrados
$queryProdutos = "SELECT COUNT(*) AS total FROM produtos";
$resultProdutos = $conn->query($queryProdutos);

$totalProdutos = ($resultProdutos && $resultProdutos->num_rows > 0) ? $resultProdutos->fetch_assoc()['total'] : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <!-- Bootstrap CSS (Online via CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Estilos personalizados -->
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <?php include '../partials/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-grow-1">
      <!-- Topbar -->
      <?php include '../partials/header.php'; ?>

      <!-- Dashboard Content -->
      <div class="container py-4">
        <h1 class="mb-4">Bem-vindo ao Dashboard</h1>

        <div class="row">

          <!-- Card: Quantidade de Produtos -->
          <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
              <div class="card-header">Produtos</div>
              <div class="card-body">
                <h5 class="card-title">Quantidade Total</h5>
                <p class="card-text fs-3"><?= $totalProdutos; ?></p>
              </div>
            </div>
          </div>

          <!-- Card Exemplo: Outros Dados -->
          <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
              <div class="card-header">Quantidade de Entrada</div>
              <div class="card-body">
                <h5 class="card-title">Exemplo</h5>
                <p class="card-text fs-3">35</p>
              </div>
            </div>
          </div>

          <!-- Card Exemplo: Vendas (a personalizar) -->
          <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
              <div class="card-header">Quantidade de Saida</div>
              <div class="card-body">
                <h5 class="card-title">Exemplo</h5>
                <p class="card-text fs-3">123</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!-- Footer -->
  <?php include '../partials/footer.php'; ?>
  <!-- Bootstrap JS (Online via CDN) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>