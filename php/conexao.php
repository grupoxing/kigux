<?php

ob_start(); 
session_start();
ini_set("display_errors", 1);

if (basename($_SERVER["PHP_SELF"]) == "conexao.php") {
    die("ACESSO ILEGAL: Esse arquivo nao pode ser acessado diretamente.");  exit;
}
 
/* ----------- Dados de conexao ----------------------------------------- */
/* login us */ $login_db  = 'kiser895_xing';
/* senha us */ $senha_db  = 'mysqlxing007';
/* Local db */ $local_db  = 'localhost';
/* dbase db */ $dbase_db  = 'kiser895_kigux';
/* conexão  */ $conexao   = mysql_pconnect($local_db,$login_db,$senha_db);
if (!($conexao === false)) {
    if (mysql_select_db($dbase_db, $conexao) === false) {
        echo 'Erro no select Banco de Dados';
        Header("location:/php/manutencion.php#1"); exit;
    }
}else{
    echo 'Erro na conexao';
    Header("location:/php/manutencion.php#2"); exit;
}
 
/*------------------------------------------------------------------------*/ 

?>