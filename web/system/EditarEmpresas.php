<?php
    
    require("classes/validarconexao.php");
    require("classes/header_source.class.php");
    require("classes/Template.class.php");

    $tpl = new Template("html/EditarEmpresas.html");
    
    function exibedados($idemp, $pdo,$tpl)
    {
        
        
        $categoria = $pdo->prepare("SELECT nome, id FROM categorias ORDER BY nome ASC");
        $categoria->execute();

        foreach($categoria as $cat =>$c){
            $tpl->OPCAT1 = $c["nome"];
            $tpl->OPCAT2 = $c["nome"];
            $tpl->OPCAT3 = $c["nome"];
            $tpl->VALOR_CAT1 = $c["id"];
            $tpl->VALOR_CAT2 = $c["id"];
            $tpl->VALOR_CAT3 = $c["id"];
            $tpl->block("BLOCK_CAT1");
            $tpl->block("BLOCK_CAT2");
            $tpl->block("BLOCK_CAT3");
        }
        
        $preenxeempresas = $pdo->prepare("SELECT nome_empresa, endereco_empresa, telefone_empresa, email_empresa, site_empresa, facebook_empresa, categoria1_empresa, categoria2_empresa, categoria3_empresa, logo_empresa, logogrande_empresa, bannersimples_empresa, bannergrande_empresa, ativo_empresa FROM empresas WHERE id = $idemp");
        $preenxeempresas->execute();
        
        foreach ($preenxeempresas as $empre => $emp)
        {
            $tpl->ID_EMPRESA = $idemp;
            $tpl->NOME_EMPRESA = $emp['nome_empresa'];
            $tpl->ENDERECO_EMPRESA = $emp['endereco_empresa'];
            $tpl->TELEFONE_EMPRESA = $emp['telefone_empresa'];
            $tpl->EMAIL_EMPRESA = $emp['email_empresa'];
            $tpl->SITE_EMPRESA = $emp['site_empresa'];
            $tpl->FACEBOOK_EMPRESA = $emp['facebook_empresa'];
            
            
            if($emp['logo_empresa'] != "")
            {
                $imagem = $emp['logo_empresa'];
                $tpl->IMAGEM_EMPRESA = $imagem;
            }
            else
            {
                $imagem = "images/standardSmall.png";
                $tpl->IMAGEM_EMPRESA = $imagem;
            }
            
            $cat1 = $emp['categoria1_empresa'];
            $selectcat = $pdo->prepare("SELECT nome FROM categorias WHERE id = $cat1");
            $selectcat->execute();
            foreach ($selectcat as $selcat=>$sc)
            {
                $tpl->VALOR_CAT1SELECTED = $cat1;
                $tpl->OPCAT1SELECTED = $sc['nome'];
                $tpl->block("BLOCK_CAT1SELECTED");
            }
            
            if($emp['categoria2_empresa']!= -1)
            {
                $cat2 = $emp['categoria2_empresa'];
                 
                $selectcat = $pdo->prepare("SELECT nome FROM categorias WHERE id = $cat2");
                $selectcat->execute();
                foreach ($selectcat as $selcat=>$sc)
                {
                    $tpl->VALOR_CAT2SELECTED = $cat2;
                    $tpl->OPCAT2SELECTED = $sc['nome'];
                    $tpl->block("BLOCK_CAT2SELECTED");
                }
            }
            
            if($emp['categoria3_empresa']!= -1)
            {
                 $cat3 = $emp['categoria3_empresa'];
                 
                $selectcat = $pdo->prepare("SELECT nome FROM categorias WHERE id = $cat3");
                $selectcat->execute();
                foreach ($selectcat as $selcat=>$sc)
                {
                    $tpl->VALOR_CAT3SELECTED = $cat3;
                    $tpl->OPCAT3SELECTED =$sc['nome'];
                    $tpl->block("BLOCK_CAT3SELECTED");
                }
            }
            
            if($emp['bannersimples_empresa'] == 1)
            {
                $tpl->VALUEBANNERSIMPLESSELECTED = 1;
                $tpl->CONTENTBANNERSIMPLESSELECTED = utf8_encode("Exibiчуo na Homescreen");
                $tpl->block("BLOCK_BANNERSIMPLESSELECTED");
            }
            else
            {
                $tpl->VALUEBANNERSIMPLESSELECTED = 0;
                $tpl->CONTENTBANNERSIMPLESSELECTED = utf8_encode("Exibiчуo Simples");
                $tpl->block("BLOCK_BANNERSIMPLESSELECTED");
            }
            
            if($emp['bannergrande_empresa'] == 1)
            {
                $tpl->VALUEBANNERGRANDESELECTED = 1;
                $tpl->CONTENTBANNERGRANDESELECTED = utf8_encode("Exibiчуo na Homescreen");
                $tpl->block("BLOCK_BANNERGRANDESELECTED");
            }
            else
            {
                $tpl->VALUEBANNERGRANDESELECTED = 0;
                $tpl->CONTENTBANNERGRANDESELECTED = utf8_encode("Sem Exibiчуo");
                $tpl->block("BLOCK_BANNERGRANDESELECTED");
            }

            if($emp['logogrande_empresa'] != "")
            {
                $imagem = $emp['logogrande_empresa'];
                $tpl->IMAGEM_BANNERGRANDE = $imagem;
                $tpl->block("BLOCK_BANNERGRANDEIMG");
            }
            
            if($emp['ativo_empresa'] == 1)
            {
                $tpl->VALUESELECTATIVO = 1;
                $tpl->CONTENTSELECTATIVO = "Ativo";
                $tpl->block("BLOCK_SELECTATIVO");
            }
            else
            {
                $tpl->VALUESELECTATIVO = 0;
                $tpl->CONTENTSELECTATIVO = "Inativo";
                $tpl->block("BLOCK_SELECTATIVO");
            }
            
        }
        
        $ultimasCadastradas = $pdo->prepare("SELECT id,nome_empresa FROM empresas ORDER BY id DESC LIMIT 10");
        $ultimasCadastradas->execute();

        foreach ($ultimasCadastradas as $ulti => $ul)
        {
            $tpl->ULTIMAS_NOME_EMPRESA = $ul['nome_empresa'];
            $tpl->ULTIMAS_ID_EMPRESA = $ul['id'];
            $tpl->block("BLOCK_ULTIMASCADASTRADAS");
        }
            
    }
    
    
    if($c->rowCount())
    {
        if(isset($_POST['submit']))
        {
            include "classes/Upload.class.php";
            
            $idempresa = $_POST['idempresa'];
            $nomeempresa = $_POST['nomeempresa'];
            $enderecoempresa = $_POST['enderecoempresa'];
            $telefoneempresa = $_POST['telefoneempresa'];
            $emailempresa = $_POST['emailempresa'];
            $ativoempresa = $_POST['selectativo'];
            if(isset($_POST['siteempresa']))
            {
                $siteempresa = $_POST['siteempresa'];
            }
            if(isset($_POST['facebookempresa']))
            {
                $facebookempresa = $_POST['facebookempresa'];
            }
            
            $cat1 = $_POST['selectcategorias1empresa'];
            $cat2 = $_POST['selectcategorias2empresa'];
            $cat3 = $_POST['selectcategorias3empresa'];
            
            $bannerpequeno = $_POST['selectbannersimplesempresa'];
            $bannergrande = $_POST['selectbannergrandeempresa'];
            
            $erro = 0;
            //Verifica a imagem do banner simples
           if(!empty($_FILES["bannerempresa"])) 
           {
               $upload = new Upload($_FILES["bannerempresa"],150,280,"images/simples/");
               $location = $upload -> salvar();
               if($location != "0" && $location != "1")
               {
                   $locationBannerSimples = $location;
               }
               else
                {
                    if($location == "1")
                    {
                        $erro+=1;
                    }
                    else
                    {
                        $locationBannerSimples ="";
                    }
                }
           }
           else
           {
               $locationBannerSimples ="";
           }    
           //caso nуo tenha encontrado erros prossegue verificando a imagem do banner grande
           if($erro == 0)
           {
                if(!empty($_FILES["bannergrandeempresa"]))
                {
                    $upload = new Upload($_FILES["bannergrandeempresa"],400,1230,"images/grande/");
                    $location = $upload -> salvar();
                    if($location != "0" && $location != "1")
                    {
                        $locationBannerGrande = $location;
                    }
                    else
                    {
                        if($location == "1")
                        {
                            $erro+=1;
                        }
                        else
                        {
                            $locationBannerGrande ="";
                        }
                    }
                }
                else
                {
                    $locationBannerGrande ="";
                }
                //caso nуo encontre nenhum erro dс sequencia ao cadastro
                if($erro == 0)
                {
                    //verifica se jс existe no banco de dados
                    $comp = $pdo->prepare("SELECT nome_empresa FROM empresas WHERE nome_empresa = '$nomeempresa' AND id != $idempresa");
                    $comp ->execute();

                    if($comp->rowCount()){
                        $tpl->ERRO = utf8_encode("Empresa jс existente.");
                        $tpl->block("BLOCK_ERROR");
                        
                        exibedados($idempresa,$pdo,$tpl);
                        

                    }
                    else{
                        
                        if($locationBannerGrande == "" && $locationBannerSimples == "")
                        {
                            $alteraEmpresa=false;
                            $alteraEmpresa = $pdo->prepare("UPDATE empresas SET nome_empresa = '$nomeempresa', endereco_empresa = '$enderecoempresa', telefone_empresa = '$telefoneempresa', email_empresa = '$emailempresa', site_empresa = '$siteempresa', facebook_empresa = '$facebookempresa', categoria1_empresa = $cat1, categoria2_empresa = $cat2, categoria3_empresa = $cat3, bannersimples_empresa = $bannerpequeno, bannergrande_empresa = $bannergrande, ativo_empresa = $ativoempresa WHERE id = $idempresa");
                            $alteraEmpresa->execute();
                            if($alteraEmpresa)
                            {
                                $tpl->ERRO = utf8_encode("Empresa alterada com sucesso.");
                                $tpl->block("BLOCK_ERROR");
                                
                                exibedados($idempresa,$pdo,$tpl);
                            }
                            else
                            {
                                $tpl->ERRO = utf8_encode("Erro ao alterar Empresa.");
                                $tpl->block("BLOCK_ERROR");
                                
                                exibedados($idempresa,$pdo,$tpl);
                            }
                        }
                        else
                        {
                            if($locationBannerSimples != "" && $locationBannerGrande == "")
                            {
                                $pegaAntiga = $pdo->prepare("SELECT logo_empresa FROM empresas WHERE id = $idempresa");
                                $pegaAntiga->execute();
                                foreach ($pegaAntiga as $pega=>$pa)
                                {
                                    $antigaimagem = $pa['logo_empresa'];
                                }
                                if($antigaimagem != "")
                                {
                                    if(!unlink($antigaimagem))
                                    {
                                        $tpl->ERRO = utf8_encode("Erro ao apagar Logo Simples antigo");
                                        $tpl->block("BLOCK_ERROR");

                                        exibedados($idempresa,$pdo,$tpl);
                                    }
                                }

                                $alteraEmpresa=false;
                                $alteraEmpresa = $pdo->prepare("UPDATE empresas SET nome_empresa = '$nomeempresa', endereco_empresa = '$enderecoempresa', telefone_empresa = '$telefoneempresa', email_empresa = '$emailempresa', site_empresa = '$siteempresa', facebook_empresa = '$facebookempresa', categoria1_empresa = $cat1, categoria2_empresa = $cat2, categoria3_empresa = $cat3, bannersimples_empresa = $bannerpequeno, bannergrande_empresa = $bannergrande, logo_empresa = '$locationBannerSimples', ativo_empresa = $ativoempresa WHERE id = $idempresa");
                                $alteraEmpresa->execute();
                                if($alteraEmpresa)
                                {
                                    $tpl->ERRO = utf8_encode("Empresa alterada com sucesso.");
                                    $tpl->block("BLOCK_ERROR");

                                    exibedados($idempresa,$pdo,$tpl);
                                }
                                else
                                {
                                    $tpl->ERRO = utf8_encode("Erro ao alterar Empresa.");
                                    $tpl->block("BLOCK_ERROR");

                                    exibedados($idempresa,$pdo,$tpl);
                                }

                            }
                            else
                            {
                                if($locationBannerSimples == "" && $locationBannerGrande != "")
                                {
                                    $pegaAntiga = $pdo->prepare("SELECT logogrande_empresa FROM empresas WHERE id = $idempresa");
                                    $pegaAntiga->execute();
                                    foreach ($pegaAntiga as $pega=>$pa)
                                    {
                                        $antigaimagem = $pa['logogrande_empresa'];
                                    }
                                    if($antigaimagem != "")
                                    {
                                        if(!unlink($antigaimagem))
                                        {
                                            $tpl->ERRO = utf8_encode("Erro ao apagar Banner Grande antigo");
                                            $tpl->block("BLOCK_ERROR");

                                            exibedados($idempresa,$pdo,$tpl);
                                        }
                                    }
                                    $alteraEmpresa=false;
                                    $alteraEmpresa = $pdo->prepare("UPDATE empresas SET nome_empresa = '$nomeempresa', endereco_empresa = '$enderecoempresa', telefone_empresa = '$telefoneempresa', email_empresa = '$emailempresa', site_empresa = '$siteempresa', facebook_empresa = '$facebookempresa', categoria1_empresa = $cat1, categoria2_empresa = $cat2, categoria3_empresa = $cat3, bannersimples_empresa = $bannerpequeno, bannergrande_empresa = $bannergrande, logogrande_empresa = '$locationBannerGrande', ativo_empresa = $ativoempresa WHERE id = $idempresa");
                                    $alteraEmpresa->execute();
                                    if($alteraEmpresa)
                                    {
                                        $tpl->ERRO = utf8_encode("Empresa alterada com sucesso.");
                                        $tpl->block("BLOCK_ERROR");

                                        exibedados($idempresa,$pdo,$tpl);
                                    }
                                    else
                                    {
                                        $tpl->ERRO = utf8_encode("Erro ao alterar Empresa.");
                                        $tpl->block("BLOCK_ERROR");

                                        exibedados($idempresa,$pdo,$tpl);
                                    }
                                }
                                else
                                {
                                    if($locationBannerSimples != "" && $locationBannerGrande != "")
                                    {
                                        $pegaAntiga = $pdo->prepare("SELECT logo_empresa, logogrande_empresa FROM empresas WHERE id = $idempresa");
                                        $pegaAntiga->execute();
                                        foreach ($pegaAntiga as $pega=>$pa)
                                        {
                                            $antigaimagemPequena = $pa['logo_empresa'];
                                            $antigaimagemGrande = $pa['logogrande_empresa'];
                                        }
                                        if($antigaimagemPequena != "")
                                        {
                                            if(!unlink($antigaimagemPequena))
                                            {
                                                $tpl->ERRO = utf8_encode("Erro ao apagar Logo Simples antigo");
                                                $tpl->block("BLOCK_ERROR");

                                                exibedados($idempresa,$pdo,$tpl);
                                            }
                                        }
                                        
                                        if($antigaimagemGrande != "")
                                        {
                                            if(!unlink($antigaimagemGrande))
                                            {
                                                $tpl->ERRO = utf8_encode("Erro ao apagar Banner Grande antigo");
                                                $tpl->block("BLOCK_ERROR");

                                                exibedados($idempresa,$pdo,$tpl);
                                            }
                                        }

                                        
                                        $alteraEmpresa=false;
                                        $alteraEmpresa = $pdo->prepare("UPDATE empresas SET nome_empresa = '$nomeempresa', endereco_empresa = '$enderecoempresa', telefone_empresa = '$telefoneempresa', email_empresa = '$emailempresa', site_empresa = '$siteempresa', facebook_empresa = '$facebookempresa', categoria1_empresa = $cat1, categoria2_empresa = $cat2, categoria3_empresa = $cat3, bannersimples_empresa = $bannerpequeno, bannergrande_empresa = $bannergrande, logo_empresa = '$locationBannerSimples', logogrande_empresa='$locationBannerGrande', ativo_empresa = $ativoempresa WHERE id = $idempresa");
                                        $alteraEmpresa->execute();
                                        if($alteraEmpresa)
                                        {
                                            $tpl->ERRO = utf8_encode("Empresa alterada com sucesso.");
                                            $tpl->block("BLOCK_ERROR");

                                            exibedados($idempresa,$pdo,$tpl);
                                        }
                                        else
                                        {
                                            $tpl->ERRO = utf8_encode("Erro ao alterar Empresa.");
                                            $tpl->block("BLOCK_ERROR");

                                            exibedados($idempresa,$pdo,$tpl);
                                        }
                                    }
                                    else
                                    {
                                        $tpl->ERRO = utf8_encode("Erro ao alterar. COD666");
                                        $tpl->block("BLOCK_ERROR");

                                        exibedados($idempresa,$pdo,$tpl);
                                    }
                                    
                                }
                            }
                        }
                    }
                    
                }
                //caso encontre erros exibe ao usuarios
                else
                {
                    $tpl->ERRO = utf8_encode("Erro ao inserir Banner Grande.");
                    $tpl->block("BLOCK_ERROR");
                    
                    exibedados($idempresa,$pdo,$tpl);
                }
           }
           //exibe o erro ao usuario
           else
           {
               $tpl->ERRO = utf8_encode("Erro ao inserir Banner Simples.");
               $tpl->block("BLOCK_ERROR");
               
               exibedados($idempresa,$pdo,$tpl);
           }
        }
        else
        {
            if(isset($_GET['idemp']))
            {
               $idemp = $_GET['idemp'];

               exibedados($idemp,$pdo,$tpl);
            }
            else
            {
                header($headerscr."Empresas.php");
            }
        }
        
         
         $tpl->show();
    }
    else
    {
        header($headerscr."login.php");
    }
    
?>