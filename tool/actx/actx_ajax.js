var actx = {
    upp : function() {
          if (iajax.aviso('actx_obs','Carregando Historico...')) {
              inic = document.getElementById('actx_view').getElementsByTagName('div')[0].id;
              idpg = document.getElementById("actx_input_idpg").value;
              iajax.novo('/tool/actx/actx_ajax.php', "idpg="+idpg+"&ini="+inic, "actx_view", "actx_obs", "pre");
          }
          setTimeout('actx.upp();',10000);
    },
    dow : function() {
          if (iajax.aviso('actx_obs','Carregando Historico...')) {

              botao = document.getElementById('actx_submit');
              document.getElementById('actx_view').removeChild(botao);

              var idpg = document.getElementById("actx_input_idpg").value;
              var divs = document.getElementById('actx_view').getElementsByTagName('div');
              var numd = divs.length;

              for (var i = numd; i > 0; i--) {
                   if (divs[i-1].className == 'actx_msg' || divs[i-1].className == 'actx_msg upp'){
                       var fim = divs[i-1].id;
                       break;
                   }
              }

              actx.cor();            
              iajax.novo('/tool/actx/actx_ajax.php', "idpg="+idpg+"&fim="+fim, "actx_view", "actx_obs", "pos");
          }
    },
    cor : function() {

          var divs = document.getElementById('actx_view').getElementsByTagName('div');
          var numd = divs.length-1;

          for (var i = 0; i < numd; i++) {
               if (divs[i].className == 'actx_msg upp'){
                   divs[i].className =  'actx_msg';
               }
          }
    },
    del : function(idms) {
          if (iajax.aviso('actx_obs','Deletando Atividade...')) {
              document.getElementById(idms+"_actx_msg").style.background = '#ffcac1';
              idpg = document.getElementById("actx_input_idpg").value;
              iajax.novo('/tool/actx/actx_ajax.php', "xmsg="+idms+"&idpg="+idpg, "actx_view", "actx_obs");
          }
    }
}