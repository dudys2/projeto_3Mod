<?php require_once __DIR__ . '/../includes/header.php'; ?>
<?php require_once __DIR__ . '/../includes/sidebar.php'; ?>

<div class="container-fluid p-4">
  <h1>Dashboard</h1>

  
  <div id="cards" class="row g-3 mb-4"></div>

  <div id="vazio" class="alert alert-secondary" style="display:none;">Nenhum dado registrado.</div>

  <table class="table table-striped">
    <thead><tr><th>Livro</th><th>Usuário</th><th>Status</th></tr></thead>
    <tbody id="corpo-tabela"></tbody>
  </table>
</div>


<script type="module" src="../assets/js/main.js"></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
