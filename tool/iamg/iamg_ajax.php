<?php session_start();

# Conectar ao BD
include ($_SERVER['DOCUMENT_ROOT']."/php/conexao.php");

$date = mktime();
$ipus = $_SERVER['REMOTE_ADDR'];

# Config Sistema --------------------------------------------------------------#
if (isset($_SESSION["user"])) {
    $id_us = $_SESSION["user"]["id_us"];
    $db_us = $_SESSION["user"];
}

# Ini - Setup - Class - iAmg
  include('iamg_class.php');
  $iamg = new iAmg();  
# Fim - Setup - Class - iAmg

# Adicionar Amigo
if (isset($_POST["iam"])) {
    echo $iamg->iamg_addam($_POST["iam"]);
    return;
}

# Remover Amigo
if (isset($_POST["xam"])) {
    echo $iamg->iamg_delam($_POST["xam"]);
    return;
}

# Seguir Usuario
if (isset($_POST["isg"])) {
    echo $iamg->iamg_addsg($_POST["isg"]);
    return;
}

# Nao Seguir Usuario
if (isset($_POST["xsg"])) {
    echo $iamg->iamg_delsg($_POST["xsg"]);
    return;
}

# Mudar ou Add Tema em uma Ctg
if (isset($_POST["tema"]) && isset($_POST["sub"]) && $db_us['admin'] > 2) {
    $id_pre = $_POST["tema"];
    $id_pos = $_POST["sub"];    
    echo $iamg->iamg_addtema($id_pre, $id_pos);
    return;
}

function isEmail($email) {
         return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i",$email));
}

$call .= '<div class="iamg_call">';
   $call .= '<input class="iamg_call_input" id="call_email_2" type="text" size="10" onblur="ontx(\'call_email_2\',\'Digite o Email do Amigo!\');" onkeypress="iamg.vkey(event,2);oftx(\'call_email_2\',\'Digite o Email do Amigo!\');" onclick="oftx(\'call_email_2\',\'Digite o Email do Amigo!\');" onfocus="oftx(\'call_email_2\',\'Digite o Email do Amigo!\');" value="Digite o Email do Amigo!" maxlength="50">';
   $call .= '<input class="iamg_call_enter" type="submit" value="Convidar" onclick="iamg.call(2);">';
$call .= '</div>';

