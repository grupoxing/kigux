var amigo = {
    addam : function(id_am, obj){
      	  param = "iam="+id_am;
   	  xmlhttp.open('POST', '/tool/iamg/iamg_ajax.php', true);
    	  xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      	  xmlhttp.onreadystatechange = function() {
              if (xmlhttp.readyState == 4) {
                  obj.innerHTML = unescape(xmlhttp.responseText);
              }
          }
          xmlhttp.send(param);
    },    
    delam : function(id_am,div,cmd,echo){
          if (echo == 1){
              document.getElementById("amg-lista").removeChild(div);
              param = "xam="+id_am;
              xmlhttp.open('POST', '/tool/iamg/iamg_ajax.php', true);
              xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
              xmlhttp.onreadystatechange = function() {
                      if(xmlhttp.readyState == 4) {
                         resposta = unescape(xmlhttp.responseText);
                         //if (resposta != 'erro') {
                         //    if ((l.innerHTML.length < 200) && (n1.innerHTML > 1)) {
                         //         window.location.reload();
                         //    }
                         //}
                      }
              }
              xmlhttp.send(param);
          }else{
              param = "xam="+id_am;
              xmlhttp.open('POST', '/tool/iamg/iamg_ajax.php', true);
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
    vkey : function(event, call){

           var ikey = 0;

           if (window.event) {
               ikey = event.keyCode;
           } else {
               ikey = event.which;
           }

           if (ikey == 13) {
               iamg.call(call);
           } 
    },
    call : function(call){
           email = document.getElementById('call_email_'+call).value;
           idrop.open('/tool/iamg/iamg_ajax.php?email='+email);
    },   
    seg : function(id,div,acao){
          if (acao == 1) { param = "isg="+id; }else{ param = "xsg="+id; }
          xmlhttp.open('POST', '/tool/iamg/iamg_ajax.php', true);
          xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
          xmlhttp.onreadystatechange = function() {
              if (xmlhttp.readyState == 4) {
                  resposta = unescape(xmlhttp.responseText);
                  div.innerHTML = resposta;
              }
          }
          xmlhttp.send(param);
    }, 
    itm : function(sub,tema,div){
          if (confirm("Alterar Categoria?")) {
              param = "tema="+tema+"&sub="+sub;
              xmlhttp.open('POST', '/tool/iamg/iamg_ajax.php', true);
              xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
              xmlhttp.send(param);
          }
    }
}

function toggleAll(element){
         var form = document.forms.openinviter, z = 0;
         for (z=0; z<form.length;z++){
              if (form[z].type == 'checkbox'){
                  form[z].checked = element.checked;
              }
         }
}