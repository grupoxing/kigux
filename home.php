<?php

// Direcionar para HTTPS
if (!$_SERVER["HTTPS"]) {
    $url_ini = $_SERVER['SERVER_NAME'];
    $url_new = "https://" . $url_ini . $_SERVER['REQUEST_URI'];
    header("Location: $url_new");
    exit;
}

session_start();

# Ini - Setup - Class - iConta
require_once $_SERVER['DOCUMENT_ROOT'] . '/php/conexao.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tool/iconta/iconta_class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/tool/ocriador_class.php';

$user     = new Usuario();
$oCriador = new oCriador();

if (!$user->dbpg) {
    Header("location:/" . (($user->dbus && $user->dbus != $user->dbpg) ? $user->dbus['id_ur'] : ''));
    exit;
}else{
    // Adicionar Pergunta Boot
    if ($user->dbpg['public'] == 0) {
        $oCriador->add_boot($user->dbpg['id_us'], $user->dbpg['date_up']);
    }   
}
# Fim - Setup - Class - iConta

$cmd = isset($_GET["cmd"]) ? $_GET["cmd"] : (isset($_GET["q"]) ? 'bus' : 'pg');
$exe = substr($cmd, 0, 1);

# Ini - Setup - Class - Main
include("php/main.class.php");
$ex = new Main();
//$tool['css'] .= $ex->main_head('css');
//$tool['jss'] .= $ex->main_head('jss');
# Fim - Setup - Class - Main  

# Ini - Setup - Class - iAmg
include('tool/iamg/iamg_class.php');
$amigo = new iAmg();
$tool['css'] .= $amigo->iamg_head('css');
$tool['jss'] .= $amigo->iamg_head('jss');
# Fim - Setup - Class - iAmg  

# Ini - Setup - Class - iFoto
include('tool/ifoto/ifoto_class.php');
$ifoto = new iFoto();
$tool['css'] .= $ifoto->ifoto_head('css');
$tool['jss'] .= $ifoto->ifoto_head('jss');
# Fim - Setup - Class - iFoto

# Ini - Setup - Class - iMsg
include('tool/imsg/imsg_class.php');
$msg = new iMsg();
$tool['css'] .= $msg->imsg_head('css');
$tool['jss'] .= $msg->imsg_head('jss');
# Fim - Setup - Class - iMsg

# Ini - Setup - Class - iPoll
include('tool/poll/ipoll_class.php');
$poll = new iPoll();
$tool['css'] .= $poll->ipoll_head('css');
$tool['jss'] .= $poll->ipoll_head('jss');
# Fim - Setup - Class - iPoll

# Ini - Setup - Class - iGroup
include('tool/igroup/igroup_class.php');
$grupos = new iGroup();
$tool['css'] .= $grupos->igroup_head('css');
$tool['jss'] .= $grupos->igroup_head('jss');
# Fim - Setup - Class - iGroup

# Ini - Setup - Class - iDrop
include('tool/idrop/idrop_class.php');
$idrop = new iDrop();
$tool['css'] .= $idrop->idrop_head('css');
$tool['jss'] .= $idrop->idrop_head('jss');
# Fim - Setup - Class - iDrop

# Ini - Setup - Class - Inow
include('tool/inow/inow_class.php');
$inow = new Inow();
$tool['css'] .= $inow->inow_head('css');
$tool['jss'] .= $inow->inow_head('jss');
# Ini - Setup - Class - Inow

# Ini - Setup - Class - iConf
include('tool/iconf/iconf_class.php');
$iconf = new iConf();
$tool['css'] .= $iconf->iconf_head('css');
$tool['jss'] .= $iconf->iconf_head('jss');
# Fim - Setup - Class - iConf

if ($user->dbus['id_us'] == $user->dbpg['id_us']) {
    # Ini - Setup - Class - Convite
    include('tool/icall/icall_class.php');
    $call = new iCall();
    $tool['css'] .= $call->icall_head('css');
    $tool['jss'] .= $call->icall_head('jss');
    # Fim - Setup - Class - Convite 
}

if ($cmd == 'wg' || $cmd == 'pw') {
    # Ini - Setup - Class - WindGet
    include('tool/wdg/wdg_class.php');
    $wd = new Wdg();
    $tool['css'] .= $wd->wdg_head('css');
    $tool['jss'] .= $wd->wdg_head('jss');
    # Fim - Setup - Class - WindGet
} else

if ($cmd == 'pf') {
    # Ini - Setup - Class - iBox
    include('tool/ibox/ibox_class.php');
    $ibox = new iBox();
    $tool['css'] .= $ibox->ibox_head('css');
    $tool['jss'] .= $ibox->ibox_head('jss');
    # Fim - Setup - Class - iBox
    
    # Ini - Setup - Class - Wall
    include('tool/wall/wall_class.php');
    $wall = new Wall();
    $tool['css'] .= $wall->wall_head('css');
    $tool['jss'] .= $wall->wall_head('jss');
    # Fim - Setup - Class - Wall
    
    # Ini - Setup - Class - Actx
    include('tool/actx/actx_class.php');
    $actx = new Actx();
    $tool['css'] .= $actx->actx_head('css');
    $tool['jss'] .= $actx->actx_head('jss');
    # Fim - Setup - Class - Actx
} else

