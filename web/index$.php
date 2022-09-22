<?php
    
    require("classes/header_source.class.php");
    require("classes/Template.class.php");
    require("classes/conexao.class.php");

    $tpl = new Template("html/index.html");

    
    $empresas = $pdo->prepare("SELECT nome_empresa, endereco_empresa, telefone_empresa, email_empresa, site_empresa, facebook_empresa, logogrande_empresa, ativo_empresa FROM empresas WHERE bannergrande_empresa = 1 ORDER BY nome_empresa ASC");
    $empresas->execute();

    foreach ($empresas as $empre => $emp)
    {
        if($emp['ativo_empresa'] == 1)
        {
            $tpl->BIG_NOME_EMPRESA = $emp['nome_empresa'];
            $tpl->BIG_TELEFONE_EMPRESA = $emp['telefone_empresa'];
            $tpl->BIG_EMAIL_EMPRESA = $emp['email_empresa'];
            if($emp['site_empresa']!= "")
            {
                $tpl->BIG_SITE_EMPRESA = $emp['site_empresa'];
                $tpl->block("BLOCK_BIGSITE");
            }

            if($emp['facebook_empresa'] != "")
            {
                $tpl->BIG_FACEBOOK_EMPRESA = $emp['facebook_empresa'];
                $tpl->block("BLOCK_BIGFACEBOOK");
            }
            if($emp['logogrande_empresa'] != "")
            {
                $imagem = "system/".$emp['logogrande_empresa'];
                $tpl->BANNERGRANDE_EMPRESA = $imagem;
            }
            else
            {
                $imagem = "system/images/standardBig.png";
                $tpl->IMAGEM = $imagem;
            }


            $tpl->block("BLOCK_BIGBANNER");
        }
    }
    
    
    $empresas = $pdo->prepare("SELECT nome_empresa, endereco_empresa, telefone_empresa, email_empresa, site_empresa, facebook_empresa, logo_empresa, ativo_empresa FROM empresas WHERE bannersimples_empresa = 1 ORDER BY nome_empresa ASC");
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


            $tpl->block("BLOCK_SIMPLEBANNER");
        }
    }
    $tpl->show();
?>