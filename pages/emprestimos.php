<?php
require_once __DIR__ . '/../includes/conexao.php';
require_once __DIR__ . '/../includes/funcoes.php';

$status = $_GET['status'] ?? null;
$status = $status === '' ? null : $status;
$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;

// Busca, filtro e paginação vêm prontos da stored procedure
$resultado = listarEmprestimos($conexao, $status, null, $pagina, 10);
$linhas = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/sidebar.php';
?>

<div class="container-fluid p-4">
  <h1>Empréstimos</h1>
  <p class="subtitulo">Quem está com qual livro, e desde quando.</p>

  <form method="GET" class="mb-3">
    <select name="status" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
      <option value="">Todos</option>
      <option value="ativo" <?= $status === 'ativo' ? 'selected' : '' ?>>Ativos</option>
      <option value="atrasado" <?= $status === 'atrasado' ? 'selected' : '' ?>>Atrasados</option>
      <option value="devolvido" <?= $status === 'devolvido' ? 'selected' : '' ?>>Devolvidos</option>
    </select>
  </form>

  <?php if (empty($linhas)): ?>
    <p class="text-muted">Nenhum empréstimo encontrado com esse filtro.</p>
  <?php else: ?>
  <table class="table table-striped">
    <thead>
      <tr><th>Livro</th><th>Usuário</th><th>Status</th><th>Dias de atraso</th></tr>
    </thead>
    <tbody>
      <?php foreach ($linhas as $e): ?>
      <tr>
        <td><?= htmlspecialchars($e['titulo']) ?></td>
        <td><?= htmlspecialchars($e['usuario']) ?></td>
        <td>
          <?php
            $cor = $e['status'] === 'atrasado' ? 'danger'
                 : ($e['status'] === 'ativo' ? 'success' : 'secondary');
          ?>
          <span class="badge bg-<?= $cor ?>"><?= htmlspecialchars($e['status']) ?></span>
        </td>
        <td><?= (int)$e['dias_atraso'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>

  <a href="?pagina=<?= max(1, $pagina - 1) ?>&status=<?= htmlspecialchars($status ?? '') ?>"
     class="btn btn-outline-secondary btn-sm">Anterior</a>
  <a href="?pagina=<?= $pagina + 1 ?>&status=<?= htmlspecialchars($status ?? '') ?>"
     class="btn btn-outline-secondary btn-sm">Próxima</a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
