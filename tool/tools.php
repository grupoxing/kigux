<?php

# Conectar ao BD
  include ($_SERVER['DOCUMENT_ROOT']."/php/conexao.php");

# Abrir Toll
  if  (isset($_POST['open'])) {
       
       $tool = filter_input(INPUT_POST,"open",FILTER_SANITIZE_SPECIAL_CHARS);
       
       # Abrir Mural de Recados
       if ($tool == 'wall') {
           require_once 'wall/wall_class.php';
           $wall = new Wall();
           $code = $wall->wall_main();
       }
       
       # Abrir Historico de Atividades
       if ($tool == 'actx') {
           require_once 'actx/actx_class.php';
           $actx = new Actx();
           $code = $actx->actx_main();
       }

       # Ini - Lista - Amigos - Espera
       if ($tool == 'iamg') {
           require_once 'iamg/iamg_class.php';
           $iamg = new iAmg();
           $code = $iamg->esperas();
       }
       # Fim - Lista - Amigos - Espera
       
       echo ($code == '') ? 'erro' : $code;
      
  }


?>
