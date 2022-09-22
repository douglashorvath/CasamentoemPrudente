<?php
    
    require("classes/header_source.class.php");
    require("classes/Template.class.php");
    require("classes/conexao.class.php");

    $tpl = new Template("html/empresas.html");
    
        $categorias = $pdo->prepare("SELECT id,nome,image FROM categorias ORDER BY nome ASC");
        $categorias->execute();
        
        foreach ($categorias as $categ => $cat)
        {
            if($cat['image'] == "")
            {
                $imagem = "system/image/standardSmall.png";
            }
            else
            {
                $imagem = "system/".$cat['image'];
            }
            $tpl->IMAGEM = $imagem;
            $tpl->ALT = $cat['nome'];
            $tpl->IDCAT = $cat['id'];
            $tpl->block("BLOCK_CATEGORIAS");
        }
    
    $tpl->show();
?>