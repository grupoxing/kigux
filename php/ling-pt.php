<?php

$_CONFIG['chat'] = 'Chat#';
$_CONFIG['foto']['max_f'] = 40;
$_CONFIG['foto']['max_w'] = 600;
$_CONFIG['foto']['max_h'] = 800;
$_CONFIG['foto']['min_w'] = 200;
$_CONFIG['foto']['min_h'] = 210;

// Link & Site URL Atual
$_CONFIG['link'] = strTr($_SERVER['HTTP_HOST'], array('www.' => '', 'mail.' => ''));
$trechos = explode(".", $_CONFIG['link']);
if (count($trechos) > 2 && strlen($trechos[0]) > 4 && strlen($trechos[1]) > 4) {
    if ($trechos[1] != 'com' && $trechos[1] != 'es' && $trechos[1] != 'de' && $trechos[1] != 'br' && $trechos[1] != 'net') {
        $_CONFIG['link'] = eregi_replace($trechos[1] . '.', "", $_CONFIG['link']);
    }
}

if ($_CONFIG['link'] == 'xn--ndia-upa.com') {
    $_CONFIG['site'] = $_CONFIG['link'] = 'índia.com';
}elseif ($_CONFIG['link'] == 'xn--cone-upa.com') {
    $_CONFIG['site'] = $_CONFIG['link'] = 'ícone.com';
} else {
    $_CONFIG['site'] = ucwords($_CONFIG['link']);
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/php/ling-br.php';

?>