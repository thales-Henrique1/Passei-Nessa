<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "PASSEINESSA";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if (isset($_POST['idMateria'], $_POST['idConteudo'], $_POST['nrHora'], $_POST['nrMinuto'], $_POST['nrSegundo'])) {
    $idMateria = intval($_POST['idMateria']);
    $idConteudo = $_POST['idConteudo'];
    $nrHora = intval($_POST['nrHora']);
    $nrMinuto = intval($_POST['nrMinuto']);
    $nrSegundo = intval($_POST['nrSegundo']);

    // Obtém a data e hora atual no formato 'Y-m-d H:i:s'
    $dataHoraAtual = date('Y-m-d H:i:s');

    $stmtConteudo = $conn->prepare("SELECT NM_CONTEUDO FROM TB_MATERIAS WHERE ID = ?");
    $stmtConteudo->bind_param("i", $idConteudo);

    if ($stmtConteudo->execute()) {
        $stmtConteudo->bind_result($nomeConteudo);
        $stmtConteudo->fetch();
        $stmtConteudo->close();

        $stmt = $conn->prepare("INSERT INTO TB_ATIVIDADE (ID_MATERIA, DT_INICIO, NM_CONTEUDO, NR_HORA, NR_MINUTO, NR_SEGUNDO) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issiii", $idMateria, $dataHoraAtual, $nomeConteudo, $nrHora, $nrMinuto, $nrSegundo);

        if ($stmt->execute()) {
            echo "Atividade salva com sucesso!";
        } else {
            echo "Erro ao salvar atividade: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Erro ao recuperar o nome do conteúdo: " . $stmtConteudo->error;
    }
} else {
    echo "Parâmetros inválidos.";
}

$conn->close();
?>
