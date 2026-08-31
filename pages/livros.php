<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

// CRUD: inclusão e edição
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

// Exclusão com regra de negócio: bloqueia se o livro está emprestado
if (isset($_GET['excluir'])) {
    $resultadoExclusao = excluirLivro($conexao, (int)$_GET['excluir']);
    $mensagemExclusao = $resultadoExclusao['mensagem'];
    $tipoMensagem = $resultadoExclusao['sucesso'] ? 'alert-success' : 'alert-danger';
}

// Edição (carrega o registro no modal)
$livroEditando = null;
if (isset($_GET['editar'])) {
    $livroEditando = buscarPorId($conexao, 'livros', (int)$_GET['editar']);
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
  <p class="subtitulo">O acervo completo, com autor, categoria e disponibilidade.</p>

  <?php if (!empty($mensagemExclusao)): ?>
    <div class="alert <?= $tipoMensagem ?>"><?= htmlspecialchars($mensagemExclusao) ?></div>
  <?php endif; ?>
  <?php if (!empty($erro)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
  <?php endif; ?>

  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalLivro">
    Novo Livro
  </button>

  <?php if (mysqli_num_rows($livros) === 0): ?>
    <p class="text-muted">Nenhum livro cadastrado ainda.</p>
  <?php else: ?>
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    <?php while ($livro = mysqli_fetch_assoc($livros)): ?>
    <div class="col">
      <div class="livro-card">
        <div class="capa-livro capa-<?= corCapa($livro['titulo']) ?>">
          <span class="capa-titulo"><?= htmlspecialchars($livro['titulo']) ?></span>
          <span class="capa-autor"><?= htmlspecialchars($livro['autor'] ?? 'Autor desconhecido') ?></span>
        </div>
        <div class="livro-meta">
          <?= htmlspecialchars($livro['categoria'] ?? 'Sem categoria') ?> ·
          <?= (int)$livro['quantidade_disponivel'] ?> disponível(is)
        </div>
        <div class="livro-acoes">
          <a href="?editar=<?= $livro['id'] ?>" class="btn btn-sm btn-secondary">Editar</a>
          <a href="?excluir=<?= $livro['id'] ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Excluir este livro? Essa ação não pode ser desfeita.')">Excluir</a>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
  <?php endif; ?>
</div>

<div class="modal fade" id="modalLivro" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?= $livroEditando ? 'Editar Livro' : 'Novo Livro' ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <?php if ($livroEditando): ?>
          <input type="hidden" name="id" value="<?= $livroEditando['id'] ?>">
        <?php endif; ?>

        <input type="text" name="titulo" class="form-control mb-2" placeholder="Título"
               value="<?= htmlspecialchars($livroEditando['titulo'] ?? '') ?>" required>

        <select name="autor_id" class="form-select mb-2">
          <?php mysqli_data_seek($autores, 0); while ($a = mysqli_fetch_assoc($autores)): ?>
            <option value="<?= $a['id'] ?>"
              <?= (isset($livroEditando['autor_id']) && $livroEditando['autor_id'] == $a['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($a['nome']) ?>
            </option>
          <?php endwhile; ?>
        </select>

        <select name="categoria_id" class="form-select mb-2">
          <?php mysqli_data_seek($categorias, 0); while ($c = mysqli_fetch_assoc($categorias)): ?>
            <option value="<?= $c['id'] ?>"
              <?= (isset($livroEditando['categoria_id']) && $livroEditando['categoria_id'] == $c['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['nome']) ?>
            </option>
          <?php endwhile; ?>
        </select>

        <input type="number" name="quantidade_total" class="form-control" placeholder="Quantidade" min="1"
               value="<?= htmlspecialchars($livroEditando['quantidade_total'] ?? '') ?>" required>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </form>
  </div>
</div>

<?php if ($livroEditando): ?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    new bootstrap.Modal(document.getElementById("modalLivro")).show();
  });
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
