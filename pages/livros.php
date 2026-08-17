<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'titulo'                => $_POST['titulo'],
        'autor_id'               => $_POST['autor_id'],
        'categoria_id'           => $_POST['categoria_id'],
        'quantidade_total'       => $_POST['quantidade_total'],
        'quantidade_disponivel'  => $_POST['quantidade_total'],
    ];

    if (!validarTexto($dados['titulo']) || !validarNumeroPositivo($dados['quantidade_total'])) {
        $erro = "Preencha o título e uma quantidade válida.";
    } elseif (!empty($_POST['id'])) {
        atualizarRegistro($conexao, 'livros', $dados, $_POST['id']);
    } else {
        inserirRegistro($conexao, 'livros', $dados);
    }
}


if (isset($_GET['excluir'])) {
    $resultadoExclusao = excluirLivro($conexao, (int)$_GET['excluir']);
    $mensagemExclusao = $resultadoExclusao['mensagem'];
    $tipoMensagem = $resultadoExclusao['sucesso'] ? 'alert-success' : 'alert-danger';
}

$autores = listarDados($conexao, 'autores');
$categorias = listarDados($conexao, 'categorias');
$livros = mysqli_query($conexao, "
    SELECT l.*, a.nome AS autor, c.nome AS categoria
    FROM livros l
    LEFT JOIN autores a ON a.id = l.autor_id
    LEFT JOIN categorias c ON c.id = l.categoria_id
");

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="container-fluid p-4">
  <h1>Livros</h1>

  <?php if (!empty($mensagemExclusao)): ?>
    <div class="alert <?= $tipoMensagem ?>"><?= htmlspecialchars($mensagemExclusao) ?></div>
  <?php endif; ?>
  <?php if (!empty($erro)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  
  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalLivro">
    Novo Livro
  </button>

  <table class="table table-striped">
    <thead><tr><th>Título</th><th>Autor</th><th>Categoria</th><th>Disponíveis</th><th></th></tr></thead>
    <tbody>
      <?php while ($livro = mysqli_fetch_assoc($livros)): ?>
      <tr>
        <td><?= htmlspecialchars($livro['titulo']) ?></td>
        <td><?= htmlspecialchars($livro['autor'] ?? '-') ?></td>
        <td><?= htmlspecialchars($livro['categoria'] ?? '-') ?></td>
        <td><?= (int)$livro['quantidade_disponivel'] ?></td>
        <td>
          <a href="?excluir=<?= $livro['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Excluir este livro?')">Excluir</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<div class="modal fade" id="modalLivro" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Novo Livro</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="titulo" class="form-control mb-2" placeholder="Título" required>

        <select name="autor_id" class="form-select mb-2">
          <?php mysqli_data_seek($autores, 0); while ($a = mysqli_fetch_assoc($autores)): ?>
            <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nome']) ?></option>
          <?php endwhile; ?>
        </select>

        <select name="categoria_id" class="form-select mb-2">
          <?php mysqli_data_seek($categorias, 0); while ($c = mysqli_fetch_assoc($categorias)): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
          <?php endwhile; ?>
        </select>

        <input type="number" name="quantidade_total" class="form-control" placeholder="Quantidade" min="1" required>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
