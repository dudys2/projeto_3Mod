<?php
require_once __DIR__ . '/../includes/conexao.php';

header('Content-Type: application/json; charset=utf-8');

// Consumida pelo fetch em ts/api.ts
$resultado = mysqli_query($conexao, "SELECT * FROM vw_emprestimos_detalhados");
$dados = [];

while ($linha = mysqli_fetch_assoc($resultado)) {
    // ajusta os tipos pra bater com a interface TypeScript
    $linha['valor_multa'] = (float)$linha['valor_multa'];
    $linha['dias_atraso'] = (int)$linha['dias_atraso'];
    $dados[] = $linha;
}

echo json_encode($dados);
