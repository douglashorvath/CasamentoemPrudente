<?php
    
    require("classes/validarconexao.php");
    require("classes/header_source.class.php");
    
    function preenxeultimas($tpl,$pdo,$all)
    {
        if($all == "true")
         {
            $ultimasCadastradas = $pdo->prepare("SELECT id,nome_empresa FROM empresas ORDER BY nome_empresa ASC");
            $ultimasCadastradas->execute();
         }
         else
         {
            $ultimasCadastradas = $pdo->prepare("SELECT id,nome_empresa FROM empresas ORDER BY id DESC LIMIT 10");
            $ultimasCadastradas->execute();
         }
         
        

        foreach ($ultimasCadastradas as $ulti => $ul)
        {
            $tpl->ULTIMAS_NOME_EMPRESA = $ul['nome_empresa'];
            $tpl->ULTIMAS_ID_EMPRESA = $ul['id'];
            $tpl->ALL=$all;
            $tpl->block("BLOCK_ULTIMASCADASTRADAS");
        }
    }
    
    if($c->rowCount())
    {
         require("classes/Template.class.php");

         $tpl = new Template("html/Empresas.html");
         
         if(isset($_GET['all']))
         {
             $all = $_GET['all'];
         }
         else
         {
             $all = "false";
         }
         
         if(isset($_GET['deleteempid']))
         {
             $idemp = $_GET['deleteempid'];
             
             if(isset($_GET['confirm']))
             {
                 $emptoexclude = $pdo->prepare("SELECT nome_empresa,logo_empresa,logogrande_empresa FROM empresas WHERE id=$idemp");
                 $emptoexclude->execute();
                 
                 foreach ($emptoexclude as $empex=> $ee)
                {
                    $nomeemp = $ee['nome_empresa'];
                    $imagesimplesemp =  $ee['logo_empresa'];
                    $imagegrandeemp = $ee['logogrande_empresa'];
                    
                    
                        if($imagesimplesemp != "")
                        {
                            unlink($imagesimplesemp);
                        }
                        if($imagegrandeemp != "")
                        {
                            unlink($imagegrandeemp);
                        }
                        $excludeemp = $pdo->prepare("DELETE from empresas WHERE id=$idemp");
                        $excludeemp->execute();
                        
                        if($excludeemp)
                        {
                            $tpl->MSG = "Empresa excluida com sucesso!";
                            $tpl->block("BLOCK_MSG");
                        }
                        else
                        {
                            $tpl->MSG = "Erro ao excluir empresa!";
                            $tpl->block("BLOCK_MSG");
                        }
                }
             }
             else
             {
                $emptoexclude = $pdo->prepare("SELECT nome_empresa FROM empresas WHERE id=$idemp");
                $emptoexclude->execute();

                foreach ($emptoexclude as $empex=> $ee)
                {
                    $tpl->EXCLUSAO_NOME_EMPRESA = utf8_encode($ee['nome_empresa']);
                    $tpl->EMP_TO_DELETE = $idemp;
                    $tpl->block("BLOCK_CONFIRMAEXCLUSAO");
                }
             }
             
             
         }
         
         preenxeultimas($tpl,$pdo,$all);
         
         
         $tpl->show();
    }
    else
    {
        header($headerscr."login.php");
    }
    
?>