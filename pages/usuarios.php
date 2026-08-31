<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

// CRUD: inclusão e edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = ['nome' => $_POST['nome'], 'email' => $_POST['email']];

    if (!validarTexto($dados['nome']) || !validarTexto($dados['email'])) {
        $erro = "Nome e e-mail são obrigatórios.";
    } elseif (!empty($_POST['id'])) {
        atualizarRegistro($conexao, 'usuarios', $dados, $_POST['id']);
    } else {
        inserirRegistro($conexao, 'usuarios', $dados);
    }
}

// CRUD: exclusão
if (isset($_GET['excluir'])) {
    excluirRegistro($conexao, 'usuarios', (int)$_GET['excluir']);
}

// CRUD: edição (carrega o registro no formulário)
$usuarioEditando = null;
if (isset($_GET['editar'])) {
    $usuarioEditando = buscarPorId($conexao, 'usuarios', (int)$_GET['editar']);
}

$usuarios = listarDados($conexao, 'usuarios');

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="container-fluid p-4">
  <h1>Usuários</h1>
  <p class="subtitulo">Quem pode pegar livros emprestados.</p>

  <?php if (!empty($erro)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <form method="POST" class="row g-2 mb-4">
    <?php if ($usuarioEditando): ?>
      <input type="hidden" name="id" value="<?= $usuarioEditando['id'] ?>">
    <?php endif; ?>

    <div class="col-auto">
      <input type="text" name="nome" class="form-control" placeholder="Nome"
             value="<?= htmlspecialchars($usuarioEditando['nome'] ?? '') ?>" required>
    </div>
    <div class="col-auto">
      <input type="email" name="email" class="form-control" placeholder="E-mail"
             value="<?= htmlspecialchars($usuarioEditando['email'] ?? '') ?>" required>
    </div>
    <div class="col-auto">
      <button class="btn btn-primary"><?= $usuarioEditando ? 'Salvar edição' : 'Adicionar' ?></button>
    </div>
    <?php if ($usuarioEditando): ?>
      <div class="col-auto">
        <a href="usuarios.php" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    <?php endif; ?>
  </form>

  <?php if (mysqli_num_rows($usuarios) === 0): ?>
    <p class="text-muted">Nenhum usuário cadastrado ainda.</p>
  <?php else: ?>
  <table class="table table-striped">
    <thead><tr><th>Nome</th><th>E-mail</th><th></th></tr></thead>
    <tbody>
      <?php while ($usuario = mysqli_fetch_assoc($usuarios)): ?>
      <tr>
        <td><?= htmlspecialchars($usuario['nome']) ?></td>
        <td><?= htmlspecialchars($usuario['email']) ?></td>
        <td>
          <a href="?editar=<?= $usuario['id'] ?>" class="btn btn-sm btn-secondary">Editar</a>
          <a href="?excluir=<?= $usuario['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Excluir este usuário? Essa ação não pode ser desfeita.')">Excluir</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
