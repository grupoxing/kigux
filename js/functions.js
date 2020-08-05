// ------------------------------------------------------------ ajax chat ----------------------------------------------------------------//

function XMLHTTP(){
	var request = null;
	try{
		request = new XMLHttpRequest()
	}catch(e){
		try{
			request = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				request = new ActiveXObject("Microsoft.XMLHTTP");
			}catch(e){
				request = null;
			}
		}
	}
	return request;
}

// ------------------------------------------------------ ler msg recebidas chat ---------------------------------------------------------//

ajaxRead = new XMLHTTP();
var responsetime = null;
var clear    = 0;
var readcont = 0;
var usernum  = Array();

function readMessage(){
    ajaxRead.open('POST','/php/upXat.php',true);
	ajaxRead.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	ajaxRead.onreadystatechange = function(){
			if(ajaxRead.readyState == 4){
					if(ajaxRead.status == 200){
						var reponse = ajaxRead.responseText;
						var json = eval('(' +reponse+ ')');

						if(json.result[2].status == 'unregistered'){
						   // Off Painel xat
						   document.getElementById('status').innerHTML   = 'Reinicie o Sistema';
						   document.getElementById('userlist').innerHTML = 'Voc&ecirc; est&aacute; (Off-line)';
						   document.getElementById('j0').style.display   = 'none';
						   // Fechar Xats divs
                           var xatDivs = document.getElementById("xat");
                           while (xatDivs.firstChild) {
	                              xatDivs.removeChild(xatDivs.firstChild);
                           }
						}else if(json.result.length == 3){
						   // On Painel Xat
                           document.getElementById('j0').style.display   = 'block';
						   // Buscar Users
							var userlist = '';
							var totaluser =  json.result[1].users.length;
							for(var i = 0; i < totaluser; i++){
							    Tidus = json.result[1].users[i].idus;
                                usernum[Tidus] =  i;
								userlist += '<a href="javascript:void(0)" onclick="GeraConversa(\''+Tidus+'\',\''+json.result[1].users[i].photo+'\',\''+json.result[1].users[i].name+'\')">'+json.result[1].users[i].name +'</a>';
							}

							if(userlist == ''){ userlist = 'Nenhum Amigo...'; }
							document.getElementById('userlist').innerHTML = userlist;
							document.getElementById('tituloj0').innerHTML = "Amigos Online ("+totaluser+")";

							//mensagens
							var totalmessage =  json.result[0].messages.length;
							for(var i = 0; i < totalmessage; i++){
								GeraConversaRecebida(json.result[0].messages[i].idsend,json.result[0].messages[i].name,json.result[0].messages[i].photo);
								AddMessage(json.result[0].messages[i].idsend,json.result[0].messages[i].name,json.result[0].messages[i].message,'msgreceive');
							}

							if(totalmessage > 0){
							   som = true;
							   PlaySound();
							}

						}

						clearTimeout(responsetime);
						setTimeout('readMessage()',5000);
						document.getElementById('status').innerHTML = 'Conectado';

					}else{
						document.getElementById('status').innerHTML = 'Reiniciando...';
					}
			}
	}
	readcont++;
	if(readcont == 10){
		clear = 1;
		readcont = 0;
	}
	ajaxRead.send('&clearuser='+clear);
	responsetime = setTimeout('AbortMessage()',25000);
	clear = 0;
}

function AbortMessage(){
	document.getElementById('status').innerHTML = 'Reiniciando...';
	ajaxRead.abort();
	readMessage();
}
// ------------------------------------------------------- Criar janela chat -------------------------------------------------------------//

