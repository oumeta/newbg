/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
function load_category(catid) {
	makeRequest('action=category&category_title='+category_title+'&category_moduleid='+category_moduleid+'&category_extend='+category_extend+'&category_deep='+category_deep+'&catid='+catid, DTPath+'ajax.php', 'into_category');
}
function into_category() {    
	if(xmlHttp.readyState==4 && xmlHttp.status==200) {
		if(xmlHttp.responseText) {
			$('load_category').innerHTML = xmlHttp.responseText;
		}
	}
}