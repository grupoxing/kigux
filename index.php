<?php

// Direcionar para HTTPS
if (!$_SERVER["HTTPS"]) {
    $url_ini = $_SERVER['SERVER_NAME'];
    $url_new = "https://" . $url_ini . $_SERVER['REQUEST_URI'];
    header("Location: $url_new");
    exit;
}

# Ini - Setup - Class - iConta
if (!class_exists("Usuario")) {
    include $_SERVER['DOCUMENT_ROOT'] . '/php/conexao.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/tool/iconta/iconta_class.php';
    $user = new Usuario();
}
# Fim - Setup - Class - iConta

# Ini - Setup - Class - Main
include("php/main.class.php");
$ex = new Main();
# Fim - Setup - Class - Main

# Ini - Setup - Class - Amigos
include('tool/iamg/iamg_class.php');
$amigo = new iAmg();
$tool['css'] .= $amigo->iamg_head('css');
$tool['jss'] .= $amigo->iamg_head('jss');
# Fim - Setup - Class - Amigos

# Ini - Setup - Class - iGroup
include('tool/igroup/igroup_class.php');
$grupos = new iGroup();
$tool['css'] .= $grupos->igroup_head('css');
$tool['jss'] .= $grupos->igroup_head('jss');
# Fim - Setup - Class - iGroup

# Ini - Setup - Class - iBusca
include('tool/ibusca/ibusca_class.php');
$busca = new iBusca();
$tool['css'] .= $busca->ibusca_head('css');
$tool['jss'] .= $busca->ibusca_head('jss');
# Fim - Setup - Class - iBusca

