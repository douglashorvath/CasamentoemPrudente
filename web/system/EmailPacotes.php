<?php

    require("classes/validarconexao.php");
    require("classes/header_source.class.php");
    
    function enviarEmail($para,$assunto,$mensagem)
    {
       
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
            return true;
        }
        else
        {
            return false;
        }
    }
    
    if($c->rowCount())
    {
         require("classes/Template.class.php");

         $tpl = new Template("html/EmailPacotes.html");
         
         if(isset($_POST['submit']))
         {
             if($_POST['assuntoemail'] != "" && $_POST['conteudoemail']!= "")
             {
                $assunto =  $_POST['assuntoemail'];
                $conteudo = $_POST['conteudoemail'];
                
                if($_POST['selectpacotedestinatariosemail'] == 0)
                {
                    $destino = "Todas as Empresas";
                    $consultaEmpresas = $pdo->prepare("SELECT email_empresa FROM empresas");
                    $consultaEmpresas->execute();
                    $erro = false;
                    foreach($consultaEmpresas as $consultEmpre=>$coem)
                    {
                        $para = $coem['email'];
                        $enviarEmail = enviarEmail($para,$assunto,$conteudo);
                        
                        
                        if(enviarEmail == false)
                        {
                            $erro = true;
                        }
                    }
                    
                    if($erro == true)
                    {
                        $tpl->ERRO = "Erro ao enviar emails";
                        $tpl->block("BLOCK_ERROR");
                    }
                    else
                    {
                        $tpl->ERRO = "Emails enviados com sucesso!";
                        $tpl->block("BLOCK_ERROR");
                        
                        $salvaEnvio = $pdo->prepare("INSERT INTO emails_enviados (para,assunto,conteudo) VALUES ('$destino','$assunto','$conteudo')");
                        $salvaEnvio->execute();
                                
                    }
                    
                }
                else
                {
                    $destino = "Todos os Usuários";
                    
                    $consultaUsuarios = $pdo->prepare("SELECT email FROM usuarios");
                    $consultaUsuarios->execute();
                    $erro = false;
                    foreach($consultaUsuarios as $consultUser=>$cous)
                    {
                        $para = $cous['email'];
                        $enviarEmail = enviarEmail($para,$assunto,$conteudo);
                        
                        
                        if(enviarEmail == false)
                        {
                            $erro = true;
                        }
                    }
                    
                    if($erro == true)
                    {
                        $tpl->ERRO = "Erro ao enviar emails";
                        $tpl->block("BLOCK_ERROR");
                    }
                    else
                    {
                        $tpl->ERRO = "Emails enviados com sucesso!";
                        $tpl->block("BLOCK_ERROR");
                        
                        $salvaEnvio = $pdo->prepare("INSERT INTO emails_enviados (para,assunto,conteudo) VALUES ('$destino','$assunto','$conteudo')");
                        $salvaEnvio->execute();
                                
                    }
                }
             }  
         }
         
         
         $tpl->show();
    }
    else
    {
        header($headerscr."login.php");
    }

?>