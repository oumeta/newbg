//系统重新登录
function opendialog(x){
	try{		 
		parent.opendialog(x);
	}catch(e){		 
		parent.popendialog(x)
	}
}

 