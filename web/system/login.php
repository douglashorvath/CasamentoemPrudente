<?php
    require("classes/validarconexao.php");
    //require("classes/conexao.class.php");
    require("classes/Template.class.php");
    require("classes/header_source.class.php");
    
     
    if($c->rowCount())
    {
        header($headerscr."index.php");
    }
    else
    {
        $tpl = new Template("html/login.html");

        if(isset($_POST["enviar"])){

            $user = $_POST["login"];
            $senha = md5($_POST["senha"]);

            $login = $pdo->prepare("SELECT login, senha FROM sys_admin WHERE login = '$user' AND senha = '$senha'");
            $login->execute();

            if($l = $login->rowCount()){
                session_start();
                $_SESSION['usuario'] = $user;
                $_SESSION['senha'] = $senha;

                $tpl->ERRO = "Login efetuado com sucesso!";
                $tpl->block("BLOCK_ERRO");
                header($headerscr."index.php");

            }else{

                $tpl->ERRO = utf8_encode("Login ou Senha inválidos!");
                $tpl->block("BLOCK_ERRO");

            }


        }
    }
    
    
    
    

        
    $tpl->show();
?>
