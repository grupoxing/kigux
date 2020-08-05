<?php

/**
 * Pergunta.de: Comunidade Virtual de Aprendizagem Colaborativa
 * Copyright 2010-2010, Desenvolvimento de Sistemas Web, Inc. (http://ocriador.com.br)
 *
 * @author        James S. de Aguiar <falecom.aguiar@gmail.com>
 * @filesource
 * @copyright     Copyright 2010-2010, Desenvolvimento de Sistemas Web, Inc. (http://ocriador.com.br)
 * @link          http://pergunta.de/
 * @package       pergunta.de: Comunidade Virtual de Aprendizagem Colaborativa
 * @version       v0.3 - 12/09/2010 as 17:35
 * @license       Pergunta.de License (http://pergunta.de/license.do)
 */

# include Funcoes Globais
require_once $_SERVER['DOCUMENT_ROOT'].'/tool/ocriador_class.php';

class Main extends oCriador {

      # Construtor da Class Main
      public function __construct(){

             # Dados dos Usuarios
             $this->dbus = oCriador::val_USER();
             $this->dbpg = oCriador::val_PAGE();
             $this->conf = oCriador::val_CONF();
             $this->edit = ($this->dbus && ($this->dbpg['id_us'] == $this->dbus['id_us'] || $this->dbus['admin'] > 2)) ? true : false;
             
             # Idioma do Usuario
             $this->ling  = ($this->dbus) ? $this->dbus['ling'] : 'pt';             

      }

      # Includes Head Main
      public function main_head($type = 'all'){

             if ($type == 'css' || $type == 'all') $html .= '<link href="/css/kicell.css" rel="stylesheet" type="text/css" >'."\n";
             if ($type == 'jss' || $type == 'all') $html .= '<script type="text/javascript" src="/js/iajax.js"></script>'."\n";

             return $html;
      }

