<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Matéria</title>
</head>
<body>
    <menu class="Meu_Item">
        <a href="Home.php" class="Item_Menu">Início</a>
        <a href="AtividadeHome.php" class="Item_Menu">Atividade</a>
        <a href="MateriaHome.php" class="Item_Menu" style="text-decoration: underline;">Matérias</a>
    </menu>
    <center>
        <div >
            <h2>Editar Matéria</h2>

            <?php
            if (isset($_POST["editar"]) && isset($_POST["id"])) {
                $idEditar = $_POST["id"];

                $host = "localhost";
                $usuario = "root";
                $senha = "";
                $banco = "PASSEINESSA";
                $conexao = new mysqli($host, $usuario, $senha, $banco);

                if ($conexao->connect_error) {
                    die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
                }

                $sql = "SELECT ID, NM_MATERIA, NM_CONTEUDO FROM TB_MATERIAS WHERE ID = ?";
                $stmt = $conexao->prepare($sql);
                $stmt->bind_param("i", $idEditar);
                $stmt->execute();
                $stmt->bind_result($id, $nmMateria, $nmConteudo);
                $stmt->fetch();
                $stmt->close();
                $conexao->close();
            }
            ?>

            <form method="POST" action="atualizar_materia.php">
                <div class="Card">
                    <div class="c1">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <label for="nmMateria">Matéria:</label>
                        <br>
                        <input type="text" name="nmMateria"  class="input_Text"value="<?php echo $nmMateria; ?>" required>
                        <br>
                        <label for="nmConteudo" >Conteúdo:</label>
                        <br>
                        <input type="text" name="nmConteudo" class="input_Text" value="<?php echo $nmConteudo; ?>" required>
                    </div>
                    <div class="btn">
                        <input type="submit" value="Salvar"  class="Btn_Salvar">
                        <a href="MateriaHome.php"><input type="button" value="Voltar" class="Btn_Cancelar"></a>
                    </div>
                </div>
            </form>
        </div>
    </center>    
</body>
</html>
