<?php
session_start();
include 'db.php';

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $usuario = $result->fetch_assoc();

    if (password_verify($senha, $usuario['senha'])) {

        if ($usuario['tipo'] === 'admin') {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['admin'] = true;

            header("Location: ver_dados.php");
            exit;

        } else {
            header("Location: login.html?error=nao_admin");
            exit;
        }

    } else {
        header("Location: login.html?error=senha");
        exit;
    }

} else {
    header("Location: login.html?error=email");
    exit;
}

$stmt->close();
$conn->close();
?>