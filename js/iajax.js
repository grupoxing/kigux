function XMLHTTP(){
         var request = null;
         try{
             request = new XMLHttpRequest();
         }catch(ee){
             try{
                 request = new ActiveXObject("Msxml2.XMLHTTP");
             }catch(e){
                 try{
                     request = new ActiveXObject("Microsoft.XMLHTTP");
                 }catch(E){
                     request = false;
                 }
             }
         }
         return request;
}
var httpx = new XMLHTTP();
var order = [];
var posic = 0;
var iajax = {
    oftx : function (id1, info1) {
           info1 = (info1) ? info1 : 'digite aqui!';
           item1 = document.getElementById(id1);
           if (iajax.getx(item1) == info1) {
               iajax.setx(item1, "");
           }
           item1.style.color = '#000';
    },
    ontx : function (id2, info2) {
           info2 = (info2) ? info2 : 'digite aqui!';
           item2 = document.getElementById(id2);
           if(iajax.getx(item2) == info2) { 
              item2.style.color='#B0B0B0'; 
           }else if(iajax.getx(item2) == '') { 
              iajax.setx(item2, info2); 
              item2.style.color='#B0B0B0'; 
           }else if(iajax.getx(item2) != info2) { 
              item2.style.color='#000';
           }   
    },
    nutx : function (id_area, id_num, max){
           input = document.getElementById(id_area);
           texto = input.value;
           total = texto.length;
           if (total <= max){
               resto = max-total;
               document.getElementById(id_num).innerHTML = resto;
           }else{
               input.value     = texto.substr(0,max);
               input.scrollTop = input.scrollHeight;
           }
    },     
    setx : function (obj, texto) {
           if (obj.tagName == 'INPUT' || obj.tagName == 'TEXTAREA') {
               obj.value = texto;
           }else{
               obj.innerHTML = texto;
           }
    },
    getx : function (obj) {
           if (obj.tagName == 'INPUT' || obj.tagName == 'TEXTAREA') {
               return obj.value;
           }else{
               return obj.innerHTML;
           }
    },    
    row : function(obj) {
          str_1 = new String(obj.value);
          str_2 = str_1.split("\n");
          obj.style.height = (str_2.length*14)+'px';
    },     
    divon : function (obj) {
            if (document.getElementById(obj)){
                document.getElementById(obj).style.display = 'block';
            }else if (obj.id){
                obj.style.display = 'block';
            }
    },
    divof : function (obj) {
            if (document.getElementById(obj)){
                document.getElementById(obj).style.display = 'none';
            }else if (obj.id){
                obj.style.display = 'none';
            }
    },
    divnf : function (obj) {
            if (document.getElementById(obj)){
                obj = document.getElementById(obj);
            }
            if (obj.id){
                if (obj.style.display == 'none') {
                    obj.style.display = 'block';
                }else{
                    obj.style.display = 'none';
                }
            }
    },
    divzh : function (obj) {
            if (document.getElementById(obj)){
                obj = document.getElementById(obj);
            }
            var divs = obj.getElementsByTagName('div');
            for (var i = 0; i < divs.length; i++) {
                 divs[i].innerHTML = '';
            }
    },
    chtml : function(id1, id2) {
            if (document.getElementById(id1)) {
                if (document.getElementById(id2)){
                    document.getElementById(id1).innerHTML = document.getElementById(id2).innerHTML;
                }else{
                    return document.getElementById(id1).innerHTML;
                }
            }
    },
    ihtml : function(id, html, add) {
            if (document.getElementById(id)) {
                htmlID  = document.getElementById(id);
                if (add == 'pre') {
                    htmlID.innerHTML  = html+htmlID.innerHTML;
                }else if (add == 'pos'){
                    htmlID.innerHTML += html;
                }else{
                    htmlID.innerHTML  = html;
                }
            }
    },
    ivalue : function(id, txt, add) {
            if (document.getElementById(id)) {
                inputID = document.getElementById(id);
                if (add == 'pre') {
                    inputID.value  = txt+inputID.value;
                }else if (add == 'pos'){
                    inputID.value += txt;
                }else{
                    inputID.value  = txt;
                }
            }
    },
    aviso : function(div, html) {
            var obs = document.getElementById(div);
            if (obs.style.display == 'none') {
                obs.style.display = 'block';
                obs.innerHTML  = (html == 1) ? 'Carregando...' : html;
                return true;
            }else{
                setTimeout("obs.style.display = 'none'",10000);
                obs.innerHTML = "Aguarde um Momento...";
                return false;
            }
    },
    ifile : function (fileurl, filetype){
            if (filetype == "js"){
                var fileref = document.createElement('script');
                fileref.setAttribute("type","text/javascript");
                fileref.setAttribute("src", fileurl);
            }
            else if (filetype == "css"){
                var fileref = document.createElement("link");
                fileref.setAttribute("rel", "stylesheet");
                fileref.setAttribute("type", "text/css");
                fileref.setAttribute("href", fileurl);
            }
            if (fileref != "undefined") { document.getElementsByTagName("head")[0].appendChild(fileref); }
    },
    novo : function(url, param, idhtm, idobs, addhtml, call){
           order[order.length] = [url, param, idhtm, idobs, addhtml, call];
           if ((posic + 1) == order.length){ iajax.exec(); }
    },
    exec : function(){
           httpx.open('POST', order[posic][0], true);
           httpx.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
           httpx.onreadystatechange = function() {
                  if (httpx.readyState == 4) {
                      
                      html = unescape(httpx.responseText);
                      
                      if (order[posic][3]) {
                          dobs = (document.getElementById(order[posic][3])) ? document.getElementById(order[posic][3]) : order[posic][3];
                          if (html == 'erro') {
                              dobs.innerHTML     = 'Erro: Tente Novamente!';
                              setTimeout("dobs.style.display = 'none';",1500);
                          }else{
                              dobs.style.display = 'none';
                          }
                      }
                      
                      if (order[posic][2] && html != 'erro') {
                          idhtml = (document.getElementById(order[posic][2])) ? document.getElementById(order[posic][2]) : order[posic][2];
                          if (order[posic][4]){
                              if (order[posic][4] == "pre"){
                                  idhtml.innerHTML  = unescape(httpx.responseText)+idhtml.innerHTML;
                              }else{
                                  idhtml.innerHTML += unescape(httpx.responseText);
                              }
                          }else{
                              idhtml.innerHTML  = unescape(httpx.responseText);
                          }
                      }

                      if (order[posic][5]) {
                          setTimeout(order[posic][5],10);
                      }

                      iajax.next();
                  }
           }
           httpx.send(order[posic][1]);
    },
    next : function(){
           posic++;
           if (posic < order.length){
               setTimeout("iajax.exec()", 20);
           }
    }
}
