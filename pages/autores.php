<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

// CRUD: inclusão e edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];

    if (!validarTexto($nome)) {
        $erro = "O nome é obrigatório.";
    } elseif (!empty($_POST['id'])) {
        atualizarRegistro($conexao, 'autores', ['nome' => $nome], $_POST['id']);
    } else {
        inserirRegistro($conexao, 'autores', ['nome' => $nome]);
    }
}

// CRUD: exclusão
if (isset($_GET['excluir'])) {
    excluirRegistro($conexao, 'autores', (int)$_GET['excluir']);
}

// CRUD: edição (carrega o registro no formulário)
$autorEditando = null;
if (isset($_GET['editar'])) {
    $autorEditando = buscarPorId($conexao, 'autores', (int)$_GET['editar']);
}

$autores = listarDados($conexao, 'autores');

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="container-fluid p-4">
  <h1>Autores</h1>
  <p class="subtitulo">Quem escreveu os livros do acervo.</p>

  <?php if (!empty($erro)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <form method="POST" class="row g-2 mb-4">
    <?php if ($autorEditando): ?>
      <input type="hidden" name="id" value="<?= $autorEditando['id'] ?>">
    <?php endif; ?>

    <div class="col-auto">
      <input type="text" name="nome" class="form-control" placeholder="Nome do autor"
             value="<?= htmlspecialchars($autorEditando['nome'] ?? '') ?>" required>
    </div>
    <div class="col-auto">
      <button class="btn btn-primary"><?= $autorEditando ? 'Salvar edição' : 'Adicionar' ?></button>
    </div>
    <?php if ($autorEditando): ?>
      <div class="col-auto">
        <a href="autores.php" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    <?php endif; ?>
  </form>

  <?php if (mysqli_num_rows($autores) === 0): ?>
    <p class="text-muted">Nenhum autor cadastrado ainda.</p>
  <?php else: ?>
  <table class="table table-striped">
    <thead><tr><th>Nome</th><th></th></tr></thead>
    <tbody>
      <?php while ($autor = mysqli_fetch_assoc($autores)): ?>
      <tr>
        <td><?= htmlspecialchars($autor['nome']) ?></td>
        <td>
          <a href="?editar=<?= $autor['id'] ?>" class="btn btn-sm btn-secondary">Editar</a>
          <a href="?excluir=<?= $autor['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Excluir este autor? Essa ação não pode ser desfeita.')">Excluir</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
