<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Cadastrar Atividade</title>
</head>

<body>
    <menu class="Meu_Item">
        <a href="Home.php" class="Item_Menu">Início</a>
        <a href="AtividadeHome.php" class="Item_Menu" style="text-decoration: underline;">Atividade</a>
        <a href="MateriaHome.php" class="Item_Menu">Matérias</a>
    </menu>
    <div>
        <a href="AtividadeHome.php">Voltar a lista de atividades</a>
    </div>

    <?php
    $modoEdicao = false;
    $idAtividade = "";

    if (isset($_GET['editar']) && isset($_GET['id'])) {
        $modoEdicao = true;
        $idAtividade = $_GET['id'];
    }
    ?>

    <form method="POST" action="<?php echo $modoEdicao ? 'EditarAtividade.php' : 'CadastrarAtividade.php'; ?>">
        <input type="hidden" name="modoEdicao" value="<?php echo $modoEdicao ? 'true' : 'false'; ?>">
        <input type="hidden" name="id" value="<?php echo $idAtividade; ?>">
        
        <center>
            <div class="Cards">
                <h2><?php echo $modoEdicao ? 'Edição de Atividade' : 'Cadastro de Atividade'; ?></h2>
                <div class="c1">
                    <div class="NomeMateriaContainer">
                        <b>Nome da matéria:</b><br><br>
                        <select name="materia" id="materiaSelect">
                            <option value="">Selecione</option>
                            <?php
                            $conexao = new mysqli("localhost", "root", "", "PASSEINESSA");

                            if ($conexao->connect_error) {
                                die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
                            }

                            $sql = "SELECT ID, NM_MATERIA FROM TB_MATERIAS";
                            $resultado = $conexao->query($sql);
                            if ($resultado->num_rows > 0) {
                                while ($linha = $resultado->fetch_assoc()) {
                                    $selected = ($modoEdicao && $linha["ID"] == $idAtividade) ? 'selected' : '';
                                    echo "<option value='" . $linha["ID"] . "' $selected>" . $linha["NM_MATERIA"] . "</option>";
                                }
                            }
                            $conexao->close();
                            ?>
                        </select>
                    </div>
                    <br>
                    <div class="ConteudoContainer">
                        <b>Conteúdo:</b><br>
                        <textarea name="conteudo" id="conteudoSelect" rows="4"><?php echo $modoEdicao ? 'Conteúdo existente' : ''; ?></textarea>
                    </div>
                </div>

                <div>
                    <div class="DataContainer">
                        <p>Data</p>
                        <input type="date" name="data" id="data" class="Data">

                    </div>
                    <h3 style=" text-align: left; margin-bottom: -10px;">Duração</h3>
                    <div class="Display-flex">
                        <p style="margin-left: 10px;">Horas</p><br>
                        <p style="margin-left: 20px;">Minutos</p><br>
                        <p style="margin-left: 10px;">Segundos</p><br>
                    </div>
                    <div class="Display-flex">
                        <input type="number" name="Horas" id="Horas" step="0.01">
                        <input type="number" name="Minutos" id="Minutos">
                        <input type="number" name="Segundos" id="Segundos">
                    </div>

                    <div class="btn">
                        <input type="submit" value="Salvar" class="Btn_Salvar">
                        <a href="AtividadeHome.php"><input type="button" value="Voltar" class="Btn_Cancelar"></a>
                    </div>
                </div>

            </div>
        </center>
    </form>

    <script>
        document.getElementById("materiaSelect").addEventListener("change", function() {
            var materiaId = this.value;
            var conteudoSelect = document.getElementById("conteudoSelect");
            conteudoSelect.innerHTML = "<option value=''>Selecione</option>";

            if (materiaId) {
                var xhr = new XMLHttpRequest();
                var url = "buscar_conteudo.php?materia_id=" + materiaId;
                xhr.open("GET", url, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            var conteudo = xhr.responseText;
                            console.log(conteudo);
                            conteudoSelect.value = conteudo;
                        } else {
                            console.error("Erro na solicitação: " + xhr.status);
                        }
                    }
                };
                xhr.send();
            }
        });
    </script>

</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modoEdicao = $_POST["modoEdicao"] === "true";
    $idEditar = $_POST["id"];
    $idMateria = $_POST["materia"];
    $conteudo = $_POST["conteudo"];
    $data = $_POST["data"];
    $horas = $_POST["Horas"];
    $minutos = $_POST["Minutos"];
    $segundos = $_POST["Segundos"];

    $data = date('Y-m-d', strtotime(str_replace('/', '-', $data)));

    $conexao = new mysqli("localhost", "root", "", "PASSEINESSA");

    if ($conexao->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
    }

    if ($modoEdicao) {
        $sql = "UPDATE TB_ATIVIDADE SET ID_MATERIA = ?, DT_INICIO = ?, NM_CONTEUDO = ?, NR_HORA = ?, NR_MINUTO = ?, NR_SEGUNDO = ? WHERE ID = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("isssiii", $idMateria, $data, $conteudo, $horas, $minutos, $segundos, $idEditar);
    } else {
        $sql = "INSERT INTO TB_ATIVIDADE (ID_MATERIA, DT_INICIO, NM_CONTEUDO, NR_HORA, NR_MINUTO, NR_SEGUNDO) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("isssii", $idMateria, $data, $conteudo, $horas, $minutos, $segundos);
    }

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $mensagem = $modoEdicao ? 'Atividade editada com sucesso!' : 'Atividade cadastrada com sucesso!';
        echo "<script>alert('$mensagem'); window.location.href='AtividadeHome.php';</script>";
    } else {
        $erro = $modoEdicao ? 'Erro ao editar a atividade' : 'Erro ao cadastrar a atividade';
        echo "$erro: " . $stmt->error;
    }

    $stmt->close();
    $conexao->close();
}
?>
