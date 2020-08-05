<?php

# Include - Class - Funcoes
require_once $_SERVER['DOCUMENT_ROOT'].'/tool/ocriador_class.php';

# Class - Actx
class Actx extends oCriador {

      # Construtor da Class Historico
      public function __construct() {
            
             # Dados dos Usuarios
             $this->dbus = oCriador::val_USER();
             $this->dbpg = oCriador::val_PAGE();          
             $this->conf = oCriador::val_CONF();

             # Diretorio do actx
             $this->size = 300;
             $this->dirp = '/tool/actx/';
             $this->qtdp = oCriador::total_db("actx","id_pg='".$this->dbpg['id_us']."'");

             # Deletar Recado do Mural
             if  (filter_has_var(INPUT_POST, "actx_del") == true) $this->actx_del(filter_input(INPUT_POST,"actx_del",FILTER_SANITIZE_SPECIAL_CHARS),$idp,$idr);
      }

      # Includes Head Actx
      public function actx_head($type = 'all'){

             if ($type == 'css' || $type == 'all') $html .= '<link href="'.$this->dirp.'actx_style.css" rel="stylesheet" type="text/css" >'."\n";
             if ($type == 'jss' || $type == 'all') $html .= '<script type="text/javascript" src="'.$this->dirp.'actx_ajax.js"></script>'."\n";

             return $html;
      }

      public function actx_add($idus, $idpg, $acion, $table, $idrow){

             if (($table == 'perg' || $table == 'resp')) {
                 $insert = mysql_query("INSERT INTO actx (id_us, id_pg, acion, tabela, idrow, data) VALUES ('$idus', '$idpg', '$acion', '$table', '$idrow', '".mktime()."')");
             }

             return ($insert) ? true : false;
      }

      public function actx_del($idms = '', $idpg = '') {
             if ($idms != "" && $idpg != "") {
                 $query = "DELETE FROM actx WHERE id=$idms";
                 $mysql = mysql_query($query);
                 return ($mysql) ? true : false;
             }
             return false;
      }    
      
      # Visualizar Historico
      public function actx_main() {
             $html .= '<div id="actx">';
                   $html .= '<div class="actx_obs" id="actx_obs" style="display: none;"></div>';
                   $html .= '<input type="hidden" value="'.$this->dbpg['id_us'].'" id="actx_input_idpg">';
                   $html .= '<div class="actx_view" id="actx_view">';
                         $html .= $this->actx_view($this->dbpg['id_us']);
                   $html .= '</div>';
             $html .= '</div>';
             
             return $html;
       }
       
