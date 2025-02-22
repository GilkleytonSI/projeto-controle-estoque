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

          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCategoria">
            <i class="bi bi-tags"></i> Cadastrar Categoria
          </button>
          <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modalVisualizarCategorias">
            <i class="bi bi-eye"></i> Visualizar Categorias
          </button>


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
    $query = "SELECT p.id, p.codigo, p.nome, c.nome AS categoria, p.quantidade, p.preco 
          FROM produtos p
          LEFT JOIN categorias c ON p.categoria = c.id
          ORDER BY p.id DESC";
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
                      <!-- Botão de Registrar Entrada -->
                      <button class='btn btn-sm btn-success me-1 entrada-btn' 
                            data-bs-toggle='modal' 
                            data-bs-target='#entradaModal' 
                            data-id='{$row['id']}' 
                            data-nome='{$row['nome']}'>
                        <i class='bi bi-box-arrow-in-down'></i> Entrada
                      </button>
                      <!-- Botão de Registrar Saída -->
                      <button class='btn btn-sm btn-danger me-1 saida-btn' 
                              data-bs-toggle='modal' 
                              data-bs-target='#saidaModal' 
                              data-id='{$row['id']}' 
                              data-nome='{$row['nome']}'>
                        <i class='bi bi-box-arrow-up'></i> Saída
                      </button>
                      <!-- Botão de Editar -->                                 
                      <button class='btn btn-sm btn-warning me-2' data-bs-toggle='modal' 
                                data-bs-target='#editProductModal' 
                                data-id='{$row['id']}' 
                                data-nome='{$row['nome']}' 
                                data-categoria='{$row['categoria']}' 
                                data-quantidade='{$row['quantidade']}' 
                                data-preco='{$row['preco']}'>
                            <i class='bi bi-pencil-fill'></i>
                      </button>
                      <!-- Botão de Excluir -->
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
                    <select class="form-control" id="nome_categoria" name="nome_categoria" required>
                      <option value="">Selecione a Categoria</option>
                        <?php
                          require_once '../config/db.php';
                          $query = "SELECT id, nome FROM categorias";
                          $stmt = $pdo->query($query);
                          while ($categoria = $stmt->fetch(PDO::FETCH_ASSOC)) {
                              echo "<option value='{$categoria['id']}'>{$categoria['nome']}</option>";
                          }
                        ?>
                    </select>
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
            <select class="form-control" id="nome_categoria" name="nome_categoria" required>
                      <option value="">Selecione a Categoria</option>
                        <?php
                          require_once '../config/db.php';
                          $query = "SELECT id, nome FROM categorias";
                          $stmt = $pdo->query($query);
                          while ($categoria = $stmt->fetch(PDO::FETCH_ASSOC)) {
                              echo "<option value='{$categoria['id']}'>{$categoria['nome']}</option>";
                          }
                        ?>
                    </select>    
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

<!-- Modal de Entrada -->
<div class="modal fade" id="entradaModal" tabindex="-1" aria-labelledby="entradaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="entradaModalLabel">Registrar Entrada de Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../actions/entrada.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="produto_id" id="entradaProdutoId">
                    <p>Produto: <strong id="entradaProdutoNome"></strong></p>
                    <label for="quantidadeEntrada" class="form-label">Quantidade:</label>
                    <input type="number" name="quantidade" id="quantidadeEntrada" class="form-control" required min="1">

                    <label for="motivo_entrada">Motivo:</label>
                    <textarea class="form-control" name="motivo" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Registrar Entrada</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Saída -->
<div class="modal fade" id="saidaModal" tabindex="-1" aria-labelledby="saidaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="saidaModalLabel">Registrar Saída de Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="../actions/saida.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="produto_id" id="saidaProdutoId">
                    <p>Produto: <strong id="saidaProdutoNome"></strong></p>
                    <label for="quantidadeSaida" class="form-label">Quantidade:</label>
                    <input type="number" name="quantidade" id="quantidadeSaida" class="form-control" required min="1">

                    <label for="motivo_saida">Motivo:</label>
                    <textarea class="form-control" name="motivo" required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Registrar Saída</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para cadastro de categoria -->
<div class="modal fade" id="modalCategoria" tabindex="-1" aria-labelledby="modalCategoriaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCategoriaLabel">Cadastrar Nova Categoria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../actions/adicionar_categoria.php" method="POST">
                    <div class="mb-3">
                        <label for="nome_categoria" class="form-label">Nome da Categoria</label>
                        <input type="text" class="form-control" id="nome_categoria" name="nome_categoria" required>
                    </div>
                    <button type="submit" class="btn btn-success">Cadastrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Visualizar Categorias -->
<div class="modal fade" id="modalVisualizarCategorias" tabindex="-1" aria-labelledby="modalVisualizarCategoriasLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalVisualizarCategoriasLabel">Categorias Cadastradas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nome das categorias</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once '../config/db.php';
                        $query = "SELECT id, nome FROM categorias";
                        $stmt = $pdo->query($query);

                        if ($stmt->rowCount() > 0) {
                            while ($categoria = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                        <td>{$categoria['nome']}</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='2' class='text-center'>Nenhuma categoria encontrada</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
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
