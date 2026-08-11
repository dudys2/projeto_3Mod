<?php

function limpar($valor){
    return trim(htmlspecialchars($valor));
}

function validarTexto($texto){
    return !empty(trim($texto));
}

function validarNumeroPositivo($numero){
    return is_numeric($numero) && $numero >= 0;
}

/*
|--------------------------------------------------------------------------
| FUNÇÕES QUE FALTAVAM NO PROJETO
|--------------------------------------------------------------------------
*/

function contarRegistros($conexao, $tabela){
    $sql = "SELECT COUNT(*) AS total FROM $tabela";
    $resultado = mysqli_query($conexao, $sql);

    if(!$resultado){
        return 0;
    }

    $dados = mysqli_fetch_assoc($resultado);
    return (int)$dados['total'];
}

function listarDados($conexao, $tabela){
    $sql = "SELECT * FROM $tabela";
    return mysqli_query($conexao, $sql);
}

/*
|--------------------------------------------------------------------------
| FILTRO EM ARRAY (TECH FORGE)
|--------------------------------------------------------------------------
| Retorna apenas os itens que contenham o valor buscado no campo informado
*/
function filtrarLista($lista, $campo, $valor){
    $filtrados = [];

    foreach($lista as $item){
        if(isset($item[$campo]) && stripos($item[$campo], $valor) !== false){
            $filtrados[] = $item;
        }
    }

    return $filtrados;
}

/*
|--------------------------------------------------------------------------
| REGRAS DE NEGÓCIO / VALIDAÇÕES
|--------------------------------------------------------------------------
*/

function validarLivro($dados){
    $erros = [];

    if(!isset($dados['titulo']) || !validarTexto($dados['titulo'])){
        $erros[] = "O título do livro é obrigatório.";
    }

    if(!isset($dados['isbn']) || !validarTexto($dados['isbn'])){
        $erros[] = "O ISBN é obrigatório.";
    }

    if(!isset($dados['ano']) || !is_numeric($dados['ano'])){
        $erros[] = "O ano precisa ser numérico.";
    } else {
        $ano = (int)$dados['ano'];
        $anoAtual = date('Y');

        if($ano < 1000 || $ano > $anoAtual){
            $erros[] = "Ano inválido.";
        }
    }

    if(!isset($dados['quantidade']) || !validarNumeroPositivo($dados['quantidade'])){
        $erros[] = "A quantidade precisa ser 0 ou maior.";
    }

    return $erros;
}

function validarUsuario($dados){
    $erros = [];

    if(!isset($dados['nome']) || !validarTexto($dados['nome'])){
        $erros[] = "O nome do usuário é obrigatório.";
    }

    if(!isset($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)){
        $erros[] = "E-mail inválido.";
    }

    return $erros;
}

function validarEmprestimo($idUsuario, $idLivro, $dataEmprestimo, $dataDevolucao){
    $erros = [];

    if(empty($idUsuario)){
        $erros[] = "Selecione um usuário.";
    }

    if(empty($idLivro)){
        $erros[] = "Selecione um livro.";
    }

    if(empty($dataEmprestimo)){
        $erros[] = "Informe a data do empréstimo.";
    }

    if(empty($dataDevolucao)){
        $erros[] = "Informe a data de devolução.";
    }

    if(!empty($dataEmprestimo) && !empty($dataDevolucao)){
        if($dataDevolucao < $dataEmprestimo){
            $erros[] = "A devolução não pode ser anterior ao empréstimo.";
        }
    }

    return $erros;
}

function buscarLivroPorId($conexao, $idLivro){
    $idLivro = (int)$idLivro;

    $sql = "SELECT * FROM livro WHERE id_livro = $idLivro LIMIT 1";
    $resultado = mysqli_query($conexao, $sql);

    if($resultado && mysqli_num_rows($resultado) > 0){
        return mysqli_fetch_assoc($resultado);
    }

    return null;
}

function buscarUsuarioPorId($conexao, $idUsuario){
    $idUsuario = (int)$idUsuario;

    $sql = "SELECT * FROM usuario WHERE id_usuario = $idUsuario LIMIT 1";
    $resultado = mysqli_query($conexao, $sql);

    if($resultado && mysqli_num_rows($resultado) > 0){
        return mysqli_fetch_assoc($resultado);
    }

    return null;
}

function podeEmprestar($livro){
    if(!$livro){
        return false;
    }

    return isset($livro['quantidade']) && (int)$livro['quantidade'] > 0;
}

