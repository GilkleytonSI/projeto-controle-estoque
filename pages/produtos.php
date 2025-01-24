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
          <h1 class="h4">Gestão de Produtos</h1>

          <?php if (isset($_GET['success'])): ?>
            <div id="alert-success" class="alert alert-success">
              <?= htmlspecialchars($_GET['success']) ?>
            </div>
          <?php elseif (isset($_GET['error'])): ?>
            <div id="alert-error" class="alert alert-danger">
              <?= htmlspecialchars($_GET['error']) ?>
            </div>
          <?php endif; ?>

          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus-lg me-2"></i> Adicionar Produto
          </button>
        </div>

        <!-- Tabela de Produtos -->
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th scope="col">Código</th>
              <th scope="col">Nome</th>
              <th scope="col">Categoria</th>
              <th scope="col">Quantidade</th>
              <th scope="col">Preço</th>
              <th scope="col" class="text-center">Ações</th>
            </tr>
          </thead>
          <tbody>
          <?php
            // Consulta para buscar os produtos
            $query = "SELECT * FROM produtos ORDER BY id DESC";
            $stmt = $pdo->query($query);

            // Loop para exibir os produtos
            if ($stmt->rowCount() > 0) {
                foreach ($stmt as $row) {
                    echo "<tr>
                            <td>{$row['codigo']}</td>
                            <td>{$row['nome']}</td>
                            <td>{$row['categoria']}</td>
                            <td>{$row['quantidade']}</td>
                            <td>R$ " . number_format($row['preco'], 2, ',', '.') . "</td>
                            <td class='text-center'>
                              <button class='btn btn-sm btn-warning me-2' data-bs-toggle='modal' 
                                      data-bs-target='#editProductModal' 
                                      data-id='{$row['id']}' 
                                      data-nome='{$row['nome']}' 
                                      data-categoria='{$row['categoria']}' 
                                      data-quantidade='{$row['quantidade']}' 
                                      data-preco='{$row['preco']}'>
                                  <i class='bi bi-pencil-fill'></i>
                              </button>
                              <button class='btn btn-sm btn-danger' data-bs-toggle='modal' 
                                      data-bs-target='#deleteProductModal' 
                                      data-id='{$row['id']}' 
                                      data-nome='{$row['nome']}'>
                                  <i class='bi bi-trash-fill'></i>
                              </button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>Nenhum produto encontrado</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal para Adicionar Produto -->
  <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addProductModalLabel">Adicionar Produto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
          <div class="modal-body">
          <form action="../actions/adicionar_produto.php" method="POST">
              <div class="mb-3">
                <label for="productName" class="form-label">Nome do Produto</label>
                <input type="text" class="form-control" id="productName" name="nome" required>
              </div>
              <div class="mb-3">
                <label for="productCategory" class="form-label">Categoria</label>
                <input type="text" class="form-control" id="productCategory" name="categoria" required>
              </div>
              <div class="mb-3">
                <label for="productQuantity" class="form-label">Quantidade</label>
                <input type="number" class="form-control" id="productQuantity" name="quantidade" required>
              </div>
              <div class="mb-3">
                <label for="productPrice" class="form-label">Preço</label>
                <input type="text" class="form-control" id="productPrice" name="preco" required>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Salvar</button>
              </div>
          </form>
        </div> 
      </div>
    </div>
  </div>

  <!-- Modal para Editar Produto -->          
  <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProductModalLabel">Editar Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="../actions/editar_produto.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="id" id="editProductId">
          <div class="mb-3">
            <label for="editProductName" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" id="editProductName" name="nome" required>
          </div>
          <div class="mb-3">
            <label for="editProductCategory" class="form-label">Categoria</label>
            <input type="text" class="form-control" id="editProductCategory" name="categoria" required>
          </div>
          <div class="mb-3">
            <label for="editProductQuantity" class="form-label">Quantidade</label>
            <input type="number" class="form-control" id="editProductQuantity" name="quantidade" required>
          </div>
          <div class="mb-3">
            <label for="editProductPrice" class="form-label">Preço</label>
            <input type="text" class="form-control" id="editProductPrice" name="preco" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal para Excluir Produto -->
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteProductModalLabel">Excluir Produto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="../actions/excluir_produto.php" method="POST">
        <div class="modal-body">
          <input type="hidden" name="id" id="deleteProductId">
          <p>Tem certeza que deseja excluir o produto <strong id="deleteProductName"></strong>?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Excluir</button>
        </div>
      </form>
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
