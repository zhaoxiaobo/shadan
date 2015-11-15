var docEle = function() {
return document.getElementById(arguments[0]) || false;
}
function openNewDiv(_id) {
var w=800;
var h=565;
var url="/platform/service/qq.htm";
var m = "mask";
if (docEle(_id)) document.removeChild(docEle(_id));
if (docEle(m)) document.removeChild(docEle(m));


// 新激活图层
var newDiv = document.createElement("div");
newDiv.id = _id;
newDiv.style.position = "absolute";
newDiv.style.zIndex = "9999";
newDiv.style.width = w;
newDiv.style.height = h;

//newDiv.style.top = "50px";
//newDiv.style.left = (parseInt(document.body.scrollWidth) - 300) / 2 + "px"; // 屏幕居中
var moveTop = (screen.availHeight-30-h)/2; //获得窗口的垂直位置; 
var moveLeft = (screen.availWidth-10-w)/2; //获得窗口的水平位置; 

//var moveTop = (document.body.scrollHeight-30-h)/2; //获得窗口的垂直位置; 
//var moveLeft = (document.body.scrollWidth-10-w)/2; //获得窗口的水平位置; 
newDiv.style.top = moveTop;
newDiv.style.left =moveLeft;
//newDiv.style.background = "#EFEFEF";
//newDiv.style.border = "1px solid #860001";
//newDiv.style.padding = "5px";
var strhtml="<div style=\"background:#FFFFFF;\"><iframe src=\""+url+"\" width=\""+(w)+"px\" height=\""+(h)+"px\" frameborder=\"0\" scrolling='0'></iframe></div>";
newDiv.innerHTML = strhtml;
document.body.appendChild(newDiv);
// mask图层
var body = document.body;
var bodyWidth = parseInt((body.scrollWidth<body.clientWidth)?body.clientWidth:body.scrollWidth);
var bodyHeight = parseInt((body.scrollHeight<body.clientHeight)?body.clientHeight:body.scrollHeight);
var newMask = document.createElement("div");
newMask.id = m;
newMask.style.position = "absolute";
newMask.style.zIndex = "1";
newMask.style.width = bodyWidth + "px";
newMask.style.height = bodyHeight + "px";
newMask.style.top = "0px";
newMask.style.left = "0px";
//newMask.style.background = "#000";
//newMask.style.filter = "alpha(opacity=40)";
//newMask.style.opacity = "0.40";
newMask.style.cssText = "position:absolute;left:0px;top:0px;width:"+bodyWidth+"px;height:"+bodyHeight+"px;filter:Alpha(Opacity=30);opacity:0.3;background-color:#000000;z-index:101;";
document.body.appendChild(newMask);
// 关闭mask和新图层
//var newA = document.createElement("a");
//newA.href = "#";
//newA.innerHTML = "<div align=center><font color=red><b>关闭窗口</b><font></div>";
//newA.onclick = function() {
//document.body.removeChild(docEle(_id));
//document.body.removeChild(docEle(m));
//return false;
//}
//newDiv.appendChild(newA);
}