function diminuirQuantidadeLivro($conexao, $idLivro){
    $idLivro = (int)$idLivro;
    $sql = "UPDATE livro SET quantidade = quantidade - 1 WHERE id_livro = $idLivro AND quantidade > 0";
    return mysqli_query($conexao, $sql);
}

function aumentarQuantidadeLivro($conexao, $idLivro){
    $idLivro = (int)$idLivro;
    $sql = "UPDATE livro SET quantidade = quantidade + 1 WHERE id_livro = $idLivro";
    return mysqli_query($conexao, $sql);
}

/*
|--------------------------------------------------------------------------
| CADASTRO DE EMPRÉSTIMO COM REGRA DE NEGÓCIO
|--------------------------------------------------------------------------
| - valida campos
| - verifica se livro existe
| - verifica se há estoque
| - cria empréstimo
| - baixa 1 unidade do livro
*/
function cadastrarEmprestimo($conexao, $idUsuario, $idLivro, $dataEmprestimo, $dataDevolucao){
    $erros = validarEmprestimo($idUsuario, $idLivro, $dataEmprestimo, $dataDevolucao);

    if(!empty($erros)){
        return [
            'sucesso' => false,
            'mensagem' => implode('<br>', $erros)
        ];
    }

    $usuario = buscarUsuarioPorId($conexao, $idUsuario);
    if(!$usuario){
        return [
            'sucesso' => false,
            'mensagem' => 'Usuário não encontrado.'
        ];
    }

    $livro = buscarLivroPorId($conexao, $idLivro);
    if(!$livro){
        return [
            'sucesso' => false,
            'mensagem' => 'Livro não encontrado.'
        ];
    }

    if(!podeEmprestar($livro)){
        return [
            'sucesso' => false,
            'mensagem' => 'Não é possível emprestar: livro sem estoque.'
        ];
    }

    mysqli_begin_transaction($conexao);

    try {
        $idUsuario = (int)$idUsuario;
        $idLivro = (int)$idLivro;
        $dataEmprestimo = mysqli_real_escape_string($conexao, $dataEmprestimo);
        $dataDevolucao = mysqli_real_escape_string($conexao, $dataDevolucao);

        $sqlEmprestimo = "
            INSERT INTO emprestimo (id_usuario, id_livro, data_emprestimo, data_devolucao)
            VALUES ($idUsuario, $idLivro, '$dataEmprestimo', '$dataDevolucao')
        ";

        $okEmprestimo = mysqli_query($conexao, $sqlEmprestimo);

        if(!$okEmprestimo){
            throw new Exception("Erro ao cadastrar empréstimo.");
        }

        $okBaixa = diminuirQuantidadeLivro($conexao, $idLivro);

        if(!$okBaixa || mysqli_affected_rows($conexao) <= 0){
            throw new Exception("Erro ao atualizar a quantidade do livro.");
        }

        mysqli_commit($conexao);

        return [
            'sucesso' => true,
            'mensagem' => 'Empréstimo cadastrado com sucesso.'
        ];
    } catch (Exception $e){
        mysqli_rollback($conexao);

        return [
            'sucesso' => false,
            'mensagem' => $e->getMessage()
        ];
    }
}

/*
|--------------------------------------------------------------------------
| DEVOLUÇÃO DE LIVRO
|--------------------------------------------------------------------------
*/
function devolverEmprestimo($conexao, $idEmprestimo){
    $idEmprestimo = (int)$idEmprestimo;

    $sql = "SELECT * FROM emprestimo WHERE id_emprestimo = $idEmprestimo LIMIT 1";
    $resultado = mysqli_query($conexao, $sql);

    if(!$resultado || mysqli_num_rows($resultado) === 0){
        return [
            'sucesso' => false,
            'mensagem' => 'Empréstimo não encontrado.'
        ];
    }

    $emprestimo = mysqli_fetch_assoc($resultado);
    $idLivro = (int)$emprestimo['id_livro'];

    mysqli_begin_transaction($conexao);

    try{
        $sqlDelete = "DELETE FROM emprestimo WHERE id_emprestimo = $idEmprestimo";
        $okDelete = mysqli_query($conexao, $sqlDelete);

        if(!$okDelete){
            throw new Exception("Erro ao remover empréstimo.");
        }

        $okLivro = aumentarQuantidadeLivro($conexao, $idLivro);

        if(!$okLivro){
            throw new Exception("Erro ao devolver quantidade ao livro.");
        }

        mysqli_commit($conexao);

        return [
            'sucesso' => true,
            'mensagem' => 'Livro devolvido com sucesso.'
        ];
    } catch(Exception $e){
        mysqli_rollback($conexao);

        return [
            'sucesso' => false,
            'mensagem' => $e->getMessage()
        ];
    }
}