# Convidar Amigo
if (isset($_GET["email"])) {

    # Ini - Setup - Class - Amigos
    include($_SERVER['DOCUMENT_ROOT']."/tool/iamg/iamg_class.php");
    $am = new iAmg();
   
    # Email
    $email   = filter_input(INPUT_GET,"email");

    # Msg de Erro
    $erro =    '<div class="idrop_title" onmousedown="move.xy(this.parentNode.parentNode);">
                     Convidar Amigo
                </div>
                <div class="idrop_main">
                    <table style="background: #fff;" border="0" cellpadding="0" cellspacing="0">
                           <tr style="font: 11px Verdana; color: #404040; text-align: left;" valign="top">
                               <td style="padding: 10px;">
                                   <div class="iamg_call_erro">Erro ao Enviar o Convite!</div>
                                   <p style="margin: 0px; padding-top: 10px;"><b>Email:</b> <u>'.$email.'</u></p>
                                   <p style="margin: 0px; padding-top: 10px;">Por favor tente novamnete:</p>
                              </td>
                         </tr>
                    </table>
                </div>
                <div class="idrop_acion">
                     '.$call.'
                </div>';

    if (isEmail($email)){

        # Title do email
        $subject = "Convite de ".$db_us['nome'];

        # $headers  = "MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=UTF-8\n";
        $headers .= "From: Convite para $site <falecom@".$link.">\n";

        $message = '<html>
                    <head>
                    <title>Convite de Email - '.$site.'</title>
                    </head>
                    <body>
                    <table style="border: 1px solid #8080FF; background: #fff;" border="0" cellpadding="0" cellspacing="0">
                           <tr style="font: 11px Verdana; color: #404040; text-align: left;" valign="top">
                               <td style="padding: 10px;">
                                   <p style="margin: 0px; padding: 10px 0;">Ol&aacute;, sou eu <b>'.$db_us['nome'].'</b>!</p>
                                   <p style="margin: 0px; padding-bottom: 5px;">Lhe enviei este Convite para que voc&ecirc; tamb&eacute;m conhe&ccedil;a e participe do site: <a href="http://'.$link.'/'.$db_us['id_ur'].'" target="_blank">'.$site.'</a><br><br>
                                       No site '.$site.' voc&ecirc; pode:<br>
                                       - Criar seu perfil de Perguntas e Respostas Gr&aacute;tis;<br>
                                       - Conhecer melhor seus Amigos;<br>
                                       - Perguntar, tirar suas D&uacute;vidas ou Responda a de outros;<br>
                                       - Enviar perguntas An&ocirc;nimas;<br>
                                       - Participar de Discuss&otilde;es sobre o que voc&ecirc; Conhece ou tem Interesse;<br>
                                       - Criar Enquetes, Compartilhar Conhecimento;<br>
                                       - Falar no Bate-Papo Online e muito Mais - <b>Tudo Gr&aacute;tis!</b>
                                   </p>
                                   <div style="text-align: left; margin: 10px 0 0 0; padding: 0px;">
                                       <div style="float: left;">
                                           <a href="http://'.$link.'/'.$db_us['id_ur'].'" target="_blank">
                                              <img src="'.$db_us['foto'].'" width="75px">
                                           </a>
                                       </div>
                                       <div style="float: left; margin-left: 10px;">
                                           <b>Est&aacute; &eacute; minha Conta:</b>
                                           <BR>
                                           <BR>E-mail: '.$db_us['email'].'
                                           <BR>Link: http://'.$link.'/'.$db_us['id_ur'].'
                                           <BR>Estou esperando por voc&ecirc; - <a href="http://'.$link.'/'.$db_us['id_ur'].'" target="_blank">Me Adicione!!</a>
                                       </div>
                                   </div>
                                   <div style="margin-top: 20px; width: 100%; display: table; border-top: 1px solid #D0D0D0; padding-top: 5px; font-size: 10px;">
                                         Se n&atilde;o deseja receber esses e-mails, clique <a href="http://'.$link.'/login.do?noemail='.$email.'" target="_blank">aqui</a>!
                                   </div>
                              </td>
                         </tr>
                    </table>
                    </body>
                    </html>';

                    # Enviar o Convite
                    if (mail($email, $subject, $message, $headers)){
                        echo '<div class="idrop_title" onmousedown="move.xy(this.parentNode.parentNode);">
                                   Convidar Amigo
                              </div>
                              <div class="idrop_main">
                                  <table style="background: #fff;" border="0" cellpadding="0" cellspacing="0">
                                         <tr style="font: 11px Verdana; color: #404040; text-align: left;" valign="top">
                                             <td style="padding: 10px;">
                                                 <p style="margin: 0px; padding: 10px 0;">Ol&aacute; <b>'.$db_us['nome'].'</b>,</p>
                                                 <p style="margin: 0px; padding-bottom: 5px;">Seu Convite foi Enviado com Sucesso!</p>
                                                 <p style="margin: 0px; padding-bottom: 5px;"><b>Para o Email:</b> <u>'.$email.'</u></p>
                                                 <ul style="text-align: left; list-style: none outside none; margin: 10px 0 0 0; padding: 0px;">
                                                     <li style="float: left;">
                                                         <a href="http://'.$link.'/'.$db_us['id_ur'].'" target="_blank"><img src="'.$db_us['foto'].'" width="95px"></a>
                                                     </li>
                                                     <li style="margin-left: 10px; float: left;">
                                                         <b> Est&aacute; &eacute; sua Conta:</b>
                                                         <BR>
                                                         <BR>E-mail: '.$db_us['email'].'
                                                         <BR>Link: http://'.$link.'/'.$db_us['id_ur'].'
                                                     </li>
                                                 </ul>
                                                 <p style="clear: both; padding-top: 10px;">Obrigado por fazer parte do '.$site.'</p>
                                            </td>
                                       </tr>
                                  </table>
                            </div>
                            <div class="idrop_acion">
                                 '.$call.'
                            </div>';
                    }else{
                        echo $erro;
                    }
    }else{
       echo $erro;
    }

}else{
    echo       '<div class="idrop_title" onmousedown="move.xy(this.parentNode.parentNode);">
                     Convidar Amigo
                </div>
                <div class="idrop_main">
                    <table style="background: #fff;" border="0" cellpadding="0" cellspacing="0">
                           <tr style="font: 11px Verdana; color: #404040; text-align: left;" valign="top">
                               <td style="padding: 10px;">
                                   <p style="margin: 0px; padding: 10px 0;">Ol&aacute; <b>'.$db_us['nome'].'</b>,</p>
                                   <p style="margin: 0px; padding-top: 10px;">Envie um Convite:</p>
                              </td>
                         </tr>
                    </table>
                </div>
                <div class="idrop_acion">
                     '.$call.'
                </div>';
}

?>