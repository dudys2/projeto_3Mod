<?php
include 'includes/conexao.php';
include 'includes/functions.php';
include 'includes/header.php';
include 'includes/menu.php';

$resultado = listarDados($conexao, "livro");
$livros = [];

if($resultado){
    while($row = mysqli_fetch_assoc($resultado)){
        $livros[] = $row;
    }
}

$busca = $_GET['busca'] ?? '';

if(!empty($busca)){
    $livros = filtrarLista($livros, 'titulo', $busca);
}
?>

<div class="container mt-4">
    <h2 class="mb-4">Livros</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET">
                <div class="row">
                    <div class="col-md-10">
                        <input type="text" name="busca" class="form-control" placeholder="Pesquisar por título..." value="<?php echo htmlspecialchars($busca); ?>">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Pesquisar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Lista de Livros
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>ISBN</th>
                        <th>Ano</th>
                        <th>Quantidade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($livros)): ?>
                        <?php foreach($livros as $livro): ?>
                            <tr>
                                <td><?php echo $livro['id_livro']; ?></td>
                                <td><?php echo $livro['titulo']; ?></td>
                                <td><?php echo $livro['isbn']; ?></td>
                                <td><?php echo $livro['ano']; ?></td>
                                <td><?php echo $livro['quantidade']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Nenhum livro encontrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>