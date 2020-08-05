try{
    xmlhttp = new XMLHttpRequest();
}catch(ee){
    try{
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    }catch(e){
        try{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }catch(E){
            xmlhttp = false;
        }
    }
}

var ajax = {
	iGrupo : function(id_gp,div){
    	param = "iGrupo="+id_gp;
 	    xmlhttp.open('POST', '/php/ajax-groups.php', true);
 	    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    	xmlhttp.onreadystatechange = function() {
             if(xmlhttp.readyState == 4) {
        		resposta = unescape(xmlhttp.responseText);
                document.getElementById(div).innerHTML = resposta;
             }
		}
 	    xmlhttp.send(param);
	},
	xGrupo : function(id_gp,div){
	    if(confirm("Tem certeza que deseja remover esse Membro?")) {
           param = "xGrupo="+id_gp;
     	   xmlhttp.open('POST', '/php/ajax-groups.php', true);
     	   xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
           xmlhttp.onreadystatechange = function() {
                 if(xmlhttp.readyState == 4) {
            		resposta = unescape(xmlhttp.responseText);
            	 	document.getElementById(div).innerHTML = resposta;
                 }
    	   }
     	   xmlhttp.send(param);
        }
	},
    forHtml : function(div,array,resposta){
	    if(document.getElementById('dMembro')[array] == true) {
	       document.getElementById('dMembro')[array].innerHTML = resposta;
	       ajax.forHtml(div,array+1,resposta);
		}
	},
	xMsg : function(idms,idgp){
   	    div = document.getElementById(idms);
   	    num = document.getElementById("numC");
   	    msg = document.getElementById("msg-"+idms);
   	    aut = document.getElementById("aut-"+idms);
        ldv = document.getElementById("msg-lista");
   	    msg.style.background = "#FFDDDD";
        aut.style.background = "url(/css/point-3.png) 100% 18px no-repeat";
	    if(confirm("Tem certeza que deseja apagar?")) {
           setInterval("ldv.removeChild(div)",800);
    	   param = "idms="+idms+"&idgp="+idgp;
    	   xmlhttp.open('POST', '/php/ajax-groups.php', true);
    	   num.innerHTML = num.innerHTML-1;
    	   xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
           xmlhttp.onreadystatechange = function() {
                   if(xmlhttp.readyState == 4) {
            		  resposta = unescape(xmlhttp.responseText);
            		  if (resposta != 'erro') {
                          if ((ldv.innerHTML.length < 200) && (num.innerHTML != 0)) {
                              window.location.reload();
                          }
                      }
                   }
    	   }
    	   xmlhttp.send(param);
		}else{
		   msg.style.background = "#EDEFF0";
		   aut.style.background = "url(/css/point.png) 100% 18px no-repeat";
		}
	},
	xLista : function(id_gp,div){
        d  = document.getElementById(div);
        l  = document.getElementById("amg-lista");
        n1 = document.getElementById("num1");
        n2 = document.getElementById("num2");
        d.style.background = "#FFDDDD";
	    if(confirm("Tem certeza que deseja remover esse Membro?")) {
           l.removeChild(d);
           param = "xGrupo="+id_gp;
     	   xmlhttp.open('POST', '/php/ajax-groups.php', true);
     	   n1.innerHTML = n1.innerHTML-1;
           n2.innerHTML = n1.innerHTML;
     	   xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
           xmlhttp.onreadystatechange = function() {
                   if(xmlhttp.readyState == 4) {
            		  resposta = unescape(xmlhttp.responseText);
            		  if (resposta != 'erro') {
                          if ((l.innerHTML.length < 200) && (n1.innerHTML != 0)) {
                              window.location.reload();
                          }
                      }
                   }
    	   }
     	   xmlhttp.send(param);
		}else{
		   d.style.background = "#f4f4f4";
		}
	},
	xFala : function(){
	    if(confirm("Tem certeza que deseja fechar esse janela?")) {
           param = "closeFala=0";
     	   xmlhttp.open('POST', '/php/ajax-groups.php', true);
     	   xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
     	   xmlhttp.send(param);
     	   setInterval("history.go(0)",1000)
		}
	}
}
