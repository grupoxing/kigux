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
var upbg = false;
var ajax = {
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
	lbr : function(idms){
          param = "oku="+idms;
          document.getElementById("oku-"+idms).style.display = 'none';
	      xmlhttp.open('POST', 'php/ajax.php', true);
	      xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	      xmlhttp.send(param);
	},
	xms : function(idms){
	      if (confirm("Tem certeza que deseja apagar está pergunta?")) {
              div = document.getElementById(idms);
              ldv = document.getElementById("msg-lista");
              ldv.removeChild(div);
              param = "xms="+idms;
    	      xmlhttp.open('POST', 'php/ajax.php', true);
    	      xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
              xmlhttp.onreadystatechange = function() {
                      if(xmlhttp.readyState == 4) {
            		     resposta = unescape(xmlhttp.responseText);
            		     if (resposta != 'erro' && ldv.innerHTML.length < 200) { window.location.reload(); }
                      }
              }
    	      xmlhttp.send(param);
          }
	},
	xsb : function(idms,idsb){
	      if (confirm("Tem certeza que deseja apagar está resposta?")) {
              div = document.getElementById("sub-msg-"+idsb);
              ldv = document.getElementById("lista-sub-"+idms);
              ldv.removeChild(div);
	          param = "xsb="+idsb;
	          xmlhttp.open('POST', 'php/ajax.php', true);
	          xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
              xmlhttp.onreadystatechange = function() {
                      if(xmlhttp.readyState == 4) {
             		     resposta = unescape(xmlhttp.responseText);
            		     if (resposta != 'erro' && ldv.innerHTML.length < 200) {  window.location.reload(); }
                      }
              }
	          xmlhttp.send(param);
           }
	}
}
