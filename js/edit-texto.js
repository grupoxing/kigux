<HTML>
<HEAD>
 <TITLE>Novo Documento</TITLE>
</HEAD>
<BODY>
  <HTML>
<HEAD>
<TITLE> </TITLE>
<script language=javascript>
function SelText(){
// pegando o texto selecionado
var meuTexto = document.selection.createRange().text;

//texto original
var textoOriginal = document.testef.lala.value;

//novo texto
var meuNTexto = "";

var textoFormatado = "";
var meuNTextoI = "";
var meuNTextoF = "";

// verifica se tem algo selecionado
if (meuTexto.length == 0){
alert ("Selecione algo");
}else{

//aplica a formatacao escolhida

//negrito
if (document.testef.fNegrito.checked) {
meuNTextoI = "<b>";
meuNTextoF = "</b>";
}
//itálico
if (document.testef.fItalico.checked) {
meuNTextoI += "<i>";
meuNTextoF += "</i>";
}

//sublinhado
if (document.testef.fSublinhado.checked) {
meuNTextoI += "<u>";
meuNTextoF += "</u>";
}

//texto final
meuNTexto = meuNTextoI + meuTexto + meuNTextoF;

//substitui o texto antigo com o novo, formatado
textoFormatado = (textoOriginal.replace(meuTexto, meuNTexto));
document.testef.lala.value = textoFormatado;

}
Visualizar(textoFormatado);
}

//insere no div o texto formatado para visualização HTML
function Visualizar(fTexto){
visual.innerHTML = fTexto;
}


</script>

</HEAD>

<BODY>
<form name=testef>
Selecione o estilo: <P>
<input type=checkbox name=fNegrito value="s"> Negrito<P>
<input type=checkbox name=fItalico value="s"> Itálico<P>
<input type=checkbox name=fSublinhado value="s"> Sublinhado<P>

Insira seu texto:<textarea name=lala> </textarea>
  <P>
<P>
<input type=button onclick=SelText(); value=Visualizar>
</form>
Texto formatado:<div id=visual border=2>

</div>
</BODY>
</HTML>
</BODY>
</HTML>
