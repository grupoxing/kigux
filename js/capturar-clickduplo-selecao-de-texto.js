/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function getSel() {
    var txt = '';
    if (window.getSelection) {
        txt = window.getSelection();
    }
    else if (document.getSelection) {
        txt = document.getSelection();
    }
    else if (document.selection) {
        rng = document.selection.createRange();
        txt = rng.text;

        rng.moveStart('word', -2);
        if (rng.text.indexOf("-") > 0)
            txt = rng.text;
        else {
            rng = document.selection.createRange();
            rng.moveEnd('word', 2);
            if (rng.text.indexOf("-") > 0)
                txt = rng.text;
        }
        txt = trim(txt);
    }
    else return;

    return txt;
}

function DefinePalavra(pal) {
    if (pal == "")
        return;
        document.location.href = "buscar.de?q=" + pal;
}

//exemplo: DefinePalavra(getSel());