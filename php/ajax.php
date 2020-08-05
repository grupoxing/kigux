<?php 

session_start();
header("Content-Type: text/html; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$date = mktime();
$ipus = $_SERVER['REMOTE_ADDR'];

# Config Sistema --------------------------------------------------------------#
if (isset($_SESSION["user"])) {
    include("conexao.php");
    $id_us = $_SESSION["user"]["id_us"];
    $db_us = $_SESSION["user"];
}else{
    echo (isset($_POST["iam"])) ? '<a class="fam" href="javascript:" onclick="idrop.abrir(\'/tool/iconta/iconta_login.php\',false,true);">Entrar!</a>' : '<a class="fsg" href="javascript:" onclick="idrop.abrir(\'/tool/iconta/iconta_login.php\',false,true);">Entrar!</a>' ; exit;
}

if (isset($_POST["closeFala"])) {
    mysql_query("UPDATE user SET novos=0 WHERE id_us='$id_us'");
}

# Apcoes para Votacao e Favoritos ------------------------------------------#
if (isset($_POST["idms"]) && isset($_POST["acao"])) {

    $idms = $_POST["idms"];
    $acao = $_POST["acao"];

    $bus = mysql_query("SELECT posit, negat, favor FROM msg WHERE id_ms='$idms'");
    $msg = mysql_fetch_assoc($bus);
    $num = mysql_num_rows($bus);
    if ($num  > 0 && ($acao == 'posit' || $acao == 'negat' || $acao == 'favor')){

        $nu['posit'] = $msg['posit'];
        $nu['negat'] = $msg['negat'];
        $nu['favor'] = $msg['favor'];

        $bus = mysql_query("SELECT voto, favor FROM fav WHERE id_ms='$idms' AND id_us='$id_us'");
        $fav = mysql_fetch_assoc($bus);
        $num = mysql_num_rows($bus);
        if ($num  > 0){

            if ($acao == 'favor'){
                if ($fav['favor'] == 0){
                    $set_msg = 'favor=favor+1';
                    $set_fav = 'favor=1';
                    $nu['favor'] = $msg['favor']+1;
                }else{
                    $set_msg = ($msg['favor'] > 0) ? 'favor=favor-1' : 'favor=favor';
                    $set_fav = 'favor=0';
                    $nu['favor'] = ($msg['favor'] > 0) ? $msg['favor']-1 : $msg['favor'];
                }
            }else{
                if ($fav['voto'] == 'posit' || $fav['voto'] == 'negat') {
                    $set_msg = ($msg[$fav['voto']] > 0) ? $fav['voto']."=".$fav['voto']."-1, " : $fav['voto']."=".$fav['voto'].", ";
                    if ($fav['voto'] == 'posit' && $acao == 'negat'){
                        $nu['posit'] = ($msg['posit'] > 0) ? $msg['posit']-1 : $msg['posit'];
                        $nu['negat'] = $msg['negat']+1;
                    }
                    if ($fav['voto'] == 'negat' && $acao == 'posit'){
                        $nu['posit'] = $msg['posit']+1;
                        $nu['negat'] = ($msg['negat'] > 0) ? $msg['negat']-1 : $msg['negat'];
                    }
                }else{
                    $nu[$acao] = $msg[$acao]+1;
                }

                $set_msg .= "$acao=$acao+1";
                $set_fav  = "voto='$acao'";
            }

            $up_msg = mysql_query("UPDATE msg SET $set_msg WHERE id_ms='$idms'");
            $up_fav = mysql_query("UPDATE fav SET $set_fav WHERE id_ms='$idms' AND id_us='$id_us'");

        }else{

            $nu[$acao] = $msg[$acao]+1;

            if ($acao == 'favor') {
                $atr = 'favor';
                $val = 1;
            }else{
                $atr = 'voto';
                $val = "'$acao'";
            }

            $date = mktime();
            $iplg = $_SERVER['REMOTE_ADDR'];
            $upms = mysql_query("UPDATE msg SET $acao=$acao+1 WHERE id_ms='$idms'");
            $adfv = mysql_query("INSERT INTO fav (id_ms, id_us, iplog, $atr, date) VALUES ('$idms', '$id_us', '$iplg', $val, $date)");
        }

        $nu['posit'] = str_pad((int) $nu['posit'],3,"0",STR_PAD_LEFT);
        $nu['negat'] = str_pad((int) $nu['negat'],3,"0",STR_PAD_LEFT);
        $nu['favor'] = str_pad((int) $nu['favor'],3,"0",STR_PAD_LEFT);

        echo $nu['posit'].'|'.$nu['negat'].'|'.$nu['favor'];

    }else{
        echo 'erro';
    }
}

# Liberar Mensagens para View -> user Normal ----------------------------------#
if (isset($_POST["oku"])) {
    $BuscaMsg = mysql_query("SELECT * FROM msg WHERE id_ms='".$_POST["oku"]."'");
    $existMsg = mysql_num_rows($BuscaMsg);
    $assocMsg = mysql_fetch_assoc($BuscaMsg);
    if($existMsg > 0) {
       if (($assocMsg['id_am'] == $id_us || $db_us['admin'] > 2) && $assocMsg['ok_user'] == 0) {
           @mysql_query("UPDATE user SET msg_pd=msg_pd-1 WHERE id_us='".$assocMsg['id_am']."'");
           @mysql_query("UPDATE msg SET ok_user=1 WHERE id_ms='".$_POST["oku"]."'");
       }else{
           echo 'erro';
       }
    }
}

# Liberar Mensagens para Sist -> user Admin -----------------------------------#
if (isset($_POST["lbr"])) {
    if ($db_us['admin'] > 1) {
        $BuscaMsg = mysql_query("SELECT * FROM msg WHERE id_ms='".$_POST["lbr"]."'");
        $existMsg = mysql_num_rows($BuscaMsg);
        $assocMsg = mysql_fetch_assoc($BuscaMsg);
        if($existMsg > 0){
           if (($assocMsg['id_am'] == $id_us || ($assocMsg['public'] == 1 && $db_us['admin'] > 0)) && $assocMsg['ok_user'] == 0) {
               @mysql_query("UPDATE user SET msg_pd=msg_pd-1 WHERE id_us='".$assocMsg['id_am']."'");
               @mysql_query("UPDATE msg SET ok_user=1 WHERE id_ms='".$_POST["lbr"]."'");
           }
           if ($db_us['admin'] > 0){
               @mysql_query("UPDATE msg SET ok_adm=1 WHERE id_ms='".$_POST["lbr"]."'");
           }
        }
    }
}

# Mudar ou Add Msg em uma Ctg -> user Admin -----------------------------------#
if (isset($_POST["idms"]) && isset($_POST["idtm"]) && $db_us['admin'] > 0) {
    $idms = $_POST["idms"];
    $idtm = $_POST["idtm"];
    $buscaMsg = mysql_query("SELECT * FROM msg WHERE id_ms='$idms'");
    $assocMsg = mysql_fetch_assoc($buscaMsg);
    $existMsg = mysql_num_rows($buscaMsg);
    if($existMsg > 0){
       if (($assocMsg['public'] == 1 && $db_us['admin'] > 0 && $assocMsg['ok_adm'] == 0) || $db_us['admin'] > 2){
            $adm = ($db_us['admin'] < 3) ? ', ok_adm=0' : '';
            $up1 = mysql_query("UPDATE msg SET public=1, id_am='$idtm'".$adm." WHERE id_ms='$idms'");
       }
       if ($up1) {
           if($assocMsg['ok_user'] == 0){
              $up2 = mysql_query("UPDATE user SET msg_qt=msg_qt-1, msg_pd=msg_pd-1 WHERE id_us='".$assocMsg['id_am']."'");
              $up3 = mysql_query("UPDATE user SET msg_qt=msg_qt+1, msg_pd=msg_pd+1 WHERE id_us='".$idtm."'");
           }else{
              $up2 = mysql_query("UPDATE user SET msg_qt=msg_qt-1 WHERE id_us='".$assocMsg['id_am']."'");
              $up3 = mysql_query("UPDATE user SET msg_qt=msg_qt+1 WHERE id_us='".$idtm."'");
           }
       }else{
           echo 'erro';
       }
    }else{
       echo 'erro';
    }
}

# Aceitar Resposta -> user Admin ----------------------------------------------#
if (isset($_POST["isub"]) && $db_us['admin'] > 0) {
    $up1 = mysql_query("UPDATE resp SET visto=1 WHERE id_sb='".$_POST["isub"]."'");
}


?>
