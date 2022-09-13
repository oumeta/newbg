/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
var isIE = (document.all && window.ActiveXObject && !window.opera) ? true : false;
var xmlHttp;
var Try = {
  these: function() {
    var returnValue;
    for (var i = 0; i < arguments.length; i++) {
      var lambda = arguments[i];
      try {
        returnValue = lambda();
        break;
      } catch (e) {}
    }

    return returnValue;
  }
}
function makeRequest(queryString, php, func, method) {
	xmlHttp = Try.these(
      function() {return new ActiveXObject('Msxml2.XMLHTTP')},
      function() {return new ActiveXObject('Microsoft.XMLHTTP')},
      function() {return new XMLHttpRequest()}
    );
	method = method ? 'get' : 'post';
	if(func) xmlHttp.onreadystatechange = eval(func);
	xmlHttp.open(method, php, true);
	xmlHttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlHttp.send(method == 'post' ? queryString : null);
}
function $() {
  var elements = new Array();
  for (var i = 0; i < arguments.length; i++) {
    var element = arguments[i];
    if (typeof element == 'string') element = document.getElementById(element);
    if (arguments.length == 1) return element;
    elements.push(element);
  }
  return elements;
}
var tID=0;
function Tab(ID) {
	var tTab=$('Tab'+tID);
	var tTabs=$('Tabs'+tID);
	var Tab=$('Tab'+ID);
	var Tabs=$('Tabs'+ID);
	if(ID!=tID)	{
		tTab.className='tab';
		Tab.className='tab_on';
		tTabs.style.display='none';
		Tabs.style.display='';
		tID=ID;
	}
}
function checkall(form) {
	form = $(form);
	for(var i = 0; i < form.elements.length; i++) {
		var e = form.elements[i];
		if(e.type != 'checkbox') continue;
		e.checked = e.checked ? false : true;
	}
}
function stoinp(str, id, sp, rp) {
	var sp = sp ? sp : '|';
	var arr = $(id).value.split(sp);
	var rp = rp ? true : false;
	if(rp) str = str.replace(/&/, '').replace(/nbsp;/, '').replace(/├/g, '').replace(/└/g, '').replace(/│/g, '');
	for (var i=0; i<arr.length; i++){
	  if(str == arr[i]) return;
	}
	$(id).value += $(id).value ? sp+str : str;
}
function select_op(id, val) {
	var o = $(id);
	for(var i=0; i<o.options.length; i++) {
		if(o.options[i].value == val) {o.options[i].selected=true;break;}
	}
}
function Dmsg(str, id, s, t) {
	var t = t ? t : 5000;
	var s = s ? true : false;
	try{if(s){window.scrollTo(0,0);}$('d'+id).innerHTML = '<img src="'+SKPath+'image/check_error.gif" width="12" height="12" align="absmiddle"/> '+str+sound('tip');$(id).focus();}catch(e){}
	window.setTimeout(function(){$('d'+id).innerHTML = '';}, t);
}
function confirmURI(message, forward) {
	if(confirm(message)) window.location = forward;
}
function Print(id) {
	var id = id ? id : 'content';
	var w = window.open('','','');
	w.opener = null;
	w.document.write('<div style="width:640px;">'+$(id).innerHTML+'</div>');
	w.window.print();
}
function addFav(title, url) {
	var title = title ? title : document.title;
	var url = url ? url : window.location;
	try {window.external.addFavorite(url, title);}catch(e){window.sidebar.addPanel(title, url, '');}
}
function showmsg(msg, t) {
	var t = t ? t : 5000;
	var s = msg.indexOf('删除') != -1 ? 'delete' : 'ok';
	try{
		$('msgbox').style.display = '';
		$('msgbox').innerHTML = msg+sound(s);
		window.setTimeout('closemsg();', t);
	}catch(e){}
}
function closemsg() {
	try{
		$('msgbox').innerHTML = '';
		$('msgbox').style.display = 'none';
	}catch(e){}
}
function sound(file) {
	return '<div style="float:left;"><embed src="'+DTPath+'file/flash/'+file+'.swf" quality="high" type="application/x-shockwave-flash" height="0" width="0" hidden="true"/></div>';
}
function fontZoom(z, id){
	var id = id ? id : 'content';
	var size = $(id).style.fontSize ? $(id).style.fontSize : '14px';
	var new_size = Number(size.replace('px', ''));
	new_size += z == '+' ? 1 : -1;
	if(new_size < 1) new_size = 1;
	$('content').style.fontSize=new_size+'px';
}
function ImgZoom(Id, m){
	var m = m ? m : 550;
	var w = Id.width;
	if(w < m){
		return;
	} else {
		var h = Id.height;
		Id.title = '点击打开原图';
		Id.onclick = function (e) {window.open(Id.src);}
		Id.height = parseInt(h*m/w);
		Id.width = m;
	}
}
function Album(id, s) {
	for(var i=0;i<3;i++) {
		$('t_'+i).className = i==id ? 'ab_on' : 'ab_im';
	}
	$('abm').innerHTML = '<img src="'+s+'" onload="if(this.width>400){this.width=400;}" onclick="window.open(this.src);"/>';
}
function Ds(ID) {
	$(ID).style.display = '';
}
function Dh(ID) {
	$(ID).style.display = 'none';
}

function Eh(tag) {
	var tag = tag ? tag : 'select';
	if(isIE) {
		var arVersion = navigator.appVersion.split("MSIE");
		var IEversion = parseFloat(arVersion[1]);		
		if(IEversion >= 7 || IEversion < 5) return;
		var ss = document.body.getElementsByTagName(tag);					
		for(var i=0;i<ss.length;i++) {
			ss[i].style.visibility = 'hidden';
		}
	}
}
function Es(tag) {
	var tag = tag ? tag : 'select';
	if(isIE) {
		var arVersion = navigator.appVersion.split("MSIE");
		var IEversion = parseFloat(arVersion[1]);		
		if(IEversion >= 7 || IEversion < 5) return;
		var ss = document.body.getElementsByTagName(tag);					
		for(var i=0;i<ss.length;i++) {
			ss[i].style.visibility = 'visible';
		}
	}
}
function FCKLen(ID) {
	var ID = ID ? ID : 'content';
	var oEditor = FCKeditorAPI.GetInstance(ID);
	var oDOM = oEditor.EditorDocument;
	var iLength ;
	if (document.all) {
		iLength = oDOM.body.innerText.length;
	} else {
		var r = oDOM.createRange() ;
		r.selectNodeContents(oDOM.body);
		iLength = r.toString().length;
	}
	return iLength;
}
document.onkeydown = function(e) {
	var k;
	if(typeof e == 'undefined') {
		k = event.keyCode;
	} else {
		k = e.keyCode;
	}
	if(k == 37) try{if($('destoon_previous').href)window.location=$('destoon_previous').href;}catch(e){}
	else if(k == 39)  try{if($('destoon_next').href)window.location=$('destoon_next').href;}catch(e){}
}