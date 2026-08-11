<?php

include 'includes/conexao.php';

$sql = "SELECT * FROM autor";

$resultado = mysqli_query(
$conexao,
$sql
);

include 'includes/header.php';
include 'includes/menu.php';

?>

<h2>Autores</h2>

<table class="table table-bordered">

<tr>
<th>ID</th>
<th>Nome</th>
<th>Nacionalidade</th>
</tr>

<?php while($autor = mysqli_fetch_assoc($resultado)){ ?>

<tr>

<td><?= $autor['id_autor'] ?></td>

<td><?= $autor['nome'] ?></td>

<td><?= $autor['nacionalidade'] ?></td>

</tr>

<?php } ?>

</table>

<?php
include 'includes/footer.php';
?>