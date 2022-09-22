<?php
            session_start();
            require 'classes/conexao.class.php';

            $senha = $_SESSION['senha'];
            $user = $_SESSION['usuario'];
            $c = $pdo->prepare("SELECT usuario, senha FROM usuario WHERE usuario = '$user' AND senha = '$senha' ");
            $c->execute();

            return $c;
?>