      public function menuTop($cmd) {
             $user = ($this->dbus['id_us'] == false || !isset($this->dbus['id_us'])) ? false : true;
             $idtp = ($user == false) ? 'diversas' : $this->dbus['id_ur'];
             $html .= '<div id="barra">';
                   $html .= '<div class="menu">';
                         $html .= '<div class="fixed">';                         
                               $html .= '<div class="status" onclick="document.getElementById(\'ichat_topo_min_0\').click();" style="float: left; padding-top: 4px;">';
                                    $html .= (isset($this->dbus['id_us'])) ? '<img src="/css/status-on.gif" border="0">' : '<img src="/css/status-of.gif" border="0">';
                               $html .= '</div>';                          
                               $html .= '<div class="left">'; 
                                     $html .= '<li><a href="/">Início</a></li>';
                                     $buscaTemas    = mysql_query("SELECT id_us, id_ur, nome FROM ctg, user_perfil WHERE id_us=id_ctg AND public=1 AND NOT EXISTS (SELECT id_pos FROM group_sb WHERE id_pos=id_ctg) ORDER BY nome LIMIT 0, 300");
                                     while ($temas  = mysql_fetch_array($buscaTemas)) {
                                            $li_1 .= '<li class="temas_nivel_1" onclick="window.open(\'https://'.$this->conf['link'].'/'.$temas['id_ur'].'\',\'_self\');">'.$temas['nome'].'</li>';
                                            $buscaST1 = mysql_query("SELECT id_us, id_ur, nome FROM group_sb, user_perfil WHERE id_pre='".$temas['id_us']."' AND id_us=id_pos AND public=1 ORDER BY nome LIMIT 0, 300");
                                            while ($assocST1  = mysql_fetch_array($buscaST1)) {
                                                   $li_1 .= '<li class="temas_nivel_2" onclick="window.open(\'https://'.$this->conf['link'].'/'.$assocST1['id_ur'].'\',\'_self\');">'.$assocST1['nome'].'</li>';
                                                   $buscaST2 = mysql_query("SELECT id_us, id_ur, nome FROM group_sb, user_perfil WHERE id_pre='".$assocST1['id_us']."' AND id_us=id_pos AND public=1 ORDER BY nome LIMIT 0, 300");
                                                   while ($assocST2  = mysql_fetch_array($buscaST2)) {
                                                          $li_1 .= '<li class="temas_nivel_3" onclick="window.open(\'https://'.$this->conf['link'].'/'.$assocST2['id_ur'].'\',\'_self\');">'.$assocST2['nome'].'</li>';
                                                   }                                                      
                                            }
                                     }
                                     $html .= '<li class="dropdown menup" onclick="iajax.divnf(\'type-option-temas\');">';
                                           $html .= '<a style="text-transform: uppercase; font-weight: bold;" href="javascript:void():">Grupos</a>';
                                           $html .= '<ul id="type-option-temas" style="display: none;">';
                                                 $html .= $li_1;
                                           $html .= '</ul>';
                                     $html .= '</li>';                
                                     $html .= '<div id="menu_pc">';
                                         if ($user == false){
                                             $refer = ($cmd != 'exit') ? '?url='.$_SERVER ['REQUEST_URI'] : '';                                        
                                         }else{
                                             $html .= '<li><a href="/'.$this->dbus['id_ur'].'/pf">'.$this->conf['gb-mp'].'</a></li>';
                                         }
                                         $html .= '<li><a href="/'.$idtp.'/pg#l2">Perguntas</a></li>';
                                         $html .= '<li><a href="/'.$idtp.'/poll'.(($this->dbpg['total']['enquetes'] > 0) ? (($this->dbpg['total']['poll_recebidas'] == 0) ? (($this->dbpg['total']['poll_enviadas'] == 0) ? (($this->dbpg['total']['poll_votadas'] == 0) ? (($this->dbpg['total']['poll_favoritas'] == 0) ? '/pa' : '/fv') : '/vt') : '/mi') : '') : '').'#l2">Enquetes</a></li>';
                                         if ($user == true) $html .= '<li><a href="/'.$idtp.'/am#l2">Contatos</a></li>';
                                         $html .= '<li><a href="/buscar.do?id='.$this->dbpg['id_ur'].'&q=#l2">Buscar</a>';
                                     $html .= '</div>';
                                     $html .= '</div>';
                               $html .= '<div class="right">';
                                     if ($user == false){
                                         $html .= '<li><a href="javascript:" onclick="idrop.abrir(\'/tool/iconta/iconta_login.php'.$refer.'\',false,true);">'.$this->conf['gb-ln'].'</a></li>';
                                         $html .= '<li><a class="btm btm_sig" href="javascript:" onclick="idrop.abrir(\'/tool/iconta/iconta_signup.php'.$refer.'\',false,true);">'.$this->conf['gb-rg'].'</a>';
                                     }else{
                                         $html .= '<li class="li_nick" onclick="window.open(\'https://'.$this->conf['link'].'/'.$this->dbus['id_ur'].'\',\'_self\');"><span>www.'.$this->conf['link'].'/</span><b>'.$this->dbus['id_ur'].'</b></li>';                                                                                                           
                                         $html .= '<li><a href="/'.$this->dbus['id_ur'].'/config#l2">Editar</a></li>';
                                         $html .= '<li><a href="/?cmd=exit" target="_parent">'.$this->conf['gb-sr'].'</a>';         
                                     }
                               $html .= '</div>';
                        $html .= '</div>';
                  $html .= '</div>';
             $html .= '</div>';
             
             /*
             $html .= '<div id="barra2">';
                   $html .= '<div class="menu">';
                         $html .= '<div class="left">';
                               $html .= $this->selectTemas($cmd);
                         $html .= '</div>';  
                         $html .= '<div class="right">';
                               $html .= '<b>https://'.$this->conf['link'].'/'.$this->dbus["id_ur"].'</b>';
                         $html .= '</div>'; 
                  $html .= '</div>';
             $html .= '</div>'; */
             
             return $this->type($html);
      }

