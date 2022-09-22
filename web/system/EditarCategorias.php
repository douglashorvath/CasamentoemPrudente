<?php
    
    


    require("classes/validarconexao.php");
    require("classes/header_source.class.php");
    require("classes/Template.class.php");
         
    $tpl = new Template("html/EditarCategorias.html");
    
    function exibedados($id,$pdo,$tpl)
    {
        $idcat = $id;


        $categoria = $pdo->prepare("SELECT nome,image FROM categorias WHERE id = $idcat");
        $categoria->execute();

        $nomecat = "";

        foreach ($categoria as $categ => $cat)
        {
            $nomecat = $cat['nome'];
            $imagecat = $cat['image'];
            
        }

        if($nomecat == "")
        {
            header($headerscr."Categorias.php");
        }
        
        if($imagecat == "")
        {
            $imagecat = "images/standardSmall.png";
        }
        else
        {
            $ultimasCadastradas = $pdo->prepare("SELECT id,nome FROM categorias ORDER BY id DESC LIMIT 3");
            $ultimasCadastradas->execute();

            foreach ($ultimasCadastradas as $ulti => $ul)
            {
                $tpl->ULTIMAS_NOME_CATEGORIA = $ul['nome'];
                $tpl->ULTIMAS_ID_CATEGORIA = $ul['id'];
                $tpl->block("BLOCK_ULTIMASCADASTRADAS");
            }

            $tpl->ID_CATEGORIA = $idcat;
            $tpl->NOME_CATEGORIA = $nomecat;
            $tpl->IMAGEM_CATEGORIA = $imagecat;
        }

    }
    
    if($c->rowCount())
    {
         
         
         if(isset($_POST['submit']))
         {
             if(isset($_POST['idcategoria']) && isset($_POST['nomecategoria']))
             {
                 $nomecategoria = $_POST['nomecategoria'];
                 if($nomecategoria != "")
                 {
                     $idcategoria = $_POST['idcategoria'];
                     
//                     if(isset($_FILES['imagemcategoria']))
//                     {
//                         $novaimagem = $_FILES['imagemcategoria'];
//                     }
//                     else
//                     {
//                         $novaimagem = "";
//                     }
                     
                     $pesquisaNome = $pdo->prepare("SELECT id FROM categorias WHERE nome = '$nomecategoria' AND id != $idcategoria");
                     $pesquisaNome->execute();
                     
                     if($pesquisaNome->rowCount())
                     {
                        $tpl->ERRO = utf8_encode("Nome já existente");
                        $tpl->block("BLOCK_ERROR");
                        
                        exibedados($idcategoria,$pdo,$tpl);
                     }
                     else
                     {
                         if(empty($_FILES['imagemcategoria']['name']))
                         {
                             $alteraCategoria = $pdo->prepare("UPDATE categorias SET nome = '$nomecategoria' WHERE id = $idcategoria");
                             $alteraCategoria->execute();
                             
                             if($alteraCategoria)
                             {
                                $tpl->ERRO = utf8_encode("Categoria alterada com Sucesso!");
                                $tpl->block("BLOCK_ERROR");
                                
                                exibedados($idcategoria,$pdo,$tpl);
                             }
                             else
                             {
                                 $tpl->ERRO = uft8_encode("Erro ao alterar Categoria!");
                                 $tpl->block("BLOCK_ERROR");
                                 
                                 exibedados($idcategoria,$pdo,$tpl);
                             }
                         }
                         else
                         {
                             include "classes/Upload.class.php";
                             $upload = new Upload($_FILES["imagemcategoria"],150,280,"images/categorias/");
                             $location = $upload -> salvar();
                             
                             if($location == "1")
                            {
                                //Imagem maior do que o permitido
                                $tpl->ERRO = utf8_encode("Imagem inválida");
                                $tpl->block("BLOCK_ERROR");
                                
                                exibedados($idcategoria,$pdo,$tpl);
                            }
                            else
                            {
                                if($location == "0")
                                {
                                   //Erro 
                                     $tpl->ERRO = utf8_encode("Erro ao inserir imagem");
                                     $tpl->block("BLOCK_ERROR");
                                     
                                     exibedados($idcategoria,$pdo,$tpl);
                                }
                                else
                                {
                                    $pegaAntiga = $pdo->prepare("SELECT image FROM categorias WHERE id = $idcategoria");
                                    $pegaAntiga->execute();
                                    foreach ($pegaAntiga as $pega=>$pa)
                                    {
                                        $antigaimagem = $pa['image'];
                                    }

                                    if(unlink($antigaimagem))
                                    {
                                        $alteraCategoria = $pdo->prepare("UPDATE categorias SET nome = '$nomecategoria', image = '$location' WHERE id = $idcategoria");
                                        $alteraCategoria->execute();    
                                        
                                        if($alteraCategoria)
                                        {
                                           $tpl->ERRO = utf8_encode("Categoria alterada com Sucesso!");
                                           $tpl->block("BLOCK_ERROR");

                                           exibedados($idcategoria,$pdo,$tpl);
                                        }
                                        else
                                        {
                                            $tpl->ERRO = uft8_encode("Erro ao alterar Categoria!");
                                            $tpl->block("BLOCK_ERROR");

                                            exibedados($idcategoria,$pdo,$tpl);
                                        }
                                    }
                                    else
                                    {
                                        $tpl->ERRO = utf8_encode("Erro ao apagar imagem antiga");
                                        $tpl->block("BLOCK_ERROR");
                                     
                                        exibedados($idcategoria,$pdo,$tpl);
                                    }
                                }
                             
                            }
                         }
                     }
                     
                 }
                 else
                 {
                    $tpl->ERRO = utf8_encode("Nome inválido");
                    $tpl->block("BLOCK_ERROR");
                    
                    exibedados($idcategoria,$pdo,$tpl);
                 }
             }
             else
             {
                 $tpl->ERRO = "Erro ao alterar categoria";
                 $tpl->block("BLOCK_ERROR");
                 
                 exibedados($idcategoria,$pdo,$tpl);
             }
             
         }
         else
         {
            if(isset($_GET['idcat']))
            {
               

               $idcat = $_GET['idcat'];

               exibedados($idcat,$pdo,$tpl);
            }
            else
            {
                header($headerscr."Categorias.php");
            }
         }
         
        $tpl->show();
         
    }
    else
    {
        header($headerscr."login.php");
    }
    
?>