<?php
if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php');
    exit;
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container-fluid">
      <!-- Título ou Nome do Sistema -->
      <a class="navbar-brand" href="#">Sistema de Estoque</a>
  
      <!-- Botão para colapsar o menu em telas pequenas -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
        <span class="navbar-toggler-icon"></span>
      </button>
  
      <!-- Conteúdo do Menu Superior -->
      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav ms-auto">
          <!-- Avatar do Usuário -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="../assets/img/avatar.png" alt="Avatar" class="rounded-circle me-2" style="width: 30px; height: 30px;">
              <?= htmlspecialchars($_SESSION['usuario']['nome']); ?>
            </a>
            <!-- Menu Dropdown -->
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#perfilModal">Perfil</a></li>
              <li><a class="dropdown-item" href="../pages/configuracoes.php">Configurações</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="../actions/logout.php">Sair</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Modal do Perfil -->
<div class="modal fade" id="perfilModal" tabindex="-1" aria-labelledby="perfilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="perfilModalLabel">Perfil do Usuário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img src="../assets/img/avatar.png" alt="Avatar" class="rounded-circle mb-3" style="width: 100px; height: 100px;">
                </div>
                <p><strong>Nome:</strong> <?= htmlspecialchars($_SESSION['usuario']['nome']); ?></p>
                <p><strong>E-mail:</strong> <?= htmlspecialchars($_SESSION['usuario']['email']); ?></p>
                <!-- <p><strong>ID do Usuário:</strong> <?= htmlspecialchars($_SESSION['usuario']['id']); ?></p> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
  