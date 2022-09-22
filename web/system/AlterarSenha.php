<?php
    
    require("classes/validarconexao.php");
    require("classes/header_source.class.php");
    
    if($c->rowCount())
    {
         require("classes/Template.class.php");

         $tpl = new Template("html/AlterarSenha.html");
         
        if(isset($_POST['submit']))
        {
            if(isset ($_POST['senhaatual']) && isset($_POST['senhanova']))
            {
                $senhaatual = md5($_POST['senhaatual']);
                $senhanova = md5($_POST['senhanova']);
                
                $login = $pdo->prepare("SELECT login, senha FROM sys_admin WHERE senha = '$senhaatual'");
                $login->execute();

                if($login->rowCount()){
                    
                    $alteraSenha = $pdo->prepare("UPDATE sys_admin SET senha='$senhanova' WHERE senha = '$senhaatual'");
                    $alteraSenha->execute();
                    
                    if($alteraSenha)
                    {
                        $_SESSION['senha'] = $senhanova;
                        
                        $tpl->ERRO = "Senha alterada com sucesso!";
                        $tpl->block("BLOCK_ERROR");
                    }
                    else
                    {
                        $tpl->ERRO = "Erro ao alterar senha!";
                        $tpl->block("BLOCK_ERROR");
                    }
                    

                }else{

                    $tpl->ERRO = utf8_encode("Senha inválida!");
                    $tpl->block("BLOCK_ERROR");

                }
            }
            else
            {
                $tpl->ERRO = "Insira os dados corretamente.";
                $tpl->block("BLOCK_ERROR");
            }
        }
         
         $tpl->show();
    }
    else
    {
        header($headerscr."login.php");
    }
    
?>