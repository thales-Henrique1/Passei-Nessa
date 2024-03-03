<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "PASSEINESSA";

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
}

$sql = "SELECT ID, NM_MATERIA FROM TB_MATERIAS";
$resultado = $conexao->query($sql);

if ($resultado) {
    $options = "<option value='0'>Matérias</option>";

    while ($linha = $resultado->fetch_assoc()) {
        $options .= "<option value='" . $linha["ID"] . "'>" . $linha["NM_MATERIA"] . "</option>";
    }

    echo $options;
} else {
    echo "<option value='0'>Nenhuma matéria encontrada</option>";
}

$conexao->close();
?>
