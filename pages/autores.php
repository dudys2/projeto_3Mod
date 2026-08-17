<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];

    if (!validarTexto($nome)) {
        $erro = "O nome é obrigatório.";
    } elseif (!empty($_POST['id'])) {
        // RUBRICA: Edição
        atualizarRegistro($conexao, 'autores', ['nome' => $nome], $_POST['id']);
    } else {
        inserirRegistro($conexao, 'autores', ['nome' => $nome]);
    }
}


if (isset($_GET['excluir'])) {
    excluirRegistro($conexao, 'autores', (int)$_GET['excluir']);
}

$autores = listarDados($conexao, 'autores');

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="container-fluid p-4">
  <h1>Autores</h1>

  <?php if (!empty($erro)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <form method="POST" class="row g-2 mb-4">
    <div class="col-auto">
      <input type="text" name="nome" class="form-control" placeholder="Nome do autor" required>
    </div>
    <div class="col-auto">
      <button class="btn btn-primary">Adicionar</button>
    </div>
  </form>

  <table class="table table-striped">
    <thead><tr><th>Nome</th><th></th></tr></thead>
    <tbody>
      <?php while ($autor = mysqli_fetch_assoc($autores)): ?>
      <tr>
        <td><?= htmlspecialchars($autor['nome']) ?></td>
        <td>
          <a href="?excluir=<?= $autor['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Excluir este autor?')">Excluir</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
