<?php
    require("classes/conexao.class.php");
    
    require("classes/Template.class.php");
    
    $tpl = new Template("construcao.html");
    
    if(isset($_POST["enviar"])){
    
        if(isset($_POST["email"]))
        {
            $email = $_POST["email"];
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
                    $tpl->MSG = utf8_encode("Você será avisado quando o site estiver online!");
                }
                else
                {
                    $tpl->MSG = utf8_encode("Erro ao cadastrar seu e-mail, entre em contato com a CaffeineDev");
                }
        }
        else
        {
            $tpl->MSG = uft8_encode("e-mail inválido");
        }
        $tpl->block("BLOCK_MSG");
    }
    
    $tpl->show();
    
?>


