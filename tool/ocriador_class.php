<?php

/**                                                               
* 
* @desc          Script para Tools Criadas por oCriador                                              
* @author 	 James S. de Aguiar <falecom.aguiar@gmail.com>    
* @copyright     Copyright 2008-2012, Desenvolvimento de Sistemas Web                                   
* @version       v1.4 - 26/03/2012 as 01:56                                                                                
* @license       Ocriador License (https://ocriador.com.br/license.php)         
*                                                                 
*/

require_once $_SERVER['DOCUMENT_ROOT'].'/php/conexao.php';

class oCriador{

      public function __construct() { 
              $this->dbus = $this->val_USER();
              $this->dbpg = $this->val_PAGE();          
              $this->conf = $this->val_CONF();          
      }
      
      private static $val_GET  = false;
      
      public function val_GET($chave) {
             if (self::$val_GET === false){ 
                 foreach($_GET as $_GETchave=>$_GETvalor) {
                         self::$val_GET[$_GETchave] = filter_input(INPUT_GET, $_GETchave);
                 } 
             }
             return self::$val_GET[$chave];
      }
      
      private static $val_POST = false;
      
      public function val_POST($chave) {
             if (self::$val_POST === false){ 
                 foreach($_POST as $_POSTchave=>$_POSTvalor) {
                         self::$val_POST[$_POSTchave] = filter_input(INPUT_POST, $_POSTchave);
                 } 
             }
             return self::$val_POST[$chave];
      }      
      
      private static $val_USER = false;
      
      public function val_USER($chave = false) {
             if (self::$val_USER === false){       
                 self::$val_USER = (isset($_SESSION["user"])) ? $_SESSION["user"] : false; 
                 // echo "<!--user:"; print_r(self::$val_USER); echo "-->";
             }
             if (self::$val_USER) {
                 if ($chave) {
                     return self::$val_USER[$chave]; 
                 }else{
                     return self::$val_USER;
                 }
             }else{
                 return false;
             }
      }   
      
      private static $val_PAGE = false;
      
      public function val_PAGE($chave = false) {
          
             if (self::$val_PAGE === false){
 
                 if (isset($_GET["idp"])) {
                     $buscaMsg = @mysql_query("SELECT * FROM msg WHERE cd_ms='".$this->val_GET("idp")."'");
                     if (@mysql_num_rows($buscaMsg) > 0) {
                         $assocMsg  = @mysql_fetch_assoc($buscaMsg);
                         $buscaUser = @mysql_query("SELECT SQL_CACHE * FROM user_perfil WHERE id_us='".$assocMsg['id_am']."'");
                         if (@mysql_num_rows($buscaUser) > 0) {
                             self::$val_PAGE = @mysql_fetch_assoc($buscaUser);
                         }
                     }                  
                 }elseif (isset($_GET["user"])) {
                     $id_pg = strtolower($this->val_GET("user"));
                     self::$val_PAGE = $this->assoc_db("user_perfil", "id_us='".$id_pg."' OR id_ur='".$id_pg."'");                      
                 }elseif (isset($_GET["id"])) {
                     $id_pg = strtolower($this->val_GET("id"));
                     self::$val_PAGE = $this->assoc_db("user_perfil", "id_us='".$id_pg."' OR id_ur='".$id_pg."'");                      
                 }
                 
                 if (self::$val_PAGE) {
                     // Idade User Page
                     self::$val_PAGE['idade'] = $this->idade(self::$val_PAGE['niver']);
                     // Contador User Page
                     self::$val_PAGE['total'] = $this->tool_iTotal()->ver_contador(self::$val_PAGE['id_us']);
                 }

		 //echo "ID_PAGE:"; print_r(self::$val_PAGE);
                 
             }  
             
             if (self::$val_PAGE) {
                 if ($chave) {
                     return self::$val_PAGE[$chave]; 
                 }else{
                     return self::$val_PAGE;
                 }
             }else{
                 return false;
             }
      } 
      
      private static $val_CONF = false;
      
      public function val_CONF($chave = false, $idioma = 'pt') {
             if (self::$val_CONF === false || self::$val_CONF['idioma'] != $idioma){  
                 include($_SERVER['DOCUMENT_ROOT'].'/php/ling-'.$idioma.'.php');  
                 self::$val_CONF = $_CONFIG; 
                 self::$val_CONF['idioma'] = $idioma;
             }  
             if ($chave) {
                 return self::$val_CONF[$chave]; 
             }else{
                 return self::$val_CONF;
             }
      }  
      
      private static $tool_iCache = false;
      
      public function tool_iCache() {
             if (self::$tool_iCache === false){  
                 require_once 'icache/icache_class.php';
                 self::$tool_iCache = new iCache();                             
             }  
             return self::$tool_iCache;
      }
      
      private static $tool_iTotal = false;
      
      public function tool_iTotal() {
             if (self::$tool_iTotal === false){  
                 require_once 'itotal/itotal_class.php';
                 self::$tool_iTotal = new iTotal();                             
             }  
             return self::$tool_iTotal;
      }
      
      private static $tool_iFav = false;
      
      public function tool_iFav($tipo_obj = false, $id_obj = false, $id_us = false) {
             if (self::$tool_iFav === false){  
                 require_once 'ifav/ifav_class.php';
                 self::$tool_iFav = new iFav();
             }  
             if ($tipo_obj && $id_obj) {
                 return self::$tool_iFav->ifav_main($tipo_obj, $id_obj, $id_us);
             }else{             
                 return self::$tool_iFav;
             }
      } 
      
      private static $tool_iMsg = false;
      
      public function tool_iMsg() {
             if (self::$tool_iMsg === false){  
                 require_once 'imsg/imsg_class.php';
                 self::$tool_iMsg = new iMsg();
             }  
             return self::$tool_iMsg;
      }      
      
      public function set_alert($type, $texto){
             if ($texto != '') {
                 $ponteiro = count($this->alert[$type]);
                 $this->alert[$type][$ponteiro] = $texto;
             } 
      }

      public function get_alert($type = 'all'){          
             if (($type == 'all' || $type == 'erro') && $this->alert['erro']) {
                 for ($i = 0; $i < count($this->alert['erro']); $i++) {
                     $html .= '<div class="alert_erro">'.$this->alert['erro'][$i].'</div>';
                 }
             }
             if (($type == 'all' || $type == 'aviso') && $this->alert['aviso']) {
                 for ($i = 0; $i < count($this->alert['aviso']); $i++) {
                     $html .= '<div class="alert_aviso">'.$this->alert['aviso'][$i].'</div>';
                 }
             }
             return $html;
      } 
      
      public function user_nivel($id_us){
      
             $total = $this->assoc_db("total", "id_us='$id_us'", "pontos");
             $user  = $this->info_user($id_us, 'sexo, public, admin');
             
             if ($user['public'] == 0) {

                 if ($user['admin'] > 3) $nivel = $this->conf['clasc'][1].' - '; else
                 if ($user['admin'] > 0) $nivel = $this->conf['clasc'][2].' - '; else
                                         $nivel = 'Usu&aacute;ri'.(($user['sexo'] == 'm') ? 'o' : 'a').' - ';
             }else{
                 
                 $nivel  =  'Grupo - ';
                 
             }

             if ($total['pontos'] < 300)      $nivel .= $this->conf['clasc'][3];  else
             if ($total['pontos'] < 800)      $nivel .= $this->conf['clasc'][4];  else
             if ($total['pontos'] < 1500)     $nivel .= $this->conf['clasc'][5];  else
             if ($total['pontos'] < 2500)     $nivel .= $this->conf['clasc'][6];  else
             if ($total['pontos'] < 5000)     $nivel .= $this->conf['clasc'][7];  else
             if ($total['pontos'] < 10000)    $nivel .= $this->conf['clasc'][8];  else
             if ($total['pontos'] < 15000)    $nivel .= $this->conf['clasc'][9];  else
             if ($total['pontos'] < 20000)    $nivel .= $this->conf['clasc'][10]; else
             if ($total['pontos'] > 25000)    $nivel .= $this->conf['clasc'][11];
             
             $nivel .= ' - '.$total['pontos'].' Pontos';
                     
             return $nivel;
       
      }      
      
