/* This script and many more are available free online at
The JavaScript Source!! http://javascript.internet.com
Created by: Lee Underwood | http://javascript.internet.com/ */

function getFocus() {
  // place the id below of the field you want to have focus upon page load
  var focusHere = document.getElementById("memnum");
  focusHere = focusHere.focus();
}

// Multiple onload function created by: Simon Willison
// http://simonwillison.net/2004/May/26/addLoadEvent/
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
  getFocus();
});


