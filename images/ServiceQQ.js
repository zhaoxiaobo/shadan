document.writeln("<div class=\"qqbox\" id=\"divQQbox\">");
document.writeln("");
document.writeln("  <div class=\"qqkf\"  id=\"contentid\" onMouseOut=\"hideMsgBox(event)\">");
document.writeln("    <div class=\"qqcenter\">");
document.writeln("      <div class=\"qqcenter_box\">");
document.writeln("        <div class=\"qq_line\"></div>");
document.writeln("        <div class=\"qq_line\"></div>");
document.writeln("        <div class=\"qq1_box\" style=\"text-align:center;\">");
document.writeln("<a target=\'_blank\' href=\'http://wpa.qq.com/msgrd?v=3&uin=158213339&site=qq&menu=yes\'><img src=\"/images/qq/cgnh.png\" width=81 height=153 /></a><br />");
document.writeln("</div>");
document.writeln("  </div>");
document.writeln("</div>");
document.writeln("");
document.writeln("");
//<![CDATA[
var tips; 
var theTop = 128;
var old = theTop;
function initFloatTips() 
{ 
	tips = document.getElementById('divQQbox');
	moveTips();
}
function moveTips()
{
	 	  var tt=50; 
		  if (window.innerHeight) 
		  {
		pos = window.pageYOffset  
		  }else if (document.documentElement && document.documentElement.scrollTop) {
		pos = document.documentElement.scrollTop  
		  }else if (document.body) {
		    pos = document.body.scrollTop;  
		  }
		  //http:
		  pos=pos-tips.offsetTop+theTop; 
		  pos=tips.offsetTop+pos/10; 
		  if (pos < theTop){
			 pos = theTop;
		  }
		  if (pos != old) { 
			 tips.style.top = pos+"px";
			 tt=10;//alert(tips.style.top);  
		  }
		  old = pos;
		  setTimeout(moveTips,tt);
}
initFloatTips();
	if(typeof(HTMLElement)!="undefined")//给firefox定义contains()方法，ie下不起作用
		{  
		  HTMLElement.prototype.contains=function (obj)  
		  {  
			  while(obj!=null&&typeof(obj.tagName)!="undefind"){//
	   　　 　if(obj==this) return true;  
	   　　　	　obj=obj.parentNode;
	   　　	  }  
			  return false;  
		  }
	}
function show()
{
	document.getElementById("meumid").style.display="none"
	document.getElementById("contentid").style.display="block"
}
	function hideMsgBox(theEvent){
	  if (theEvent){
		var browser=navigator.userAgent;
		if (browser.indexOf("Firefox")>0){//Firefox
		    if (document.getElementById("contentid").contains(theEvent.relatedTarget)) {
				return
			}
		}
		if (browser.indexOf("MSIE")>0 || browser.indexOf("Presto")>=0){
	        if (document.getElementById('contentid').contains(event.toElement)) {
			    return;//结束函式
		    }
		}
	  }
	  document.getElementById("meumid").style.display = "block";
	  document.getElementById("contentid").style.display = "none";
 	}