      public function add_boot($id_perfil, $date_upp) {

             $num_perg  = self::$tool_iTotal->ver_contador($id_perfil, 'perg_recebidas');
             $num_pend  = self::$tool_iTotal->ver_contador($id_perfil, 'perg_pendentes');
	     $num_meses = $this->tempo($date_upp);
             $num_meses = $num_meses['meses'];

             if (($num_perg < 5 && $num_pend < 2) ||  $num_pend == 0 || $num_meses > 3){ 
                $perg_boot = @mysql_fetch_array(@mysql_query("SELECT id, pergunta FROM poll_boot WHERE categoria='usuario' ORDER BY contador ASC LIMIT 1"));
                if ($perg_boot) {
                    $date = mktime();
                    $txt  = $this->filtro($perg_boot['pergunta']);
                    $cdms = $this->codigo('msg', 'cd_ms', 'P', 20);
                    $addd = @mysql_query("INSERT INTO msg (cd_ms, id_us, id_am, texto, ok_user, ok_adm, date, date_up) VALUES ('$cdms','','".$id_perfil."','$txt','1','1','$date','$date')");
                    $id_perg = @mysql_insert_id();
                    if ($addd) {
                        $this->tool_iTotal()->add_contador('perg_pendentes', $id_perfil);
                        $this->tool_iTotal()->add_contador('perg_recebidas', $id_perfil);
                        $this->add_email($id_perg, $id_perfil);
                        @mysql_query("UPDATE poll_boot SET contador=contador+1 WHERE id=".$perg_boot['id']);
                        @mysql_query("UPDATE user_perfil SET date_up=$date WHERE id_us='$id_perfil'");
                    }
                }
             }
      }
      
      public function add_email($id_perg, $id_perfil){
          
             $timeNow = mktime();
             $timePre = $timeNow - 10800;

             $buscaMsg = @mysql_query("SELECT id_ms, cd_ms, id_us, id_am, texto, date FROM msg WHERE id_am='$id_perfil' AND id_ms<=$id_perg ORDER BY id_ms DESC LIMIT 0, 2");
             $assocMsg = @mysql_fetch_assoc($buscaMsg);
             $assocPre = @mysql_fetch_assoc($buscaMsg);

             if ($assocPre['date'] < $timePre) {

                 $info_us  = $this->info_user($assocMsg['id_us'], "id_us, id_ur, nome, email, foto");
                 $info_am  = $this->info_user($assocMsg['id_am'], "id_us, id_ur, nome, email, foto");

                 # Title do email
                 $subject  = $this->conf['site'].' '.$info_am['nome'];

                 # $headers  = "MIME-Version: 1.0\n";
                 $headers .= "Content-type: text/html; charset=iso-8859-1\n";
                 $headers .= "From: Nova Pergunta <aviso@pergunta.de>\n";

                 $message  = '
                 Ol�, <b>'.$info_am['nome'].'</b><br>Voc� recebeu uma nova pergunta!
                 <div style="width: 100%; display: table; margin-top: 20px;">
                      <div style="float: left; width: 79px; display: block;">
                           <a href="'.$this->conf['link'].'/'.$info_us['id_ur'].'"><img src="https://'.$this->conf['link'].$info_us['foto'].'" border="0" width="74"></a>
                      </div>
                      <div style="float: left; width: 20px; text-align: right; padding-top: 10px; margin-left: 5px;">
                           <img src="'.$this->conf['link'].'/tool/imsg/images/imsg_point.png" border="0">
                      </div>
                      <div style="float: left; width: 380px; padding: 5px 5px 2px 5px; background: #EDEFF0; margin-bottom: 15px;">
                           <div style="width: 372px; padding: 3px; overflow: auto; background: #fff;">
                                <div style="color: #909090; margin-bottom: 3px;">
                                     '.$info_us['nome'].' Perguntou:
                                </div>
                                <div style="display: block;">
                                     '.$this->XXview($assocMsg['texto'],45).'
                                </div>
                           </div>
                           <div style="margin-top: 5px; text-align: right;">
                                <a href="'.$this->conf['link'].'/view.do?idp='.$assocMsg['cd_ms'].'#l2"><img src="'.$this->conf['link'].'/tool/imsg/images/imsg_resp.jpg" border="0"></a>
                           </div>
                     </div>
                 </div>
                 <div style="width: 100%; display: table; margin-top: 15px;">
                      Seu Link: <a href="'.$this->conf['link'].'/'.$info_am['id_ur'].'">'.$this->conf['link'].'/'.$info_am['id_ur'].'</a>
                 </div>
                 <div style="width: 100%; display: table; margin-top: 5px; border-top: 1px solid rgb(208, 208, 208); padding-top: 5px; font-size: 9px; text-align: right;">
                      Se n�o deseja receber esses e-mails, clique <a href="'.$this->conf['link'].'/login.do?noemail='.$info_am['email'].'">aqui</a>
                 </div>';

                 @mail($info_am['email'], $subject, $message, $headers);
             }
      }      
      
      public function html_demo($total, $title, $link, $demo) {

             $html .= '<div class="box_demo">';
                   $html .= '<div class="box_head">'.$title.(($total != '') ? ' ('.$total.')' : '').'</div>';
                   $html .= '<div class="box_foto">'.$demo.'</div>';
                   $html .= '<div class="box_link">'.$link.'</div>';
             $html .= '</div>';

             return $this->type($html);
      }
      
      public function html_view($dados) {
              
             $html = '<div class="iamg_user">';
                   $html .= '<div class="auto">';
                         $html .= '<a class="foto" href="/'.$dados['id_ur'].'"><img src="'.$dados['foto'].'" width="60px" border="0" title="'.$dados['nome'].'"></a>';
                   $html .= '</div>';
                   $html .= '<div class="body">';
                         $html .= '<div class="perg">';
                               $html .= '<div class="title">';
                                     $html .= '<span class="gene-'.$dados['sexo'].' left"><a href="/'.$dados['id_ur'].'">'.$dados['nome'].'</a>, ';
                                     $html .= ($dados['sexo'] == 'm' || $dados['sexo'] == 'f') ? $this->idade($dados['niver']).' anos.' : '';
                                     $html .= '</span>';
                                     $html .= '<span class="right"> desde '.date("d-M-Y", $dados["date"]).'</span>';
                               $html .= '</div>';
                               $html .= '<br>&nbsp;https://'.$this->conf['link'].'/'.$dados['id_ur'];
                         $html .= '</div>';
                         $html .= '<div class="option">';
                               $html .= '<div id="ac-'.$dados['id_us'].'" class="acion-am">'.$this->acion($dados).'</div>';
                         $html .= '</div>';
                   $html .= '</div>';
             $html .= '</div>';

             return $this->type($html);
      }
      
      public function html_espera($assoc_amigo) {   
          
              $html = '<div class="iamg_user">
                             <div class="auto">
                                  <a class="foto" href="/'.$assoc_amigo['id_ur'].'"><img src="'.$assoc_amigo['foto'].'" width="60px" border="0" title="'.$assoc_amigo['nome'].'"></a>
                             </div>
                             <div class="body">
                                  <div class="perg">
                                       <div class="title">
                                            <span class="gene-'.$assoc_amigo['sexo'].' left"><a href="/'.$assoc_amigo['id_ur'].'">'.$assoc_amigo['nome'].'</a>, '.$this->idade($assoc_amigo['niver']).' anos. Adicionou Voc&ecirc;:</span>
                                            <span class="right">'.date("d-M-y H:i", $assoc_espera['date']).'</span>
                                       </div><br>
                                       '.self::$val_CONF['vcac'].' <u>'.$assoc_amigo['nome'].'</u> '.self::$val_CONF['como'].' seu novo Amigo?
                                  </div>
                                  <div class="option">
                                       <form method="POST"><input name="id_sp" type="hidden" value="'.$assoc_amigo['id_us'].'"><button name="on" type="submit">'.self::$val_CONF['gb-ac'].'</button>&nbsp;<button name="nao" type="submit">'.self::$val_CONF['gb-rc'].'</button></form>
                                  </div>
                             </div>
                        </div>';
                   
              return $this->type($html);
      } 
      
