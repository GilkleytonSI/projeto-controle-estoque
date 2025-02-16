<?php
// Inicia a sessão e verifica se o usuário está autenticado
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php');
    exit;
}
?>

<?php
include '../config/db.php'; // Inclui a conexão com o banco
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produtos</title>
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
      <!-- Header -->
      <?php include '../partials/header.php'; ?>

      <!-- Conteúdo Principal -->
      <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1 class="h4">Gestão de Compras</h1>

          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adicionarCompraModal">
                <i class="bi bi-plus-lg me-2"></i>Adicionar Nova Compra
          </button>

        </div>

        <table class="table table-striped table-hover mt-3">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Fornecedor</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Data de Compra</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT compras.id, produtos.nome AS produto_nome, compras.fornecedor, compras.quantidade, compras.preco_unitario, compras.data_compra 
                FROM compras
                JOIN produtos ON compras.produto_id = produtos.id
                ORDER BY compras.data_compra DESC";
                $stmt = $pdo->query($query);

                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['produto_nome']}</td>
                            <td>{$row['fornecedor']}</td>
                            <td>{$row['quantidade']}</td>
                            <td>R$ " . number_format($row['preco_unitario'], 2, ',', '.') . "</td>
                            <td>{$row['data_compra']}</td>
                         </tr>";
                }
                ?>
            </tbody>
        </table>

        


      </div>
    </div>
  </div>

  <!-- Modal de Adicionar Nova Compra -->
<div class="modal fade" id="adicionarCompraModal" tabindex="-1" aria-labelledby="adicionarCompraModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adicionarCompraModalLabel">Adicionar Nova Compra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../actions/adicionar_compra.php" method="POST">
                    <div class="mb-3">
                        <label for="fornecedor" class="form-label">Fornecedor</label>
                        <input type="text" class="form-control" id="fornecedor" name="fornecedor" required>
                    </div>
                    <div class="mb-3">
                        <label for="produto_id" class="form-label">Produto</label>
                        <select class="form-control" id="produto_id" name="produto_id" required>
                            <option value="">Selecione um Produto</option>
                            <?php
                            require_once '../config/db.php';
                            $query = "SELECT id, nome FROM produtos";
                            $stmt = $pdo->query($query);
                            while ($produto = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$produto['id']}'>{$produto['nome']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantidade" class="form-label">Quantidade</label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade" required>
                    </div>
                    <div class="mb-3">
                        <label for="preco" class="form-label">Preço Unitário</label>
                        <input type="text" class="form-control" id="preco" name="preco" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-success">Salvar Compra</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

  

  







 <!-- Footer -->
  <?php include '../partials/footer.php'; ?>          
  <!-- Bootstrap JS (Online via CDN) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Scripts personalizados -->
  <script src="../assets/js/scripts.js"></script>
</body>
</html>
