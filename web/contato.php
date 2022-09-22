<?php
    
    require("classes/header_source.class.php");
    require("classes/Template.class.php");

    $tpl = new Template("html/contato.html");

    
    if(isset($_POST['submit']))
    {
        if(isset($_POST['nome']) && isset($_POST['assunto']) && isset($_POST['email']) && isset($_POST['telefone']) && isset($_POST['mensagem']))
        {
            if($_POST['nome'] == "Nome:" || $_POST['assunto'] == "Assunto:" || $_POST['email']=="Email:" || $_POST['telefone']=="Telefone:" || $_POST['mensagem']=="Mensagem:")
            {
                $tpl->MENSAGEM_ERRO = utf8_encode("Preenxa o Formulário corretamente.");
                $tpl->block("BLOCK_CONTATO");
            }
            else
            {
                //1 ? Definimos Para quem vai ser enviado o email
                $para = "contato@casamentoemprudente.com.br";
                //2 - resgatar o nome digitado no formulário e  grava na variavel $nome
                $nome = $_POST['nome'];
                // 3 - resgatar o assunto digitado no formulário e  grava na variavel //$assunto
                $assunto = $_POST['assunto'];
                 //4 ? Agora definimos a  mensagem que vai ser enviado no e-mail
                $mensagem = "<strong>Nome:  </strong>".$nome;
                $mensagem .= "<br>  <strong>Email: </strong>".$_POST['email'];
                $mensagem .= "<br>  <strong>Telefone: </strong>".$_POST['telefone'];
                $mensagem .= "<br>  <strong>Mensagem: </strong>".$_POST['mensagem'];

                //5 ? agora inserimos as codificações corretas e  tudo mais.
                $headers =  "Content-Type:text/html; charset=UTF-8\n";
                $headers .= "From:  Casamento em Prudente<contato@casamentoemprudente.com.br>\n"; //Vai ser //mostrado que  o email partiu deste email e seguido do nome
                $headers .= "X-Sender:  <contato@casamentoemprudente.com.br>\n"; //email do servidor //que enviou
                $headers .= "X-Mailer: PHP  v".phpversion()."\n";
                $headers .= "X-IP:  ".$_SERVER['REMOTE_ADDR']."\n";
                $headers .= "Return-Path:  <contato@casamentoemprudente.com.br>\n"; //caso a msg //seja respondida vai para  este email.
                $headers .= "MIME-Version: 1.0\n";

                $emaileviado = mail($para, $assunto, $mensagem, $headers);  //função que faz o envio do email.
                if($emaileviado)
                {
                    $tpl->MENSAGEM_AGRADECIMENTO = uft8_encode("Obrigado pelo contato!");
                    $tpl->block("BLOCK_AGRADECIMENTO");
                }
                else
                {
                    $tpl->MENSAGEM_ERRO = uft8_encode("Erro ao enviar o formulário. Contate o administrador: contato@caffeinedev.com.br");
                    $tpl->block("BLOCK_CONTATO");
                }
            }
        }
        else
        {
            $tpl->MENSAGEM_ERRO = uft8_encode("Preenxa o Formulário corretamente.");
            $tpl->block("BLOCK_CONTATO");
        }
    }
    else
    {
        $tpl->block("BLOCK_CONTATO");
    }
    
    $tpl->show();
?>