      public function html_face_msg($link, $num_post = 10, $width = 600) {
             $html  = '<div id="fb-root"></div>';
             $html .= '<script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, \'script\', \'facebook-jssdk\'));</script>';
             $html .= '<div class="fb-comments" data-href="'.$link.'" data-num-posts="'.$num_post.'" data-width="'.$width.'"></div>';
             return $html;
      }
      
      /**
      * @desc   Listar como temas as contas de user que seja Publica           
      * @param  string $id_conted -                               
      * @param  string $id_send   -                               
      * @return html $html - html do select temas                 
      *************************************************************************/
      public function select_temas($id_conted, $id_send, $id_us) {

              $html = '<span><select onchange="if (this.options[this.selectedIndex].value){ajax.uptm(\'' . $id_conted . '\',this.options[this.selectedIndex].value);}" style="width: 90px; height: 18px; float: right; margin-left: 5px;"><option value="">Usu&aacute;rios</option>';

              if ($id_us != '') {
                  $html .= '<option value="' . $id_us . '"';
                  if ($id_am == $id_us) $html .= 'style="font-weight: bold" selected="selected"'; $html .= '>Ele Mesmo!</option>';
              }

              $buscaTemas = @mysql_query("SELECT id_us, nome FROM user_perfil WHERE public=1 ORDER BY nome LIMIT 0, 300");
              while ($info = @mysql_fetch_array($buscaTemas)) {
                     $html .= ( $info['id_us'] == $id_pg) ? '<option value="' . $info['id_us'] . '" style="font-weight: bold" selected="selected">' . $info['nome'] . '</option>' : '<option value="' . $info['id_us'] . '">' . $info['nome'] . '</option>';
              }

              $html .= '</select></span>';

              return $html;
      }   

      public function url_load($url = false){
             $url = ($url == false) ? $_SERVER['REQUEST_URI'] : $url;
             header("Location:$url"); exit;
      }
      
      public function teste_amigo($id1, $id2){
             
             $buscaAmigo = @mysql_query("SELECT status FROM amg WHERE ((id_us='".$id1."' AND id_am='".$id2."') OR (id_us='".$id2."' AND id_am='".$id1."')) AND status=1");

             return (@mysql_num_rows($buscaAmigo) > 0) ? true : false;

      }
      
      public function historic($idus, $idpg, $acion, $table, $idrow){
          
             if (($table == 'perg' || $table == 'resp' || $table == 'wall')) {
                 $insert = @mysql_query("INSERT INTO actx (id_us, id_pg, acion, tabela, idrow, data) VALUES ('$idus', '$idpg', '$acion', '$table', '$idrow', '".mktime()."')");
             }

             return ($insert) ? true : false;
      }

     # Filtro de texto
     public function iPoll_filtro($txt) {
               
            while (substr($txt, 0, 1) == ' ')  $txt = substr($txt, 1);
            while (substr($txt, -1, 1) == ' ') $txt = substr($txt, 0, -1);

            $txt = strTr($txt, array('? ?' => '?', '??' => '?', '1)' => '','2)' => '','3)' => '','4)' => '','5)' => '','6)' => '','7)' => '','8)' => '','9)' => '','10)' => '', 'a)' => '','b)' => '','c)' => '','d)' => '','e)' => '','f)' => '','g)' => '','h)' => '','i)' => '','j)' => ''));
            $txt = strtoupper(substr($txt, 0, 1)).substr($txt, 1);
            $txt = htmlentities($txt, ENT_QUOTES, "UTF-8");
            $txt = eregi_replace("&lt;br&gt;", " ", $txt);
            $txt = eregi_replace("  ", " ", $txt);

            return $txt;
     }
     
      # Gravar Op��es de Respostas para Enquetes
      public function iPoll_add_resp($cd_ms, $a_resp) {
             if (count($a_resp) > 2) {
                 for ($i = 1; $i <= count($a_resp); $i++){         
                      $resp = $this->iPoll_filtro(strip_tags($a_resp[$i])); 
                      if (strlen($resp) > 0 && $resp != 'Adicionar op&ccedil;&atilde;o de resposta') {
                          if ($this->total_db("msg", "cd_ms='$cd_ms'") > 0) {        
                              if ($this->total_db("poll_resp","cd_ms='$cd_ms'") <= 10){
                                  $addr = @mysql_query("INSERT INTO poll_resp (cd_ms, id_us, resposta) VALUES ('$cd_ms','".$this->dbus['id_us']."','$resp')");
                              }
                          }
                      }
                 }           
             }
             if ($addr){
                 @mysql_query("UPDATE msg SET poll=1 WHERE cd_ms='$cd_ms'");
                 return true;
             }       
             $this->set_alert('erro', 'Erro ao Adicionar uma Resposta!');
             return false;
      }
      
      # Gravar nova Pergunta
      public function iMsg_add_perg($perg) {         
             
             if (strlen($perg) > 3 && $perg != 'digite sua pergunta aqui!') {
                 
                 # Verificar Envio de Spam httP
                 $num_http = substr_count(strtoupper($perg), 'HTTP');
                 if ($num_http > 0) {
                     if ($num_http > 5) {
                         $this->set_alert('erro', 'Est� pergunta foi reprovada, por favor evite o uso excessivo de links!');
                         return; 
                     }else{
                         $busca_spam = @mysql_query("SELECT * FROM poll_spam ORDER BY date DESC, rank DESC LIMIT 0, 100");
                         while ($assoc_spam = @mysql_fetch_array($busca_spam)) {
                                if (substr_count(strtoupper($perg),strtoupper($assoc_spam['texto'])) > 0){    
                                    @mysql_query("UPDATE poll_spam SET rank=rank+1, date=$date WHERE texto='".$assoc_spam['texto']."'");
                                    $this->set_alert('erro', 'Est� pergunta foi reprovada, por favor n�o use o termo: <b>'.$assoc_spam['texto'].'</b>');
                                    return;
                                }
                         }  
                     }
                 }

                 # Verificar Tamanho da Pergunta
                 if (strlen($perg) > 300) {
                     $this->set_alert('erro', 'Pergunta muito longa, reduza e encontre uma resposta mais r�pida!');
                     return;                     
                 }
                 
                 # Buscar Perguntas enviadas pelo user Atual
                 $date  = time();
                 $iplog = $_SERVER['REMOTE_ADDR'];
                 $busca = @mysql_query("SELECT id_ms, cd_ms, texto FROM msg WHERE ip='$iplog' ORDER BY id_ms DESC LIMIT 0, 30");            
                 
                 # Verificar enviou repetitivo de Perguntas
                 if (@mysql_num_rows($busca) > 5) {
                     $this->set_alert('erro', 'Vo�� me fez muitas perguntas sequenciais, por favor tente mais tarde!');
                     return;
                 }
                 
                 # Filtro de tratamenyo da Pergunta
                 $perg  = $this->filtro($perg).'?';
                 $perg  = str_replace('??', '?', $perg);
                 
                 # Verificar Repeticao de Pergunta Enviada
                 while ($x = @mysql_fetch_array($busca)) {
                        similar_text(strip_tags(strtoupper($perg)), strip_tags(strtoupper($x['texto'])), $pst);
                        if ((number_format($pst, 0) > 90)){
                            $this->set_alert('erro', 'Recebemos uma pergunta similar Recentemente, Crie outra ou <a href="https://'.$this->conf['link'].'/view.do?idp='.$x['cd_ms'].'#l2" target="_blank" title="Clique para Visualizar">- Veja essa Pergunta Aqui!</a>');
                            return;
                        }
                 } 
                 
                 # Gerar Codigo da Pergunta e Indentificar IDs user e page
                 $cdms = $this->codigo('msg', 'cd_ms', 'P', 20);
                 $idus = ($this->dbus['id_us']) ? (isset($_POST["anonimo"]) ? '_'.$this->dbus['id_us'] : $this->dbus['id_us']) : '';
                 $idpg = (isset($_POST["tema"])) ? filter_input(INPUT_POST,"tema") : $this->dbpg['id_us'];

                 // Tipo de Msg
                 $pubc = ($this->dbpg['public'] == 1) ? 1 : 0 ;

                 // Validacao da Msg
                 $okms = ($this->dbus['admin'] > 0) ? 1 : 0;
                 $okus = ($this->dbus['id_us'] == $this->dbpg['id_us'] || $this->dbus['admin'] > 0) ? 1 : 0;

                 // Privacidade da Msg
                 $view = (isset($_POST["privat_view"]) && $this->dbpg['public'] == 0 && ($_POST["privat_view"] >= 0 && $_POST["privat_view"] <= 2)) ? $_POST["privat_view"] : 0;
                 $resp = (isset($_POST["privat_view"]) && $this->dbpg['public'] == 0 && ($_POST["privat_reply"] >= 0 && $_POST["privat_reply"] <= 2)) ? $_POST["privat_reply"] : 0;

                 // Adicionar Msg
                 $addd = @mysql_query("INSERT INTO msg (cd_ms, id_us, id_am, texto, public, ip, ok_user, ok_adm, privat_view, privat_reply, date, date_up) VALUES ('$cdms','$idus','$idpg','$perg', $pubc, '$iplog','$okus','$okms', $view, $resp, '$date','$date')");
                 $idms = @mysql_insert_id();

                 if ($addd) {

                     # Atualizar Historico
                     $this->historic($idus, $this->dbpg['id_us'], 'add', 'perg', $idms);

                     # Enviar Aviso de Nova Pergunta
                     if ($this->dbpg['public'] == 0 && $this->dbus['id_us'] != $this->dbpg['id_us']) $this->add_email($idms, $this->dbpg['id_us']);

                     # Atualizar Contadores
                     $this->tool_iTotal()->add_contador('perg_pendentes', $idpg);
                     $this->tool_iTotal()->add_contador('perg_recebidas', $idpg);
                     if ($this->dbus) {
                         $this->tool_iTotal()->add_contador('perg_enviadas', $this->dbus['id_us']);
                     }

     		     // Atualizar Data UPP de Usuario
		     @mysql_query("UPDATE user_perfil SET date_up=$date WHERE id_us='$idpg'");
                     
                     return $cdms;

                 }else{
                     $this->set_alert('erro', 'Erro ao enviar a Pergunta - Tente Novamente!');
                     return;
                 }
             }else{
                 $this->set_alert('erro', 'Por favor digite uma pergunta v&aacute;lida!');
                 return;
             }
      }
      
