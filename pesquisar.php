<?php
include 'db.php';

$pesquisa = $_GET['nome'];

$sql = "SELECT * FROM candidato WHERE nome LIKE '%$pesquisa%'";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
    echo "Nome: " . $row['nome'] . "<br>";
    echo "Telefone: " . $row['telefone'] . "<br><hr>";
}
?>