       public function actx_view($id_pg, $id_ini = 0, $order = 'pre', $obs = true) {

              $order = ($order == 'pre') ? '>' : '<';
              $query = "SELECT * FROM actx WHERE (id_us='".$id_pg."' OR id_pg='".$id_pg."' OR id_pg IN (SELECT id_ac FROM seg s WHERE s.id_ad='".$id_pg."' AND s.id_ac=id_pg)) AND id".$order.intval($id_ini)." ORDER BY data DESC LIMIT 10";
              $mysql = @mysql_query($query);

              while ($actx = @mysql_fetch_array($mysql)){

                     $us_db = oCriador::info_user($actx['id_us'], "id_ur, nome, foto");
                     $pg_db = oCriador::info_user($actx['id_pg'], "id_ur, nome");

                    if ($actx['acion'] == 'add'){

                        if ($actx['tabela'] == 'perg') {
                                     $perg = oCriador::assoc_db("msg", "id_ms='".$actx['idrow']."'");
                            $actx['aviso'] = 'fez uma pergunta na p&aacute;gina de ';
                            $actx['local'] = '<a href="/view.do?idp='.$perg['cd_ms'].'">'.$pg_db['nome'].'</a>';
                            $actx['texto'] = oCriador::XXview($perg['texto'], 80, '?', false);
                        }else 

                        if ($actx['tabela'] == 'resp') {
                                     $resp = oCriador::assoc_db("resp", "id_sb='".$actx['idrow']."'");
                                     $perg = oCriador::assoc_db("msg", "id_ms='".$resp['id_ms']."'");
                            $actx['aviso'] = 'respondeu a pergunta:';
                            $actx['local'] = '<a href="/view.do?idp='.$perg['cd_ms'].'">'.oCriador::XXview($perg['texto'], 40).'</a>';
                            $actx['texto'] = oCriador::XXview($resp['texto'], 80, false, false);
                        }else

                        if ($actx['tabela'] == 'wall') {
                                     $wall = oCriador::assoc_db("wall", "id_wall='".$actx['idrow']."'");
                            $actx['aviso'] = 'enviou um recado para ';
                            $actx['local'] = ($actx['id_us'] != $actx['id_pg']) ? '<a href="/'.$pg_db['id_ur'].'/pf">'.$pg_db['nome'].'</a>' : 'si mesmo!';
                            $actx['texto'] = ($wall['status'] == 1 || $this->dbus['id_us'] == $wall['id_am'] || $this->dbus['id_us'] == $wall['id_us']) ? oCriador::XXview($wall['texto'], 80, false, false) : (($wall['texto'] != '') ? 'privado' : '');
                        }else

                        if ($actx['tabela'] == 'inow') {
                                     $inow = oCriador::assoc_db("inow", "id_us='".$actx['idrow']."'");
                            $actx['aviso'] = 'atualizou o status do seu perfil!';
                            $actx['local'] = '';
                            $actx['texto'] = oCriador::XXview($inow['texto'], 80, false, false);
                        }
                    }

                    if (strlen($actx['texto']) > 1){
                        if ($actx['texto'] != 'privado') {
                            $html .= '<div class="actx_msg'.(($id_ini != 0 && $obs) ? ' upp' :'').'" id="'.$actx['id'].'_actx_msg">';
                                  $html .= '<div class="actx_auto">';
                                        $html .= '<a href="/'.$us_db['id_ur'].'"><img src="'.$us_db['foto'].'" width="26"></a>';
                                  $html .= '</div>';
                                  $html .= '<div class="actx_main">';
                                        $html .= '<div class="actx_txt">';
                                              $html .= '<a href="/'.$us_db['id_ur'].'">'.$us_db['nome'].'</a> '.$actx['aviso'].' '.$actx['local'].'<br>'.$actx['texto'];
                                        $html .= '</div>';
                                        $html .= '<div class="actx_title">';
                                              $html .= date("d M Y \a\s H:i",$actx['data']);
                                        $html .= '</div>';
                                        $html .= ($this->dbus['id_us'] != '' && ($actx['id_pg'] == $this->dbus['id_us'] || $actx['id_us'] == $this->dbus['id_us'] || $this->dbus['admin'] > 2)) ? '<div class="actx_xmsg" onclick="actx.del('.$actx['id'].');" title="Excluir Agora!">&nbsp;</div>' : '';
                                  $html .= '</div>';
                            $html .= '</div>';
                        }
                    }else{
                        oCriador::delete_db('actx', "id='".$actx['id']."'");
                    }

                    $nu_actx++;
                    $id_final = $actx['id'];
             }

             if ($html == '') {
                 if (mysql_num_rows($mysql) == 10) {
                     $html = $this->actx_view($id_pg, $id_final, 'pos', false);
                 }else{
                     $html = '<br><center>Lista de Atividades est&aacute; vazia!</center><br>';
                 }
             }elseif (mysql_num_rows($mysql) == 10){
                 if ($nu_actx < 6) {
                     $html .= $this->actx_view($id_pg, $id_final, 'pos');
                 }else{
                     $html .= '<div class="actx_submit" id="actx_submit" onclick="actx.dow();">Ver mais</div>';
                 }
             }

             return $html;     
       }
}

?>