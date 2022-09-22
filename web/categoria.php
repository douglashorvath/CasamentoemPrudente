<?php
    
    require("classes/header_source.class.php");
    require("classes/Template.class.php");
    require("classes/conexao.class.php");

    $tpl = new Template("html/categoria.html");
    
    if(isset($_GET['idcat']))
    {
        $idcat = $_GET['idcat'];
        
        
        $categoria = $pdo->prepare("SELECT nome FROM categorias WHERE id = $idcat");
        $categoria->execute();
        
        $nomecat = "";
        foreach ($categoria as $categ => $cat)
        {
            $nomecat = $cat['nome'];
            
        }
        if($nomecat != "")
        {
            $tpl->NOME_CATEGORIA = $cat['nome'];
        }
        else
        {
            header($headerscr."empresas.php");
        }
            
        $empresas = $pdo->prepare("SELECT nome_empresa, endereco_empresa, telefone_empresa, email_empresa, site_empresa, facebook_empresa, logo_empresa, ativo_empresa FROM empresas WHERE categoria1_empresa = $idcat OR categoria2_empresa = $idcat OR categoria3_empresa = $idcat ORDER BY nome_empresa ASC");
        $empresas->execute();
        
        foreach ($empresas as $empre => $emp)
        {
            if($emp['ativo_empresa'] == 1)
            {
                $tpl->NOME_EMPRESA = $emp['nome_empresa'];
                $tpl->ENDERECO_EMPRESA = $emp['endereco_empresa'];
                $tpl->TELEFONE_EMPRESA = $emp['telefone_empresa'];
                $tpl->EMAIL_EMPRESA = $emp['email_empresa'];
                if($emp['site_empresa']!= "")
                {
                    $tpl->SITE_EMPRESA = $emp['site_empresa'];
                    $tpl->block("BLOCK_SITE");
                }
                
                if($emp['facebook_empresa'] != "")
                {
                    $tpl->FACEBOOK_EMPRESA = $emp['facebook_empresa'];
                    $tpl->block("BLOCK_FACEBOOK");
                }
                if($emp['logo_empresa'] != "")
                {
                    $imagem = "system/".$emp['logo_empresa'];
                    $tpl->IMAGEM = $imagem;
                }
                else
                {
                    $imagem = "system/images/standardSmall.png";
                    $tpl->IMAGEM = $imagem;
                }
                
                
                $tpl->block("BLOCK_EMPRESA");
            }
        }
        
        $tpl->show();
    }
    else
    {
        header($headerscr."empresas.php");
    }

    
?>