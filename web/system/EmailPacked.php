<?php

    require("classes/validarconexao.php");
    require("classes/header_source.class.php");
    
    if($c->rowCount())
    {
         require("classes/Template.class.php");

         $tpl = new Template("html/EmailPacked.html");
         
         
         
         $tpl->show();
    }
    else
    {
        header($headerscr."login.php");
    }

?>