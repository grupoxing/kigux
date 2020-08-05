<?php

/**                                                                            *
* @desc          Class gMaps: Script para Gerar Marks no Google Maps           *
* @author        James S. de Aguiar <falecom@ocriador.com>                     *
* @copyright     Copyright 2009-2018, Desenvolvimento de Sistemas Web          *
* @version       v2 - 27/05/2018 as 01:47                                    *
* @license       OCriador License (http://ocriador.com.br/license.do)          *
*******************************************************************************/

# Class oCriador
require_once $_SERVER['DOCUMENT_ROOT'].'/tool/ocriador_class.php';

$key_id_user = 'AIzaSyCZUqiIU6-x9RlmI_sSYbwseG7NSQCHc68';

# Class Google Maps
class gMaps extends oCriador{
    
	function __construct() {
		$this->mapsKey  = 'AIzaSyCZUqiIU6-x9RlmI_sSYbwseG7NSQCHc68';
                $this->mapsHost = 'www.google.com';
                
	}

	function geoLocal($cord, $endereco, $id_us) { 
            if (strlen($cord) < 15) {
                $xml = @simplexml_load_file('https://maps.googleapis.com/maps/api/geocode/xml?address='.urlencode($endereco).'&key='.$this->mapsKey);
                $result = $xml->result;
                $adress = $result->geometry;
                $cord   = '{lat: '.$adress->location->lat.', lng: '.$adress->location->lng.'}';
                @mysql_query("UPDATE user_perfil SET map='".$cord."' WHERE id_us='".$id_us."'");
            }
            return $cord;
	}
                             
        function mapLocal($local, $id_us, $loop = 0) {
            if (strlen($local) < 10 || substr_count($local, '- ,')) {            
                $cidad = @mysql_fetch_array(mysql_query("SELECT * FROM local_cidade ORDER BY boot ASC, RAND() LIMIT 3"));
                $estad = @mysql_fetch_array(mysql_query("SELECT * FROM local_estado WHERE estado_id=".$cidad['estado_id']." LIMIT 1"));
                $local = $cidad['cidade_nome'].' - '.$estad['estado_sigla'].', Brasil';
                if (strlen($estad['estado_sigla']) < 2 && $loop < 5) {
                    $this->mapLocal($local, $id_us, $loop++);
                    return;
                }else{
                   @mysql_query("UPDATE local_cidade SET boot=boot+1 WHERE cidade_id='".$cidad['cidade_id']."'");
                   @mysql_query("UPDATE user_perfil SET local='".$local."' WHERE id_us='".$id_us."'");
                }
            }
            return $local;
        }
                              
        # Criar Lista de Marker Map
        public function gmaps_marker_lista() {
               $lista = oCriador::tool_iCache()->icache_read('gmaps_marker_lista');
               if (!$lista) {  
                   $query = mysql_query("SELECT id_us, id_ur, nome, niver, foto, local, map, descricao FROM user_perfil ORDER BY RAND() LIMIT 0, 1500");
                   while ($assoc = mysql_fetch_array($query)) {
                          $assoc['local'] = $this->mapLocal($assoc['local'], $assoc['id_us']);
                          $assoc['map']   = $this->geoLocal($assoc['map'], $assoc['local'], $assoc['id_us']);
                          $assoc['ico']   = $this->gmaps_marker_thumb($assoc['foto'], $assoc['id_us']);
                          if (strlen($assoc['local']) > 10 && strlen($assoc['map']) > 15) {                                                           
                              $latt .= $assoc['map'].',';
                              $code .= '{idus:"'.trim($assoc['id_us']).'",idur:"'.trim($assoc['id_ur']).'",link:"/'.trim($assoc['id_ur']).'",name:"'.trim($assoc['nome']).'",age:"'.oCriador::idade($assoc['niver']).' anos",local:"'.trim($assoc['local']).'",icon:"'.$assoc['ico'].'",photo:"'.trim($assoc['foto']).'"},';
                          }
                   }
                   if (substr($latt, -1, 1) == ',') $latt = substr($latt, 0, -1);
                   if (substr($code, -1, 1) == ',') $code = substr($code, 0, -1);                  
                   $lista = '[['.$latt.'],['.$code.']]';                  
                   if ($latt != '') oCriador::tool_iCache()->icache_save('gmaps_marker_lista', $lista);                  
               }               
               return $lista;
        }
        
        # Criar Foto Thumb
         public function gmaps_marker_thumb($foto_temp, $id_us, $tw = 32, $th = 32) {

                 $foto_dest = $_SERVER['DOCUMENT_ROOT'].'/tool/gmaps/marker/'.$id_us.'.png';
                 $foto_mark = $_SERVER['DOCUMENT_ROOT'].'/tool/gmaps/marker/marker.gif';
                 $foto_temp = $_SERVER['DOCUMENT_ROOT'].$foto_temp;

                 if (file_exists($foto_temp) && !file_exists($foto_dest)) {
                     
                     # Recupera as dimensoes originais da imagem
                     list($largura, $altura, $type) = getimagesize($foto_temp);

                     switch ($type){
                             case 1: $original = imagecreatefromgif($foto_temp);  break;
                             case 2: $original = imagecreatefromjpeg($foto_temp); break;
                             case 3: $original = imagecreatefrompng($foto_temp);  break;
                             default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
                     }
                     
                     $marker = imagecreatefromgif($foto_mark);
                     
                     //Redimensiona a imagem seguindo a proporcao original
                     //$scale = min(($tw/$largura), ($th/$altura));

                     //$tw = floor($scale*$largura);
                     //$th  = floor($scale*$altura);

                     $saida = imagecreatetruecolor($tw, $th);

                     // Pimtar fundo da img de branco
                     $cor_fundo = imagecolorallocate($saida,255,255,255);
                                  imagefill($saida, 0, 0, $cor_fundo);

                     imagecopyresampled($saida, $original, 0, 0, 0, 0, $tw, $th, $largura, $altura);
                     
                     imagecopy($marker, $saida, 4, 4, 0, 0, $tw, $th);               
 
                     imagepng($marker,$foto_dest);

                     imagedestroy($marker);
                     imagedestroy($saida);
                     imagedestroy($original);
                 }
                 
                 return '/tool/gmaps/marker/'.$id_us.'.png';
         }        
}

?>