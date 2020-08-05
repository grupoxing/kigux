<?php

/**
 * Pergunta.de: Comunidade Virtual de Aprendizagem Colaborativa
 * Copyright 2010-2012, Desenvolvimento de Sistemas Web, Inc. (http://ocriador.com.br)
 *
 * @author        James S. de Aguiar <falecom.aguiar@gmail.com>
 * @filesource
 * @copyright     Copyright 2010-2012, Desenvolvimento de Sistemas Web, Inc. (http://ocriador.com.br)
 * @package       pergunta.de: Comunidade Virtual de Aprendizagem Colaborativa
 * @version       v1 - 30/05/2012 as 01:02
 * @license       Pergunta.de License (http://pergunta.de/license.do)
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/tool/ocriador_class.php';

class iAmg extends oCriador{

      public function __construct(){

             # Dados dos Usuarios
             $this->dbus = oCriador::val_USER();
             $this->dbpg = oCriador::val_PAGE();
             $this->conf = oCriador::val_CONF();
             $this->edit = ($this->dbus && ($this->dbpg['id_us'] == $this->dbus['id_us'] || $this->dbus['admin'] > 2)) ? true : false;

             # Diretorio
             $this->dirp = '/tool/iamg/';

             # Configuracao
             $this->meus = ($this->dbus['id_us'] == $this->dbpg['id_us']) ? 'MEUS ' : '';

             # Acoes de Espera de Amigos
             if (isset($_POST["id_sp"])) {
                 $id_sp = $_POST["id_sp"];
                 $id_pg = $this->dbpg['id_us'];
                 $busca = mysql_query("SELECT * FROM amg WHERE id_us='$id_sp' AND id_am='$id_pg' AND status=0");
                 if (mysql_num_rows($busca) > 0) {
                     if (isset($_POST["on"])) {
                         $mysql = mysql_query("UPDATE amg SET status=1 WHERE id_us='$id_sp' AND id_am='$id_pg'");
                         if ($mysql) {
                             oCriador::tool_iTotal()->add_contador('amigos', $id_pg);
                             oCriador::tool_iTotal()->add_contador('amigos', $id_sp);  
                         }
                     }else{                         
                         $mysql = mysql_query("DELETE FROM amg WHERE id_us='$id_sp' AND id_am='$id_pg'");
                     }
                     if ($mysql) {
                         oCriador::tool_iTotal()->del_contador('espera', $id_pg);
                         oCriador::tool_iTotal()->del_contador('pedido', $id_sp);  
                         Header("location:".$_SERVER ['REQUEST_URI']); exit;
                     }
                 }
             }    
             
             
      }
      
      # Includes Head iAmg
      public function iamg_head($type = 'all'){

             if ($type == 'css' || $type == 'all') $html .= '<link href="'.$this->dirp.'iamg_style.css" rel="stylesheet" type="text/css" >'."\n";
             if ($type == 'jss' || $type == 'all') $html .= '<script type="text/javascript" src="'.$this->dirp.'iamg_ajax.js"></script>'."\n";

             return $html;
      }

      public function menu($cmd) {
             
             $html .= '<div id="menu2">';
                 $html .= '<a class="'; $html .= ($cmd == 'am') ? 'on' : 'of'; $html .= '" href="/'.$this->dbpg['id_ur'].'/am#l2">Amigos (<span id="am-num">'.$this->dbpg['total']['amigos'].'</span>)</a>';
                 $html .= '<span class="separe"></span>';
                 $html .= '<a class="'; $html .= ($cmd == 'ad') ? 'on' : 'of'; $html .= '" href="/'.$this->dbpg['id_ur'].'/ad#l2">Seguidos (<span id="ad-num">'.$this->dbpg['total']['seguidos'].'</span>)</a>';
                 $html .= '<span class="separe"></span>';
                 $html .= '<a class="'; $html .= ($cmd == 'ac') ? 'on' : 'of'; $html .= '" href="/'.$this->dbpg['id_ur'].'/ac#l2">Seguidores (<span id="ac-num">'.$this->dbpg['total']['seguidores'].'</span>)</a>';
                 $html .= '<span class="separe"></span>';

                 if ($this->edit) {
                     $html .= '<a class="'; $html .= ($cmd == 'ap') ? 'on' : 'of'; $html .= '" href="/'.$this->dbpg['id_ur'].'/ap#l2">Pedidos (<span id="ap-num">'.($this->dbpg['total']['pedido']).'</span>)</a>';
                     $html .= '<span class="separe"></span>';
                     $html .= '<a class="'; $html .= ($cmd == 'as') ? 'on' : 'of'; $html .= '" href="/'.$this->dbpg['id_ur'].'/as#l2">Esperas (<span id="as-num">'.($this->dbpg['total']['espera']).'</span>)</a>';
                     $html .= '<span class="separe"></span>';                     
                 }else{
                     $html .= '<!--<a class="'; $html .= ($cmd == 'au') ? 'on' : 'of'; $html .= '" href="/'.$this->dbpg['id_ur'].'/au#l2">Em Comum</a>-->';
                     $html .= '<!--<span class="separe"></span>-->';
                 }

                 $html .= '<a class="'; $html .= ($cmd == 'an') ? 'on' : 'of'; $html .= '" href="/'.$this->dbpg['id_ur'].'/an#l2">Novos Usu&aacute;rios</a>';
                 $html .= '<span class="separe"></span>';
                 $html .= '<!--<a class="'; $html .= ($cmd == 'an') ? 'on' : 'of'; $html .= '" href="#">Aniversariantes</a>-->';
                 
                 if ($this->edit) {
                     $html .= '<a class="'; $html .= ($cmd == 'av') ? 'on' : 'of'; $html .= '" href="/'.$this->dbpg['id_ur'].'/av#l2">Convidar</a>';
                 }

             $html .= '</div>';
             
             return oCriador::type($html);
      }
    
      public function demo_amigos($num = 12) {

             $buscaAm  = mysql_query("SELECT a.id_us IdU, a.id_am IdA, u.id_ur, u.nome, u.sexo, u.foto FROM amg a, user_perfil u WHERE (a.id_us='".$this->dbpg['id_us']."' AND a.status=1 AND u.id_us=a.id_am) OR (a.id_am='".$this->dbpg['id_us']."' AND a.status=1 AND u.id_us=a.id_us) ORDER BY u.foto NOT LIKE '%perf/foto%' DESC, u.date DESC LIMIT 0, $num");
             while ($x = mysql_fetch_array($buscaAm)) {
                    if ($x['IdU'] == $this->dbpg['id_us']) $id =  $x['IdA']; else $id =  $x['IdU'];
                    $amg .= '<a href="/'.$x['id_ur'].'"><img src="'.$x['foto'].'" title="Amig'.(($x['sexo'] == 'm') ? 'o' : 'a').': '.$x['nome'].'" width="44px" height="48px" border="0"><span>'.$x['nome'].'</span></a>';
             }

             if ($this->dbpg['total']['amigos'] < 4 && $this->edit){
                 $vmais = '<a href="/'.$this->dbpg['id_ur'].'/an:call#l2">'.$this->conf['adnw'].' &raquo;</a>';
             }else{
                 $vmais = '<a href="/'.$this->dbpg['id_ur'].'/am#l2"><u>'.$this->conf['gb-vt'].' &raquo;</u></a>';
             }

             return oCriador::html_demo($this->dbpg['total']['amigos'],$this->meus.'AMIGOS',$vmais,$amg);

      }
    
      public function demo_seguidos($num = 12) {
                 
             $buscaAm  = mysql_query("SELECT s.id_ad IdU, s.id_ac IdA, u.id_ur, u.nome, u.sexo, u.foto FROM seg s, user_perfil u WHERE u.public=0 AND (s.id_ad='".$this->dbpg['id_us']."' AND u.id_us=s.id_ac) ORDER BY u.foto NOT LIKE '%perf/foto%' DESC, RAND() DESC LIMIT 0, $num");
             while ($x = mysql_fetch_array($buscaAm)) {
                    $amg .= '<a href="/'.$x['id_ur'].'"><img src="'.$x['foto'].'" title="Seguindo: '.$x['nome'].'" width="44px" height="48px" border="0"><span>'.$x['nome'].'</span></a>';
             }

             if ($this->dbpg['total']['seguidos'] < 4 && $this->edit) {
                 $vmais = '<a href="/'.$this->dbpg['id_ur'].'/an#l2">'.$this->conf['adnw'].' &raquo;</a>';
             }else{
                 $vmais = '<a href="/'.$this->dbpg['id_ur'].'/ad#l2"><u>'.$this->conf['gb-vt'].' &raquo;</u></a>';
             }

             return oCriador::html_demo($this->dbpg['total']['seguidos'],$this->meus.'SEGUIDOS',$vmais,$amg);

      }
    
      public function demo_seguidores($num = 8) {

             $buscaAm  = mysql_query("SELECT s.id_ad IdU, s.id_ac IdA, u.id_ur, u.nome, u.sexo, u.foto FROM seg s, user_perfil u WHERE (s.id_ac='".$this->dbpg['id_us']."' AND u.id_us=s.id_ad) ORDER BY u.foto NOT LIKE '%perf/foto%' DESC, RAND() DESC LIMIT 0, $num");
             while ($x = mysql_fetch_array($buscaAm)) {
                    $amg .= '<a href="/'.$x['id_ur'].'"><img src="'.$x['foto'].'" title="Seguidor'.(($x['sexo'] == 'm') ? '' : 'a').': '.$x['nome'].'" width="44px" height="48px" border="0"><span>'.$x['nome'].'</span></a>';
             }
             if ($this->dbpg['total']['seguidores'] < 4 && $this->edit) {
                 $vmais = '<a href="/'.$this->dbpg['id_ur'].'/av#l2"><u>convidar &raquo;</u></a>';
             }else{
                 $vmais = '<a href="/'.$this->dbpg['id_ur'].'/ac#l2"><u>'.$this->conf['gb-vt'].' &raquo;</u></a>';
             }

             return oCriador::html_demo($this->dbpg['total']['seguidores'],$this->meus.'SEGUIDORES',$vmais,$amg);

      }
    
      public function demo_novos($num = 8, $w = 40) {
             //if (!$this->edit || $this->dbpg['total']['seguidos'] < 8 || $this->dbpg['total']['amigos'] < 8) {

                 $date_login  = mktime()-7776000;
                 $date_ativa  = mktime()-15552000;

                 $buscaPublic  = mysql_query("SELECT id_ur, nome, sexo, foto FROM user_perfil WHERE foto NOT LIKE '%perf/foto%' AND public=0 ORDER BY RAND() DESC, date>$date_login DESC LIMIT 0, $num");
                 while ($x = mysql_fetch_array($buscaPublic)) {
                        $amg .= '<a href="/'.$x['id_ur'].'"><img src="'.$x['foto'].'" title="Nov'.(($x['sexo'] == 'm') ? 'o' : 'a').' Usuári'.(($x['sexo'] == 'm') ? 'o' : 'a').': '.$x['nome'].'" width="44px" height="48px" border="0"><span>'.$x['nome'].'</span></a>';
                 }

                 $vmais = '<a href="/'.$this->dbpg['id_ur'].'/an#l2"><u>'.$this->conf['gb-vt'].' &raquo;</u></a>';

                 return oCriador::html_demo('','NOVOS USU&Aacute;RIOS',$vmais,$amg);
             //}
      }

      public function demo_pedidos($num = 8) {
             if ($this->edit) {
                  $pedidos  = mysql_query("SELECT a.id_us, a.id_am, u.id_ur, u.nome, u.sexo, u.foto FROM amg a, user_perfil u WHERE a.id_us='".$this->dbpg['id_us']."' AND a.status=0 AND u.id_us=a.id_am ORDER BY u.foto NOT LIKE '%perf/foto%' DESC, u.date DESC LIMIT 0, $num");
                  while ($x = mysql_fetch_array($pedidos)) {
                         $amg .= '<a href="/'.$x['id_ur'].'"><img src="'.$x['foto'].'" title="Convidou: '.$x['nome'].'" width="44px" height="48px" border="0"><span>'.$x['nome'].'</span></a>';
              	  }

                  if ($this->dbpg['total']['pedido'] < 4){
                      $vmais = '<a href="/'.$this->dbpg['id_ur'].'/an#l2">'.$this->conf['adnw'].' &raquo;</a>';
                  }else{
                      $vmais = '<a href="/'.$this->dbpg['id_ur'].'/ap#l2"><u>'.$this->conf['gb-vt'].' &raquo;</u></a>';
                  }

                  return oCriador::html_demo($this->dbpg['total']['pedido'],$this->meus.'PEDIDOS',$vmais,$amg);
             }
      }
      
      public function iamg_addam($id_am) {
             
             if ($this->dbus) {
                 $BuscaAmigo = mysql_query("SELECT * FROM amg WHERE id_us='".$this->dbus['id_us']."' AND id_am='$id_am' OR id_us='$id_am' AND id_am='".$this->dbus['id_us']."'");
                 $assocAmigo = mysql_fetch_assoc($BuscaAmigo);
                 $existAmigo = mysql_num_rows($BuscaAmigo);
                 if ($existAmigo > 0){
                     if (($assocAmigo['id_am'] == $this->dbus['id_us']) && ($assocAmigo['status'] == 0)) {
                         $up  = mysql_query("UPDATE amg SET status=1 WHERE id_us='$id_am' AND id_am='".$this->dbus['id_us']."'");
                         if ($up) {
                            oCriador::tool_iTotal()->add_contador('amigos', $assocAmigo['id_us']);
                            oCriador::tool_iTotal()->add_contador('amigos', $assocAmigo['id_am']);
                            oCriador::tool_iTotal()->del_contador('pedido', $assocAmigo['id_us']);
                            oCriador::tool_iTotal()->del_contador('espera', $assocAmigo['id_am']);
                            $html = '<span class="fam">Confirmado</span>';
                         }else{
                            $html = '<span class="xam">Erro.Up.</span>';
                         }
                     }else{
                         $html = '<span class="fam">Já Existe</span>';
                     }
                 }else{
                    $date   = mktime();
                    $assocPublic = oCriador::assoc_db("user_perfil", "id_us='$id_am'", "public");
                    $status = ($assocPublic['public'] == 1) ? 1 : 0;
                    $add    = mysql_query("INSERT INTO amg (id_us, id_am, date, status) VALUES ('".$this->dbus['id_us']."', '$id_am', '$date', $status)");
                    if ($add) {
                        if ($status == 0) {
                            oCriador::tool_iTotal()->add_contador('pedido', $this->dbus['id_us']);
                            oCriador::tool_iTotal()->add_contador('espera', $id_am);
                            $html = '<span class="fam">Aguardando</span>';
                        }else{
                            oCriador::tool_iTotal()->add_contador('amigos', $this->dbus['id_us']);
                            oCriador::tool_iTotal()->add_contador('amigos', $id_am);
                            $html = '<span class="fam">Confirmado</span>';
                        }
                    }else{
                       $html = '<span class="xam">Erro.Ad.</span>';
                    }
                 } 
             }else{
                  $html = '<a class="fam" href="javascript:" onclick="idrop.abrir(\'/tool/iconta/iconta_login.php\',false,true);">Entrar!</a>';
             }
             
             return $html;
      }
      
      public function iamg_delam($id_am) {
             if ($this->dbus) {
                 $BuscaAmigo = mysql_query("SELECT * FROM amg WHERE (id_us='".$this->dbus['id_us']."' AND id_am='$id_am') OR (id_us='$id_am' AND id_am='".$this->dbus['id_us']."')");
                 $assocAmigo = mysql_fetch_assoc($BuscaAmigo);
                 $existAmigo = mysql_num_rows($BuscaAmigo);
                 if ($existAmigo > 0){
                     $remov = mysql_query("DELETE FROM amg WHERE (id_us='".$this->dbus['id_us']."' AND id_am='$id_am') OR (id_us='$id_am' AND id_am='".$this->dbus['id_us']."')");
                     if ($remov && ($assocAmigo['status'] == 1)) {
                         oCriador::tool_iTotal()->del_contador('amigos', $this->dbus['id_us']);
                         oCriador::tool_iTotal()->del_contador('amigos', $id_am);
                         $html = '<span class="fam">Removido</span>';
                     }elseif ($remov){
                         oCriador::tool_iTotal()->del_contador('pedido', $assocAmigo['id_us']);
                         oCriador::tool_iTotal()->del_contador('espera', $assocAmigo['id_am']);                         
                         $html = '<span class="fam">Cancelado</span>';
                     }else{
                        $html = '<span class="xam">Erro..</span>';
                     }
                  }else{
                     $html = '<span class="fam">Inválido</span>';
                  }
                  
                  return $html;
             }
             
             return false;  
      }
      
      public function iamg_addsg($id_am) {
             
             if ($this->dbus) {      
                 if (oCriador::total_db("seg", "id_ad='".$this->dbus['id_us']."' AND id_ac='$id_am'") > 0){
                     $html = '<span class="fsg">Seguindo</span>';
                 }else{
                     $date = mktime();
                     $add  = mysql_query("INSERT INTO seg (id_ad, id_ac, date) VALUES ('".$this->dbus['id_us']."', '$id_am', '$date')");
                     if ($add) {
                         $dados_am = oCriador::assoc_db("user_perfil", "id_us='$id_am'", "public");
                         if ($dados_am['public'] == 1){
                             oCriador::tool_iTotal()->add_contador('temas', $this->dbus['id_us']);
                             oCriador::tool_iTotal()->add_contador('membros', $id_am);                             
                         }else{
                             oCriador::tool_iTotal()->add_contador('seguidos', $this->dbus['id_us']);
                             oCriador::tool_iTotal()->add_contador('seguidores', $id_am);
                         }
                         $html = '<span class="fsg">Confirmado</span>';
                     }else{
                         $html = '<span class="xsg">Erro..</span>';
                     }
                 }
             }else{
                 $html = '<a class="fsg" href="javascript:" onclick="idrop.abrir(\'/tool/iconta/iconta_login.php\',false,true);">Entrar!</a>';
             }
             
             return $html;
      } 

      public function iamg_delsg($id_am) {
             
             if ($this->dbus) {        
                 if (oCriador::total_db("seg", "id_ad='".$this->dbus['id_us']."' AND id_ac='$id_am'") > 0){
                     $remov = mysql_query("DELETE FROM seg WHERE id_ad='".$this->dbus['id_us']."' AND id_ac='$id_am'");
                     if ($remov) {
                         $dados_am = oCriador::assoc_db("user_perfil", "id_us='$id_am'", "public");
                         if ($dados_am['public'] == 1){
                             oCriador::tool_iTotal()->del_contador('temas', $this->dbus['id_us']);
                             oCriador::tool_iTotal()->del_contador('membros', $id_am);                             
                         }else{
                             oCriador::tool_iTotal()->del_contador('seguidos', $this->dbus['id_us']);
                             oCriador::tool_iTotal()->del_contador('seguidores', $id_am);
                         }                                               
                         $html = '<span class="fsg">Removido</span>';
                     }else{
                        $html = '<span class="xsg">Erro..</span>';
                     }
                 }else{
                     $html = '<span class="fsg">Inválido</span>';
                 } 
             }else{
                  $html = '<a class="fsg" href="javascript:" onclick="idrop.abrir(\'/tool/iconta/iconta_login.php\',false,true);">Entrar!</a>';
             }
             
             return $html;
      } 
      
      # Adicionar um Sub Tema
      public function iamg_addtema($id_pre, $id_pos) {
             
             if ($this->dbus['admin'] > 2) {    
                 $buscaTema = mysql_query("SELECT * FROM group_sb WHERE id_pos='$id_pos'");
                 $assocTema = mysql_fetch_assoc($buscaTema);
                 $existTema = mysql_num_rows($buscaTema);
                 if ($existTema > 0){
                     if ($id_pre == 0) {
                         mysql_query("DELETE FROM group_sb WHERE id_pos='$id_pos'");
                     }else{                    
                         $up1 = mysql_query("UPDATE group_sb SET id_pre='$id_pre' WHERE id_pos='$id_pos'");
                         if ($up1) {
                             $html = '<span class="fam">Atualizado</span>';
                         }else{
                             $html = '<span class="xam">Erro.Up.</span>';
                         }
                     }
                 }else{
                     $add = mysql_query("INSERT INTO group_sb (id_pre, id_pos) VALUES ('$id_pre', '$id_pos')");
                     if ($add) {
                         $html = '<span class="fam">Adicionado</span>';
                     }else{
                         $html = '<span class="xam">Erro.Add.</span>';
                     }
                 }
                 return $html;
             }
             
             return false;
      }
      
      public function friends($gt_pg = 0, $cmd = 'am') {
               
               if ($cmd == 'am'){ $qtd = $this->dbpg['total']['amigos'];                }else
               if ($cmd == 'ad'){ $qtd = $this->dbpg['total']['seguidos'];              }else
               if ($cmd == 'ac'){ $qtd = $this->dbpg['total']['seguidores'];            }else
               if ($cmd == 'an'){ $qtd = $this->total_db("user_perfil","public=0");  }else
               if ($this->edit && $cmd == 'ap'){ $qtd = $this->dbpg['total']['pedido']; }else
               if ($this->edit && $cmd == 'as'){ $html .=  $this->esperas(); }
               
               $npg = 16;
               $ipg = oCriador::ipage($qtd, $npg); 
               
               if ($cmd == 'am'){ $query = "SELECT id_us id1, id_am id2 FROM amg WHERE id_us='".$this->dbpg['id_us']."' AND status=1 OR id_am='".$this->dbpg['id_us']."' AND status=1 ORDER BY date DESC LIMIT {$ipg['row']}, $npg "; }else
               if ($cmd == 'ad'){ $query = "SELECT id_ad id1, id_ac id2 FROM seg WHERE id_ad='".$this->dbpg['id_us']."' ORDER BY date DESC LIMIT {$ipg['row']}, $npg "; }else
               if ($cmd == 'ac'){ $query = "SELECT id_ad id2, id_ac id1 FROM seg WHERE id_ac='".$this->dbpg['id_us']."' ORDER BY date DESC LIMIT {$ipg['row']}, $npg "; }else
               if ($cmd == 'an'){ $query = "SELECT id_us id1, id_us id2 FROM user_perfil WHERE public=0 ORDER BY date DESC LIMIT {$ipg['row']}, $npg "; }else
               if ($cmd == 'ap'){ 
                   $query = "SELECT id_us id1, id_am id2 FROM amg WHERE id_us='".$this->dbpg['id_us']."' AND status=0 OR id_am='".$this->dbpg['id_us']."' AND status=0 ORDER BY date DESC LIMIT {$ipg['row']}, $npg "; 
                   if (isset($_GET['amg']) && $_GET['amg'] != $this->dbus['id_us']) { 
                       $html  .= $this->friend($_GET['amg']); 
                   }
               }
               //elseif ($cmd == 'ao'){ $time  = time(); $query = "SELECT a.id_us id1, a.id_am id2 FROM amg a, xat_us u WHERE (a.id_us='".$this->dbus['id_us']."' AND a.status=1 AND u.id_us=a.id_am AND u.status=1 AND u.lifetime > $time) OR (a.id_am='".$this->dbus['id_us']."' AND a.status=1 AND u.id_us=a.id_us AND u.status=1 AND u.lifetime > $time) ORDER BY u.lifetime DESC LIMIT {$ipg['row']}, $npg "; }                                                                             }else

               $html .= '<span id="amg-lista">';
                        $lista = mysql_query($query);
                        $exist = mysql_num_rows($lista);
                        
                        if ($exist > 0) {
                            while ($dados = mysql_fetch_array($lista)) {
                                $id_us = ($dados['id1'] == $this->dbpg['id_us']) ? $dados['id2'] : $dados['id1'];
                                $assoc = oCriador::info_user($id_us, 'id_us , id_ur, nome, foto, niver, sexo, public, date, date_up');
                                if ($assoc['id_ur'] != ''){
                                    $html .= oCriador::html_view($assoc);
                                    if ($assoc['public'] == 0){
                                        oCriador::add_boot($assoc['id_us'], $assoc['date_up']);
                                    }                                    
                                }else{
                                    oCriador::delete_db("amg","id_us='$id_us' or id_am='$id_us'");
                                    oCriador::delete_db("seg","id_ad='$id_us' or id_ac='$id_us'");
                                    oCriador::tool_iTotal()->new_contador($this->dbpg['id_us']);
                                }
                            }
                        }
                        
               $html .= '</span>';


               $html .= $ipg['html'];
               
               return oCriador::type($html);
      }
      
      public function esperas() {

           if ($this->dbpg['id_us'] == $this->dbus['id_us'] || $this->dbus['admin'] > 2) {
               if ($this->dbpg['total']['espera'] > 0){
                   $busca_espera  = mysql_query("SELECT * FROM amg WHERE id_am='".$this->dbpg['id_us']."' AND status=0 ORDER BY date ASC LIMIT 0, 5");
                   while ($assoc_espera = mysql_fetch_array($busca_espera)) {
                          $assoc_amigo  = oCriador::info_user($assoc_espera['id_us'], 'id_us, id_ur, nome, email, niver, sexo, foto');

                          $html .= '<div class="msg">
                                         <div class="auto-obs">
                                              <a href="/'.$assoc_espera['id_us'].'"><img src="'.$assoc_amigo['foto'].'" width="60px" border="0" title="'.$assoc_amigo['nome'].'"></a>
                                         </div>
                                         <div class="body-obs">
                                              <div class="perg">
                                                   <div class="title">
                                                        <span class="left"><a href="/'.$assoc_amigo['id_ur'].'" target="black">'.$assoc_amigo['nome'].'</a>, '.oCriador::idade($assoc_amigo['niver']).' anos. Adicionou Voc&ecirc;:</span>
                                                        <span class="right">'.date("d-M-y H:i", $assoc_espera['date']).'</span>
                                                   </div><br>
                                                   '.$this->conf['vcac'].' <u>'.$assoc_amigo['nome'].'</u> como seu novo Amigo?
                                              </div>
                                              <div class="resp_option">
                                                   <form method="POST"><input name="id_sp" type="hidden" value="'.$assoc_amigo['id_us'].'"><input class="ki-button" name="on" type="submit" value="'.$this->conf['gb-ac'].'">&nbsp;<input class="ki-button" name="of" type="submit" value="'.$this->conf['gb-rc'].'"></form>
                                              </div>
                                         </div>
                                   </div>';
                   }

               }else{
                   $html = '<br><center>Voc&ecirc; n&atilde;o tem solicita&ccedil;&otilde;es de Amizade!</center><br>';
               }
           }else{
               $html = '<br><center>Ops!</center><br>';
           }

           return oCriador::type($html);
    }
    
} ?>