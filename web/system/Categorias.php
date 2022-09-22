<?php
    
    require("classes/validarconexao.php");
    require("classes/header_source.class.php");
    
    
    function preenxeultimas($tpl,$pdo,$all)
    {
        if($all == "true")
         {
            $ultimasCadastradas = $pdo->prepare("SELECT id,nome FROM categorias ORDER BY nome ASC");
            $ultimasCadastradas->execute();
         }
         else
         {
            $ultimasCadastradas = $pdo->prepare("SELECT id,nome FROM categorias ORDER BY id DESC LIMIT 10");
            $ultimasCadastradas->execute();
         }
        

        foreach ($ultimasCadastradas as $ulti => $ul)
        {
            $tpl->ULTIMAS_NOME_CATEGORIA = $ul['nome'];
            $tpl->ULTIMAS_ID_CATEGORIA = $ul['id'];
            $tpl->ALL=$all;
            $tpl->block("BLOCK_ULTIMASCADASTRADAS");
        }
    }
    
    if($c->rowCount())
    {
         require("classes/Template.class.php");

         $tpl = new Template("html/Categorias.html");
        
         if(isset($_GET['all']))
         {
             $all = $_GET['all'];
         }
         else
         {
             $all = "false";
         }
         
         if(isset($_GET['deletecatid']))
         {
             $idcat = $_GET['deletecatid'];
             
             if(isset($_GET['confirm']))
             {
                 $cattoexclude = $pdo->prepare("SELECT nome,image FROM categorias WHERE id=$idcat");
                 $cattoexclude->execute();
                 
                 foreach ($cattoexclude as $catex=> $ce)
                {
                    $nomecat = $ce['nome'];
                    $imagecat =  $ce['image'];
                    
                    if(unlink($imagecat))
                    {
                        $excludecat = $pdo->prepare("DELETE from categorias WHERE id=$idcat");
                        $excludecat->execute();
                        
                        if($excludecat)
                        {
                            $tpl->MSG = "Categoria excluida com sucesso!";
                            $tpl->block("BLOCK_MSG");
                        }
                        else
                        {
                            $tpl->MSG = "Erro ao excluir categoria!";
                            $tpl->block("BLOCK_MSG");
                        }
                    }
                    else
                    {
                        $tpl->MSG = "Erro ao excluir categoria!";
                        $tpl->block("BLOCK_MSG");
                    }
                }
             }
             else
             {
                $cattoexclude = $pdo->prepare("SELECT nome FROM categorias WHERE id=$idcat");
                $cattoexclude->execute();

                foreach ($cattoexclude as $catex=> $ce)
                {
                    $tpl->EXCLUSAO_NOME_CATEGORIA = $ce['nome'];
                    $tpl->CAT_TO_DELETE = $idcat;
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