      public function menuPerf($cmd) {
    
             $exe = substr($cmd, 0, 1);
             
             $html .= '<div id="menu1">';
             $html .= '<div class="clear">';
                   
                   $html .= '<a class="'; $html .= ($cmd == 'pf') ? 'on' : 'of'; $html .= '" href="/'.$this->dbpg['id_ur'].'/pf">Perfil</a>';
                   $html .= '<a class="'; $html .= ($cmd == false || $cmd == 'pg' || $cmd == 'pm' || $cmd == 'pr' || $cmd == 'pp' || $cmd == 'ps' || $cmd == 'pb' || $cmd == 'pw' || $cmd == 'fv' || $cmd == 'pa') ? 'on' : 'of'; $html .= '" href="/'.$this->dbpg['id_ur'].'/pg#l2">Perguntas (<span id="n1pg">'.($this->dbpg['total']['perg_recebidas']+$this->dbpg['total']['perg_enviadas']+$this->dbpg['total']['perg_respondidas']+$this->dbpg['total']['perg_favoritas']).'</span>)</a>';
                   $html .= '<a class="'; $html .= ($cmd == 'poll') ? 'on' : 'of'; $html .= '" href="/'.$this->dbpg['id_ur'].'/poll'.(($this->dbpg['total']['poll_recebidas'] == 0) ? (($this->dbpg['total']['poll_enviadas'] == 0) ? (($this->dbpg['total']['poll_votadas'] == 0) ? (($this->dbpg['total']['poll_favoritas'] == 0) ? '/pa' : '/fv') : '/vt') : '/mi') : '').'#l2">Enquetes ('.($this->dbpg['total']['poll_recebidas']+$this->dbpg['total']['poll_enviadas']+$this->dbpg['total']['poll_votadas']).')</a>';

                   if ($this->dbpg['public'] == 1) {
                       $html .= '<a class="'; $html .= ($cmd == 'tb' || $cmd == 'an') ? 'on' : 'of'; $html .= '" href="/'.$this->dbpg['id_ur'].'/tb#l2">Membros ('.($this->dbpg['total']['seguidores']).')</a>';
                       $html .= '<a class="'; $html .= ($cmd == 'tm' || $cmd == 'tt') ? 'on' : 'of'; $html .= '" href="/'.$this->dbpg['id_ur'].'/tt#l2">Grupos</a>';
                   }else{
                       $html .= '<a class="'.(($exe == 'a') ? 'on' : 'of').'" href="/'.$this->dbpg['id_ur'].'/am#l2">Contatos (<span id="nam3">'.($this->dbpg['total']['amigos']+$this->dbpg['total']['seguidos']+$this->dbpg['total']['seguidores']).'</span>)</a>';
                       $html .= '<a class="'.(($exe == 't') ? 'on' : 'of').'" href="/'.$this->dbpg['id_ur'].'/tm#l2">Grupos</a>';
                       $html .= '<a class="'.(($exe == 'w') ? 'on' : 'of').'" href="/'.$this->dbpg['id_ur'].'/wg#l2">Widgets</a>';
                   }

                   if ($this->dbus['admin'] > 2 || $this->dbus['id_us'] == $this->dbpg['id_us']) {
                       $html .= '<a class="'; $html .= ($cmd == 'config' || $cmd == 'design' || $cmd == 'senha' || $cmd == 'delete') ? 'on' : 'of'; $html .= '" href="/'.$this->dbpg['id_ur'].'/config#l2">Editar</a>';
                   }
                     
             $html .= '</div>';
             $html .= '</div>';
             
             return $this->type($html);
      }

