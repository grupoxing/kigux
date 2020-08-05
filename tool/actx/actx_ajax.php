<?php

# Conectar ao BD
  include ($_SERVER['DOCUMENT_ROOT']."/php/conexao.php");
 
# Ini - Setup - Class - Actx
  include('actx_class.php');
  $actx = new actx();
# Fim - Setup - Class - Actx
 
# Atualizar Historico
  if  (isset($_POST['idpg'])){

       $id_pg = filter_input(INPUT_POST,"idpg",FILTER_SANITIZE_SPECIAL_CHARS);

       # Adicionar Historico
       if (isset($_POST['acion']) && isset($_POST['table']) && isset($_POST['idrow'])) {

           $id_us = $_SESSION["user"]["id_us"];
           $acion = filter_input(INPUT_POST,"acion",FILTER_SANITIZE_SPECIAL_CHARS);
           $table = filter_input(INPUT_POST,"table",FILTER_SANITIZE_SPECIAL_CHARS);
           $idrow = filter_input(INPUT_POST,"idrow",FILTER_SANITIZE_SPECIAL_CHARS);

           if (!$actx->actx_add($id_us, $id_pg, $acion, $table, $idrow)) {
               echo 'erro'; exit;
           }
       }

       # Deletar Historico
       if (isset($_POST['xmsg'])) {
           $xmsg = filter_input(INPUT_POST,"xmsg",FILTER_SANITIZE_SPECIAL_CHARS);
           if ($actx->actx_del($xmsg, $id_pg)) { 
               echo $actx->actx_view($id_pg);
           }else{
               echo 'erro'; exit;
           }
       }

       # Atualizar Historico View
       if (isset($_POST['ini'])){
           $id_ini = (@intval($_POST['ini']) > 0) ? filter_input(INPUT_POST,"ini",FILTER_SANITIZE_SPECIAL_CHARS) : 0;
           echo $actx->actx_view($id_pg, $id_ini, 'pre');
       }

       # Carregar mais Historico View
       if (isset($_POST['fim'])){
           $id_ini = (@intval($_POST['fim']) > 0) ? filter_input(INPUT_POST,"fim",FILTER_SANITIZE_SPECIAL_CHARS) : 0;
           echo $actx->actx_view($id_pg, $id_ini, 'pos');
       }
      
  }else{
      echo 'erro';
  }


?>