//qq回复
function openReplyDiv(_id,url) {
var w=400;
var h=230;
//var url="/platform/service/QQReply.asp";
var m = "mask";
if (docEle(_id)) document.removeChild(docEle(_id));
if (docEle(m)) document.removeChild(docEle(m));

// 新激活图层
var newDiv = document.createElement("div");
newDiv.id = _id;
newDiv.style.position = "absolute";
newDiv.style.zIndex = "9999";
newDiv.style.width = "400px";
newDiv.style.height = "230px";
//newDiv.style.top = "50px";
//newDiv.style.left = (parseInt(document.body.scrollWidth) - 300) / 2 + "px"; // 屏幕居中
var moveTop = (screen.availHeight-30-h)/2; //获得窗口的垂直位置; 
var moveLeft = (screen.availWidth-10-w)/2; //获得窗口的水平位置; 

var moveTop = 250; //获得窗口的垂直位置; 
var moveLeft =350; //获得窗口的水平位置; 

//var moveTop = (document.body.scrollHeight-30-h)/2; //获得窗口的垂直位置; 
//var moveLeft = (document.body.scrollWidth-10-w)/2; //获得窗口的水平位置; 
newDiv.style.top = moveTop;
newDiv.style.left =moveLeft;
newDiv.style.background = "#EFEFEF";
newDiv.style.border = "1px solid #860001";
newDiv.style.padding = "5px";
var strhtml="<div style=\"background:#FFFFFF;\"><iframe src=\""+url+"\" width=\""+(w)+"px\" height=\""+(h-23)+"px\" frameborder=\"0\" scrolling='0'></iframe></div>";
newDiv.innerHTML = strhtml;
document.body.appendChild(newDiv);
// mask图层
var newMask = document.createElement("div");
newMask.id = m;
newMask.style.position = "absolute";
newMask.style.zIndex = "1";
newMask.style.width = document.body.scrollWidth + "px";
newMask.style.height = document.body.scrollHeight + "px";
newMask.style.top = "0px";
newMask.style.left = "0px";
//newMask.style.background = "#000";
newMask.style.filter = "alpha(opacity=40)";
newMask.style.opacity = "0.40";
document.body.appendChild(newMask);
// 关闭mask和新图层
var newA = document.createElement("a");
newA.href = "#";
newA.innerHTML = "<div align=center><font color=red><b>关闭</b><font></div>";
newA.onclick = function() {
document.body.removeChild(docEle(_id));
document.body.removeChild(docEle(m));
return false;
}
newDiv.appendChild(newA);
}

function openNewWindows(_id,url,w,h) {
var m = "mask";
if (docEle(_id)) document.removeChild(docEle(_id));
if (docEle(m)) document.removeChild(docEle(m));


// 新激活图层
var newDiv = document.createElement("div");
newDiv.id = _id;
newDiv.style.position = "absolute";
newDiv.style.zIndex = "9999";
newDiv.style.width = w+"px";
newDiv.style.height = h+"px";

//newDiv.style.top = "50px";
//newDiv.style.left = (parseInt(document.body.scrollWidth) - 300) / 2 + "px"; // 屏幕居中
var moveTop = (screen.availHeight-10-h)/2 + "px"; //获得窗口的垂直位置; 
var moveLeft = (screen.availWidth-10-w)/2 + "px"; //获得窗口的水平位置; 

//var moveTop = "250px"; //获得窗口的垂直位置; 
//var moveLeft = "350px"; //获得窗口的水平位置; 

//var moveTop = (document.body.scrollHeight-30-h)/2; //获得窗口的垂直位置; 
//var moveLeft = (document.body.scrollWidth-10-w)/2; //获得窗口的水平位置; 
newDiv.style.top = moveTop;
newDiv.style.left =moveLeft;

var strhtml="<div style=\"background:#FFFFFF;text-align:center;\"><iframe src=\""+url+"\" width=\""+(w-25)+"px\" height=\""+(h)+"px\" frameborder=\"0\" scrolling=\"no\" ></iframe></div>";
newDiv.innerHTML = strhtml;
document.body.appendChild(newDiv);
// mask图层
var body = document.body;
var bodyWidth = parseInt((body.scrollWidth<body.clientWidth)?body.clientWidth:body.scrollWidth);
var bodyHeight = parseInt((body.scrollHeight<body.clientHeight)?body.clientHeight:body.scrollHeight);
var newMask = document.createElement("div");
newMask.id = m;
newMask.style.position = "absolute";
newMask.style.zIndex = "1";
newMask.style.width = bodyWidth + "px";
newMask.style.height = bodyHeight + "px";
newMask.style.top = "0px";
newMask.style.left = "0px";

newMask.style.cssText = "position:absolute;left:0px;top:0px;width:"+bodyWidth+"px;height:"+bodyHeight+"px;filter:Alpha(Opacity=30);opacity:0.3;background-color:#000000;z-index:101;";
document.body.appendChild(newMask);
}


function killErrors() {
  return true;
}
function closeIframe(){
//document.body.removeChild(document.getElementById("newDiv"));
document.body.removeChild(docEle("newDiv"));
document.body.removeChild(docEle("mask"));
		//document.getElementById("newDiv").style.display="none";
}
window.onerror = killErrors;