    function selectTemas($cmd) {
             $html .= '<span class="select_temas">';
                      $idur  = ($this->dbus['id_us'] == false) ? $this->dbpg['id_ur'] : $this->dbus['id_ur'];
                      
                      $id_tema = ($this->dbpg['public'] == 1) ? $idur : 'diversas';
                      
                      $html .= '<span class="dropdown" onmouseover="acion(\'type-option-0\');" onmouseout="acion(\'type-option-0\');">';
                   	           $html .= '<span id="type-current-0" class="dropdown-current" >'; $html .= ($this->dbpg['public'] == 1 || $cmd == 'at') ? 'Grupos' : 'Usu&aacute;rios'; $html .='</span>';
                               $html .= '<ul id="type-option-0" style="display: none;">';
                                        $html .= '<li onclick="window.open(\'https://'.$this->conf['link'].'/'.$id_tema.'/at\',\'_self\');"'; if ($this->dbpg['public'] == 1 || $cmd == 'at') $html .= 'class="current"'; $html .= '>Grupos</li>';
                                        $html .= '<li onclick="window.open(\'https://'.$this->conf['link'].'/'.$idur.'/an\',\'_self\');"'; if ($this->dbpg['public'] == 0 && $cmd != 'at') $html .= 'class="current"'; $html .= '>Usu&aacute;rios</li>';
                   	           $html .= '</ul>';
                      $html .= '</span>';

                      if ($this->dbpg['public'] == 1 || $cmd == 'at') {
                          $buscaTemas    = mysql_query("SELECT id_ctg, nivel, num_temas, id_ur, nome FROM ctg, user_perfil WHERE id_ctg=id_us ORDER BY nome LIMIT 0, 100");
                          while ($temas  = mysql_fetch_array($buscaTemas)) {
                                 //$subtm  = mysql_fetch_assoc(mysql_query("SELECT a.id_pre id_prea, b.id_pre id_preb FROM group_sb a, group_sb b WHERE a.id_pos='".$this->dbpg['id_us']."' AND b.id_pos=a.id_pre"));
                                 $subtm1  = mysql_fetch_assoc(mysql_query("SELECT id_pre, id_pos FROM group_sb WHERE id_pos='".$this->dbpg['id_us']."'"));
                                 $subtm2  = mysql_fetch_assoc(mysql_query("SELECT id_pre, id_pos FROM group_sb WHERE id_pos='".$subtm1['id_pre']."'"));
                                 if (($subtm2['id_pre'] == $temas['id_ctg'] || $subtm1['id_pre'] == $temas['id_ctg'] || $temas['id_ctg'] == $this->dbpg['id_us']) && $temas['nivel'] == 1) {
                                      $li_1 .= '<li class="current" onclick="window.open(\'https://'.$this->conf['link'].'/'.$temas['id_ur'].'\',\'_self\');">'.$temas['nome'].'</li>';
                                      $idtm  = $temas['id_ctg'];
                                      $nutm  = $temas['num_temas'];
                                      $idsb  = $subtm1['id_pre'];
                                      $nusb  = $sub_tm['id_pos'];
                                      // Dados do Tema 1
                                      $historico[1]['nome'] = $temas['nome'];
                                      $historico[1]['idur'] = $temas['id_ur'];
                                 }else{
                                     $li_1 .= '<li onclick="window.open(\'https://'.$this->conf['link'].'/'.$temas['id_ur'].'\',\'_self\');">'.$temas['nome'].'</li>';
                                 }
               	          }

                          $html .= '<span class="dropdown" onmouseover="acion(\'type-option-1\');" onmouseout="acion(\'type-option-1\');">';
                       	           $html .= '<span id="type-current-1" class="dropdown-current" >'; $html .= (isset($historico[1]['nome'])) ? $historico[1]['nome'] : 'Escolhar o Grupo'; $html .= '</span>';
                                   $html .= '<ul id="type-option-1" style="display: none;">';
                                                 $html .= $li_1.'<li onclick="window.open(\'https://'.$this->conf['link'].'/'.$this->dbpg['id_ur'].':at\',\'_self\');">Ver Todos (250)</li>';
                       	           $html .= '</ul>';
                         $html .= '</span>';

                         if (isset($idtm)) {
                             $buscaSub = mysql_query("SELECT id_pre, id_pos, id_ur, nome FROM group_sb, user_perfil WHERE id_pre='".$idtm."' AND id_pre<>id_pos AND id_pos=id_us ORDER BY nome LIMIT 0, 100");
                             if (mysql_num_rows($buscaSub) > 0){
                                 while ($temas  = mysql_fetch_array($buscaSub)) {
                                        if ($temas['id_pos'] == $this->dbpg['id_us'] || $temas['id_pos'] == $idsb) {
                                            $li_2 .= '<li class="current" onclick="window.open(\'https://'.$this->conf['link'].'/'.$temas['id_ur'].'\',\'_self\');">'.$temas['nome'].'</li>';
                                            $idst  = $temas['id_pos'];
                                            // Dados do Tema 2
                                            $historico[2]['nome'] = $temas['nome'];
                                            $historico[2]['idur'] = $temas['id_ur'];
                                        }else{
                                            $li_2 .= '<li onclick="window.open(\'https://'.$this->conf['link'].'/'.$temas['id_ur'].'\',\'_self\');">'.$temas['nome'].'</li>';
                                        }
                       	         }

                       	         $html .= '<span class="dropdown" onmouseover="acion(\'type-option-2\');" onmouseout="acion(\'type-option-2\');">';
                       	                  $html .= '<span id="type-current-2" class="dropdown-current" >'; $html .= (isset($historico[2]['nome'])) ? $historico[2]['nome'] : 'Sub-Grupos ('.$nutm.')'; $html .= '</span>';
                                          $html .= '<ul id="type-option-2" style="display: none;">';
                                                   $html .= $li_2;
     	                                  $html .= '</ul>';
                                 $html .= '</span>';
                             }
                             if (isset($idst)){
                                 $buscaTemas = mysql_query("SELECT id_pre, id_pos, id_ur, nome FROM group_sb, user_perfil WHERE id_pre='".$idst."' AND id_pre<>id_pos AND id_pos=id_us ORDER BY nome LIMIT 0, 100");
                                 if (mysql_num_rows($buscaTemas) > 0){
                                     $tema  = mysql_fetch_assoc(mysql_query("SELECT num_temas FROM ctg WHERE id_ctg='".$idst."'"));
                                     while ($temas  = mysql_fetch_array($buscaTemas)) {
                                            if ($temas['id_pos'] == $this->dbpg['id_us']) {
                                                $li_3 .= '<li class="current" onclick="window.open(\'https://'.$this->conf['link'].'/'.$temas['id_ur'].'\',\'_self\');">'.$temas['nome'].'</li>';
                                                // Dados do Tema 3
                                                $historico[3]['nome'] = $temas['nome'];
                                                $historico[3]['idur'] = $temas['id_ur'];
                                            }else{
                                                $li_3 .= '<li onclick="window.open(\'https://'.$this->conf['link'].'/'.$temas['id_ur'].'\',\'_self\');">'.$temas['nome'].'</li>';
                                            }
                           	         }

                                     $html .= '<span class="dropdown" onmouseover="acion(\'type-option-3\');" onmouseout="acion(\'type-option-3\');">';
                                   	          $html .= '<span id="type-current-3" class="dropdown-current" >'; $html .= (isset($historico[3]['nome'])) ? $historico[3]['nome'] : 'Sub-Grupos ('.$tema['num_temas'].')'; $html .= '</span>';
                                              $html .= '<ul id="type-option-3" style="display: none;">';
                                                            $html .= $li_3;
                                   	          $html .= '</ul>';
                                     $html .= '</span>';
                                 }
                             }
                         }else{

                         }
                      }else{
                         $buscaSeguido = mysql_query("SELECT * FROM seg WHERE id_ad='".$this->dbus['id_us']."' AND id_ac='".$this->dbpg['id_us']."'");
                         $existSeguido =(mysql_num_rows($buscaSeguido) > 0) ? 1 : 0;

               	         if ($this->dbus['id_us'] != false) {
               	             $tt_mi = 'Minha';
               	             $tt_me = 'Meus';
                         }else{
               	             $tt_mi = $tt_me = 'Ver';
                         }

                         if ($this->dbus['id_us'] == $this->dbpg['id_us'] && $cmd != 'ad' && $cmd != 'an' && $cmd != 'am') {
                             $selct['class'][1] = ' class="current"';
                             $selct['class'][2] = '';
                             $selct['class'][3] = '';
                             $selct['title']    = $tt_mi.' Conta';
                         }elseif ($cmd == 'am'){
                             $selct['class'][1] = '';
                             $selct['class'][2] = ' class="current"';
                             $selct['class'][3] = '';
                             $selct['title']    = $tt_me.' Amigos';
                         }elseif ($existSeguido == 1 || $cmd == 'ad'){
                             $selct['class'][1] = '';
                             $selct['class'][2] = '';
                             $selct['class'][3] = ' class="current"';
                             $selct['title']    = $tt_me.' Seguidos';
                         }

                         $html .= '<span class="dropdown" onmouseover="acion(\'type-option-4\');" onmouseout="acion(\'type-option-4\');">';
                       	          $html .= '<span id="type-current-4" class="dropdown-current" >'; $html .= (isset($selct['title'])) ? $selct['title'] : 'Ver Usu&aacute;rios'; $html .= '</span>';
                                  $html .= '<ul id="type-option-4" style="display: none;">';
                                           $html .= '<li onclick="window.open(\'https://'.$this->conf['link'].'/'.$idur.'\',\'_self\');"'.$selct['class'][1].'>'.$tt_mi.' Conta</li>';
                                           $html .= '<li onclick="window.open(\'https://'.$this->conf['link'].'/'.$idur.'/am\',\'_self\');"'.$selct['class'][2].'>'.$tt_me.' Amigos</li>';
                                           $html .= '<li onclick="window.open(\'https://'.$this->conf['link'].'/'.$idur.'/ad\',\'_self\');"'.$selct['class'][3].'>'.$tt_me.' Seguidos</li>';
                                           $html .= '<li onclick="window.open(\'https://'.$this->conf['link'].'/'.$idur.'/an\',\'_self\');"'; if (!isset($selct['title']))  $html .= ' class="current"'; $html .= '>Ver Usu&aacute;rios</li>';
                       	          $html .= '</ul>';
                         $html .= '</span>';

                         if ($cmd != 'an') {
                             if ($this->dbus['id_us'] != false && ($existSeguido == 1 || $this->dbus['id_us'] == $this->dbpg['id_us'])){
                                 $querySeg = "SELECT id_us, id_ur, nome FROM seg, user_perfil WHERE id_ad='".$this->dbus['id_us']."' AND id_us=id_ac ORDER BY nome LIMIT 0, 20";
                             }else{
                                 $querySeg = "SELECT id_us, id_ur, nome FROM user_perfil WHERE id_us<>'".$this->dbpg['id_us']."' AND public=0 ORDER BY date DESC LIMIT 0, 20";
                             }
                             $buscaSeg = mysql_query($querySeg);
                             if (mysql_num_rows($buscaSeg) > 0){
                                 while ($seg  = mysql_fetch_array($buscaSeg)) {
                                        if ($seg['id_us'] == $this->dbpg['id_us']) {
                                            $li_5 .= '<li class="current" onclick="window.open(\'https://'.$this->conf['link'].'/'.$seg['id_ur'].'\',\'_self\');">'.$this->strcvt($seg['nome'], 3, 20).'</li>';
                                        }else{
                                            $li_5 .= '<li onclick="window.open(\'https://'.$this->conf['link'].'/'.$seg['id_ur'].'\',\'_self\');">'.$this->strcvt($seg['nome'], 3, 20).'</li>';
                                        }
                       	         }
                             }
                             $html .= '<span class="dropdown" onmouseover="acion(\'type-option-5\');" onmouseout="acion(\'type-option-5\');">';
                           	          $html .= '<span id="type-current-5" class="dropdown-current">'.$this->strcvt($this->dbpg['nome'], 3, 20).'</span>';
                                      $html .= '<ul id="type-option-5" style="display: none;">';
                                               $html .= $li_5;
                           	          $html .= '</ul>';
                             $html .= '</span>';
                         }
                      }
             $html .= '</span>';
             
             return $this->type($html);
    }

