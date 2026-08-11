<?php
include 'includes/conexao.php';
include 'includes/functions.php';
include 'includes/header.php';
include 'includes/menu.php';

$mensagem = "";
$tipoMensagem = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $idUsuario = $_POST['id_usuario'] ?? '';
    $idLivro = $_POST['id_livro'] ?? '';
    $dataEmprestimo = $_POST['data_emprestimo'] ?? '';
    $dataDevolucao = $_POST['data_devolucao'] ?? '';

    $resultadoCadastro = cadastrarEmprestimo(
        $conexao,
        $idUsuario,
        $idLivro,
        $dataEmprestimo,
        $dataDevolucao
    );

    $mensagem = $resultadoCadastro['mensagem'];
    $tipoMensagem = $resultadoCadastro['sucesso'] ? 'success' : 'danger';
}

if(isset($_GET['devolver'])){
    $idEmprestimo = (int)$_GET['devolver'];

    $resultadoDevolucao = devolverEmprestimo($conexao, $idEmprestimo);
    $mensagem = $resultadoDevolucao['mensagem'];
    $tipoMensagem = $resultadoDevolucao['sucesso'] ? 'success' : 'danger';
}

$usuarios = mysqli_query($conexao, "SELECT * FROM usuario ORDER BY nome");
$livros = mysqli_query($conexao, "SELECT * FROM livro ORDER BY titulo");

$sqlEmprestimos = "
    SELECT 
        e.id_emprestimo,
        u.nome AS usuario,
        l.titulo AS livro,
        e.data_emprestimo,
        e.data_devolucao
    FROM emprestimo e
    INNER JOIN usuario u ON e.id_usuario = u.id_usuario
    INNER JOIN livro l ON e.id_livro = l.id_livro
    ORDER BY e.id_emprestimo DESC
";
$listaEmprestimos = mysqli_query($conexao, $sqlEmprestimos);
?>

<div class="container mt-4">
    <h2 class="mb-4">Empréstimos</h2>

    <?php if(!empty($mensagem)): ?>
        <div class="alert alert-<?php echo $tipoMensagem; ?>">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            Novo Empréstimo
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Usuário</label>
                        <select name="id_usuario" class="form-control" required>
                            <option value="">Selecione</option>
                            <?php while($usuario = mysqli_fetch_assoc($usuarios)): ?>
                                <option value="<?php echo $usuario['id_usuario']; ?>">
                                    <?php echo $usuario['nome']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Livro</label>
                        <select name="id_livro" class="form-control" required>
                            <option value="">Selecione</option>
                            <?php while($livro = mysqli_fetch_assoc($livros)): ?>
                                <option value="<?php echo $livro['id_livro']; ?>">
                                    <?php echo $livro['titulo']; ?> (Qtd: <?php echo $livro['quantidade']; ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Data do empréstimo</label>
                        <input type="date" name="data_emprestimo" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Data de devolução</label>
                        <input type="date" name="data_devolucao" class="form-control" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Cadastrar Empréstimo</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Lista de Empréstimos
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuário</th>
                        <th>Livro</th>
                        <th>Empréstimo</th>
                        <th>Devolução</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($listaEmprestimos && mysqli_num_rows($listaEmprestimos) > 0): ?>
                        <?php while($item = mysqli_fetch_assoc($listaEmprestimos)): ?>
                            <tr>
                                <td><?php echo $item['id_emprestimo']; ?></td>
                                <td><?php echo $item['usuario']; ?></td>
                                <td><?php echo $item['livro']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($item['data_emprestimo'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($item['data_devolucao'])); ?></td>
                                <td>
                                    <a href="emprestimos.php?devolver=<?php echo $item['id_emprestimo']; ?>" 
                                       class="btn btn-sm btn-success"
                                       onclick="return confirm('Confirmar devolução do livro?')">
                                       Devolver
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Nenhum empréstimo cadastrado.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>