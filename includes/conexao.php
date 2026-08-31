<?php
/**
 * Conexão com o banco de dados (MySQLi).
 * Toda página do sistema começa dando require_once neste arquivo.
 */

$host   = "localhost";
$usuario = "root";
$senha  = "";
$banco  = "biblioteca";

$conexao = mysqli_connect($host, $usuario, $senha, $banco);

if (!$conexao) {
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}

mysqli_set_charset($conexao, "utf8mb4");
