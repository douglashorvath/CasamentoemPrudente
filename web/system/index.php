<?php

    require("classes/validarconexao.php");
    require("classes/header_source.class.php");
    
    if($c->rowCount())
    {
         require("classes/Template.class.php");

         $tpl = new Template("html/index.html");
         
         $ultimasCadastradas = $pdo->prepare("SELECT id,nome_empresa FROM empresas ORDER BY id DESC LIMIT 5");
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