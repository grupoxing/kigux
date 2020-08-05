<?php   
       
// Direcionar para HTTPS
if(!$_SERVER["HTTPS"]){
    $url_ini = $_SERVER['SERVER_NAME'];
    $url_new = "https://".$url_ini.$_SERVER['REQUEST_URI'];
    header("Location: $url_new");
    exit;
}

// Ini - Setup - Class - iConta
   include $_SERVER['DOCUMENT_ROOT'].'/php/conexao.php';
   include $_SERVER['DOCUMENT_ROOT'].'/tool/iconta/iconta_class.php';
   $user = new Usuario(); 
// Fim - Setup - Class - iConta 
   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
     <title>Login de Usu&aacute;rio - Portal de Perguntas e Respostas.</title>
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
     <link href="/css/login.css" type="text/css" rel="stylesheet">
     <script src="/js/lib/prototype.js" type="text/javascript"></script>
     <script src="/js/lib/scriptaculous.js?load=dragdrop,effects" type="text/javascript"></script>
     <script type="text/javascript">
     Event.observe(window, 'load', function(){ new Draggable( 'win' ); });
     </script>
</head>
<body>
<div id="win">
      <div id="bg-tl"></div>
      <div id="bg-tc"></div>
      <div id="bg-tr"></div>
      <div id="bg-mc">     
           <?php include 'tool/iconta/iconta_login.php'; ?>          
           <div id="footer">
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
               <div class="footer_copy">&copy;2009-<?=date('Y');?> <b><?=$site;?></b> - Todos los derechos reservados - <a href="pv.php" title="Política de Privacidad">Privacidad</a>.</div>
           </div>          
      </div>
      <div id="bg-bl"></div>
      <div id="bg-bc"></div>
      <div id="bg-br"></div>
</div>
</body>
</html>