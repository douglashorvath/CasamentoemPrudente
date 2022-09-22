<?php
    
    require("classes/validarconexao.php");
    require("classes/header_source.class.php");
    
    if($c->rowCount())
    {
         require("classes/Template.class.php");

         $tpl = new Template("html/CadastroEmpresas.html");
         
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
        
        
        
        if(isset($_POST['submit']))
        {
            include "classes/Upload.class.php";
            
            $nomeempresa = strtoupper($_POST['nomeempresa']);
            $enderecoempresa = $_POST['enderecoempresa'];
            $telefoneempresa = $_POST['telefoneempresa'];
            $emailempresa = $_POST['emailempresa'];
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
           //caso não tenha encontrado erros prossegue verificando a imagem do banner grande
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
                //caso não encontre nenhum erro dá sequencia ao cadastro
                if($erro == 0)
                {
                    //verifica se já existe no banco de dados
                    $comp = $pdo->prepare("SELECT nome_empresa FROM empresas WHERE nome_empresa = '$nomeempresa'");
                    $comp ->execute();

                    if($comp->rowCount()){
                        $tpl->ERRO = utf8_encode("Empresa já existente.");
                        $tpl->block("BLOCK_ERROR");

                    }
                    else{
                        $cadastrarEmpresa = $pdo->prepare("INSERT INTO empresas (nome_empresa, endereco_empresa, telefone_empresa, email_empresa, site_empresa, facebook_empresa, categoria1_empresa, categoria2_empresa, categoria3_empresa, bannersimples_empresa, bannergrande_empresa, logo_empresa, logogrande_empresa, ativo_empresa) VALUES ('$nomeempresa', '$enderecoempresa', '$telefoneempresa', '$emailempresa', '$siteempresa','$facebookempresa',$cat1,$cat2,$cat3,$bannerpequeno,$bannergrande,'$locationBannerSimples','$locationBannerGrande',1)");
                        $cadastrarEmpresa->execute();
                        if($cadastrarEmpresa == TRUE)
                        {
                            $tpl->ERRO = utf8_encode("Empresa inserida com sucesso.");
                            $tpl->block("BLOCK_ERROR");
                        }
                        else
                        {
                            $tpl->ERRO = utf8_encode("Erro ao inserir Empresa.");
                            $tpl->block("BLOCK_ERROR");
                        }
                    }
                    
                }
                //caso encontre erros exibe ao usuarios
                else
                {
                    $tpl->ERRO = utf8_encode("Erro ao inserir Banner Grande.");
                    $tpl->block("BLOCK_ERROR");
                }
           }
           //exibe o erro ao usuario
           else
           {
               $tpl->ERRO = utf8_encode("Erro ao inserir Banner Simples.");
               $tpl->block("BLOCK_ERROR");
           }
            
           
           
           
        }
        
                
        $ultimasCadastradas = $pdo->prepare("SELECT id,nome_empresa FROM empresas ORDER BY id DESC LIMIT 3");
        $ultimasCadastradas->execute();

        foreach ($ultimasCadastradas as $ulti => $ul)
        {
            $tpl->ULTIMAS_NOME_EMPRESA = $ul['nome_empresa'];
            $tpl->ULTIMAS_ID_EMPRESA = $ul['id'];
            $tpl->block("BLOCK_ULTIMASCADASTRADAS");
        }
         
         $tpl->show();
    }
    else
    {
        header($headerscr."login.php");
    }
    
?>