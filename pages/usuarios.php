<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

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

if (isset($_GET['excluir'])) {
    excluirRegistro($conexao, 'usuarios', (int)$_GET['excluir']);
}

$usuarios = listarDados($conexao, 'usuarios');

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="container-fluid p-4">
  <h1>Usuários</h1>

  <?php if (!empty($erro)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <form method="POST" class="row g-2 mb-4">
    <div class="col-auto">
      <input type="text" name="nome" class="form-control" placeholder="Nome" required>
    </div>
    <div class="col-auto">
      <input type="email" name="email" class="form-control" placeholder="E-mail" required>
    </div>
    <div class="col-auto">
      <button class="btn btn-primary">Adicionar</button>
    </div>
  </form>

  <table class="table table-striped">
    <thead><tr><th>Nome</th><th>E-mail</th><th></th></tr></thead>
    <tbody>
      <?php while ($usuario = mysqli_fetch_assoc($usuarios)): ?>
      <tr>
        <td><?= htmlspecialchars($usuario['nome']) ?></td>
        <td><?= htmlspecialchars($usuario['email']) ?></td>
        <td>
          <a href="?excluir=<?= $usuario['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Excluir este usuário?')">Excluir</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
