/*******************************************************************************
 *                                                                             *
 * @author     James S. de Aguiar <falecom@ocriador.com>                       *
 * @copyright  Desenvolvimento de Sistemas Web, Inc. http://ocriador.com.br/   *
 * @version    oCriador JS - v2.2 - 24/05/2012 ás 14:25                        *
 * @license    oCriador License (http://ocriador.com.br/license.php)           *
 *                                                                             *
 ******************************************************************************/

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

// Ajax Assincrona
var httpx = new XMLHTTP();
var order = [];
var posic = 0;

// Move Janela
var posx = 0;
var posy = 0;
var difx = 0;
var dify = 0;
var movx = true;
var movy = true;
var mobj = false;

var oCriador = {
    // ------------------ Enviar dados com Ajax Assincrona ------------------ //
    ajax : function(url, param, idhtm, idobs, addhtml, call){
           order[order.length] = [url, param, idhtm, idobs, addhtml, call];
           if ((posic + 1) == order.length){ oCriador.exec(); }
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
                          dobs.innerHTML = 'Erro: Tente Novamente!';
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
                          idhtml.innerHTML = unescape(httpx.responseText);
                      }
                  }
                  if (order[posic][5]) {
                      setTimeout(order[posic][5],10);
                  }
                  oCriador.next();
              }
           }
           httpx.send(order[posic][1]);
    },
    next : function(){
           posic++;
           if (posic < order.length){
               setTimeout("oCriador.exec()", 20);
           }
    },    
    // ---------------------- Ocultar Texto Temporario ---------------------- //
    oftx : function (id1, info1) {
           info1 = (info1) ? info1 : 'digite aqui!';
           item1 = document.getElementById(id1);
           if (oCriador.getx(item1) == info1) {
               oCriador.setx(item1, "");
           }
           item1.style.color = '#000';
    },
    ontx : function (id2, info2) {
           info2 = (info2) ? info2 : 'digite aqui!';
           item2 = document.getElementById(id2);
           if(oCriador.getx(item2) == info2) { 
              item2.style.color='#B0B0B0'; 
           }else if(oCriador.getx(item2) == '') { 
              oCriador.setx(item2, info2); 
              item2.style.color='#B0B0B0'; 
           }else if(oCriador.getx(item2) != info2) { 
              item2.style.color='#000';
           }   
    },
    // ----------------- Limitador e Contador de Caracteres ----------------- //
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
    // ---------------- Set ou Get um Valor no Obj qualquer ----------------- //
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
    // -------------------------- Alterar Display --------------------------- //
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
    // ------------------------- Limpar Divs do Obj ------------------------- //
    divzh : function (obj) {
            if (document.getElementById(obj)){
                obj = document.getElementById(obj);
            }
            var divs = obj.getElementsByTagName('div');
            for (var i = 0; i < divs.length; i++) {
                 divs[i].innerHTML = '';
            }
    },
    // ----------------- Copiar Conteudo de Obj1 para Obj2 ------------------ //
    chtml : function(id1, id2) {
            if (document.getElementById(id1)) {
                if (document.getElementById(id2)){
                    document.getElementById(id1).innerHTML = document.getElementById(id2).innerHTML;
                    return true;
                }else{
                    return document.getElementById(id1).innerHTML;
                }
            }
            return false;
    },
    // --------------- Adicionar ou Substituir html na tag[id] -------------- //
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
    // ------------- Adicionar ou Substituir valor no campo[id] ------------- //
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
    // -------------- Aviso de Carregamento de uma Solicitacao -------------- //
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
    // --------------------- Alterar height da textarea --------------------- //
    row : function(obj) {
          obj.style.height = obj.scrollHeight+'px';
    },   
    // ---------------------- Alterar Opacidade do Obj ---------------------- //
    opct : function(obj, value) {
           if (obj) {
               obj.style.opacity = value/10;
               obj.style.filter = 'alpha(opacity=' + value*10 + ')';
           }
    },
    // ---------------------------- Mover Janela ---------------------------- //
    move : function(o, x, y){

           mobj = o ? o : false;
           movx = x ? x : true;
           movy = y ? y : true;
        
           if (movx) { difx = posx - parseInt(mobj.style.left); }
           if (movy) { dify = posy - parseInt(mobj.style.bottom); }
           
           document.onmousemove =  oCriador.mbox;
          
    },
    mbox : function(e){
          if (document.all){
              if (movx) { posx =  event.clientX; }
              if (movy) { posy = -event.clientY; }
          } else {
              if (movx) { posx =  e.clientX; }
              if (movy) { posy = -e.clientY; }
          }
          if (mobj){
              if (movx) { mobj.style.left   = (posx) - difx +'px'; }
              if (movy) { mobj.style.bottom = (posy) - dify +'px'; }
              oCriador.opct(mobj, 8);
          }
    },    
    mfim : function() {
          oCriador.opct(mobj, 10);
          mobj = false;
    } 
}

document.onmousemove = oCriador.mbox;
document.onmouseup   = new Function("oCriador.mfim();");