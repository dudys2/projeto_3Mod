<?php
include 'includes/conexao.php';
include 'includes/funcoes.php';

$qtdLivros = contarRegistros($conexao, "livro");
$qtdUsuarios = contarRegistros($conexao, "usuario");
$qtdEmprestimos = contarRegistros($conexao, "emprestimo");

include 'includes/header.php';
include 'includes/menu.php';
?>

<h1>Sistema Biblioteca</h1>

<div class="row">

<div class="col-md-4">
<div class="card">
<div class="card-body">
<h3>Livros</h3>
<h1><?= $qtdLivros ?></h1>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card">
<div class="card-body">
<h3>Usuários</h3>
<h1><?= $qtdUsuarios ?></h1>
</div>
</div>
</div>

<div class="col-md-4">
<div class="card">
<div class="card-body">
<h3>Empréstimos</h3>
<h1><?= $qtdEmprestimos ?></h1>
</div>
</div>
</div>

</div>

<?php include 'includes/footer.php'; ?>