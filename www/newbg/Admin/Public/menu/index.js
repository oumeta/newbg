/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
var index_timeout, index_l = '';
function index_timer(l) {
	index_timeout = setTimeout(
	function(){
		index_show(l);
	}
	,200);
}
function index_out() {
	clearTimeout(index_timeout);
}
function index_show(l) {
	if(index_l) $('index_'+index_l).className = 'icatalog_letter_li';
	index_l = l;
	$('index_'+l).className = 'icatalog_letter_on';
	$('catalog_index').className = 'icatalog_index';
	$('catalog_index').innerHTML = $('letter_'+l).innerHTML+'<div onclick="index_hide()" title="收起">&nbsp;</div>';
}
function index_hide() {
	if(index_l) $('index_'+index_l).className = 'icatalog_letter_li';
	$('catalog_index').innerHTML = '';
	$('catalog_index').className = 'dsn';
	index_out();
}
var cid = 0;
var cmids = [5,6,7,4];
function catalog(id) {
	try{
		$('icatalog_'+cid).className = 'icatalog_li';
		$('icatalog_'+id).className = 'icatalog_on';
		$('icatalog').innerHTML = $('icatalog').innerHTML.replace(new RegExp(curls[cid],"gm"), curls[id]);
		$('letters').innerHTML = $('letters').innerHTML.replace(new RegExp(curls[cid],"gm"), curls[id]);
		$('catalog_index').innerHTML = $('catalog_index').innerHTML.replace(new RegExp(curls[cid],"gm"), curls[id]);
		if(id < 3) $('iadd').href = member_url+'my.php?action=add&mid='+cmids[id];
		cid = id;
	}
	catch(e){}
}
var ipages = new Array();
ipages['sell'] = ipages['buy'] = ipages['product'] = 1;
var istr = '';
function ipage(str, type) {
	var page = 0;
	if(type == '+') {
		page = ipages[str] + 1;
	} else {
		page = ipages[str] - 1;
	}
	if(page < 1) {
		ipages[str] = 1;
		return false;
	}
	ipages[str] = page;
	istr = 'i'+str;
	$(istr).innerHTML = '<div class="loading">&nbsp;</div>';
	makeRequest('action=ipage&job='+str+'&page='+page, DTPath+'ajax.php', '_ipage');	
}

function _ipage() {    
	if(xmlHttp.readyState==4 && xmlHttp.status==200) {
		$(istr).innerHTML = xmlHttp.responseText ? xmlHttp.responseText : '<center>已至最后一页</center>';
	}
}
var stopscroll = false;
var scrollElem = $("ivip_0");
var marqueesHeight = scrollElem.style.height;
scrollElem.onmouseover = new Function('stopscroll = true');
scrollElem.onmouseout  = new Function('stopscroll = false');
var preTop = 0;
var currentTop = 0;
var stoptime = 0;
var leftElem = $("ivip_1");
function init_srolltext(){
	scrollElem.scrollTop = 0;
	setInterval('scrollUp()', 25);
}
function scrollUp(){
	if(stopscroll) return;
	currentTop += 1;
	if(currentTop == 21) {
		stoptime += 1;
		currentTop -= 1;
		if(stoptime == 220) {
			currentTop = 0;
			stoptime = 0;
		}
	} else {
		preTop = scrollElem.scrollTop;
		scrollElem.scrollTop += 1;
		if(preTop == scrollElem.scrollTop){
			scrollElem.scrollTop = 0;
			scrollElem.scrollTop += 1;
		}
	}
}
scrollElem.appendChild(leftElem.cloneNode(true));
init_srolltext();