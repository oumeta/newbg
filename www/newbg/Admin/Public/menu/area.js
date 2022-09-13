/*
	[Destoon B2B System] Copyright (c) 2009 Destoon.COM
	This is NOT a freeware, use is subject to license.txt
*/
function load_area(areaid) {
	makeRequest('action=area&area_title='+area_title+'&area_extend='+area_extend+'&areaid='+areaid, DTPath+'ajax.php', 'into_area');
}
function into_area() {    
	if(xmlHttp.readyState==4 && xmlHttp.status==200) {
		if(xmlHttp.responseText) {
			$('load_area').innerHTML = xmlHttp.responseText;
		}
	}
}