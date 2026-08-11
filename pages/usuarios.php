<?php

include 'includes/conexao.php';

$sql = "SELECT * FROM usuario";

$resultado = mysqli_query(
$conexao,
$sql
);

include 'includes/header.php';
include 'includes/menu.php';

?>

<h2>Usuários</h2>

<table class="table table-hover">

<tr>
<th>ID</th>
<th>Nome</th>
<th>Email</th>
<th>Telefone</th>
</tr>

<?php while($usuario = mysqli_fetch_assoc($resultado)){ ?>

<tr>

<td><?= $usuario['id_usuario'] ?></td>

<td><?= $usuario['nome'] ?></td>

<td><?= $usuario['email'] ?></td>

<td><?= $usuario['telefone'] ?></td>

</tr>

<?php } ?>

</table>

<?php
include 'includes/footer.php';
?>