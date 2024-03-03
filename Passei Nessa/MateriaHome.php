<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materia início</title>
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
        <a href="AtividadeHome.php" class="Item_Menu">Atividade</a>
        <a href="MateriaHome.php" class="Item_Menu" style="text-decoration: underline;">Matérias</a>
    </menu>
    <div class="margin-botom">
        <a href="CadastrarMateria.php">Cadastrar matéria</a>
    </div>

    <div class="card-materia">
        <h2>Lista de Matérias</h2>
        <?php
        function verificarAtividadesVinculadas($id) {
            $conexao = new mysqli("localhost", "root", "", "PASSEINESSA");

            if ($conexao->connect_error) {
                die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
            }

            $sql = "SELECT COUNT(*) as total FROM TB_ATIVIDADE WHERE ID_MATERIA = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            $resultado = $stmt->get_result();
            $linha = $resultado->fetch_assoc();

            $stmt->close();
            $conexao->close();

            return $linha['total'] > 0;
        }

        function excluirMateria($id) {
            $conexao = new mysqli("localhost", "root", "", "PASSEINESSA");

            if ($conexao->connect_error) {
                die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
            }

            $stmt = null;

            if (verificarAtividadesVinculadas($id)) {
                echo "<script>alert('Não é possível excluir a matéria. Existem atividades vinculadas a ela.');</script>";
            } else {
                $stmt = $conexao->prepare("DELETE FROM TB_MATERIAS WHERE ID = ?");
                $stmt->bind_param("i", $id);

                if ($stmt->execute()) {
                    //echo "<script>alert('Matéria excluída com sucesso!'); window.location.reload();</script>";
                } else {
                    echo "Erro ao excluir a matéria: " . $stmt->error;
                }
            }

            if ($stmt instanceof mysqli_stmt) {
                $stmt->close();
            }

            $conexao->close();
        }

        $conexao = new mysqli("localhost", "root", "", "PASSEINESSA");

        if ($conexao->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
        }

        $sql = "SELECT ID, NM_MATERIA, NM_CONTEUDO FROM TB_MATERIAS";
        $resultado = $conexao->query($sql);

        if ($resultado->num_rows > 0) {
            echo "<center>";
            echo "<table class='resultado'>
            <tr>
                <th>Matéria</th>
                <th>Conteúdo</th>
                <th></th>
            </tr>";

            while ($linha = $resultado->fetch_assoc()) {
                echo "<tr>
                        <td>" . $linha["NM_MATERIA"] . "</td>
                        <td>" . $linha["NM_CONTEUDO"] . "</td>
                        <td>
                            <div class='display-flex-container'>
                                <div>
                                    <form method='POST' action='MateriaHome.php'>
                                        <input type='hidden' name='id' value='" . $linha["ID"] . "'>
                                        <button type='submit' name='excluir' class='excluir-btn'>🗑️</button>
                                    </form>
                                </div>
                                <div>
                                <form method='POST' action='EditarMateria.php'>
                                    <input type='hidden' name='id' value='" . $linha["ID"] . "'>
                                    <button type='submit' name='editar' class='editar-btn'>✏️</button>
                                </form>
                                </div>
                            </div>
                        </td>
                      </tr>";
            }

            echo "</table>";
            echo "</center>";
        } else {
            echo "Nenhuma matéria cadastrada ainda.";
        }

        if (isset($_POST["excluir"]) && isset($_POST["id"])) {
            $idExcluir = $_POST["id"];
            excluirMateria($idExcluir);
        }

        $conexao->close();
        ?>
    </div>    
</body>
</html>
