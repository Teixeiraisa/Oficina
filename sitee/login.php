<?php

include("conexao.php");

$acao = $_GET["acao"] ?? "";

/* LOGIN */

if(isset($_POST["entrar"])){

    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    $sql = "
        SELECT *
        FROM gerencia
        WHERE usuario='$usuario'
    ";

    $resultado = $conn->query($sql);

    if($resultado->num_rows > 0){

        $dados = $resultado->fetch_assoc();

        if(
            password_verify(
                $senha,
                $dados["senha"]
            )
        ){

            $_SESSION["gerencia"] = true;

            header(
                "Location: gerencia.php"
            );

            exit;
        }
    }

    $erro = "Usuário ou senha inválidos";
}


/* CADASTRO */

if(isset($_POST["cadastrar"])){

    $usuario = $_POST["novo_usuario"];

    $senha = password_hash(
        $_POST["nova_senha"],
        PASSWORD_DEFAULT
    );

    $verifica = $conn->query(
        "SELECT id FROM gerencia WHERE usuario='$usuario'"
    );

    if($verifica->num_rows > 0){

        $erro = "Usuário já existe.";

    }else{

        $conn->query(
            "INSERT INTO gerencia
            (usuario, senha)
            VALUES
            ('$usuario','$senha')"
        );

        $sucesso =
        "Cadastro realizado com sucesso!";
    }
}

?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<link rel="stylesheet" href="login.css">

<title>Login</title>

</head>

<body>

<div class="card">

<?php if($acao == "cadastro"){ ?>

    <h1>Cadastro Gerência</h1>

    <?php
    if(isset($erro)){
        echo "<p>$erro</p>";
    }

    if(isset($sucesso)){
        echo "<p>$sucesso</p>";
    }
    ?>

    <form method="POST">

        <input
        type="text"
        name="novo_usuario"
        placeholder="Usuário"
        required>

        <input
        type="password"
        name="nova_senha"
        placeholder="Senha"
        required>

        <button
        type="submit"
        name="cadastrar">

            Cadastrar

        </button>

    </form>

    <br>

    <a href="login.php">
        Voltar ao Login
    </a>

<?php } elseif($acao == "esqueci"){ ?>

    <h1>Recuperar Senha</h1>

    <p>
        Entre em contato com a administração
        para redefinir sua senha.
    </p>

    <br>

    <a href="login.php">
        Voltar ao Login
    </a>

<?php } else { ?>

    <h1>Login Gerência</h1>

    <?php
    if(isset($erro)){
        echo "<p>$erro</p>";
    }
    ?>

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
        name="entrar">

            Entrar

        </button>

    </form>

    <div class="links">

        <br>

        <a href="?acao=cadastro">
            Não tem cadastro? Cadastre-se
        </a>

        <br><br>

        <a href="?acao=esqueci">
            Esqueci minha senha
        </a>

    </div>

<?php } ?>

</div>

</body>
</html>