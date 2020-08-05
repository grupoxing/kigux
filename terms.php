<?php

// Direcionar para HTTPS
if(!$_SERVER["HTTPS"]){
    $url_ini = $_SERVER['SERVER_NAME'];
    $url_new = "https://".$url_ini.$_SERVER['REQUEST_URI'];
    header("Location: $url_new");
    exit;
}

session_start();
include("php/conexao.php");
include("php/ling-pt.php");

if (isset($_GET['cmd'])){
    $cmd = $_GET['cmd'];
    if ($cmd == 'exit'){
        $id_us = false;
        unset($_SESSION["user"]);
        setcookie("user", "", - time() + 360*86400,"/");
        $aviso = (isset($_GET['obs'])) ? $_GET['obs'] : 'Obrigado por usar '.$site.'!';
    }
}elseif (isset($_SESSION["user"])){
    $db_us = $_SESSION["user"];
    $id_us = $_SESSION["user"]["id_us"];
}else{
    $id_us = false;
}

$id_pg = $db_pg['id_us'] = 'ctg64';
         $db_pg['id_ur'] = 'diversas';

# Ini - Setup - Class - Main
  include("php/main.class.php");
  $ex = new Main();
# Fim - Setup - Class - Main

# Ini - Setup - Class - iDrop
  include('tool/idrop/idrop_class.php');
  $idrop = new iDrop();
  $tool['css'] .= $idrop->idrop_head('css');
  $tool['jss'] .= $idrop->idrop_head('jss');
# Fim - Setup - Class - iDrop
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
            <head>
            <title><?=$site;?> - Pol&iacute;tica de Privacidade!</title>
            <meta http-equiv="Content-Language" content="pt-br" />
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	    <link type="text/css" href="/css/style.css" media="screen" rel="stylesheet" />
            <script type="text/javascript" src="/js/iajax.js"></script>
            <script type="text/javascript" charset="utf-8">
            function acion(div) {
                 div = document.getElementById(div);
                 if (div.style.display == 'none') {
                     div.style.display = 'block';
                 }else{
                     div.style.display = 'none';
                 }
            }
            </script>
            <style type="text/css">
            #terms {
             color: #000;
             margin: 100px 10px;
             text-align: justify;
            }
            #terms .t {
             display: block;
             margin-top: 15px;
             margin-bottom: 5px;
             font-size: 15px;
             font-weight: bold;
            }
            #terms .p {
             display: block;
             text-indent: 20px;
             font-size: 13px;
            }
            #terms .u{
             display: block;
             margin-top: 10px;
             text-indent: 20px;
             font-size: 13px;
             text-decoration: underline;
            }
            </style>
            <?php

            # Ini - Echo - Tool - Style
              echo $tool['css'];
            # Fim - Echo - Tool - Style

            # Ini - Echo - Tool - Scripts
              echo $tool['jss'];
            # Fim - Echo - Tool - Scripts
            
            ?>
</head>
<body onload="<?php echo ($id_us == false) ? 'parent.ofChat();' : 'parent.upChat();'; ?>">

<!-- Main - Menu Topo  -->
<? echo $ex->menuTop('exit'); ?>

<!-- Janela iDrop -->
<? echo $idrop->idrop_main(); ?>

