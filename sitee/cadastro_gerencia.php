<?php

include("conexao.php");

$mensagem = "";

if(isset($_POST["cadastrar"])){

    $usuario = trim($_POST["usuario"]);
    $senha = password_hash(
        $_POST["senha"],
        PASSWORD_DEFAULT
    );

    $verifica = $conn->query(
        "SELECT id FROM gerencia WHERE usuario='$usuario'"
    );

    if($verifica->num_rows > 0){

        $mensagem = "Usuário já existe!";

    }else{

        $sql = "
        INSERT INTO gerencia
        (usuario, senha)
        VALUES
        ('$usuario', '$senha')
        ";

        if($conn->query($sql)){
            $mensagem = "Cadastro realizado com sucesso!";
        }else{
            $mensagem = "Erro ao cadastrar.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Cadastro da Gerência</title>
<link rel="stylesheet" href="login.css">
</head>

<body>

<div class="card">

    <h1>Cadastrar Gerência</h1>

    <?php if($mensagem != ""){ ?>
        <p><?php echo $mensagem; ?></p>
    <?php } ?>

    <form method="POST"> 

        <input
        type="text"
        name="usuario"
        placeholder="Usuário"
        required>

        <input
        type="password"
        name="senha"
        placeholder="Senha"
        required>

        <button
        type="submit"
        name="cadastrar">

            Cadastrar

        </button>

    </form>

</div>

</body>
</html>