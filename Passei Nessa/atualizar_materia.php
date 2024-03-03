<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $nmMateria = $_POST["nmMateria"];
    $nmConteudo = $_POST["nmConteudo"];

    $host = "localhost";
    $usuario = "root";
    $senha = "";
    $banco = "PASSEINESSA";
    $conexao = new mysqli($host, $usuario, $senha, $banco);

    if ($conexao->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
    }

    $sql = "UPDATE TB_MATERIAS SET NM_MATERIA = ?, NM_CONTEUDO = ? WHERE ID = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssi", $nmMateria, $nmConteudo, $id);

    if ($stmt->execute()) {
        echo "Matéria atualizada com sucesso!";
        echo "<script>window.location.href = 'MateriaHome.php';</script>";
    } else {
        echo "Erro ao atualizar a matéria: " . $stmt->error;
    }

    $stmt->close();
    $conexao->close();
}
?>
