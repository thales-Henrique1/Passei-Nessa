<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
<title>Cadastrar Matéria</title>
</head>
<body>
    <menu class="Meu_Item">
        <a href="Home.php" class="Item_Menu">Início</a>
        <a href="AtividadeHome.php" class="Item_Menu">Atividade</a>
        <a href="MateriaHome.php" class="Item_Menu" style="text-decoration: underline;">Matérias</a>
    </menu>
    <div>
        <a href="MateriaHome.php">Voltar a lista de matérias</a>
    </div>

    <form method="POST" action="CadastrarMateria.php"> 
        <center>
            <div class="Card">
                <h2>Cadastro de matéria</h2>
                <div class="c1">
                    <b>Nome:</b><br><br>
                    <input type="text" placeholder="Nome da matéria" class="input_Text" name="nome"><br><br>
                    <b>Conteúdo:</b><br>
                    <input type="text" placeholder="Adicionar conteúdo nessa matéria" class="input_Text" name="conteudo"><br>
                </div>
                <div class="btn">
                    <input type="submit" value="Salvar" class="Btn_Salvar" onclick="redirecionarParaMateriaHome()">
                    <a href="MateriaHome.php"><input type="button" value="Voltar" class="Btn_Cancelar"></a>
                </div>
            </div>
        </center>
    </form>
</body>
</html>
<script>
        function redirecionarParaMateriaHome() {
            window.location.href = "MateriaHome.php";
        }
</script>

<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $host = "localhost";
        $usuario = "root";
        $senha = "";
        $banco = "PASSEINESSA";

        $conexao = new mysqli($host, $usuario, $senha, $banco);

        if ($conexao->connect_error) {
            die("Erro na conexão com o banco de dados: " . $conexao->connect_error);
        }

        $nomeMateria = $_POST['nome'];
        $conteudoMateria = $_POST['conteudo'];

        $sql = "INSERT INTO TB_MATERIAS (NM_MATERIA, NM_CONTEUDO) VALUES (?, ?)";
        $stmt = $conexao->prepare($sql);

        if ($stmt === false) {
            die("Erro na preparação da instrução SQL: " . $conexao->error);
        }

        if (!$stmt->bind_param("ss", $nomeMateria, $conteudoMateria)) {
            die("Erro ao vincular os parâmetros: " . $stmt->error);
        }

        if ($stmt->execute()) {
            echo "<script>alert('Matéria cadastrada com sucesso!');</script>";
        } else {
            echo "Erro ao cadastrar a matéria: " . $stmt->error;
        }

        $stmt->close();
        $conexao->close();
    }
?>

