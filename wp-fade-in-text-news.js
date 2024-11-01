/**
 *     WP fade in text news
 *     Copyright (C) 2011 - 2023 www.gopiplus.com
 *     http://www.gopiplus.com/work/2011/04/22/wordpress-plugin-wp-fadein-text-news/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

var FadeIn_FadeInterval;

window.onload = FadeIn_FadeRotate

var FadeIn_Links;
var FadeIn_Titles;
var FadeIn_Cursor = 0;
var FadeIn_Max;


function FadeIn_FadeRotate() 
{
  FadeIn_FadeInterval = setInterval(FadeIn_Ontimer, 10);
  FadeIn_Links = new Array();
  FadeIn_Titles = new Array();
  FadeIn_SetFadeLinks();
  FadeIn_Max = FadeIn_Links.length-1;
  FadeIn_SetFadeLink();
}

function FadeIn_SetFadeLink() {
  var ilink = document.getElementById("FadeIn_Link");
  ilink.innerHTML = FadeIn_Titles[FadeIn_Cursor];
  ilink.href = FadeIn_Links[FadeIn_Cursor];
}

function FadeIn_Ontimer() {
  if (FadeIn_bFadeOutt) {
    FadeIn_Fade+=FadeIn_FadeStep;
    if (FadeIn_Fade>FadeIn_FadeOut) {
      FadeIn_Cursor++;
      if (FadeIn_Cursor>FadeIn_Max)
        FadeIn_Cursor=0;
      FadeIn_SetFadeLink();
      FadeIn_bFadeOutt = false;
    }
  } else {
    FadeIn_Fade-=FadeIn_FadeStep;
    if (FadeIn_Fade<FadeIn_FadeIn) {
      clearInterval(FadeIn_FadeInterval);
      setTimeout(Faderesume, FadeIn_FadeWait);
      FadeIn_bFadeOutt=true;
    }
  }
  var ilink = document.getElementById("FadeIn_Link");
  if ((FadeIn_Fade<FadeIn_FadeOut)&&(FadeIn_Fade>FadeIn_FadeIn))
    ilink.style.color = "#" + ToHex(FadeIn_Fade);
}

function Faderesume() {
  FadeIn_FadeInterval = setInterval(FadeIn_Ontimer, 10);
}

function ToHex(strValue) {
  try {
    var result= (parseInt(strValue).toString(16));

    while (result.length !=2)
            result= ("0" +result);
    result = result + result + result;
	//alert(result);
    return result.toUpperCase();
  }
  catch(e)
  {
  }
}