if ($cmd == 'dic') {
    # Ini - Setup - Class - Poll
    include('tool/term/term_class.php');
    $term = new Term();
    $tool['css'] .= $term->term_head('css');
    $tool['jss'] .= $term->term_head('jss');
    # Fim - Setup - Class - Poll
}

# Ini - Setup - Class - iChat
include('tool/ichat/ichat_class2.php');
$ichat = new iChat();
$tool['css'] .= $ichat->ichat_head('css');
# Fim - Setup - Class - iChat
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head><meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <title><?= $user->conf['site']; ?> - <?= $ex->limpar_nome($user->dbpg['nome']); ?> - <?php echo isset($msg->perg['texto']) ? $ex->XXview($msg->perg['texto'], 40) : '&iquest;?'; ?></title>
        <meta name="description" content="<?php echo (isset($msg->perg['texto'])) ? $msg->perg['texto'] : '&iquest;?'; ?> - Crie sua página de Perguntas e Respostas Grátis. Participe de Discussões sobre o que você Conhece ou tem Interesse. Pergunte, tire suas Dúvidas ou Responda a de outros, Crie Enquetes, Relacione-se com seus Amigos, Compartilhe Conhecimento, Bate-Papo Online e Muito Mais!"/>
        
        <link type="image/gif" href="/css/favicon.gif" rel="icon"/>
        <link href="/css/kicell.css" rel="stylesheet" type="text/css"/> 
        <script src="/js/iajax.js" type="text/javascript"></script>
        <script src="/tool/ocriador.js" type="text/javascript"></script>
        <?php
        # Ini - Echo - Tool - Style
        echo $tool['css'];
        # Fim - Echo - Tool - Style
        # Ini - Echo - Tool - Scripts
        echo $tool['jss'];
        # Fim - Echo - Tool - Scripts
        # Ini - Echo - myCss - iConf
        echo $iconf->iconf_mycss();
        # Fim - Echo - myCss - iConf
        ?>    
    </head>
    <body <?php if (@$_GET['exe'] == 'call') echo ' onload="idrop.abrir(\'/tool/icall/icall_ajax.php\',false,true);"'; elseif ($cmd == 'wg') echo ' onload="widget();"'; ?>>
    <div id="site">  
        <?php 
        
        # Menu Principal
        echo $ex->menuTop($cmd);
                        
        # Janela iDrop
        echo $idrop->idrop_main();
        
        ?>
        <div id="home">
            <?php
        
            # Ini - Main - Echo Aviso
            if (isset($_GET['obs'])) echo '<div class="iAviso" style="margin-bottom: 10px;">' . $_GET['obs'] . '</div>';
            # Fim - Main - Echo Aviso

            # Ini - iFoto - Adicionar - Foto
            echo $iconf->iconf_link();
            echo $ifoto->ifoto_addfoto();
            # Fim - iFoto - Adicionar - Foto
                      
            # Ini - Setup - Class - Inow
            echo $inow->inow_main($user->edit);
            # Ini - Setup - Class - Inow
            ?>
            <div id="main"> 
                <div id="l1">
                    <?php
                    
                    # Ini - iFoto - Perfil
                    echo $ifoto->ifoto_perfil();
                    # Fim - iFoto - Perfil

                    if ($user->dbpg['public'] == 0) {
                        # Ini - Amigos - Demo - Amigos
                        echo $amigo->demo_amigos(12);
                        # Fim - Amigos - Demo - Amigos
                        
                        # Ini - Amigos - Demo - Pedidos
                        echo $amigo->demo_pedidos(12);
                        # Fim - Amigos - Demo - Pedidos
                        
                        # Ini - Amigos - Demo - Seguidos
                        echo $amigo->demo_seguidos(12);
                        # Fim - Amigos - Demo - Seguidos
                        
                        # Ini - Amigos - Demo - Seguidores
                        echo $amigo->demo_seguidores(12);
                        # Fim - Amigos - Demo - Seguidores
                        
                        # Ini - Grupos - Demo - Sub-Grupos
                        echo $grupos->igroup_demo_meus(12);
                        # Fim - Grupos - Demo - Sub-Grupos
                    } else {
                        # Ini - Grupos - Demo - Membros
                        echo $grupos->igroup_demo_memb(12);
                        # Fim - Grupos - Demo - Membros
                        
                        # Ini - Grupos - Demo - Sub-Grupos
                        echo $grupos->igroup_demo_subt(12);
                        # Fim - Grupos - Demo - Sub-Grupos
                    }

                    # Ini - Grupos - Demo - All
                    echo $grupos->igroup_demo_all(12);
                    # Fim - Grupos - Demo - All
                    
                    # Ini - Amigos - Outros
                    echo $amigo->demo_novos(12);
                    # Fim - Amigos - Outros
                    
                    # Ini - Poll - View
                    echo $poll->poll_afins($user->dbpg['id_us']);
                    # Fim - Poll - View

                    if ($user->edit && $cmd != 'av') {
                        echo '<a href="/' . $user->dbus['id_ur'] . '/av#l2"><img src="/tool/icall/images/avise.gif"></a>';
                    }
                    
                    ?>
                </div>
                <div id="l2">
                    <?php
                                   
                    if ($user->edit && $ifoto->new_foto) {

                        # Ini - iChat - Main
                        echo $ifoto->ifoto_recort();
                        # Fim - iChat - Main

                    } else {
                        
                        # Ini - Main - Menu Perfil
                        echo $ex->menuPerf($cmd);
                        # Fim - Main - Menu Perfil

                        if ($cmd == 'pf') {

                            # Ini - Main - Info Perfil
                            echo '<div class="perfil">' . $ex->info_pf() . '</div>';
                            # Fim - Main - Info Perfil                 
                            ?>

                            <script type="text/javascript">
                                ibox.add('l2', 'wall', 'Mural de Recados', 1);
                                ibox.add('l2', 'actx', 'Hist&oacute;rico de Atividades dos Seguidos', 2);
                            <?php if ($user->dbpg['public'] == 0) echo "ibox.add('l2', 'iamg', 'Pedidos de Amizade', 3);"; ?>
                            </script>

                        <?php }
                            
                            if ($user->edit && ($cmd == 'config' || $cmd == 'design' || $cmd == 'senha' || $cmd == 'delete')) { ?>

                            <!-- iConf - Main de Conf- Ini -->
                            <?= $iconf->iconf_main($cmd); ?>
                            <!-- iConf - Main de Conf - Fim -->

                            <?php
                            } elseif ($cmd == 'poll') {
                                # Ini - Poll - Main Poll
                                echo $poll->ipoll_main($user->dbpg['id_us'], $user->dbpg['public'], false, $cmd);
                                # Fim - Poll - Main Poll
                            } elseif ($cmd == 'wg') {
                                # Ini - Edit - WindGet
                                echo $wd->wdg_edit();
                                # Fim - Edit - WindGet
                            } elseif ($user->dbpg['public'] == 0 && $exe == 'a') {

                                # Ini - Menu - Amigos
                                echo $amigo->menu($cmd);
                                # Fim - Menu - Amigos                      

                                if ($cmd == 'av') {
                                    # Ini - Amigos - Convite
                                    echo $call->icall_main();
                                    # Fim - Amigos - Convite
                                } else {
                                    # Ini - Lista - Amigos
                                    echo $amigo->friends(@$_GET['pg'], $cmd);
                                    # Fim - Lista - Amigos
                                }
                                
                            } elseif ($cmd == 'tm' || $cmd == 'ts' || $cmd == 'tt' || $cmd == 'tb' || $cmd == 'an') {
                                # Ini - Grupos - View
                                echo $grupos->igroup_view(@$_GET['pg'], $cmd);
                                # Fim - Grupos - View
                            } else {

                                # Ini - Mensagens - Menu
                                echo $msg->menu($cmd);
                                # Fim - Mensagens - Menu

                                if ($cmd == 'pw') {
                                    # Ini - Edit - WindGet - Perguntas
                                    echo $wd->wdg_edit();
                                    # Fim - Edit - WindGet - Perguntas
                                } else

                                if ($cmd == 'bus' || isset($_GET['q'])) {
                                    # Ini - Setup - Class - Buscar
                                    include('tool/ibusca/ibusca_class.php');
                                    $busca = new iBusca();
                                    echo $busca->ibusca_pergunta();
                                    # Fim - Setup - Class - Buscar
                                } else

                                if (isset($_GET['idp'])) {
                                    # Ini - View - Mensagens
                                    echo $msg->view_msg();
                                    # Fim - View - Mensagens
                                } else

                                if ($cmd == 'dic') {
                                    echo $term->term_main($user->dbpg['id_us'], $user->dbus);
                                } else {

                                    # Ini - Form - Send
                                    echo $msg->imsg_form($cmd);
                                    # Fim - Form - Send

                                    if ($user->dbpg['total']['espera'] > 0 && ($user->dbus['id_us'] == $user->dbpg['id_us'] || $user->dbus['admin'] > 0)) {
                                        echo $amigo->esperas();
                                    }

                                    # Ini - Lista - Mensagens
                                    echo $msg->messages($cmd);
                                    # Fim - Lista - Mensagens
                                }
                        }
                    }           
                    ?>                          
                </div>
            </div>
            <div id="footer" class="footer2">&copy;2009-<?= date('Y'); ?> <?= $user->conf['site']; ?> - <a href="/terms.do" target="_blank" title="Todos os Direitos Reservados - Pol&iacute;tica de Privacidade!"><font color="#B0B0B0">Privacidade</font></a>.</div>
        </div>
        <?php echo $ex->rodape(); ?>
    </div>
    <?php
        # Ini - iChat - Main
        echo $ichat->ichat_html();
        echo $ichat->ichat_head('jss');
        # Fim - iChat - Main   
    ?>
    </html>
</body>