<?php
    
    require("classes/validarconexao.php");
    require("classes/header_source.class.php");
    
    if($c->rowCount())
    {
         require("classes/Template.class.php");

         $tpl = new Template("html/CadastroCategorias.html");
         
         
         
         if(isset($_POST["submit"]))
         {
             include "classes/Upload.class.php";
             
             $nomecategoria = strtoupper($_POST["nomecategoria"]);
             if(!empty($_FILES["imagemcategoria"]))
             {
                 $upload = new Upload($_FILES["imagemcategoria"],150,280,"images/categorias/");
                 $location = $upload -> salvar();
                 
                 if($location == "1")
                 {
                     //Imagem maior do que o permitido
                     $tpl->ERRO = utf8_encode("Imagem invсlida");
                     $tpl->block("BLOCK_ERROR");
                 }
                 else
                 {
                     if($location == "0")
                     {
                        //Erro 
                          $tpl->ERRO = utf8_encode("Erro ao inserir imagem");
                          $tpl->block("BLOCK_ERROR");
                     }
                     else
                     {
                         //Faz a gravaчуo no BD - Sucesso!
                         //verifica se a categoria jс existe primeiro
                        $comp = $pdo->prepare("SELECT nome FROM categorias WHERE nome = '$nomecategoria'");
                        $comp ->execute();
                        //se a categoria jс existe informa
                        if($comp->rowCount()){
                            $tpl->ERRO = utf8_encode("Categoria jс existente.");
                            $tpl->block("BLOCK_ERROR");

                        }
                        //caso a categoria nуo exista, insere no banco de dados
                        else{
                            $cat = $pdo->prepare("INSERT INTO categorias (nome, image) VALUES ('$nomecategoria','$location')");
                            $cat->execute();
                            if($cat == TRUE){
                               $tpl->ERRO = "Categoria criada com sucesso";
                               $tpl->block("BLOCK_ERROR");

                            }else{
                               $tpl->ERRO = "Erro ao criar categoria.";
                               $tpl->block("BLOCK_ERROR");

                            }
                        }
                     }
                 }
             }
         }
         
         
         $ultimasCadastradas = $pdo->prepare("SELECT id,nome FROM categorias ORDER BY id DESC LIMIT 3");
         $ultimasCadastradas->execute();

         foreach ($ultimasCadastradas as $ulti => $ul)
         {
            $tpl->ULTIMAS_NOME_CATEGORIA = $ul['nome'];
            $tpl->ULTIMAS_ID_CATEGORIA = $ul['id'];
            $tpl->block("BLOCK_ULTIMASCADASTRADAS");
         }
         
         
         $tpl->show();
    }
    else
    {
        header($headerscr."login.php");
    }
    
?>