<div id="home">
          <div id="main" style="background: #fff url(/css/logo_t.gif) no-repeat 5px 5px;">
                     <div style="position: relative; left: 650px; top: 10px; width: 200px;">
                         <script src="http://www.gmodules.com/ig/ifr?url=http://www.google.com/ig/modules/translatemypage.xml&up_source_language=pt&w=160&h=60&title=&border=&output=js"></script>
                     </div>
                     <div id="terms">
                           <b><center><h1>POL&Iacute;TICA DE PRIVACIDADE</h1></center></b><BR><BR>

                            <span class="p">Bem Vindo a Politica de Privacidade para <b><?=$site;?></b> (doravante, "Site na Web").</span>
                            <span class="p">A privacidade dos visitantes &eacute; extremamente importante para n&oacute;s, pois respeitamos a sua privacidade e estamos compromissados com a seguran&ccedil;a da mesma enquanto voc&ecirc; estiver no nosso site, Site na Web. o fato que voc&ecirc; est&aacute; usando o Site na Web significa que voc&ecirc; aceita os termos de uso e a pol&iacute;tica de privacidade (doravante, "Pol&iacute;tica de Privacidade"), e que voc&ecirc; aceita receber as notifica&ccedil;&otilde;es e interagir conosco eletronicamente (email ou perfil). se voc&ecirc; n&atilde;o aceita s a Pol&iacute;tica de Privacidade, ent&atilde;o n&atilde;o use o Site na Web.</span>
                            <span class="p">O Site na Web fornece um servi&ccedil;o gr&aacute;tis de compartilhamento de informa&ccedil;&otilde;es em perguntas, respostas, f&oacute;runs, enquetes, widgets e imagens (doravante, "Conte&uacute;do").</span>

                            <span class="t">AQUISI&Ccedil;&Atilde;O E USO DE INFORMA&Ccedil;&Otilde;ES</span>
                            <span class="p">Para a utiliza&ccedil;&atilde;o plena dos nossos servi&ccedil;os, ser&aacute; necess&aacute;rio efetuar um cadastro. Durante o cadastro no Site na Web coletaremos dados pessoais onde, parte desses dados ser&atilde;o exibidos de maneira publica, no pr&oacute;prio perfil do usu&aacute;rio. o Site na Web, se compromete em n&atilde;o torna p&uacute;blico sua senha de usu&aacute;rio e seu e-mail de cadastro, n&atilde;o se responsabilizando por invas&atilde;o de terceiros (hacker).</span>
                            <span class="p">As Informa&ccedil;&otilde;es coletadas durante o registro (obrigat&oacute;rio para a efetua&ccedil;&atilde;o do cadastro), s&atilde;o: nome, e-mail, data de nascimento, sexo e senha.</span>
                            <span class="p">As informa&ccedil;&otilde;es adicionais que o usu&aacute;rio poder&aacute; tornar publica, s&atilde;o: religi&atilde;o, orienta&ccedil;&atilde;o sexual, relacionamento, escolaridade, interesses pessoais, foto e outras.</span>

                            <span class="t">PRESTA&Ccedil;&Atilde;O DE SERVI&Ccedil;OS</span>
                            <span class="p">O Site na Web oferece aos usu&aacute;rios cadastrados os seguintes servi&ccedil;os: compartilhamento de informa&ccedil;&atilde;o com amigos e an&ocirc;nimos de forma geral, lista de amigos, seguidores, seguidos, bate-papo, enquete, widget, f&oacute;runs,  lista de Conte&uacute;do e Grupos.</span>
                            <span class="p">O Site na Web oferece aos usu&aacute;rios n&atilde;o cadastrados (an&ocirc;nimos) a op&ccedil;&atilde;o de compartilhamento de informa&ccedil;&atilde;o com usu&aacute;rios cadastrados e an&ocirc;nimos de forma geral, e busca no banco de Conte&uacute;do de acordo com o grupo espec&iacute;fico.</span>
                            <span class="p">O Site na Web se resguarda o direito de mudar ou cancelar qualquer servi&ccedil;o ou recurso prestado a qualquer momento sem a necessidade de aviso pr&eacute;vio.</span>
                            <span class="p">O Site na Web resguarda o direito de mudar a forma de presta&ccedil;&atilde;o de servi&ccedil;os, como: mudan&ccedil;a de limites de uso, cadastro especifico para determinados servi&ccedil;os e atualiza&ccedil;&atilde;o dos servi&ccedil;os.</span>
                            <span class="p">O Site na Web resguarda o direito de interrup&ccedil;&atilde;o tempor&aacute;ria dos servi&ccedil;os. Os servi&ccedil;os podem ser interrompidos temporariamente devido ao uso de diversas empresas que trabalham em conjunto e que nos prestam in&uacute;meros servi&ccedil;os que est&atilde;o sujeitas a problemas adversos.</span>
                            <span class="p">O Site na Web resguarda o direito de interrup&ccedil;&atilde;o permanente dos servi&ccedil;os.</span>
                            <span class="p">O Site na Web resguarda o direito de desativa&ccedil;&atilde;o da conta do usu&aacute;rio por conduta inapropriada, com isso todos os dados pessoais deste ser&aacute; deletado do nosso sistema, a qual o usu&aacute;rio concorda que mesmo ap&oacute;s o seu desligamento do Site na Web as Conte&uacute;do fornecidas pelo usu&aacute;rio (desde que n&atilde;o estejam no seu perfil) continuaram a ficar dispon&iacute;veis no site (ser&atilde;o consideradas dom&iacute;nio p&uacute;blico), ocorrendo apenas a substitui&ccedil;&atilde;o do nome autor pelo nome an&ocirc;nimo e de sua foto pessoal pela imagem padr&atilde;o do site.</span>
                            <span class="p">O Site na Web resguarda o direito de envio de e-mail para notifica&ccedil;&otilde;es e noticias de atualiza&ccedil;&otilde;es para os usu&aacute;rios que fornecerem o seu email de alguma forma ao Site na Web.</span>

                            <span class="t">UTILIZA&Ccedil;&Atilde;O DOS SERVI&Ccedil;OS</span>
                            <span class="p">Esperamos que os usu&aacute;rios do Site na Web se comprometam com:</span>
                            <span class="p">Veracidade dos dados e informa&ccedil;&otilde;es fornecidas pelos mesmos.</span>
                            <span class="p">A utilizar apenas a interface fornecida pelo Site na Web para o acesso dos servi&ccedil;os fornecidos pelo mesmo.</span>
                            <span class="p">A utiliza&ccedil;&atilde;o dos nossos servi&ccedil;os apenas para fins permitidos pela Pol&iacute;tica de Privacidade  e regulamentos ou pr&aacute;ticas ou diretrizes da cidade, estado, pa&iacute;s em que reside.</span>
                            <span class="p">O usu&aacute;rio concorda n&atilde;o utilizar qualquer meio para a interfer&ecirc;ncia, interrup&ccedil;&atilde;o dos servi&ccedil;os prestados pelo Site na Web. Seja utilizando em nossos servi&ccedil;os meios automatizados (scripts e/ou rastreadores web).</span>
                            <span class="p">O usu&aacute;rio concorda que n&atilde;o usar&aacute; o Site na Web para a auto-promo&ccedil;&atilde;o, nem divulga&ccedil;&atilde;o de quaisquer marca ou site, nem a divulga&ccedil;&atilde;o de material (texto ou imagem) improprio (de contexto pornogr&aacute;fico, com apologia ao racismo e/as drogas ou preconceituoso), sob a penalidade de desativa&ccedil;&atilde;o/exclus&atilde;o/substitui&ccedil;&atilde;o  da conta do usu&aacute;rio e/ou do conte&uacute;do.</span>
                            <span class="p">O usu&aacute;rio concorda que ser&aacute; o &uacute;nico respons&aacute;vel (e que o Site na Web n&atilde;o tem qualquer responsabilidade perante o usu&aacute;rio ou terceiros) por qualquer n&atilde;o cumprimento das suas obriga&ccedil;&otilde;es no que diz respeito a Pol&iacute;tica de Privacidade e pelas consequ&ecirc;ncias (incluindo qualquer perda ou dano que o Site na Web possa sofrer) resultantes desse n&atilde;o cumprimento.</span>

                            <span class="t">MODIFICA&Ccedil;&Otilde;ES DE INFORMA&Ccedil;&Otilde;ES PESSOAIS</span>
                            <span class="p">Ser&aacute; dada ao usu&aacute;rio cadastrado a op&ccedil;&atilde;o de alterar nome, data de nascimento, sexo, senha, religi&atilde;o, orienta&ccedil;&atilde;o sexual, relacionamento, escolaridade, interesses pessoais, foto e URL do perfil.</span>
                            <span class="p">Por motivos de seguran&ccedil;a n&atilde;o ser&aacute; dada ao usu&aacute;rio a op&ccedil;&atilde;o de alterar o e-mail.</span>
                            <span class="p">Por motivos de seguran&ccedil;a n&atilde;o ser&aacute; permitido ao usu&aacute;rio a recupera&ccedil;&atilde;o da senha . em caso de perda da senha o Site na Web enviar&aacute; um link para a redefini&ccedil;&atilde;o da senha para o seu e-mail de cadastro.</span>

                            <span class="t">MUDAN&Ccedil;AS NA POL&Iacute;TICA DE PRIVACIDADE</span>
                            <span class="p">N&oacute;s podemos mudar o Termo de uso e Pol&iacute;tica de Privacidade a qualquer momento.</span>
                            <span class="p">Voc&ecirc; pode revisar a vers&atilde;o mais atual desta Pol&iacute;tica de Privacidade clicando no link de hipertexto "Privacidade" localizado no rodap&eacute; do Site na Web.</span>
                            <span class="p">Voc&ecirc; &eacute; respons&aacute;vel por verificar periodicamente se houve mudan&ccedil;as nesta Pol&iacute;tica de Privacidade.</span>
                            <span class="p">Se voc&ecirc; continuar usando o Site na Web depois que publicarmos mudan&ccedil;as, voc&ecirc; estar&aacute; indicando sua aceita&ccedil;&atilde;o da nova Pol&iacute;tica de Privacidade.</span>

                            <span class="t">NOSSA POL&Iacute;TICA EM RELA&Ccedil;&Atilde;O &Agrave;S CRIAN&Ccedil;AS</span>
                            <span class="p">O Site na Web recomenda que o uso do site para menores de 13 anos seja feito com o acompanhamento dos respons&aacute;veis do menor (o Site na Web n&atilde;o se responsabiliza pelo Conte&uacute;do disponibilizado pelos mais diversos usu&aacute;rios).</span>

                            <span class="t">COMPARTILHAMENTO e DIVULGA&Ccedil;&Atilde;O DE CONTE&Uacute;DO</span>
                            <span class="p">O Site na Web n&atilde;o se responsabiliza pela veracidade das informa&ccedil;&otilde;es fornecidas pelo seus respectivos usu&aacute;rios quer sejam eles, cadastrados ou n&atilde;o cadastrados (an&ocirc;nimos). 		A finalidade do Site na Web &eacute; promover o compartilhamento de informa&ccedil;&otilde;es e uma maior intera&ccedil;&atilde;o entre os seus usu&aacute;rios atrav&eacute;s de um ferramenta interativa.</span>
                            <span class="p">Fica claro que qualquer informa&ccedil;&atilde;o contida no Site na Web n&atilde;o substitui o uso de especialistas da &aacute;rea, a qual recomendamos que o usu&aacute;rio recorra aos respectivos especialistas antes de tomar qualquer decis&atilde;o.</span>
                            <span class="p">O Site na Web n&atilde;o se responsabiliza por poss&iacute;veis perdas (financeiras ou n&atilde;o) que possam vir a ocorrer ao usu&aacute;rio pelo uso do nosso web site.</span>
                            <span class="p">Que fique claro ao usu&aacute;rio que o Site na Web &eacute; um site de compartilhamento e divulga&ccedil;&otilde;es de informa&ccedil;&atilde;o, logo todas as informa&ccedil;&otilde;es (sob forma de Conte&uacute;do) colocadas no Site na Web ser&atilde;o publicas.</span>
                            <span class="p">O Conte&uacute;do considerado fora de sua categoria poder&atilde;o ser realocadas para outro grupo com ou sem aviso pr&eacute;vio.</span>
                            <span class="p">Mensagens e palavras consideradas impropria e/ou ofensivas (de contexto pornogr&aacute;fico, com apologia ao racismo, drogas ou preconceituoso) ser&atilde;o deletas ou substituidas.</span>
                            <span class="p">Sobre transfer&ecirc;ncias comerciais: em caso de uma transfer&ecirc;ncia comercial tamb&eacute;m esta incluso todo o banco de dados obtido pelo Site na Web, sendo portanto obriga&ccedil;&atilde;o da nova gest&atilde;o a responsabilidade de armazenamento e integridade dos dados do Site na Web.</span>
                            <span class="p">Os usu&aacute;rios podem usar o Conte&uacute;do em outros sites, para isso dever&atilde;o postar em seu site ou blog o c&oacute;digo correspondente ao o widget da conta que possui o Conte&uacute;do desejado, permitindo que outros usu&aacute;rios visualizem/interagem com esse Conte&uacute;do.</span>
                            <span class="p">Se voc&ecirc; &eacute; dono(a) de alguma propriedade de Conte&uacute;do com direito autoral e n&atilde;o quiser public&aacute;-lo, entre em contato com informa&ccedil;&otilde;es detalhadas atrav&eacute;s do email:<span style="position: relative; left: 0px; top: 6px; width: 200px;"><img src="/css/e-contato.png" height="20px" border="0"></span> - sua propriedade ser&aacute; efetivamente exclu&iacute;da, preservando seu direito.</span>
                            <span class="u">Para o usu&aacute;rio cadastrado</span>
                            <span class="p">O usu&aacute;rio concorda que mesmo ap&oacute;s o seu desligamento do Site na Web o Conte&uacute;do fornecido pelo usu&aacute;rio (desde que n&atilde;o estejam no seu perfil) continuaram a ficar dispon&iacute;veis no site (ser&atilde;o consideradas dom&iacute;nio p&uacute;blico), ocorrendo apenas a substitui&ccedil;&atilde;o do nome autor pelo nome an&ocirc;nimo e de sua foto pessoal pela imagem padr&atilde;o do site.</span>
                            <span class="u">Para o usu&aacute;rio n&atilde;o cadastrado (an&ocirc;nimo)</span>
                            <span class="p">Que fique claro ao usu&aacute;rio n&atilde;o cadastrado (an&ocirc;nimo) que n&atilde;o lhe ser&aacute; dada a autoria do Conte&uacute;do fornecido por ele, onde o nome de autor ser&aacute; denominado "an&ocirc;nimo" com uma foto padr&atilde;o  do Site na Web.</span>

                            <span class="t">REGRAS DE USO</span>
                            <span class="u">Conduta do usu&aacute;rio cadastrado ou n&atilde;o cadastrado (an&ocirc;nimo)</span>
                            <span class="p">1.	Esperamos que o usu&aacute;rio do Site na Web seja educado e respeite os  demais usu&aacute;rios que utilizam o sistema;</span>
                            <span class="p">2.	O Site na Web respeita a propriedade intelectual , logo esperamos que os seus usu&aacute;rios fa&ccedil;am o mesmo dando o devido cr&eacute;dito as Conte&uacute;do obtidos de outros;</span>
                            <span class="p">3.	O Site na Web espera que os seus usu&aacute;rios sejam atentos ao categorizar o Conte&uacute;do;</span>
                            <span class="p">4.	Esperamos que os usu&aacute;rios do nosso sistema tenha coer&ecirc;ncia e clareza no envio de Conte&uacute;do;</span>
                            <span class="p">5.	Esperamos que o usu&aacute;rio do nosso web site utilize o seu perfil para quest&otilde;es de cunho pessoal;</span>
                            <span class="u">Conduta considerada inapropriada para o usu&aacute;rio cadastrado ou n&atilde;o cadastrado (an&ocirc;nimo)</span>
                            <span class="p">1.	Ofensas e ass&eacute;dios de qualquer tipo.</span>
                            <span class="p">2.	N&atilde;o ser&aacute; permitida mais de um cadastro por e-mail;</span>
                            <span class="p">3.	Conversas particulares em categorias publicas;</span>
                            <span class="p">4.	Envio de Conte&uacute;do repetido, fora de sua categoria ou sem nexo;</span>
                            <span class="p">5.	Uso de Palavra ou Conte&uacute;do considerado improprio e/ou ofensivo (de contexto pornogr&aacute;fico, 	com apologia ao racismo e/as drogas ou preconceituoso);</span>
                            <span class="p">6.	Propaganda, marketing pessoal, divulga&ccedil;&atilde;o de logomarca, site e/ou e-mail pessoal;</span>
                            <span class="p">7.	Viola&ccedil;&atilde;o da lei no estado, cidade ou pais em que reside;</span>
                            <span class="p">8.	&Eacute; terminantemente proibido a ultiza&ccedil;&atilde;o dos nossos servi&ccedil;os para propagar links, mensagens e/ou programas maliciosos ou que tenham v&iacute;rus.</span>

                            <span class="t">DADOS DE REGISTRO</span>
                            <span class="u">Log Files</span>
                            <span class="p">Tal como outros websites, colectamos e utilizamos informa&ccedil;&atilde;o contida nos registos.</span>
                            <span class="p">A informa&ccedil;&atilde;o contida nos registos, inclui o seu endere&ccedil;o IP (internet protocol), o seu ISP (internet service provider), o browser que utilizou ao visitar o nosso website (como o Internet Explorer ou o Firefox), o tempo da sua visita e que p&aacute;ginas visitou dentro do nosso website.</span>
                            <span class="u">Os Cookies e Web Beacons</span>
                            <span class="p">Este Site na Web pode utilizar cookies e/ou web beacons quando um usu&aacute;rio tem acesso &agrave;s p&aacute;ginas. Os cookies que podem ser utilizados associam-se (se for o caso) unicamente com o navegador de um determinado computador.</span>
                            <span class="p">Os cookies que s&atilde;o utilizados neste site podem ser instalados pelo mesmo, os quais s&atilde;o originados dos distintos servidores operados por este, ou a partir dos servidores de terceiros que prestam servi&ccedil;os e instalam cookies e/ou web beacons (por exemplo, os cookies que s&atilde;o empregados para prover servi&ccedil;os de publicidade ou certos conte&uacute;dos atrav&eacute;s dos quais o usu&aacute;rio visualiza a publicidade ou conte&uacute;dos em tempo pr&eacute; determinados). o usu&aacute;rio poder&aacute; pesquisar o disco r&iacute;gido de seu computador conforme instru&ccedil;&otilde;es do pr&oacute;prio navegador.</span>
                            <span class="p">Usu&aacute;rio tem a possibilidade de configurar seu navegador para ser avisado, na tela do computador, sobre a recep&ccedil;&atilde;o dos cookies e para impedir a sua instala&ccedil;&atilde;o no disco r&iacute;gido. As informa&ccedil;&otilde;es pertinentes a esta configura&ccedil;&atilde;o est&atilde;o dispon&iacute;veis em instru&ccedil;&otilde;es e manuais do pr&oacute;prio navegador.</span>
                            <span class="p">Tamb&eacute;m utilizamos publicidade de terceiros no nosso webSite na Web para suportar os custos de manuten&ccedil;&atilde;o. Alguns destes publicit&aacute;rios, poder&atilde;o utilizar tecnologias como os cookies e/ou web beacons quando publicitam no nosso website, o que far&aacute; com que esses publicit&aacute;rios (como o Google atrav&eacute;s do Google AdSense) tamb&eacute;m recebam a sua informa&ccedil;&atilde;o pessoal, como o endere&ccedil;o IP, o seu ISP , o seu browser, etc. Esta fun&ccedil;&atilde;o &eacute; geralmente utilizada para geotargeting ou apresentar publicidade direccionada a um tipo de utilizador.</span>
                            <span class="p">Voc&ecirc; detem o poder de desligar os seus cookies, nas op&ccedil;&otilde;es do seu browser, ou efectuando altera&ccedil;&otilde;es nas ferramentas de programas Anti-Virus. no entanto, isso poder&aacute; alterar a forma como interage com o nosso website, ou outros websites.</span>

                            <span class="t">ANUNCIANTES</span>
                            <span class="p">N&oacute;s usamos companhias de fora para publicar an&uacute;ncios no nosso website.</span>
                            <span class="p">Esses an&uacute;ncios, podem usar cookies e\ou web beacons para recolher dados, os quais s&atilde;o levados &agrave;s nossas empresas anunciantes.</span>
                            <span class="p">Note que: n&oacute;s n&atilde;o temos acesso a esses dados. N&oacute;s trabalhamos com a seguinte empresa anunciante: Google Adsense.</span>
                            <span class="u">An&uacute;ncios Google</span>
                            <span class="p">O Google, como fornecedor de terceiros, utiliza cookies para exibir an&uacute;ncios no seu site.</span>
                            <span class="p">Com o cookie DART, o Google pode exibir an&uacute;ncios para seus usu&aacute;rios com base nas visitas feitas aos seus e a outros sites na Internet.</span>
                            <span class="p">Os usu&aacute;rios podem desativar o cookie DART visitando a Pol&iacute;tica de Privacidade da rede de conte&uacute;do e dos an&uacute;ncios do Google.</span>
                            <span class="p">Por favor, cheque esse website para ver os respectivos documentos de Pol&iacute;tica de Privacidade.</span>
                            <span class="u">DoubleClick DART</span>
                            <span class="p">N&oacute;s tamb&eacute;m poderemos usar DART Cookies para an&uacute;ncios atrav&eacute;s do DoubleClick do Google, o qual armazena um cookie no seu computador quando voc&ecirc; estiver navegando na web e visitar um site que usa o an&uacute;ncio de DoubleClick (incluindo alguns an&uacute;ncios AdSense do Google).</span>
                            <span class="p">Esse cookie &eacute; utilizado para mostrar an&uacute;ncios especificamente para voc&ecirc; e de seu interesse ("baseado no alvo de interesses").</span>
                            <span class="p">Os an&uacute;ncios mostrados ser&atilde;o focados baseados no seu hist&oacute;rico de navega&ccedil;&atilde;o pr&eacute;vio (por exemplo, se voc&ecirc; tem visto sites sobre Carros, voc&ecirc; poder&aacute; encontrar an&uacute;ncios de Concession&aacute;rias quando visitar sites n&atilde;o relacionados, como um site sobre jogos).</span>
                            <span class="p">Isso n&atilde;o rastreia informa&ccedil;&otilde;es pessoais sobre voc&ecirc;, como o seu nome, endere&ccedil;o de e-mail, endere&ccedil;o residencial, n&uacute;mero de telefone, detalhes banc&aacute;rios ou n&uacute;meros de cart&atilde;o de cr&eacute;dito.</span>
                            <span class="p">Voc&ecirc; pode desativar esses an&uacute;ncios servidos em todos os sites que usam este servi&ccedil;o acessando: http://www.doubleclick.com/privacy/dart_adserving.aspx</span>
                            <span class="u">Links Externos</span>
                            <span class="p">Este site contem links &agrave; outros sites. Fique atento, pois n&oacute;s n&atilde;o somos respons&aacute;veis pelas pr&aacute;ticas de privacidade desses sites.</span>
                            <span class="p">N&oacute;s gostar&iacute;amos de alertar os nossos usu&aacute;rios, que ao sair do nosso website, lessem a Pol&iacute;tica de Privacidade de todos os sites que recolhem informa&ccedil;&otilde;es pessoais. Essa Declara&ccedil;&atilde;o de Privacidade, aplica-se somente &agrave;s informa&ccedil;&otilde;es recolhidas por este site.</span>

                            <span class="t">ISEN&Ccedil;&Atilde;O DE GARANTIAS</span>
                            <span class="p">N&oacute;s disponibilizamos o Site na Web no estado, com todas as falhas e tal como se apresenta.</span>
                            <span class="p">N&oacute;s n&atilde;o oferecemos nenhuma garantia ou obriga&ccedil;&otilde;es expressas sobre o Site na Web, a qual n&atilde;o somos obrigados a dar qualquer tipo de suporte.</span>
                            <span class="p">&Agrave; extens&atilde;o permitida por lei, n&oacute;s nos isentamos das garantias presumidas de que o Site na Web e todo o Conte&uacute;do e servi&ccedil;os distribu&iacute;dos pelo Site na Web seja negoci&aacute;vel, de qualidade satisfat&oacute;ria, preciso, oportuno, pr&oacute;prio para um prop&oacute;sito ou finalidade em particular, ou n&atilde;o infrator.</span>
                            <span class="p">N&oacute;s n&atilde;o garantimos que o Site na Web atender&aacute; suas exig&ecirc;ncias, seja livre de erros, seguro, sem interrup&ccedil;&atilde;o ou dispon&iacute;vel o tempo todo.</span>
                            <span class="p">N&oacute;s n&atilde;o garantimos que os resultados que possam ser obtidos com o uso do Site na Web, incluindo qualquer servi&ccedil;o de suporte, sejam eficientes, seguros, precisos ou que satisfa&ccedil;am suas exig&ecirc;ncias.</span>
                            <span class="p">N&oacute;s n&atilde;o garantimos que voc&ecirc; possa acessar ou usar o Site na Web (seja diretamente ou por redes de terceiros) nos momentos ou locais de sua escolha.</span>
                            <span class="p">N&atilde;o nos resposabilisamos pelo Conte&uacute;do ou Site na Web ser esposto em outros lugares como for&uacute;ns, blogs, sites pessoais em frames ou popup em sites de terceiros.</span>
                            <span class="p">Nenhuma informa&ccedil;&atilde;o ou conselho oral ou por escrito dados por um representante do Site na Web se constituir&atilde;o em uma garantia.</span>
                            <span class="p">Voc&ecirc; pode ter direitos de consumidor adicionais, nos temos de suas leis territoriais, que este contrato n&atilde;o pode mudar.</span>

                            <span class="t">USO INTERNACIONAL</span>
                            <span class="p">N&oacute;s n&atilde;o fazemos nenhuma afirma&ccedil;&atilde;o de que o Conte&uacute;do do Site na Web &eacute; apropriado ou dispon&iacute;vel para uso em locais fora do Brasil, e fica proibido seu acesso a partir de territ&oacute;rios onde o Conte&uacute;do &eacute; ilegal. Se voc&ecirc; optar por acessar o Site na Web desde um local fora do Brasil, voc&ecirc; o faz por sua pr&oacute;pria iniciativa, e voc&ecirc; &eacute; respons&aacute;vel por cumprir a legisla&ccedil;&atilde;o local.</span>

                            <span class="t">Maiores d&uacute;vidas</span>
                            <span class="p">Email:<span style="position: relative; left: 0px; top: 6px; width: 200px;"><img src="/css/e-contato.jpg" height="20px" border="0" /></span></span>
                        </div>
          </div>
</div>

<!-- Main - Rodape -->
<? echo $ex->rodape(); ?>

</body>
</html>
