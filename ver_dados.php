<?php
session_start();
if (!isset($_SESSION['admin'])) {
    die("Acesso negado.");
}

include 'db.php';

// Verifica se existe filtro de pesquisa
$filtro = "";
if (isset($_GET['curso']) && $_GET['curso'] != "") {
    $cursoPesquisa = $conn->real_escape_string($_GET['curso']);
    $filtro = "WHERE cu.nome_curso LIKE '%$cursoPesquisa%'";
}

// Consulta com JOIN
$sql = "
SELECT 
    c.id,
    c.nome,
    c.sexo,
    c.data_nascimento,
    c.telefone,

    c.foto_bi_frente,
    c.foto_bi_verso,
    c.certificado,

    e.nome_enc,
    e.telefone_enc,
    e.profissao,
    cu.nome_curso,
    cu.data_inscricao
FROM candidato c
LEFT JOIN encarregado e ON c.id = e.candidato_id
LEFT JOIN cursos cu ON c.id = cu.candidato_id
$filtro
ORDER BY c.id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="UTF-8">
<title>Painel do Servidor</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f0f2f5;
    margin: 0;
    padding: 0;
}
header {
    background: #2d509c;
    color: white;
    padding: 15px 30px;
    text-align: center;
}
h1 {
    margin: 0;
    font-size: 24px;
}
.container {
    width: 95%;
    margin: 20px auto;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(102, 104, 230, 0.1);
}
input[type="text"] {
    padding: 10px;
    width: 300px;
    border-radius: 5px;
    border: 1px solid #ccc;
}
button {
    padding: 10px 15px;
    border-radius: 5px;
    border: none;
    background: #4369bb;
    color: white;
    cursor: pointer;
}
button:hover {
    background: #5d9bda;
;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    border: 1px solid #7292eb;
    padding: 12px;
    text-align: center;
}
th {
    background: #4369bb;
    background: ;
    color: white;
}
tr:nth-child(even) {
    background: #f9f9f9;
}
tr:hover {
    background: #f1f1f1;
}
</style>
</head>
<body>

<header>
    <h1>Lista de Candidatos</h1>
</header>

<div class="container">
    <form method="get">
        <input type="text" name="curso" placeholder="Pesquisar Cursos" value="<?php echo isset($_GET['curso']) ? htmlspecialchars($_GET['curso']) : ''; ?>">
        <button type="submit">Pesquisar</button>
        <a href="ver_dados.php"><button type="button">Todos</button></a>
        <a href='Documentos.php?id=".$row['id']."' target='_blank'>
        <button type='button'>Documentos</button>
        </a>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Sexo</th>
            <th>Data Nascimento</th>
            <th>Telefone</th>
            <th>Encarregado</th>
            <th>Telefone Enc.</th>
            <th>Profissão</th>
            <th>Curso</th>
            <th>Data Inscrição</th>
        </tr>

        <?php
$contador = 1;

if ($result && $result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {

        echo "<tr>";

        echo "<td>".$contador."</td>";
        echo "<td>".htmlspecialchars($row['nome'])."</td>";
        echo "<td>".htmlspecialchars($row['sexo'])."</td>";
        echo "<td>".htmlspecialchars($row['data_nascimento'])."</td>";
        echo "<td>".htmlspecialchars($row['telefone'])."</td>";
        echo "<td>".htmlspecialchars($row['nome_enc'])."</td>";
        echo "<td>".htmlspecialchars($row['telefone_enc'])."</td>";
        echo "<td>".htmlspecialchars($row['profissao'])."</td>";
        echo "<td>".htmlspecialchars($row['nome_curso'])."</td>";
        echo "<td>".htmlspecialchars($row['data_inscricao'])."</td>";

        $contador++;
    }

} else {

    echo "<tr><td colspan='11'>Sem dados</td></tr>";

}
?>

</div>

<img id="serverImg" width="200"/>
<script>
function mostrar() {
  const img = localStorage.getItem("imagemGuardada");

  if (!img) {
    alert("Nenhuma imagem armazenada.");
    return;
  }

  document.getElementById("serverImg").src = img;
}

// ficheiros
$frente = upload("foto_bi_frente", $pasta);
$verso = upload("foto_bi_verso", $pasta);
$certificado = upload("certificado", $pasta);
</script>

</body>
</html>