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
img{
    border-radius:8px;
    border:1px solid #ccc;
    object-fit:cover;
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
            <th>BI_Frente</th>
            <th>BI_Verso</th>
            <th>Certificado</th>
            <th>Curso</th>
        </tr>

        <?php
$contador = 1;

if ($result && $result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {

        echo "<tr>";

        echo "<td>".$contador."</td>";
        echo "<td>".htmlspecialchars($row['nome'])."</td>";
        echo "<td>
        <a href='uploads/".$row['foto_bi_frente']."' target='_blank'>
            <img src='uploads/".$row['foto_bi_frente']."' width='120'>
        </a>
      </td>";

        echo "<td>
        <a href='uploads/".$row['foto_bi_verso']."' target='_blank'>
            <img src='uploads/".$row['foto_bi_verso']."' width='120'>
        </a>
      </td>";

echo "<td>
        <a href='uploads/".$row['certificado']."' target='_blank'>
            <img src='uploads/".$row['certificado']."' width='120'>
        </a>
      </td>";
        echo "<td>".htmlspecialchars($row['nome_curso'])."</td>";

        echo "</tr>";

        $contador++;
    }

} else {

    echo "<tr><td colspan='11'>Sem dados</td></tr>";

}
?>

</div>

<img id="serverImg" width="200"/>

</body>
</html>