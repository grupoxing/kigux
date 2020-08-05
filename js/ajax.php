<?php session_start(); ?>

xmlhttp = new XMLHTTP();

var upbg = false;
var ajax = {
	upValor : function(cmd) {
    	      num = document.getElementById('n1'+cmd).innerHTML-1;
              document.getElementById('n1'+cmd).innerHTML = num;
              document.getElementById('n2'+cmd).innerHTML = num;
              document.getElementById('n3'+cmd).innerHTML = num;
	},
    close : function(div) {
            document.getElementById(div).style.display = 'none';
    },
    abrir : function(div) {
            document.getElementById(div).style.display = 'block';
    },
    acion : function(div) {
            div = document.getElementById(div);
            if (div.style.display == 'none') {
                div.style.display = 'block';
            }else{
                div.style.display = 'none';
            }
    },
	upbg : function(idbg,idpg,div){
	       upbg = true;
	       ajax.close(div);
    	   param = "upbg="+idbg+"&idpg="+idpg;
 	       xmlhttp.open('POST', '/php/ajax.php', true);
 	       xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
 	       xmlhttp.send(param);
           document.getElementById('view-bg').style.background = "url('/foto/bg/"+idbg+"-mini.jpg') repeat";
	},
	adbg : function(img){
	        if (img == 0) {
                document.body.style.background = document.getElementById('salve-bg').value;
            }else if (img == 23) {
	            document.body.style.background = "#000 url('/foto/bg/"+img+"-normal.jpg') repeat-x";
            }else{
                document.body.style.background = "url('/foto/bg/"+img+"-normal.jpg') repeat";
            }
    },
	bgbody : function(idpg,div,w){
	         upbg = false;
	         var html = '';
	         ajax.acion(div);
	         id = document.getElementById(div);
	         document.getElementById('salve-bg').value = document.body.style.background;
             for (i = 1; i <= 36; i++){
                  html += '<a href="javascript:ajax.upbg('+i+',\''+idpg+'\',\''+div+'\');"><img onmouseover="ajax.adbg('+i+')" onmouseout="if (upbg == false) { ajax.adbg(0); }" src="/foto/bg/'+i+'-mini.jpg" width="'+w+'px" border="0"></a>';
             }
             id.innerHTML = html;
	},
	iam : function(id_am,div){
    	param = "iam="+id_am;
 	    xmlhttp.open('POST', 'php/ajax.php', true);
 	    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    	xmlhttp.onreadystatechange = function() {
             if(xmlhttp.readyState == 4) {
        		resposta = unescape(xmlhttp.responseText);
                div.innerHTML = resposta;
             }
		}
 	    xmlhttp.send(param);
	},
	seg : function(id,div,acao){
    	  if (acao == 1) { param = "isg="+id; }else{ param = "xsg="+id; }
 	      xmlhttp.open('POST', 'php/ajax.php', true);
 	      xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    	  xmlhttp.onreadystatechange = function() {
                  if(xmlhttp.readyState == 4) {
        		     resposta = unescape(xmlhttp.responseText);
                     div.innerHTML = resposta;
                  }
		  }
 	      xmlhttp.send(param);
	},
<?php if (isset($_SESSION["user"])) { ?>
    adv : function(idms,acao) {
          idp = document.getElementById("msg-pos-"+idms);
          idn = document.getElementById("msg-neg-"+idms);
          idf = document.getElementById("msg-fav-"+idms);
          if (acao == 'favor' || (acao == 'posit' && idp.className == 'ico-pos') || (acao == 'negat' && idn.className == 'ico-neg')) {
        	  param = "idms="+idms+"&acao="+acao;
     	      xmlhttp.open('POST', 'php/ajax.php', true);
     	      xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        	  xmlhttp.onreadystatechange = function() {
                  if (xmlhttp.readyState == 4) {
                      resposta = unescape(xmlhttp.responseText);
                      if (resposta != 'erro') {
                          if (acao == 'posit') {
                              idp.className = 'ico-pos-select';
                              idn.className = 'ico-neg';
                          }else if (acao == 'negat') {
                              idp.className = 'ico-pos';
                              idn.className = 'ico-neg-select';
                          }else if (acao == 'favor') {
                              if (idf.className == 'ico-fav-select') {
                                  idf.className  = 'ico-fav';
                              }else{
                                  idf.className  = 'ico-fav-select';
                              }
                          }
                          valor = resposta.split("|");
                          if (valor[0] == parseInt(valor[0])) { idp.innerHTML = valor[0]; }
                          if (valor[1] == parseInt(valor[1])) { idn.innerHTML = valor[1]; }
                          if (valor[2] == parseInt(valor[2])) { idf.innerHTML = valor[2]; }
                      }
                  }
    		  }
     	      xmlhttp.send(param);
        }
    },
    <?php if ($_SESSION["user"]['admin'] > 0) { ?>
	lbr : function(idms,cmd){
          div = document.getElementById(idms);
          ldv = document.getElementById("msg-lista");
          msg = document.getElementById("msg-"+idms);
          aut = document.getElementById("aut-"+idms);
          if (cmd == 'oku') {
              param = "oku="+idms;
   	          msg.className = 'body';
              aut.className = 'auto';
              document.getElementById("oku-"+idms).style.display = 'none';
          }else{
              param = "lbr="+idms;
              msg.className = 'body-ok';
              aut.className = 'auto-ok';
              document.getElementById("lbr-"+idms).style.display = 'none';
              if (cmd == 'lbr1') { cmd = 'admp'; setInterval("ldv.removeChild(div)",800); }
	      }
	      xmlhttp.open('POST', 'php/ajax.php', true);
	      xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	      xmlhttp.send(param);
          ajax.upValor(cmd);
	},
	itm : function(sub,tema,div){
	      if (confirm("Alterar Categoria?")) {
        	  param = "tema="+tema+"&sub="+sub;
     	      xmlhttp.open('POST', 'php/ajax.php', true);
     	      xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
     	      xmlhttp.send(param);
 	      }
	},
	uptm : function(idms,idtm){
    	   param = "idms="+idms+"&idtm="+idtm;
 	       xmlhttp.open('POST', 'php/ajax.php', true);
 	       xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            	xmlhttp.onreadystatechange = function() {
                     if(xmlhttp.readyState == 4) {
                		resposta = unescape(xmlhttp.responseText);
                		if (resposta == 'erro'){
                		    alert('Erro ao Alterar - tente Novamente');
                		}
                     }
        		}
 	       xmlhttp.send(param);
	},
	isub : function(idsb){
           document.getElementById("isub-"+idsb).style.display = 'none';
           msg = document.getElementById("ms-sb-"+idsb);
           aut = document.getElementById("us-sb-"+idsb);
           msg.className = 'resp';
           aut.className = 'info';
           param = "isub="+idsb;
   	       xmlhttp.open('POST', 'php/ajax.php', true);
   	       xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
   	       xmlhttp.send(param);
	},
	edit : function(id,div,type){
            html  = escape(div.innerHTML);
            if (html != 'erro') {
            	 param = "edit="+html+"&id="+id+"&type="+type;
         	 xmlhttp.open('POST', 'php/ajax.php', true);
         	 xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            	 xmlhttp.onreadystatechange = function() {
                     if(xmlhttp.readyState == 4) {
                		resposta = unescape(xmlhttp.responseText);
                        div.innerHTML = resposta;
                     }
        		}
         	    xmlhttp.send(param);
     	    }
    },
    <?php } ?>
	xam : function(id_am,div,cmd,echo){
	      if (echo == 1){
              d = div;
              n = document.getElementById(cmd+"1");
              l = document.getElementById("amg-lista");
              l.removeChild(d);
              n.innerHTML = n.innerHTML-1;
              param = "xam="+id_am;
         	  xmlhttp.open('POST', 'php/ajax.php', true);
              xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
              xmlhttp.onreadystatechange = function() {
                      if(xmlhttp.readyState == 4) {
                	     resposta = unescape(xmlhttp.responseText);
                		 if (resposta != 'erro') {
                             if ((l.innerHTML.length < 200) && (n1.innerHTML > 1)) {
                                  window.location.reload();
                             }
                         }
                      }
              }
       	      xmlhttp.send(param);
              document.getElementById(cmd+"2").innerHTML = n.innerHTML;
              document.getElementById(cmd+"3").innerHTML = n.innerHTML;
     	  }else{
              param = "xam="+id_am;
         	  xmlhttp.open('POST', 'php/ajax.php', true);
         	  xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
              xmlhttp.onreadystatechange = function() {
                      if(xmlhttp.readyState == 4) {
                	 	 resposta = unescape(xmlhttp.responseText);
                	 	 div.innerHTML = resposta;
                      }
        	  }
         	  xmlhttp.send(param);
     	  }
	},
	xms : function(idms,cmd){
	      if (confirm("Tem certeza que deseja apagar est� pergunta?")) {
              div = document.getElementById(idms);
              ldv = document.getElementById("msg-lista");
              msg = document.getElementById("msg-"+idms);
   	          aut = document.getElementById("aut-"+idms);
              msg.className = 'body-alert';
              aut.className = 'auto-alert';
              setInterval("ldv.removeChild(div)",800);
              document.getElementById("xms-"+idms).style.display = 'none';
              if (cmd == 'rp') { param = "xsb="+idms; }else{ param = "xms="+idms; }
    	      xmlhttp.open('POST', 'php/ajax.php', true);
    	      xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
              xmlhttp.onreadystatechange = function() {
                    if(xmlhttp.readyState == 4) {
            		   resposta = unescape(xmlhttp.responseText);
            		   if (resposta != 'erro' && ldv.innerHTML.length < 200) { window.location.reload(); }
                    }
              }
    	      xmlhttp.send(param);
              ajax.upValor(cmd);
           }
	},
	xsb : function(idms,idsb,cmd){
	      if (confirm("Tem certeza que deseja apagar est� resposta?")) {
              if (cmd == 'pr'){
                  ajax.close("xms-"+idsb);
                  div = document.getElementById(idsb);
                  ldv = document.getElementById("msg-lista");
                  msg = document.getElementById("msg-"+idsb);
       	          aut = document.getElementById("aut-"+idsb);
                  msg.className = 'body-alert';
                  aut.className = 'auto-alert';
              }else{
                  ajax.close("xsb-"+idsb);
                  div = document.getElementById("sub-msg-"+idsb);
                  ldv = document.getElementById("lista-sub-"+idms);
                  msg = document.getElementById("ms-sb-"+idsb);
                  aut = document.getElementById("us-sb-"+idsb);
                  msg.style.background = "#FFDDDD";
                  aut.style.background = "url(css/point-3.png) 100% 18px no-repeat";
              }
              setInterval("ldv.removeChild(div)",800);
	          param = "xsb="+idsb;
	          xmlhttp.open('POST', 'php/ajax.php', true);
	          xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
              xmlhttp.onreadystatechange = function() {
                   if(xmlhttp.readyState == 4) {
             		  resposta = unescape(xmlhttp.responseText);
            		  if (resposta != 'erro') {
                          if ((ldv.innerHTML.length < 200) && (num.innerHTML != 0)) {  window.location.reload(); }
                      }
                   }
              }
	          xmlhttp.send(param);
           }
	},
<?php } ?>
    row : function(txr) {
          txr.style.height = txr.scrollHeight+'px';
    },

    pal : function() {
             var txt = '';
             
             if (window.getSelection) {
                  txt = window.getSelection();
            } else if (document.getSelection) {
                  txt = document.getSelection();
            } else if (document.selection) {
                  txt = document.selection.createRange().text;
            }else return;

            return txt;
    },

    buscar : function(url,pal) {
                if (pal == "" || url == "")  return;
                document.location.href = "buscar.do?id=" + url + "&q=" + pal;
    }

}
