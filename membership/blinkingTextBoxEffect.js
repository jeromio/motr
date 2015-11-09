/* This script and many more are available free online at
The JavaScript Source!! http://javascript.internet.com
Created by: mrhoo | http://www.webdeveloper.com/forum/showpost.php?p=761000&postcount=40 */
window.Blink = function(args){
  // Set the color and seconds below, e.g., [args,'COLOR',SECONDS]
 	args = (/,/.test(args))?  args.split(/,/):  [args,'#FFD100',10];
 	var who = document.getElementById(args[0]);
 	var count = parseInt(args[2]);
 	if (--count <=0) {
  		who.style.backgroundColor = '';
  		if(who.focus) who.focus();
 	} else {
  		args[2]=count+'';
  		who.style.backgroundColor=(count%2==0)? '': args[1];
 	 	args='\"'+args.join(',')+'\"';
  		setTimeout("Blink("+args+")",500);
 	}
}

// Multiple onload function created by: Simon Willison
// http://simon.incutio.com/archive/2004/05/26/addLoadEvent

function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      if (oldonload) {
        oldonload();
      }
      func();
    }
  }
}

addLoadEvent(function() {
  // Set the field name below of where to place the focus and blinking color
  Blink('memnum');
});
