<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o script de conexão existente
require_once 'conexao.php';
$con->set_charset("utf8");

// SQL para selecionar os dados da tabela 'controlador'
// Ajustado para as colunas: idcontrolador, setor e setpoint
$sql = "SELECT idcontrolador, setor, setpoint FROM controlador";

$result = $con->query($sql);

$response = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} else {
    // Retorno padrão caso a tabela esteja vazia
    $response[] = [
        "idcontrolador" => 0,
        "setor" => "",
        "setpoint" => ""
    ];
}

// Define o cabeçalho para JSON e entrega o resultado
header('Content-Type: application/json; charset=utf-8');
echo json_encode($response, JSON_UNESCAPED_UNICODE);

$con->close();
?>