     # Gravar Op�oes para Criar Enquete
     public function ipoll_addr($cd_ms, $resp) {
            if (strlen($resp) > 0 && $resp != 'Adicionar op��o de resposta') {
                $qtdp = $this->total_db("msg", "cd_ms='$cd_ms'");
                if ($qtdp > 0) {
                    $resp = $this->filtro(strip_tags(strTr($resp, array('1)' => '','2)' => '','3)' => '','4)' => '','5)' => '','6)' => '','7)' => '','8)' => '','9)' => '','10)' => '', 'a)' => '','b)' => '','c)' => '','d)' => '','e)' => '','f)' => '','g)' => '','h)' => '','i)' => '','j)' => '',')' => ''))));                    
                    $qtdr = $this->total_db("poll_resp","cd_ms='$cd_ms'");
                    if ($qtdr <= 10){
                        $addr = @mysql_query("INSERT INTO poll_resp (cd_ms, id_us, resposta) VALUES ('$cd_ms','".$this->dbus['id_us']."','$resp')");
                        if ($addr){
                            @mysql_query("UPDATE msg SET poll=1 WHERE cd_ms='$cd_ms'");
                            return true;
                        }
                    }
                }
            }
            return false;
     }       
      
      public function acion($info) {

                 if ($this->dbus['id_us'] != $info['id_us'] && $this->dbus['public'] == 0) {

                     //if ($this->dbus['id_us'] == $info['id_us']) {
                     //    $user = true;
                     //    $info = $this->info_user($this->dbpg['id_us'], 'id_us , id_ur, nome, foto, niver, sexo, public, date_up');
                     //}

                     if ($info['public'] == 0) {
                         $BuscaAmigo = @mysql_query("SELECT * FROM amg WHERE id_us='".$this->dbus['id_us']."' AND id_am='".$info['id_us']."' OR id_us='".$info['id_us']."' AND id_am='".$this->dbus['id_us']."'");
                         $assocAmigo = @mysql_fetch_assoc($BuscaAmigo);
                         if (@mysql_num_rows($BuscaAmigo) > 0){
                             if ($assocAmigo['status'] == 1) {
                                 $acion = '<span><a class="xam" href="javascript:" onclick="amigo.delam(\''.$info['id_us'].'\',this.parentNode);">Remover</a></span>';
                             }elseif ($assocAmigo['id_am'] == $this->dbus['id_us']){
                                 $acion = '<span><a class="iam" href="javascript:" onclick="amigo.addam(\''.$info['id_us'].'\',this.parentNode);">Aceitar</a></span>';
                             }else{
                                 $acion = '<span><a class="xam" href="javascript:" onclick="amigo.delam(\''.$info['id_us'].'\',this.parentNode);">Cancelar</a></span>';
                             }
                         }else{
                             $acion = ($this->dbus) ? '<span><a class="iam" href="javascript:" onclick="amigo.addam(\''.$info['id_us'].'\',this.parentNode);">Add Amigo</a></span>' : '<span><a class="iam" href="javascript:" onclick="idrop.abrir(\'/tool/iconta/iconta_login.php?url=/'.$this->dbpg['id_ur'].'\',false,true);">Add Amigo</a></span>';
                         }
                     }elseif ($this->dbpg['id_us'] == $info['id_us'] && $this->dbus['admin'] > 2) {
                         
                             $acion .= '<span class="dropdown"><select onchange="if (this.options[this.selectedIndex].value){amigo.itm(\''.$info['id_us'].'\',this.options[this.selectedIndex].value,this.parentNode);}" style="float: left; height: 21px; width: 99px;" name="temas"><option value="">Add Tema</option>';
                             
                             $subtm  = $this->assoc_db("group_sb", "id_pos='".$info['id_us']."'", "id_pre");
                             
                             $buscaTemas    = @mysql_query("SELECT id_us, id_ur, nome, id_ur, nome FROM ctg, user_perfil WHERE id_us=id_ctg AND public=1 AND NOT EXISTS (SELECT id_pos FROM group_sb WHERE id_pos=id_ctg) ORDER BY nome LIMIT 0, 300");
                             while ($temas  = @mysql_fetch_array($buscaTemas)) {
                                    $acion .= '<option class="temas_nivel_1" value="'.$temas['id_us'].'"'.(($subtm['id_pre'] == $temas['id_us']) ? 'selected="selected"' : '').'>'.$temas['nome'].'</option>';
                                    $buscaST1 = @mysql_query("SELECT id_us, id_ur, nome FROM group_sb, user_perfil WHERE id_pre='".$temas['id_us']."' AND id_us=id_pos AND public=1 ORDER BY nome LIMIT 0, 300");
                                    while ($assocST1  = @mysql_fetch_array($buscaST1)) {
                                           $acion .= '<option class="temas_nivel_2" value="'.$assocST1['id_us'].'"'.(($subtm['id_pre'] == $assocST1['id_us']) ? 'selected="selected"' : '').'>'.$assocST1['nome'].'</option>';                                                   
                                    }
                             } 
                             $acion .= '<option class="temas_nivel_1" value="0">Deletar</option>';           
                             $acion .= '</select></span>';
                     }

                     $buscaSeguidor = @mysql_query("SELECT * FROM seg WHERE id_ad='".$this->dbus['id_us']."' AND id_ac='".$info['id_us']."'");
                     if ($buscaSeguidor) {
                         $assocSeguidor = @mysql_fetch_assoc($buscaSeguidor);
                         if (@mysql_num_rows($buscaSeguidor) > 0){
                             $textn  = ($info['public'] == 0) ? 'N&atilde;o Seguir' : 'Remover';
                             $acion .= '<span><a class="xsg" href="javascript:" onclick="amigo.seg(\''.$info['id_us'].'\',this.parentNode,0);">'.$textn.'</a></span>';
                             if ($this->dbpg['id_us'] == $info['id_us']) {
                                 $date = mktime();
                                 @mysql_query("UPDATE seg SET date='$date' WHERE id_ad='".$this->dbus['id_us']."' AND id_ac='".$info['id_us']."'");
                             }elseif ($assocSeguidor['date'] < $info['date_up']){
                                 $acion .= '';
                             }
                         }else{
                             $texts  = ($info['public'] == 0) ? 'Seguir' : 'Participar';
                             $acion .= ($this->dbus) ? '<span><a class="isg" href="javascript:" onclick="amigo.seg(\''.$info['id_us'].'\',this.parentNode,1);">'.$texts.'</a></span>' : '<span><a class="isg" href="javascript:" onclick="idrop.abrir(\'/tool/iconta/iconta_login.php?url=/'.$this->dbpg['id_ur'].':add\',false,true);">'.$texts.'</a></span>';
                         }
                     }
                     
                     $html .= $acion;
                 }

                 return $this->type($html);

      }      

