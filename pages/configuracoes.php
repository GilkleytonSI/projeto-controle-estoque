<?php
// Inicia a sessão e verifica se o usuário está autenticado
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configurações</title>
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

        <div class="container py-4">
            <div class="row">
                <!-- <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4"> -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h4">Configurações</h1>
                    </div>

                    <!-- Mensagens de sucesso ou erro -->
                    <?php if (isset($_GET['success'])): ?>
                        <div id="alert-success" class="alert alert-success">
                        <?= htmlspecialchars($_GET['success']) ?>
                        </div>
                    <?php elseif (isset($_GET['error'])): ?>
                        <div id="alert-error" class="alert alert-danger">
                        <?= htmlspecialchars($_GET['error']) ?>
                        </div>
                    <?php endif; ?>

                    <!-- Conteúdo principal -->
                    <div class="card">
                        <div class="card-body">
                        <!-- <h5 class="card-title">Configurações Gerais</h5> -->
                            <form action="../actions/salvar_configuracoes.php" method="POST">
                                <div class="mb-3">
                                <label for="username" class="form-label">Nome de Usuário</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($_SESSION['usuario']['nome']) ?>" required>
                                </div>
                                <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_SESSION['usuario']['email']) ?>" required>
                                </div>
                                <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha">
                                <div class="form-text">Deixe em branco se não quiser alterar a senha.</div>
                                </div>
                                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                            </form>
                        </div>
                    </div>
                <!-- </main> -->
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
