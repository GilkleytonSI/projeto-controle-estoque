document.addEventListener("DOMContentLoaded", function () {
  var editProductModal = document.getElementById("editProductModal");

  editProductModal.addEventListener("show.bs.modal", function (event) {
      var button = event.relatedTarget; // Botão que acionou o modal
      
      var id = button.getAttribute("data-id");
      var nome = button.getAttribute("data-nome");
      var categoria = button.getAttribute("data-categoria");
      var quantidade = button.getAttribute("data-quantidade");
      var preco = button.getAttribute("data-preco");

      // Preenche os campos do modal
      document.getElementById("editProductId").value = id;
      document.getElementById("editProductName").value = nome;
      document.getElementById("nome_categoria").value = categoria;
      document.getElementById("editProductQuantity").value = quantidade;
      document.getElementById("editProductPrice").value = preco;
  });
});


  const deleteProductModal = document.getElementById('deleteProductModal');
  deleteProductModal.addEventListener('show.bs.modal', function (event) {
    const button = event.relatedTarget; // Botão que abriu o modal
    const id = button.getAttribute('data-id');
    const nome = button.getAttribute('data-nome');

    // Preenche os campos do modal
    document.getElementById('deleteProductId').value = id;
    document.getElementById('deleteProductName').textContent = nome;
  });

  // Exibe a mensagem temporariamente e depois recarrega a página
  const successAlert = document.getElementById('alert-success');
  const errorAlert = document.getElementById('alert-error');

  if (successAlert || errorAlert) {
    setTimeout(() => {
      // Oculta a mensagem após 3 segundos
      if (successAlert) successAlert.style.display = 'none';
      if (errorAlert) errorAlert.style.display = 'none';

      // Recarrega a página após 4 segundos
      setTimeout(() => {
        window.location.href = window.location.href.split('?')[0]; // Remove parâmetros da URL
      }, 1000);
    }, 3000); // Tempo de exibição: 3 segundos
  }


// Passar dados para os modais ao clicar nos botões
document.addEventListener("DOMContentLoaded", function () {
  // Modal de Entrada
  document.querySelectorAll(".entrada-btn").forEach(button => {
      button.addEventListener("click", function () {
          let produtoId = this.getAttribute("data-id");
          let produtoNome = this.getAttribute("data-nome");
          
          document.getElementById("entradaProdutoId").value = produtoId;
          document.getElementById("entradaProdutoNome").textContent = produtoNome;
      });
  });

  // Modal de Saída
  document.querySelectorAll(".saida-btn").forEach(button => {
      button.addEventListener("click", function () {
          let produtoId = this.getAttribute("data-id");
          let produtoNome = this.getAttribute("data-nome");

          document.getElementById("saidaProdutoId").value = produtoId;
          document.getElementById("saidaProdutoNome").textContent = produtoNome;
      });
  });
});