# Ini - Setup - Class - iDrop
include('tool/idrop/idrop_class.php');
$idrop = new iDrop();
$tool['css'] .= $idrop->idrop_head('css');
$tool['jss'] .= $idrop->idrop_head('jss');
# Fim - Setup - Class - iDrop

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title><?= $user->conf['site']; ?> - Seu Portal de Perguntas e Respostas</title>
        <meta name="description" content="Crie sua página de Perguntas e Respostas Grátis. Participe de Discussões sobre o que você Conhece ou tem Interesse. Pergunte, tire suas Dúvidas ou Responda a de outros, Crie Enquetes, Relacione-se com seus Amigos, Compartilhe Conhecimento, Bate-Papo Online e muito mais!"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link type="image/gif" href="/css/favicon.gif" rel="icon"/>
        <link href="/css/kicell.css" rel="stylesheet" type="text/css"/> 
        <script type="text/javascript" src="/js/ajax.js"></script>
        <script type="text/javascript" src="/js/iajax.js"></script>
        <?php
        # Ini - Echo - Tool - Style
        echo $tool['css'];
        # Fim - Echo - Tool - Style
        # Ini - Echo - Tool - Scripts
        echo $tool['jss'];
        # Fim - Echo - Tool - Scripts
        ?>
        <link href="/css/home.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <?php
        $cont = 0;
        $buscaConted = mysql_query("SELECT id_us, id_ur, nome FROM user_perfil WHERE public=1 ORDER BY RAND() LIMIT 0, 8");
        while ($info = mysql_fetch_array($buscaConted)) {
            $demo[$cont] .= '<div class="box-' . $cont . ' box-demo">';
            $demo[$cont] .= '<h2><a href="/' . $info['id_ur'] . '">¿? ' . $info['nome'] . '</a></h2>';
            $demo[$cont] .= '<div class="box-link">';
            $demo[$cont] .= '<ol>';
            $buscaMensag = mysql_query("SELECT cd_ms, texto FROM msg WHERE id_am='" . $info['id_us'] . "' ORDER BY RAND() LIMIT 0, 3");
            while ($mesg = mysql_fetch_array($buscaMensag)) {
                $demo[$cont] .= '<li><a href="/view.do?idp=' . $mesg['cd_ms'] . '#l2" target="_blank">' . $ex->XXview($mesg['texto']) . '</a></li>';
            }
            $demo[$cont] .= '</ol>';
            $demo[$cont] .= '</div>';
            $demo[$cont] .= '</div>' . "\n";
            $cont++;
        }
        shuffle($demo); 
        ?>       
        <div id="site">
            <!-- Main - Menu Topo  -->
            <?php echo $ex->menuTop('exit'); ?>
            <!-- Janela iDrop -->
            <?php echo $idrop->idrop_main(); ?>
            <div id="main">
                <div id="demo" style="margin-top: 10px;">
                    <div class="box-demo">
                        <div class="box-busca">
                            <?= $busca->ibusca_simples(); ?>
                            <marquee id="info_desc" scrollamount="4">Crie seu perfil de Perguntas e Respostas Gr&aacute;tis, Conhe&ccedil;a melhor seus Amigos, envie perguntas An&ocirc;nimas, Participe de Discuss&otilde;es sobre o que voc&ecirc; Conhece ou tem Interesse. Pergunte, tire suas D&uacute;vidas ou Responda a de outros, Crie Enquetes, Compartilhe Conhecimento, Bate-Papo Online e muito Mais!</marquee>
                            <div onmouseover="document.getElementById('info_desc').style.display = 'none';this.style.display = 'none';document.getElementById('ibusca_input').focus();" id="info_efect"></div>
                        </div>
                    </div>                       
                    <?php if ($user->dbus['id_us'] != false) { ?>
                        <div class="box-demo">
                            <div class="user">
                                <div class="foto">
                                    <a href="/<?= $user->dbus['id_ur']; ?>"><img src="<?= $user->dbus['foto']; ?>" width="70px" border="0"></a>
                                </div>
                                <div class="info">
                                    <div class="gene-<?= $user->dbus['sexo']; ?>">
                                        <a href="/<?= $user->dbus['id_ur']; ?>"><?= $user->dbus['nome']; ?>, <?= $user->dbus['idade']; ?> anos</a>
                                    </div>
                                    <div class="endc">
                                        /<?= $user->dbus['id_ur']; ?>
                                    </div>
                                    <div class="acion">
                                        <span><a href="/<?= $user->dbus['id_ur']; ?>">Meu Perfil</a></span> -
                                        <span><a href="?cmd=exit" target="_parent">Sair</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="box-0 box-demo">
                            <div class="box-link" style="padding: 13px 0px 5px 13px; width: 408px">
                                <h2><a href="/login.do">ACESSAR CONTA</a></h2>
                                <form class="login" action="/login.do<?php if (isset($_GET['url'])) echo '?url=' . $_GET['url']; ?>" method="post">
                                    <div class="login-email">E-mail</div>
                                    <input class="login-input" name="email" type="text" size="25" />
                                    <div class="login-senha">Senha</div>
                                    <input class="login-input" name="senha" type="password" size="15" />
                                    <input class="login-logar" name="login" type="submit" value="ok" />
                                </form>
                            </div>
                        </div>
                    <?php } ?>     
                </div>
                <div id="demo">
                    <div class="box-9 box-demo">
                        <div class="box-link">
                            <?= $amigo->demo_novos(24); ?>
                        </div>
                        <div class="box-link" style="margin-top: 7px;">
                            <?= $grupos->igroup_demo_all(32); ?>
                        </div>
                    </div>
                    <div class="box-6 box-demo">
                        <div class="box-link">
                            <?php include 'tool/iconta/iconta_signup.php'; ?>
                        </div>
                    </div>
                </div>
                <iframe marginwidth="0" marginheight="0" name="gmaps" src="/tool/gmaps/gmaps_frame.php" scrolling="auto" frameborder="0" height="500px" width="100%" style="margin: 5px 0px 10px 0px;"></iframe>
                <div id="demo">
                    <?php for ($i = 0; $i <= 7; $i++) echo $demo[$i]; ?>
                </div>
            </div> 
            <div id="footer">
                <div class="view">
                    <ul class="link">
                        <li class="site"><a  href="https://<?= $user->conf['link']; ?>/" title="Seu site de Perguntas e Respostas!"><?= $user->conf['site']; ?></a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <li><a href="http://kigux.de/" target="_blank">kigux</a> <span>|</span> </li>
                        <li><a href="http://kicell.com/" target="_blank">kicell</a> <span>|</span> </li>
                        <li><a href="http://kipasa.com/" target="_blank">kipasa</a> <span>|</span> </li>
                        <li><a href="http://oscretinos.com/" target="_blank">osCretinos</a></li>
                    </ul>
                    <span class="copy">&copy;2009-<?= date('Y'); ?> <b><?= $user->conf['site']; ?></b> - Todos os Direiros Reservados - <a href="terms.do" target="_blank" title="Pol&iacute;tica de Privacidade e Termos de Uso!">Privacidade</a>.</span>
                </div>
            </div>
            <!-- Main - Rodape -->
            <?php echo $ex->rodape(); ?>     
        </div>     
    </body>  
</html>