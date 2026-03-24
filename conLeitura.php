<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define o cabeçalho para JSON
header('Content-Type: application/json; charset=utf-8');

// Inclui a conexão (ajuste o nome do arquivo se necessário)
require_once 'conexao.php';
$con->set_charset("utf8");

// SQL com JOIN selecionando exatamente os campos solicitados
$sql = "SELECT 
            l.controlador_idcontrolador, 
            l.TempLida, 
            l.DataLeitura, 
            c.setor, 
            c.setpoint 
        FROM leitura l 
        JOIN controlador c ON l.controlador_idcontrolador = c.idcontrolador";

$result = $con->query($sql);

$response = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Montando o array com todos os campos solicitados
        $response[] = [
            "controlador_idcontrolador" => (int)$row['controlador_idcontrolador'],
            "TempLida"                  => (float)$row['TempLida'],
            "DataLeitura"               => $row['DataLeitura'],
            "setor"                     => $row['setor'],
            "setpoint"                  => (float)$row['setpoint']
        ];
    }
} else {
    // Caso não encontre registros, retorna um array com valores zerados/nulos
    $response[] = [
        "controlador_idcontrolador" => 0,
        "TempLida"                  => 0.0,
        "DataLeitura"               => "",
        "setor"                     => "Sem dados",
        "setpoint"                  => 0.0
    ];
}

// Retorna o JSON final preservando caracteres especiais (como acentos no setor)
echo json_encode($response, JSON_UNESCAPED_UNICODE);

$con->close();
?>