      /**
      * @desc   Retorna Array de Intervalo de Tempo entre Duas Datas    
      * @param  int $date_ini - Data Inicial: mktimer()
      * @param  int $date_fim - Data Final: mktimer()        
      * @return array   - anos, meses, dias, horas, minutos                        
      *************************************************************************/
      public function tempo($date_ini, $date_fim = false){

	     $date_fim  = (!$date_fim) ? mktime() : $date_fim;

	     $datetime1 = date_create(date("y-m-d h:i", $date_ini));
	     $datetime2 = date_create(date("y-m-d h:i", $date_fim));
	     $interval  = date_diff($datetime2, $datetime1);

	     $tempo['anos']      = $interval->format('%y');

	     $tempo['meses']     = $interval->format('%m');
	     $tempo['meses']     = $tempo['meses']+($tempo['anos']*12);

	     $tempo['dias']      = $interval->format('%d');
	     $tempo['dias']      = $tempo['dias']+($tempo['meses']*30);

	     $tempo['horas']     = $interval->format('%H');
	     $tempo['horas']     = $tempo['horas']+($tempo['dias']*24);
	     $tempo['horas']     = str_pad($tempo['horas'], 2, "0", STR_PAD_LEFT);

	     $tempo['minutos']   = $interval->format('%I');
	     $tempo['minutos']   = $tempo['minutos']+($tempo['horas']*60);

	     return $tempo;
      }

      /**
      * @desc   Retorna o Mes por escrito para ligua escolhida    
      * @param  int $mes - numero do mes a ser convertido         
      * @return string   - mes por escrito                        
      *************************************************************************/
      public function mes($mes){
             switch ($mes) {
                     case 1: return self::$val_CONF['ms-jn'];
                     case 2: return self::$val_CONF['ms-fv'];
                     case 3: return self::$val_CONF['ms-mr'];
                     case 4: return self::$val_CONF['ms-ab'];
                     case 5: return self::$val_CONF['ms-ma'];
                     case 6: return self::$val_CONF['ms-jn'];
                     case 7: return self::$val_CONF['ms-jl'];
                     case 8: return self::$val_CONF['ms-ag'];
                     case 9: return self::$val_CONF['ms-st'];
                     case 10: return self::$val_CONF['ms-ot'];
                     case 11: return self::$val_CONF['ms-nv'];
                     case 12: return self::$val_CONF['ms-dz'];
                     default: return self::$val_CONF['ms-nd'];
             }
      }

      /**
      * @desc Gerar idade com base na data de nascimento          
      * @param Date $data1 - data de nascimento do usuario        
      * @return int $idade - idade atual do usuario               
      *************************************************************************/
      public function idade($data1) {

    	     $data2 = date("Y-m-d", mktime () );

    	     list($ano1, $mes1, $dia1) = explode("-", $data1);
    	     list($ano2, $mes2, $dia2) = explode("-", $data2);
    	     if (($mes1 > $mes2) || ($mes1 == $mes2 && $dia1 > $dia2)) $idade = ($ano2 - $ano1) - 1; else $idade = $ano2 - $ano1;

    	     return $idade;
      }

      /**
      * @desc Funcao recursiva para gerar um code unico em uma tabela no bd    
      * @param  string $table - Tabela pra fazer o teste de existencia         
      * @param  string $campo - Campo da tabela para testar existencia         
      * @param  string $ini   - Caracteres iniciais para add no code           
      * @param  int    $tam   - Quantidade de caracteres a ser add em $ini     
      * @return string $code  - codigo unico que ainda nao existe na $table    
      *************************************************************************/
      public function codigo($table, $campo, $ini = '', $tam = 20) {
             if ($table != '' && $campo != ''){
                 $code = $ini.strtoupper(substr(md5(uniqid(time())), 0, $tam));
                 $sear = @mysql_query("SELECT * FROM $table WHERE $campo='$code'");
                 if ($sear && @mysql_num_rows($sear) > 0) {
                     $code = $this->novo_code($table, $campo, $ini, $tam);
                 }else{
                     return $code;
                 }
             }else{
                 return false;
             }
      }

      /**
      * @desc Retorna 1,0 intercalados para atribuir cores a DIVs intercalados 
      * @return int $cor  - numero da cor 0 ou 1                  
      *************************************************************************/
      public function color() {
             static $cor;
             if ($cor == '1') $cor='2'; else $cor='1'; return $cor;
      }
       
      /**
      * @desc faz uma quebra de linha no html
      * @param  html $html - html a ser manipulado
      * @return html $html - com quebra de linha
      *************************************************************************/
      public function type($html) {
             return $html."\n";
      }
       
      /**
      * @desc   Converter txt em MAIUSC:1, minusc:2, Minusc:3, numCaract. Fixo
      * @param  string term - texto para converter                
      * @param  int    tipo - tipo de convercao                   
      * @param  int    tam  - quantidade de caracteres            
      * @return string term - texto convertido                    
      *************************************************************************/
      public function strcvt($term, $tipo, $tam = false) {

             if ($tipo == "1")     $term = strtr(strtoupper($term),"áàãâéêíóôõúüç","ÁÀÃÂÉÊÍÓÔÕÚÜÇ");
             elseif ($tipo == "2") $term = strtr(strtolower($term),"ÁÀÃÂÉÊÍÓÔÕÚÜÇ","áàãâéêíóôõúüç");
             elseif ($tipo == "3") $term = ucwords(strtr(strtolower($term),"ÁÀÃÂÉÊÍÓÔÕÚÜÇ","áàãâéêíóôõúüç"));
             if ($tam)             $term  = substr($term, 0, $tam);
              
             return $term;
      }
      
      public function format_link_user($link = false) {
             return '/'.(($link) ? $link : $this->dbus['id_ur']);
      }   
      
      public function format_text_sear($str) {

             # Remover CodeHTML e Outros
             $str = str_replace('&lt;', '<', $str);
             $str = str_replace('&gt;', '>', $str);
             $str = str_replace('%2F', '/', $str);
             $str = str_replace('%3F', '?', $str);
             $str = str_replace('%26', '&', $str);
             $str = str_replace('%3D', '=', $str);
             $str = strip_tags($str);


             # Transformando tudo em Min�sculas
             $str = trim(strtolower($str));

             # Tirando Espa�os Extras da String
             while (strpos($str,"  ")) $str = str_replace("  "," ",$str);
             
             $caracteresEspeciais = array ("�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�","�",".","*","$","^","&","!","?",",","_","\"","\\","/","&#34;","&#34;");
             $caracteresLimpos    = array ("A","E","o","C","a","e","O","c","A","E","o","a","e","N","A","n","a","o","O","A","o","Y","a","I","O","y","A","i","o","a","I","A","i","�","A","I","u","i","U","I","u","i","U","E","u","e","O","U","p","E","o","u","p","e","O","B","[.]","[*]","[$]","","","","","","","","","" ,"" ,"");
             $str = str_replace($caracteresEspeciais,$caracteresLimpos,$str);

             # Montar a expressão regular para o MySQL
             $caractresSimples     = array("a","e","i","o","u","c");
             $caractresEnvelopados = array("[a]","[e]","[i]","[o]","[u]","[c]");
             $caracteresParaRegExp = array("(A|a|�|&Aacute;|�|&aacute;|�|&Acirc;|�|&acirc;|�|&Agrave;|�|&agrave;|�|&Aring;|�|&aring;|�|&Atilde;|�|&atilde;|�|&Auml;|�|&auml;)","(E|e|�|&Egrave;|�|&egrave;|�|&Euml;|�|&euml;|�|&Eacute;|�|&eacute;|�|&Ecirc;|�|&ecirc;)","(I|i|�|&Iacute;|�|&iacute;|�|&Icirc;|�|&icirc;|�|&Igrave;|�|&igrave;|�|&Iuml;|�|&iuml;)","(O|o|�|&ocirc;|�|&Ograve;|�|&ograve;|�|&eth;|�|&Otilde;|�|&otilde;|�|&Ouml;|�|&ouml;|�|&Oacute;|�|&oacute;|�|&Ocirc;)","(U|u|�|&Uacute;|�|&uacute;|�|&Ucirc;|�|&ucirc;|�|&Ugrave;|�|&ugrave;|�|&Uuml;|�|&uuml;)","(C|c|�|&Ccedil;|�|&ccedil;)");

             $str = str_replace($caractresSimples,$caractresEnvelopados,$str);
             $str = str_replace($caractresEnvelopados,$caracteresParaRegExp,$str);

             # Trocando Espa�o por (.*): qual quer coisa
             $str = str_replace(" ",".*",$str);

             return $str;
      }
      
