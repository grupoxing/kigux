<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/php/conexao.php';
include_once 'gmaps_class.php';
$gmaps = new gMaps();

if ($_GET['exe'] == 'lista') {
    echo $gmaps->gmaps_marker_lista();
}else{
    echo 'erro';
}

?>