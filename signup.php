<?php

// Direcionar para HTTPS
if(!$_SERVER["HTTPS"]){
    $url_ini = $_SERVER['SERVER_NAME'];
    $url_new = "https://".$url_ini.$_SERVER['REQUEST_URI'];
    header("Location: $url_new");
    exit;
}

# Ini - Setup - Class - iConta
   include $_SERVER['DOCUMENT_ROOT'].'/php/conexao.php';
   include $_SERVER['DOCUMENT_ROOT'].'/tool/iconta/iconta_class.php';
   $user     = new Usuario();
# Fim - Setup - Class - iConta

?>
<!DOCTYPE html>
<html lang="pt">
    
<head> 
      <meta charset="UTF-8">
      <title>Registrar - <?=$site;?></title>
      <link href="/css/signup.css" type="text/css" rel="stylesheet">
      <style>#reg_head {display: none !important;}</style>
</head>
<body> 
<div id="win">
      <div id="bg-tl"></div>
      <div id="bg-tc"></div>
      <div id="bg-tr"></div>
      <div id="bg-mc">
          
           <div id="signup_head">
                <form class="signup_login" action="login.do" method="post">
                      <div class="signup_login-email">E-mail</div>
                      <input class="signup_login-input" name="email" type="text" size="10">
                      <div class="signup_login-senha">Senha</div>
                      <input class="signup_login-input" name="senha" type="password" size="10">
                      <input class="signup_login-logar" name="login" type="submit" value="ok">
                </form>               
                <div id="signup_list-user">
                     <div class="signup_list-user-t">Usuários recentes:</div>
                     <marquee scrollamount="1" behavior="alternate">
                     <?php
                     $query = mysql_query("SELECT id_ur, nome, foto FROM user_perfil WHERE foto NOT LIKE '%perf/foto%' ORDER BY date DESC LIMIT 0, 39 ");
                     while ($x = mysql_fetch_array($query)) {
                            list($nomeT) = explode(" ",$x['nome']);
            	            echo '<div class="signup_list-user-d"><a href="/'.$x['id_ur'].'" title="'.$x['nome'].'"><img src="'.$x['foto'].'" width="28px" title="'.$nomeT.'" border="0"></a></div>';
                     } ?>
                    </marquee>
                </div>
           </div>
          
           <div id="signup_main">
                <?php include 'tool/iconta/iconta_signup.php'; ?>
           </div>
          
           <div id="signup_footer">
                <ul>
                    <li class="footer_logo"><a href="http://<?=$link;?>/" title="Seu site de Perguntas e Respostas!"><?=$site;?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <li><a href="http://kigux.de/">kigux</a> <span>|</span> </li>
                    <li><a href="http://kicell.com/">kicell</a> <span>|</span> </li>
                    <li><a href="http://kipasa.com/">kipasa</a> </li>
                </ul>
                <div class="footer_sear">
                     <fieldset style="width: 400px; padding: 5px; border: #5E86B7 1px solid;">
                         <legend style="color: #fff;">Formulário de busca</legend>
                         <form style="margin-bottom: 5px;" action="buscar.do?id=diversas" id="cse-search-box">
                             <div>
                                <input class="sear_input" type="text" name="q" size="50" />
                                <input class="sear_acion" type="submit" name="sa" value="Buscar" />
                             </div>
                         </form>
                     </fieldset>
                </div>
                <div class="footer_copy">&copy;2009-<?=date('Y');?> <b><?=$site;?></b> - Todos los derechos reservados - <a href="/terms.php" title="Política de Privacidad">Privacidad</a>.</div>
           </div>
          
      </div>
      
      <div id="bg-bl"></div>
      <div id="bg-bc"></div>
      <div id="bg-br"></div>
      
</div>
    
</body>
</html>