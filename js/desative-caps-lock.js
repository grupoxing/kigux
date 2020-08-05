/****************************************************************************************
     Script to detect if Caps Lock is engaged when a key is pressed in a text input
                   v2.0.0 written by Mark Wilton-Jones, 1/11/2003
   Updated 19/02/2004 to run the function when caps lock is not engaged - see comments
*****************************************************************************************

Please see http://www.howtocreate.co.uk/jslibs/ for details and a demo of this script
Please see http://www.howtocreate.co.uk/jslibs/termsOfUse.html for terms of use

JavaScript offers no way to check the state of the Caps Lock, so this script checks the
letters pressed and compares them with the SHIFT key state

To use this, insert the following into the head of your document:

<script type="text/javascript"><!--

	//you must either define capsError as a function or a message to be alerted

	//this will make the script alert a message if Caps Lock is engaged
	var capsError = 'WARNING:\n\nCaps Lock is enabled\n\nThis field is case sensitive';

	//this will make the script run a function and pass it a parameter saying if Caps Lock is engaged
	//function format NOT compatible with v1 - it will now be run even if Caps Lock is not enabled
	function capsError( capsEngaged ) {
		if( capsEngaged ) {
			//do something to warn the user that caps lock is engaged
		} else {
			//remove any warnings that caps lock is engaged
		}
	}

//--></script>
<script src="PATH TO SCRIPT/capsDetect.js" type="text/javascript"></script>

I recommend using a function to make a DHTML warning, as the alert will be shown with
every key press (the letter appears after the alert is closed), and the user may want
Caps Lock to be engaged

Every input where you want to detect Caps Lock should contain the following attribute:
onkeypress="capsDetect(arguments[0]);"

This script was inspired by a script by Wae Technologies, but was written completely
independently to provide better browser compatibility
http://concepts.waetech.com/
_______________________________________________________________________________________*/

function capsDetect( e ) {
	if( !e ) { e = window.event; } if( !e ) { MWJ_say_Caps( false ); return; }
	//what (case sensitive in good browsers) key was pressed
	var theKey = e.which ? e.which : ( e.keyCode ? e.keyCode : ( e.charCode ? e.charCode : 0 ) );
	//was the shift key was pressed
	var theShift = e.shiftKey || ( e.modifiers && ( e.modifiers & 4 ) ); //bitWise AND
	//if upper case, check if shift is not pressed. if lower case, check if shift is pressed
	MWJ_say_Caps( ( theKey > 64 && theKey < 91 && !theShift ) || ( theKey > 96 && theKey < 123 && theShift ) );
}
function MWJ_say_Caps( oC ) {
	if( typeof( capsError ) == 'string' ) { if( oC ) { alert( capsError ); } } else { capsError( oC ); }
}