      private function dados_perf(){


              $seq = 0;

              /* -- Info 1 -- */
              $seq++;
              $perfil['campo'][$seq] = ($this->dbpg['public'] == 0) ? $this->conf['nome'] : $this->conf['titl'];
              $perfil['dados'][$seq] = $this->dbpg['nome'];

              if ($this->dbpg['public'] == 0) {

                  /* -- Info 2 -- */
                  $seq++;
                  $perfil['campo'][$seq] = $this->conf['sexo'];
                  $perfil['dados'][$seq] = ($this->dbpg['sexo'] == 'm') ? $this->conf['masc'] : $this->conf['femi'];

                  /* -- Info 3 -- */
                  $seq++;
                  list($ano,$mes,$dia) = explode("-",$this->dbpg['niver']);
                  $perfil['campo'][$seq] = $this->conf['aniv'];
                  $perfil['dados'][$seq] = $dia.' '.$this->conf['de'].' '.$this->mes($mes).', '.$this->idade($this->dbpg['niver']).' '.$this->conf['anos'];
              }
              
              /* -- Info 4 -- */
              $seq++;
              $perfil['campo'][$seq] = $this->conf['desc'];
              $perfil['dados'][$seq] = $this->dbpg['descricao'];

              /* -- Info 5 -- */
              $seq++;
              $perfil['campo'][$seq] = $this->conf['locl'];
              $perfil['dados'][$seq] = $this->dbpg['local'];
              
              if ($this->dbpg['public'] == 0) {

                  /* -- Info 6 -- */
                  $seq++;
                  $perfil['campo'][$seq] = $this->conf['relac'][0];
                  $perfil['dados'][$seq] = $this->conf['relac'][$this->dbpg['relacionamento']];

                  /* -- Info 7 -- */
                  $seq++;
                  if ($this->dbpg['sexualidade'] == 'h') $osex = $this->conf['sexh'];
                  elseif ($this->dbpg['sexualidade'] == 'g') $osex = $this->conf['sexg'];
                  elseif ($this->dbpg['sexualidade'] == 'b') $osex = $this->conf['sexb'];
                  elseif ($this->dbpg['sexualidade'] == 'c') $osex = $this->conf['sexc'];
                  $perfil['campo'][$seq] = $this->conf['osex'];
                  $perfil['dados'][$seq] = $osex;

                  /* -- Info 8 -- */
                  $seq++;
                  $num = strlen($this->dbpg['interesse'])-1;
                  for ($i = 0; $i <= $num; $i++){
                       $inte  = substr($this->dbpg['interesse'], $i, 1);
                       $intr .= ($num == $i) ? $this->conf['inter'][$inte] : $this->conf['inter'][$inte].', ';
                  }
                  $perfil['campo'][$seq] = $this->conf['inter'][0];
                  $perfil['dados'][$seq] = $intr;

                  /* -- Info 9 -- */
                  $seq++;
                  $perfil['campo'][$seq] = $this->conf['relig'][0];
                  $perfil['dados'][$seq] = $this->conf['relig'][$this->dbpg['religiao']];

                  /* -- Info 10 -- */
                  $seq++;
                  $perfil['campo'][$seq] = $this->conf['escol'][0];
                  $perfil['dados'][$seq] = $this->conf['escol'][$this->dbpg['escola']];
              }

              /* -- Info 11 -- */
              $seq++;
              $perfil['campo'][$seq] = $this->conf['clasc'][0];
              $perfil['dados'][$seq] = oCriador::user_nivel($this->dbpg['id_us']);

              /* -- Info 12 -- */
              $seq++;
              $perfil['campo'][$seq] = $this->conf['gb-lk'];
              $perfil['dados'][$seq] = 'http://'.$this->conf['link'].'/'.$this->dbpg['id_ur'];

              $perfil['num'] = $seq;

              return $perfil;
      }
      public function info_pf(){

             // ini - Campos e Dados
             $perfil = $this->dados_perf();

             for ($i = 1; $i <= $perfil['num']; $i++){
                  $html .= ($perfil['dados'][$i] != '') ? '<div class="cor'.$this->color().'"><div class="title">'.$perfil['campo'][$i].':</div><div class="dados">'.$perfil['dados'][$i].'</div></div>' : '';
             }

             return $this->type($html);
      }
    
    function rodape() { 
             $html .= $this->type("<!-- Histats.com  START  (aync)-->");
             $html .= $this->type("<script type=\"text/javascript\">var _Hasync= _Hasync|| [];");
             $html .= $this->type("_Hasync.push(['Histats.start', '1,4124689,4,0,0,0,00010000']);");
             $html .= $this->type("_Hasync.push(['Histats.fasi', '1']);");
             $html .= $this->type("_Hasync.push(['Histats.track_hits', '']);");
             $html .= $this->type("(function() {");
             $html .= $this->type("var hs = document.createElement('script'); hs.type = 'text/javascript'; hs.async = true;");
             $html .= $this->type("hs.src = ('//s10.histats.com/js15_as.js');");
             $html .= $this->type("(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);");
             $html .= $this->type("})();</script>");
             $html .= $this->type("<noscript><img src=\"//sstatic1.histats.com/0.gif?4124689&101\" border=\"0\"></noscript>");
             $html .= $this->type("<!-- Histats.com  END  -->");

             return $html;
    }

} ?>
