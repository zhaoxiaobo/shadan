
var __Index__ = 'http://bao.dazuoyong.com',
	__member__ = 'http://member.dazuoyong.com',
	__taobao__ = 'http://taobao.dazuoyong.com',
	__paipai__ = 'http://paipai.dazuoyong.com',
	__fuwu__ = 'http://fuwu.dazuoyong.com',
	__wk__ = 'http://wk.dazuoyong.com';

require.config({
	baseUrl : '/static/js',
	paths : {
		'jquery' 		: __Index__+'/static/js/jquery-1.7.2.min',
		'artDialog' 	: __Index__+'/static/js/artDialog',
		'wdate' 		: __Index__+'/static/js/My97DatePicker/WdatePicker',
		'fileUpload'	: __Index__+'/static/js/ajaxfileupload',
		'jForm'			: __Index__+'/static/js/jquery.form',
		'clipboard' 	: __Index__+'/static/js/ZeroClipboard',
		'ueconf' 		: __Index__+'/static/js/ueditor/ueditor.config',
		'ue' 			: __Index__+'/static/js/ueditor/ueditor.all.min',
		'cookie' 		: __Index__+"/static/js/jquery.cookie",
		'header' 		: __Index__+'/static/js/header',
		'tinyslider' 	: __Index__+'/static/js/tinyslider',
		'touchSlider' 	: __Index__+'/static/js/touchSlider',
		'prototype' 	: __Index__+'/static/js/prototype',
		'browserdetect' : __Index__+'/static/js/browserdetect',
		'verify' 		: __Index__+'/static/js/verify',
		'reg' 			: __Index__+'/static/js/reg',
		'placeholder' 	: __Index__+'/static/js/placeholder',
		'qq_reg' 		: __Index__+'/static/js/qq-reg',
		'loginVerify'	: __Index__+'/static/js/loginVerify',
		'cache'			: __Index__+'/static/js/cache'
	}, 
	shim: {
		'json':{
			exports: 'JSON'
		},
		'artDialog':{
			deps:['jquery']
		},
		'clipboard' : {
			exports: 'ZeroClipboard'
		},
		'ue':{
			exports: 'UE'
		},
		'header': {
			deps : ['jquery']
		}
	}
});

require(['jquery','artDialog','header'],function(){

	try{
		if (typeof _delay != 'undefined'){
			for (var i in _delay){
				_delay[i]();
			}
		}
	}catch(e){;}
});

//扩展时间类 yyy-MM-dd hh:mm:ss
Date.prototype.format = function(format){
	var o = {
		"M+" : this.getMonth()+1, //month 
		"d+" : this.getDate(), //day 
		"h+" : this.getHours(), //hour 
		"m+" : this.getMinutes(), //minute 
		"s+" : this.getSeconds(), //second 
		"q+" : Math.floor((this.getMonth()+3)/3), //quarter 
		"S" : this.getMilliseconds() //millisecond 
		} 

	if(/(y+)/.test(format)) { 
		format = format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length)); 
	} 

	for(var k in o) { 
		if(new RegExp("("+ k +")").test(format)) { 
			format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length)); 
		} 
	} 
	return format; 
}

