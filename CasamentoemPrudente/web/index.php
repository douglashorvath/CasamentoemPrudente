<?php
    require("classes/validarconexao.php");
    
    require("classes/Template.class.php");
    
    $tpl = new Template("construcao.html");
    
    if(isset($_POST("enviar")))
    {
        if(isset($_POST("email")))
        {
            $email = $_POST("email");
        }
        else
        {
            $email = "";
        }
        
        if(strlen($email)>4)
        {
                $cademail = $pdo->prepare("INSERT INTO emails_aviso (email) VALUES ('$email')");
                $cademail->execute();
                if($cademail == TRUE)
                {
                    $tpl->MSG = "Você será avisado quando o site estiver online!";
                }
                else
                {
                    $tpl->MSG = "Erro ao cadastrar seu e-mail, entre em contato com a CaffeineDev";
                }
        }
        else
        {
            $tpl->MSG = "e-mail inválido";
        }
        $tpl->block("BLOCK_MSG");
    }
    
    $tpl->show();
    
?>


