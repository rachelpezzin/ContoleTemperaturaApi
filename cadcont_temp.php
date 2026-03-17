<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once 'conexao.php';
$con->set_charset("utf8");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido. Use POST.']);
    exit;
}

$jsonParam = json_decode(file_get_contents('php://input'), true);

if (!$jsonParam) {
    echo json_encode(['success' => false, 'message' => 'Dados JSON inválidos ou ausentes.']);
    exit;
}

$tempLida      = isset($jsonParam['TempLida']) ? floatval($jsonParam['TempLida']) : null;
$idControlador = isset($jsonParam['controlador_idcontrolador']) ? intval($jsonParam['controlador_idcontrolador']) : null;
$dataLeitura   = !empty($jsonParam['DataLeitura']) ? date('Y-m-d H:i:s', strtotime($jsonParam['DataLeitura'])) : date('Y-m-d H:i:s');

if (is_null($tempLida) || is_null($idControlador)) {
    echo json_encode(['success' => false, 'message' => 'Temperatura ou ID do controlador ausentes.']);
    exit;
}

$stmt = $con->prepare("INSERT INTO leitura (TempLida, DataLeitura, controlador_idcontrolador) VALUES (?, ?, ?)");

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Erro na preparação: ' . $con->error]);
    exit;
}

$stmt->bind_param("dsi", $tempLida, $dataLeitura, $idControlador);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true, 
        'message' => 'Leitura registrada com sucesso!',
        'id_gerado' => $stmt->insert_id
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao gravar leitura: ' . $stmt->error]);
}

$stmt->close();
$con->close();

?>