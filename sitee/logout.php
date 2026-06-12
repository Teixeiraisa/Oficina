<?php
include("conexao.php");
session_destroy();
header("Location: index.php");
exit;
?>