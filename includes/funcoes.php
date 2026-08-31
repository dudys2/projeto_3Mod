<?php
/**
 * Funções utilitárias e de acesso a dados do sistema.
 */

function validarTexto($texto) {
    return !empty(trim($texto));
}

function validarNumeroPositivo($numero) {
    return is_numeric($numero) && $numero >= 0;
}

// Escolhe uma cor de capa sempre igual pro mesmo título (sem precisar guardar no banco)
function corCapa($titulo) {
    return (crc32($titulo) % 6) + 1;
}

function listarDados($conexao, $tabela) {
    return mysqli_query($conexao, "SELECT * FROM $tabela");
}

function buscarPorId($conexao, $tabela, $id) {
    $stmt = mysqli_prepare($conexao, "SELECT * FROM $tabela WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

// CRUD 
function inserirRegistro($conexao, $tabela, $dados) {
    $colunas = implode(", ", array_keys($dados));
    $marcadores = implode(", ", array_fill(0, count($dados), "?"));
    $tipos = str_repeat("s", count($dados));

    $stmt = mysqli_prepare($conexao, "INSERT INTO $tabela ($colunas) VALUES ($marcadores)");
    mysqli_stmt_bind_param($stmt, $tipos, ...array_values($dados));

    return mysqli_stmt_execute($stmt);
}

function atualizarRegistro($conexao, $tabela, $dados, $id) {
    $set = implode(" = ?, ", array_keys($dados)) . " = ?";
    $tipos = str_repeat("s", count($dados)) . "i";

    $parametros = array_values($dados);
    $parametros[] = $id;

    $stmt = mysqli_prepare($conexao, "UPDATE $tabela SET $set WHERE id = ?");
    mysqli_stmt_bind_param($stmt, $tipos, ...$parametros);

    return mysqli_stmt_execute($stmt);
}

function excluirRegistro($conexao, $tabela, $id) {
    $stmt = mysqli_prepare($conexao, "DELETE FROM $tabela WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    return mysqli_stmt_execute($stmt);
}

// Regra de negócio: não deixa excluir um livro que está emprestado no momento
function excluirLivro($conexao, $id) {
    $stmt = mysqli_prepare(
        $conexao,
        "SELECT COUNT(*) AS total FROM emprestimos WHERE livro_id = ? AND status = 'ativo'"
    );
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $dados = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($dados['total'] > 0) {
        return [
            "sucesso" => false,
            "mensagem" => "Não dá pra excluir: esse livro está emprestado no momento."
        ];
    }

    excluirRegistro($conexao, "livros", $id);
    return ["sucesso" => true, "mensagem" => "Livro excluído."];
}

// Chama a stored procedure de busca + filtro + paginação
function listarEmprestimos($conexao, $status = null, $busca = null, $pagina = 1, $itens = 10) {
    $stmt = mysqli_prepare($conexao, "CALL sp_listar_emprestimos(?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssii", $status, $busca, $pagina, $itens);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
