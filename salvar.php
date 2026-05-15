<?php
include 'db.php';

// DADOS DO FORM
$nome = $_POST['nome'];
$sexo = $_POST['sexo'];
$data = $_POST['datanascimento'];
$telefone = $_POST['telefone'];

$nomeenc = $_POST['nomeenc'];
$prof = $_POST['profiss'];
$telenc = $_POST['telefoneenc'];

$curso = $_POST['curso'];
$data_inscricao = $_POST['datainscricso'];

// UPLOAD DE IMAGENS
$pasta = "uploads/";

// nomes únicos
$bi_frente = uniqid() . "_" . $_FILES['bi_frente']['name'];
$bi_verso = uniqid() . "_" . $_FILES['bi_verso']['name'];
$cert = uniqid() . "_" . $_FILES['cert']['name'];

// mover ficheiros
move_uploaded_file(
    $_FILES['bi_frente']['tmp_name'],
    $pasta . $bi_frente
);

move_uploaded_file(
    $_FILES['bi_verso']['tmp_name'],
    $pasta . $bi_verso
);

move_uploaded_file(
    $_FILES['cert']['tmp_name'],
    $pasta . $cert
);

// INSERIR CANDIDATO
$sql1 = "INSERT INTO candidato (nome, sexo, data_nascimento, telefone, foto_bi_frente, foto_bi_verso, certificado)
VALUES ('$nome','$sexo','$data','$telefone','$bi_frente','$bi_verso','$cert')";
$conn->query($sql1);
$candidato_id = $conn->insert_id;

// INSERIR ENCARREGADO
$sql2 = "INSERT INTO encarregado (nome_enc, telefone_enc, profissao, candidato_id)
VALUES ('$nomeenc','$telenc','$prof','$candidato_id')";
$conn->query($sql2);

// INSERIR CURSO
$sql3 = "INSERT INTO cursos (nome_curso, data_inscricao, candidato_id)
VALUES ('$curso','$data_inscricao','$candidato_id')";
$conn->query($sql3);

// Redireciona de volta para o formulário com notificação de sucesso
header("Location: kalandula.html?success=1");
exit;
?>

<?php

// depois de salvar no banco

if($salvou){
    echo "sucesso";
}else{
    echo "erro";
}

?>