<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

if(!$nome || !$email || !$senha){
    echo json_encode(["status"=>"erro","msg"=>"Preencha todos os campos"]);
    exit;
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Conta usuários
$countSql = "SELECT COUNT(*) as total FROM usuarios";
$result = $conn->query($countSql);
$row = $result->fetch_assoc();
$totalUsuarios = $row['total'];

$tipo = ($totalUsuarios < 3) ? 'admin' : 'usuario';

// Inserir
$sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nome, $email, $senhaHash, $tipo);

if ($stmt->execute()) {
    echo json_encode([
        "status"=>"sucesso",
        "msg"=>"Registro realizado com sucesso"
    ]);
} else {
    echo json_encode([
        "status"=>"erro",
        "msg"=>"Erro ao registrar"
    ]);
}

$stmt->close();
$conn->close();