function Janela(){
    this.idus = 0;
    this.left = 0;
    this.foto = 'foto-m.gif';
	this.titulo = 'Janela';
	this.GeraJanela = function(){
		if(!document.getElementById('j' +this.idus)){

		    // criar div conversa no xat
			var criar = document.createElement('div');
			criar.setAttribute('id','j' +this.idus);
			document.getElementById('xat').appendChild(criar);

			// manipular o div conversa
			var div = document.getElementById('j' +this.idus);
			div.className = 'janela';

			div.onmousedown = function(){
				FocoJanela(this);
			}

			var insere = null;
			insere   =  '<div class="topo"><a class="close" href="javascript:void(0)" onclick="FechaJanela(this.parentNode.parentNode)">&nbsp;</a><a class="min" href="javascript:void(0)" onclick="MinJanela(\'j'+this.idus+'\')">&nbsp;</a><span id="tituloj' +this.idus+ '" onmousedown="CapturaJanela(this.parentNode.parentNode)">Bate-Papo com (' +this.titulo+ ')</span></div>';
			insere  +=  '<div onmousedown="CapturaJanela(this.parentNode)" class="conversa" id="bp' +this.idus+ '"></div>';
			insere  +=  '<div class="envio">';
			insere  +=  '<img onclick="frames[\'chat\'].location.href = \'home.do?id=' +this.idus+ '\'" id="foto" src="/foto/perf/'+this.foto+'" width="40">';
			insere  +=  '<input type="text" maxlength="180" id="msg'+this.idus+'" onkeypress="AnalizeKey(\'' +this.idus+ '\')" />';
			insere  +=  '<img id="send" src="/css/send.jpg" border="0" onmouseover="SendOver(this)" onclick="PrepareSend(\'' +this.idus+ '\')" />';
            insere  +=  '</div>';

			div.innerHTML = insere;

			this.left = usernum[this.idus]*55;

            var lf  = this.left;
			if(this.left < 170){
			   for(var l = this.left; l < 170+lf; l += 10){
                   this.left = l;
			   }
            }

            var fim =  tamx-270;
			if(this.left > fim){
			   for(var l = this.left; l > fim; l -= 10){
                   this.left = l;
			   }
            }

			div.style.left    = this.left +'px';

			return div;
		}else{
		    document.getElementById('j' +this.idus).style.display = 'block';
			return document.getElementById('j' +this.idus);
		}
	}
}
// ---------------------------------------------------------- add msg chat ---------------------------------------------------------------//
ajaxSend = new XMLHTTP();
var flood = false;
var key = 0;

function AddMessage(id,title,msg,style){
	var cria = document.createElement('p');
	cria.innerHTML = '<span class="' +style+ '">' +title+ '</span>: ' +msg;

	var div = document.getElementById('bp' +id);
	div.appendChild(cria);
	div.scrollTop = div.scrollHeight;
}

function Send(id){
		var msg  = document.getElementById('msg'+id).value;

		AddMessage(id,GetCookie("user"),msg,'msgsend');
		msg = encodeURIComponent(msg);

		ajaxSend.open('POST','/php/sdMsg.php',true);
		ajaxSend.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		ajaxSend.send('idreceive='+id+'&message='+msg);
}

function PrepareSend(id){
	if(flood == 0){
		var input = document.getElementById('msg' +id);
		if(input.value != ''){
			Send(id);

			input.value = '';
			input.focus();

			flood = 1;
			setTimeout('FreeFlood()',1200);
		}
	}else{
		AddMessage(id,'Aviso','Sistema anti-span ativado','msgalert');
	}
}

function FreeFlood(){
	flood = 0;
}

function AnalizeKey(id){
	switch(key){
		case 13: PrepareSend(id); break;
		case 27:
			var div = document.getElementById('j' +id);
			FechaJanela(div);
		break;
	}
}

function getKey(e){
	if(document.all){
		key = window.event.keyCode;
	}else{
		key = e.keyCode;
	}
	som = false;
}

function GetCookie(name) {
  var cookieValue = "";
  var search = name + "=";
  if(document.cookie.length > 0){
     offset = document.cookie.indexOf(search);
     if (offset != -1){
         offset += search.length;
         end = document.cookie.indexOf(";", offset);
         if (end == -1) end = document.cookie.length;
         cookieValue = unescape(document.cookie.substring(offset, end));
     }
  }
  return cookieValue;
}
document.onkeydown = getKey;
// ----------------------------------------------------- Gerar conversa chat -------------------------------------------------------------//

function GeraConversa(id,foto,nome){
	var jan    = new Janela();
	jan.idus   = id;
	jan.foto   = foto;
	jan.titulo = nome;
	var resul  = jan.GeraJanela();
	FocoJanela(resul);
}