      /**
      * @desc   Acrescentar Zeros a esquerda para fixa a quant. de digitos     
      * @param  int number                                        
      * @param  int quantidade de digitos                         
      * @return int number com qualtidade de digitos fixo         
      *************************************************************************/
      public function nupad($number, $n = 3) {
             return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
      }
      
      /**
      * @desc gerar url amigavel de acordo com o .htaccess        
      * @param string  $idur - indetificador(url) do usuario      
      * @param string  $cmd  - indetificador da pagina            
      * @param string  $exe  - sub indetificador da pagina        
      * @param int     $_pg  - indetificador da pagina atual      
      * @return string $url  - amigavel                           
      *************************************************************************/
      public function link($idur = '', $cmd = '', $exe = '', $_pg = '') {
             if ($idur != '') {
                 $url = '/'.$idur;
		 if ($cmd != '') $url .= '/'.$cmd;
		 if ($exe != '') $url .= '/'.$exe;
                 if ($_pg != '') $url .= '/'.$_pg;
	     }else{
		 $url = '/';
             }
             return $url;
      }
      
      /**
      * @desc gerar url amigavel de acordo com o .htaccess              
      * @param int     $_pg  - indetificador da pagina pra link      
      * @return string $url  - amigavel                           
      *************************************************************************/
      public function ilink($_pg = 1) {
             
             if ($this->val_GET('ctg')) {
                 $url .= '/'.$this->val_GET('ctg');                 
             }
             
             if ($this->val_GET('tag')) {
                 $url .= '/'.$this->val_GET('tag');   
             }

             
             if ($this->val_GET('user')) {
                 $url .= '/'.$this->val_GET('user');
             }elseif ($this->val_GET('id') && !$this->val_GET('tag')) {
                 $url .= '/'.$this->val_GET('id');
             }    
             
             if ($this->val_GET('cmd')) {
                 $url .= '/'.$this->val_GET('cmd');
             }
             if ($this->val_GET('exe')) {
                 $url .= '/'.$this->val_GET('exe');
             } 
             
             $url .= '/'.$_pg;
             
             return $url;
      }
      
      /**
      * @desc  Criar Paginacao de Conteudo                                        
      * @param int  $qtd - quantidade de arquivos total         
      * @param int  $npg - quantidade de arquivos por pagina 
      * @param int  $tam - quantidade de opcoes na paginacao   
      * @return array ( html, row )                          
      *************************************************************************/
      public function ipage($qtd, $npg = 10, $tam = 11) {      
             
             $ini = 1;
             $fim = $num = ceil($qtd/$npg);
             
             if ($this->val_GET('pg') > 0 && $this->val_GET('pg') <= $num) $pg = $this->val_GET('pg')-1;
             elseif ($this->val_GET('pg') > $num)                          $pg = $num-1;
             else                                                          $pg = 0;

             if ($num > $tam) {
                 $tam2 = $tam/2;
                 if ($pg > ($num - ($tam2+1))) $ini = ceil($pg - ($tam-1) + ($num - $pg));
                 elseif ($pg > ($tam2-1))      $ini = ceil($pg - ($tam2-1));
                 if ($pg < ($num - $tam2))     $fim = $ini + ($tam-1);
             }        
             
             $html .= "<div class='pg'>";
             $html .= ($pg > 0) ? "<a href='".$this->ilink(1)."#l2'><</a><a href='".$this->ilink($pg)."#l2'><<</a>" : "<span class='pg-off'><</span><span class='pg-off'><<</span>";
             for ($i = $ini; $i <= $fim; $i ++) $html .= ($i == $pg + 1) ? "<span class='pg-off'>$i</span>" : "<a href='".$this->ilink($i)."#l2'>$i</a>";
             $html .= ($pg < $num - 1) ? "<a href='".$this->ilink($pg + 2)."#l2'>>></a><a href='".$this->ilink($num)."#l2'>></a>" : "<span class='pg-off'>>></span><span class='pg-off'>></span>";
             $html .= "</div>";
             
             return array("html" => $html, "row" => $pg*$npg);
                 
      }         
      
      /*************************************************************************
      * @desc Diminuir ou Limpar nome Pessoal
      * @param  string $nome - Nome sujo, original ou grande
      * @return string $nome - Nome limpo com tamanho Maximo Escolhido
      *************************************************************************/
      public function limpar_nome($nome, $tam = 20) {
          
             $tam = (substr_count($nome, '&')*8)+$tam;
             
             if (substr_count($nome, '@') > 0) {
                 list($nome, $domain) = explode("@", $nome);
             }

             $nomes = explode(" ", ucwords(strtolower($nome)), 10);

             if (strlen($nomes[0].' '.$nomes[1]) < $tam){
                 if (strlen($nomes[0].' '.$nomes[1].' '.$nomes[2]) < $tam){
                     $nome = $nomes[0].' '.$nomes[1].' '.$nomes[2];
                 }else{
                     $nome = $nomes[0].' '.$nomes[1];
                 }
             }else{
                 if (strlen($nomes[0].' '.$nomes[2]) < $tam){
                     $nome = $nomes[0].' '.$nomes[2];
                 }else{
                     $nome = substr($nomes[0], 0, $tam);
                 }
             }

             while (substr($nome, -1, 1) == ' ') $nome = substr($nome, 0, -1);
             
             if (strlen($nome) == 0){
                 $nome = 'An&ocirc;nimo';
             }

             return $nome;
      }      
      
      public function extname($file) {
              $file = explode(".",basename($file));
              return $file[count($file)-1];
      }

      public function getfilesize($size) {
             if ($size < 5) return "$size byte";
             $units = array(' bytes', ' KB', ' MB', ' GB', ' TB');
             for ($i = 0; $size > 1024; $i++) { $size /= 1024; }
             return round($size, 2).$units[$i];
      }        

      /*************************************************************************
      * @desc   Retorna informacoes de usuario com primeiro nome               *
      * @param  string $id    - id_us de usuario na tabela user                *
      * @param  string $info  - campos a serem retornados separado por virgula *
      * @return array  $assoc - informacoes do usuario em ar                   *
      *************************************************************************/
      public function info_user($id = '', $info = '*', $tam = 20){

             $query = @mysql_query("SELECT $info FROM user_perfil WHERE id_us='$id' AND id_us<>''");
             $assoc = @mysql_fetch_assoc($query);
             $total = @mysql_num_rows($query);

             if ($total > 0){
                 $assoc['nome'] = $this->limpar_nome($assoc['nome'], $tam);
             }else{
                 $assoc['id_ur']       = '#';
                 $assoc['foto']        = '/foto/perf/foto-mx.jpg';
                 $assoc['nome']        = (strlen($id) > 3) ? strtoupper(substr($id, 0, 5)).'_USER' : 'An�nimo';
             }

             return $assoc;
      }
      
