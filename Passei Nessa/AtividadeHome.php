<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atividade início</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        .resultado {
            margin-top: 20px;
        }

        .resultado td:last-child {
            text-align: center;
        }

        .excluir-btn, .editar-btn {
            background: none;
            border: none;
            cursor: pointer;
        }
        .display-flex-container{
            display: flex;
        }
    </style>
</head>
<body>
    <menu class="Meu_Item">
        <a href="Home.php" class="Item_Menu">Início</a>
        <a href="AtividadeHome.php" class="Item_Menu" style="text-decoration: underline;">Atividade</a>
        <a href="MateriaHome.php" class="Item_Menu">Matérias</a>
    </menu>
    
    <div class="margin-bottom">
        <a href="CadastrarAtividade.php" >Cadastrar atividade</a>
    </div>

    <div class="card-materia">
        <h2 >Lista de Atividades realizadas</h2>

        <?php
        function excluirAtividade($id) {
            $host = "localhost";
            $usuario = "root";
            $senha = "";
            $banco = "PASSEINESSA";
            $conexao = new mysqli($host, $usuario, $senha, $banco);

            if ($conexao->connect_error) {
                die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
            }

            $sql = "DELETE FROM TB_ATIVIDADE WHERE ID = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
               // echo "<script>alert('Atividade excluída com sucesso!'); window.location.reload();</script>";
            } else {
                echo "Erro ao excluir a atividade: " . $stmt->error;
            }

            $stmt->close();
            $conexao->close();
        }

        $host = "localhost";
        $usuario = "root";
        $senha = "";
        $banco = "PASSEINESSA";
        $conexao = new mysqli($host, $usuario, $senha, $banco);

        if ($conexao->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
        }

        $sql = "SELECT A.ID, M.NM_MATERIA, A.DT_INICIO, A.NM_CONTEUDO, A.NR_HORA, A.NR_MINUTO, A.NR_SEGUNDO 
                FROM TB_ATIVIDADE A
                JOIN TB_MATERIAS M ON A.ID_MATERIA = M.ID";

        $resultado = $conexao->query($sql);

        if ($resultado->num_rows > 0) {
            echo "<center>";
            echo "<table class='resultado'>";
            echo "<tr><th>Matéria</th><th>Data</th><th>Conteúdo</th><th>Duração</th></tr>";
            while ($linha = $resultado->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $linha["NM_MATERIA"] . "</td>";
                echo "<td>" . $linha["DT_INICIO"] . "</td>";
                echo "<td>" . $linha["NM_CONTEUDO"] . "</td>";
                echo "<td>" . $linha["NR_HORA"] . "h " . $linha["NR_MINUTO"] . "min " . $linha["NR_SEGUNDO"] . "s</td>";
                echo "<td>
                        <div class='display-flex-container'>
                            <div>
                                <form method='POST' action='AtividadeHome.php'>
                                    <input type='hidden' name='id' value='" . $linha["ID"] . "'>
                                    <button type='submit' name='excluir' class='excluir-btn'>🗑️</button>
                                </form>
                            </div>
                            <div>
                                <form method='GET' action='EditarAtividade.php'>
                                    <input type='hidden' name='editar' value='true'>
                                    <input type='hidden' name='id' value='" . $linha["ID"] . "'>
                                    <button type='submit' class='editar-btn'>✏️</button>
                                </form>
                            </div>
                        </div>                 
                      </td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</center>";
        } else {
            echo "Nenhuma atividade cadastrada.";
        }
        
        if (isset($_POST["excluir"]) && isset($_POST["id"])) {
            $idExcluir = $_POST["id"];
            excluirAtividade($idExcluir);
        }

        $conexao->close();
        ?>
    </div>
    
</body>
</html>