function GeraConversaRecebida(id,nome,foto){
	var jan    = new Janela();
	jan.idus   = id;
	jan.foto   = foto;
	jan.titulo = nome;
	var resul  = jan.GeraJanela();
	FocoJanelaRecebida(resul);
}
// --------------------------------------------------------- somAlerta chat --------------------------------------------------------------//
var som  = true;
function PlaySound(){
	if((som == true) && (document.getElementById('somativo').value == 1)){
	    //if(window.som) {
        //   window.document["som"].Play();
        //}
	    //if(document.som) {
        //   document.som.Play();
        //}
	    if(window.som) {
           window.document["som"].SetVariable('_root.functionName', 'Tocar');
           window.document["som"].SetVariable('_root.flag', true);
        }
	    if(document.som) {
           document.som.SetVariable('_root.functionName', 'Tocar');
           document.som.SetVariable('_root.flag', true);
        }
	}
}
function xSound(){
    som = document.getElementById('somativo');
	if(som.value == 1){
	   som.value = 0;
	}else{
	   som.value = 1;
	}
}
// ----------------------------------------------------------- status chat ---------------------------------------------------------------//
ajaxStatus = new XMLHTTP();
function myStatus(cmd){
		 ajaxStatus.open('POST','/php/ajax-chat.php',true);
		 ajaxStatus.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	 	 ajaxStatus.send('status='+cmd);
    	 ajaxStatus.onreadystatechange = function(){
		    if(ajaxStatus.readyState == 4){
               var resposta = ajaxStatus.responseText;
               document.getElementById('status-chat').src = "css/staus-"+resposta+".gif";
            }
		 }
}
// ---------------------------------------------------------- largura chat ---------------------------------------------------------------//
var tamx = 170;
function getDisplaySize(){
		tamx = document.documentElement.clientWidth;
		if (window.opera){
			tamx = document.body.clientWidth;
		}
}
// ------------------------------------------------------ mudarImg botão chat ------------------------------------------------------------//
function SendOver(obj){
	obj.src = '/css/sendover.jpg';
	obj.onmouseout = function(){
		obj.src = '/css/send.jpg';
	}
}
function srcNF(obj){
     if (obj.src.indexOf('on') > 0) {
         obj.src = obj.src.replace("on","of");
     }else{
         obj.src = obj.src.replace("of","on");
     }
}
var image = Array();
image[0] = new Image();
image[0].src = '/css/send.jpg';

image[1] = new Image();
image[1].src = '/css/sendover.jpg';

image[2] = new Image();
image[2].src = '/css/close.jpg';

image[3] = new Image();
image[3].src = '/css/closeover.jpg';

// ------------------------------------------------------- Mover janela chat -------------------------------------------------------------//

var zindex = 1;
var posx = 0;
var posy = 0;
var difx = 0
var dify = 0;
var idmove = false;

function moveMouse(e){
    if(document.all){
          posx = event.clientX;
          posy = event.clientY;
    } else {
          posx = e.clientX;
          posy = e.clientY;
    }
	som = false;
}
// ---------------------------------------------------- acoes da janela chat -------------------------------------------------------------//

function Start(){
    myStatus('v');
	getDisplaySize();
	readMessage();
}

function acion(div) {
     div = document.getElementById(div);
     if (div.style.display == 'none') {
         div.style.display = 'block';
     }else{
         div.style.display = 'none';
     }
}

function HoverMenu() {
     id1 = document.getElementById('j0');
     id2 = document.getElementById('hm');
     if (id2.className == 'min') {
         id1.style.height = '26px';
         id2.className    = 'max';
     }else{
         id1.style.height = '190px';
         id2.className    = 'min';
     }
}

function FechaJanela(obj){
	document.getElementById('xat').removeChild(obj);
}

function FocoJanela(obj){
	var id = obj.getAttribute('id');
	zindex++;
	obj.style.zIndex = zindex;
	document.title   = document.getElementById('titulo' +id).innerHTML +' - Chat kigux';
	obj.className    = 'janela';
	id = id.replace('j','');
	if(id != '0'){
       document.getElementById('msg' +id).focus();
	}
}

function FocoJanelaRecebida(obj){
	obj.className = 'janelaover';
}

function CapturaJanela(obj){
	var div = obj.style;
	difx = posx - parseInt(div.left);
	document.onmousemove =  MoveJanela;
	idmove = obj;
}

function MinJanela(id){
	document.getElementById(id).style.display = 'none';
}

function SoltaJanela(){
	idmove = false;
}

function MoveJanela(e){
	moveMouse(e);
	if(idmove != false){
		var div  = idmove.style;
		div.left = (posx) - difx +'px';
	}
}

window.onload        = Start;
document.onmousemove = MoveJanela;
document.onmouseup   = SoltaJanela;