      /**
      * @desc   Atualiza Campos na Tabela do Ucuario $id  
      * @param  string $id    - id_us de usuario na tabela   
      * @param  string $set   - campo e valor para update
      * @return true ou false     
      *************************************************************************/
      public function update_user($id = '', $set = false){
             
             return ($set && (substr_count($set,"=") > 0)) ? @mysql_query("UPDATE user_perfil SET $set WHERE id_us='$id'") : false;

      }      

      /**
      * @desc   Retorna informacoes de uma tabela tal que ocorra where         
      * @param  string $table  - tabela para fazer a busca        
      * @param  string $where  - condicao para retorno de informacao do bd    
      * @param  string $campos - campos para serem retornados     
      * @return array  $assoc  - informacoes encontradas no bd    
      *************************************************************************/      
      public function assoc_db($table, $where = '', $campos = '*', $order = '', $limit = '', $cache = '5 minutes'){
          
             $query  = "SELECT $campos FROM $table";
             $query .= (($where != '') ? " WHERE $where" : "");
             $query .= (($order != '') ? " ORDER BY $order" : "");
             $query .= (($limit != '') ? " LIMIT $limit" : " LIMIT 1");
             
             
             //if ($cahe) {
                 //$assoc  = $this->tool_iCache()->icache_read($query);
             //}
             //if (!$assoc) {
                 $busca = @mysql_query($query);               
                 $assoc = @mysql_fetch_assoc($busca);
                 //if ($cahe) {
                     //$this->tool_iCache()->icache_save($query, $assoc, $cache);
                 //}
             //}
             
             return $assoc;
             
      }
      
      /**
      * @desc   Retorna Linhas de uma Tabela tal que ocorra Where         
      * @param  string $table  - tabela para fazer a busca        
      * @param  string $where  - condicao para retorno de informacao do bd    
      * @param  string $campos - campos para serem retornados     
      * @return array  $array  - Lista de Linhas Encontradas   
      *************************************************************************/      
      public function array_db($table, $where = '', $campos = '*', $order = '', $limit = '', $cache = '5 minutes'){
          
             $query  = "SELECT $campos FROM $table";
             $query .= (($where != '') ? " WHERE $where" : "");
             $query .= (($order != '') ? " ORDER BY $order" : "");
             $query .= (($limit != '') ? " LIMIT $limit" : " LIMIT 100");
             
             //if ($cahe) {
             //    $assoc = $this->tool_iCache()->icache_read($query);
             //}
             //if (!$assoc) {
                 $busca = @mysql_query($query);               
                 $total = @mysql_num_rows($busca);
                 if ($total > 0) {
                     for ($i = 0; $i < $total; $i++) {
                         $assoc[$i] = @mysql_fetch_array($busca);  
                     }
                     //if ($cahe) {
                     //    $this->tool_iCache()->icache_save($query, $assoc, $cahe);
                     //}
                 }else{
                     $assoc = array();
                 }
             //}
             
             return $assoc;
             
      }      
      
      /**
      * @desc   Deletar um Registro no BD e Enviar para Table Lixeira     
      * @param  string $table  - tabela para deletar o registro   
      * @param  string $where  - condicao para deletar o registro 
      * @return boolean        - retorna true para sucesso e false para erro  
      *************************************************************************/
      public function delete_db($table, $where){

             $query  = "SELECT * FROM $table WHERE $where";
             $result = @mysql_query($query);
             
             if (@mysql_num_rows($result) > 0) {

                 $date = mktime();
                 $ipus = $_SERVER['REMOTE_ADDR'];
                 $fqtd = @mysql_num_fields($result);
                 
                 $this->acion = (isset($this->acion)) ? $this->acion : $this->codigo('lixeira', 'acao', 'AC', 30);

                 for ($i = 0; $i < $fqtd; $i++) {

                      // Nome do Campo
                      $fields[$i] = @mysql_field_name($result, $i);

                      if ($fields[$i] == 'texto') {
                          $num_row_texto = $i;
                      }

                      // Tipo de Campo
                      $type     = @mysql_field_type($result, $i);
                      $tnum[$i] = ($type == 'tinyint' || $type == 'smallint' || $type == 'mediumint' || $type == 'int' || $type == 'bigint'  ||$type == 'timestamp') ? true : false;

                 }

                 $campos = implode(', ', $fields);

                 while ($row = @mysql_fetch_row($result)) {
                        for ($j = 0; $j < $fqtd; $j++) {
                             // Valor do Campo
                             if (!isset($row[$j])) {
                                 $values[$j] = 'NULL';
                             }elseif (!empty($row[$j])) {
                                 $values[$j] = ($tnum[$j]) ? $row[$j] : "\'".addslashes($row[$j])."\'";
                             }else{
                                 $values[$j] = "\'\'";
                             }
                        }
                        $view   = (isset($num_row_texto)) ? substr($row[$num_row_texto], 0, 99) : '';
                        $vdados = implode(', ', $values);
                        $codigo = 'INSERT INTO '.$table.' ('.$campos.') VALUES ('.$vdados.')';
                        $insert = @mysql_query("INSERT INTO lixeira (idus, idpg, acao, type, view, code, ipus, date) VALUES ('".$this->dbus['id_us']."', '".$this->dbpg['id_us']."', '".$this->acion."', '$table', '$view', '$codigo', '$ipus', $date)");
                }

                $delete  = @mysql_query("DELETE FROM $table WHERE $where");

            }

            return $delete ? true : false;
      }
      
      /**
      * @desc   Atualiza Campos de uma Tabela  
      * @param  string $table  - tabela para atualizar o registro   
      * @param  string $where  - condicao para atualizar o registro 
      * @return true ou false     
      *************************************************************************/
      public function update_db($table, $set = false, $where = false){
             
             return ($set && $where && (substr_count($set,"=") > 0)) ? @mysql_query("UPDATE $table SET $set WHERE $where") : false;

      }  
      
      /**
      * @desc   Adicionar Novos Campos na Tabela $table
      * @param  string $table  - tabela para adicionar o registro   
      * @param  string $campos - campos para adicionar o registro
      * @param  string $dados  - dados para adicionar o registro  
      * @return true ou false     
      *************************************************************************/
      public function insert_db($table, $campos = false, $dados = false){
             if ($campos && $dados) {
                 return mysql_query("INSERT INTO $table ($campos) VALUES ($dados)");
             }
             return false;
      }       

      /**
      * @desc   Contador de Registro no Banco de Dados          
      * @param  table - tabela para contagem                    
      * @param  where - Condicao para contar os registros       
      * @return int   - total de registros encontrados          
      *************************************************************************/
      public function total_db($table, $where = '', $cache = false){   
          
             $query  = "SELECT count(*) as total_db FROM $table";
             $query .= (($where != '') ? " WHERE $where" : "");
             
             if ($cache) {
                 $assoc = $this->tool_iCache()->icache_read($query);
             }
             if (!$assoc) {
                 $busca = @mysql_query($query);
                 $assoc = @mysql_fetch_assoc($busca);
                 if ($cache) {
                     $this->tool_iCache()->icache_save($query, $assoc, $cache);
                 }
             }  
             
             return ($assoc && $assoc > 0) ? $assoc['total_db'] : 0;
      }

      /**
      * @desc   Contador de Registro no Banco de Dados          
      * @param  table - tabela para contagem                    
      * @param  where - Condicao para contar os registros       
      * @return int   - total de registros encontrados          
      *************************************************************************/
      public function isCode() {
             $code  = '';
             $lista = array("z","A","2","x","B","3","v","C","4","t","D","5","s","E","p","6","n","F","7","j","G","8","H","h","9","J","L","2","M","g","3","f","N","4","d","P","5","e","R","6","b","S","7","c","T","8","a","V","9","X","Z");
             $sort  = array_rand($lista, 6);
             for($i = 0; $i < 6; $i++) $code .= $lista[$sort[$i]];
             $_SESSION["code"] = $code;
      }

