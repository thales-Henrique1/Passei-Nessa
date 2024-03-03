<?php
if (isset($_GET["materia_id"])) {
    $materiaId = $_GET["materia_id"];

    $conexao = new mysqli("localhost", "root", "", "PASSEINESSA");

    if ($conexao->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
    }

    $sql = "SELECT NM_CONTEUDO FROM TB_MATERIAS WHERE ID= $materiaId";
    $resultado = $conexao->query($sql);

    if ($resultado->num_rows > 0) {
        $linha = $resultado->fetch_assoc();
        echo $linha["NM_CONTEUDO"];
    }

    $conexao->close();
}
?>