      /**
      * @desc   Contador de Registro no Banco de Dados            
      * @param  table - tabela para contagem                      
      * @param  where - Condicao para contar os registros         
      * @return int   - total de registros encontrados            
      *************************************************************************/
      public function onCode() {
     	     if (isset($_POST['code'])) {
                 if (strtolower($_POST['code']) == strtolower($_SESSION['code'])) {
                     return "1";
                 }else{
                     $this->erro_code = '<font color="#FF0000">Erro:</font> O código de confirmação não confere.';
                     $this->isCode();
                     return "0";
                 }
             }else{
                 $this->isCode();
                 return "1";
             }
      }

      /**
      * @desc   Filtro de html para envio de Mesagem              
      * @param  string $txt - texto a ser filtrado                
      * @param  int    $tam - tamanho maximo do texto             
      * @return string $txt - texto filtrado e pronto para envio  
      *************************************************************************/
      public function filtro($txt, $tam = false) {
               
             $txt = str_replace('&lt;', '<', $txt);
	     $txt = str_replace('&gt;', '>', $txt);
               
  	     $txt = str_replace('%2F', '/', $txt);
  	     $txt = str_replace('%3F', '?', $txt);
             $txt = str_replace('%26', '&', $txt);
	     $txt = str_replace('%3D', '=', $txt);

             $txt = str_replace('"', '\"', $txt);
             $txt = str_replace("'", "\'", $txt);

	     #$txt = str_replace('$', '&#036;', $txt);
     	     $txt = str_replace('|', '&#124;', $txt);
	     $txt = str_replace('&', '&amp;', $txt);
	     #$txt = str_replace('!', '&#33;', $txt);

             $txt = preg_replace( "/onclick/i", "&#111;nclick", $txt);
	     #$txt = preg_replace( "/alert/i", "&#097;lert", $txt);
	     $txt = preg_replace( "/about:/i", "&#097;bout:", $txt);
	     $txt = preg_replace( "/onmouseover/i", "&#111;nmouseover", $txt);
	     $txt = preg_replace( "/onload/i", "&#111;nload", $txt);
	     $txt = preg_replace( "/onsubmit/i", "&#111;nsubmit", $txt);

             # verficar <br> e spac
             $txt = $this->XXspace($txt);
               
             # Converter HTML
             $txt = $this->BBencode($txt);
               
             # Ler as tags (x,y,...
             //$txt = htmlentities($txt, ENT_NOQUOTES);
               
             # Ler as tags (x,y,...
             $txt = strip_tags($txt, '<br><p><b><i><u><h1><h2><h3>');
               
             # Converter HTML
             $txt = $this->BBdecode($txt);

             # Corrigir HTML
             $txt = $this->VVcode($txt);
               
             if ($tam != false) $txt  = substr($txt, 0, $tam);

             return $txt;
      }

      /**
      * @desc   corrigir erros de fechamento de tags html         
      * @param  string $html - html a ser corrigido                
      * @return string $html - html corrigido                      
      *************************************************************************/
      public function VVcode($html) {
             $a = array("<b>","<i>","<u>","<h1","<h2","<h3","<a","<f","<td","<tr","<table","<p","<c");
             $f = array("</b>","</i>","</u>","</h1>","</h2>","</h3>","</a>","</fonte>","</td>","</tr>","</table>","</p>","</center>");
             for ($i = 0; $i <= 13; $i++) {
                  $tg1 = @substr_count($html,$a[$i]);
                  $tg2 = @substr_count($html,$f[$i]);
                  if ($tg1 > $tg2) $html  = $html.str_repeat($f[$i], ($tg1-$tg2));
             }

             return $html;
      }

      /**
      * @desc   Converter html legal para remover o resto do html 
      * @param  string $html - html a ser convertido              
      * @return string $html - html convertido para codigo aceitavel           *
      *************************************************************************/
      public function BBencode($html){

             $a = array(
                  "/\<br\>/is",
                  "/\<p\>/is",
                  "/\<i\>(.*?)\<\/i\>/is",
                  "/\<b\>(.*?)\<\/b\>/is",
                  "/\<u\>(.*?)\<\/u\>/is",
                  "/\<img (.*?)\ src=(.*?)\>/is",
                  "/\<a href=(.*?)\>(.*?)\<\/a\>/is"
             );

             // caso queira permitir url usar na ultima linha "[url=$1]$2[/url]"
             $b = array(
                  "[br]",
                  "[br]",
                  "[i]$1[/i]",
                  "[b]$1[/b]",
                  "[u]$1[/u]",
                  "[img]$2[/img]",
                  "$2"
             );

             $html = preg_replace($a, $b, $html);

             return $html;
      }

      /**
      * @desc   desConverter html legal para html padrao          
      * @param  string $html - html a ser desconvertido           
      * @return string $html - html convertido para html padrao   
      *************************************************************************/
      public function BBdecode($html){

             $a = array(
                  "/\[br\]/is",
                  "/\[i\](.*?)\[\/i\]/is",
                  "/\[b\](.*?)\[\/b\]/is",
                  "/\[u\](.*?)\[\/u\]/is",
                  "/\[img\](.*?)\[\/img\]/is",
                  "/\[url=(.*?)\](.*?)\[\/url\]/is"
             );

             // caso queira permitir url usar na ultima linha "<a href=$1 target=\"_blank\">$2</a>"
             $b = array(
                  "<br>",
                  "<i>$1</i>",
                  "<b>$1</b>",
                  "<u>$1</u>",
                  "<img src=$1>",
                  "$2"
             );

             $html = preg_replace($a, $b, $html);

             return $html;
      }

      /**
      * @desc   Ajustes para texto de Perguntas a serem enviadas  
      * @param  string $txt - texto a ser filtrado                
      * @param  int    $am  - tamanho maximo da pergunta          
      * @param  string $fim - para nao add interrogacao -> false  
      * @return string $txt - texto em formato de pergunta        
      *************************************************************************/
      public function XXview($txt, $tam = 45, $fim = '?') {

             while (substr($txt, 0, 1) == ' ')              $txt = substr($txt, 1);
             while (substr_count($txt," ,") > 0)            $txt = eregi_replace(" ,", ",", $txt);
             while (substr_count($txt,"  ") > 0)            $txt = eregi_replace("  ", " ", $txt);
             while (substr_count($txt,"<br>") > 0)          $txt = eregi_replace("<br>", "", $txt);
             while (substr_count($txt,"<BR>") > 0)          $txt = eregi_replace("<BR>", "", $txt);
             while (substr_count($txt,"\r\n") > 0)          $txt = eregi_replace("\r\n", "", $txt);
             while (substr($txt, -1, 1) == ' ')             $txt = substr($txt, 0, -1);
             while (strtoupper(substr($txt, -1, 1)) == '?') $txt = substr($txt, 0, -1);

             $txt1 = $txt;
             $txt2 = $this->strcvt($txt, 3, $tam);

             $txt = (strlen($txt2) < strlen($txt1)) ? $txt2.'.. '.$fim : $txt1.$fim;

             return $txt;
      }

      /**
      * @desc   Remover Espa�os Invalidos  
      * @param  string $txt - texto a ser filtrado                
      * @return string $txt - texto limpo        
      *************************************************************************/
      public function XXspace($txt) {
               
             while (substr($txt, 0, 1) == ' ')             $txt = substr($txt, 1);
             while (substr($txt, -1, 1) == ' ')            $txt = substr($txt, 0, -1);
             while (substr_count($txt,"  ") > 0)           $txt = eregi_replace("  ", " ", $txt);
             while (substr_count($txt,"\r\n\r\n\r\n") > 0) $txt = eregi_replace("\r\n\r\n\r\n", "\r\n\r\n", $txt);

             $txt = str_replace("\r\n", "<br>", $txt);
               
             while (substr($txt, 0, 4) == '<br>')          $txt = substr($txt, 4);
             while (substr($txt, -4, 4) == '<br>')         $txt = substr($txt, 0, -4);
             while (substr($txt, -5, 5) == '<br>?')        $txt = substr($txt, 0, -5).'?';
             while (substr($txt, -2, 2) == ' ?')           $txt = substr($txt, 0, -2).'?';
               
             $txt = strtoupper(substr($txt, 0, 1)).substr($txt, 1);

             return $txt;
      }
}

?>